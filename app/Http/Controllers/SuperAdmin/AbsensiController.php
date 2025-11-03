<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\User;
use App\Models\Setting;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AbsensiExport;

class AbsensiController extends Controller
{
    // Tampilkan rekap absensi
    public function index(Request $request)
    {
        // Ambil pengaturan jam kerja dari Setting Model (bukan Cache)
        $jamMasuk = Setting::get('jam_masuk', '08:00');
        $toleransi = (int) Setting::get('toleransi_keterlambatan', 15);
        
        // Hitung batas keterlambatan (jam masuk + toleransi)
        $batasKeterlambatan = Carbon::createFromFormat('H:i', $jamMasuk)
            ->addMinutes($toleransi)
            ->format('H:i:s');
        
        $query = Absensi::with('user');

        // Filter tanggal
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('tanggal', [$request->tanggal_mulai, $request->tanggal_selesai]);
        } elseif ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        } else {
            // Default: hari ini
            $query->whereDate('tanggal', Carbon::today());
        }

        // Filter divisi
        if ($request->filled('divisi')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('divisi', $request->divisi);
            });
        }

        // Filter karyawan
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $absensi = $query->orderBy('tanggal', 'desc')
                        ->orderBy('jam_masuk', 'desc')
                        ->paginate(15);

        // Data untuk filter
        $karyawan = User::where('role', 'karyawan')
                       ->where('status', 'aktif')
                       ->orderBy('name')
                       ->get();

        $divisiList = User::where('role', 'karyawan')
                         ->select('divisi')
                         ->distinct()
                         ->orderBy('divisi')
                         ->pluck('divisi');

        // Statistik berdasarkan pengaturan jam kerja dari Setting
        $totalHadir = $absensi->total();
        
        // Hitung tepat waktu dan terlambat berdasarkan batas keterlambatan
        $tepatWaktu = $absensi->filter(function($item) use ($batasKeterlambatan) {
            return $item->jam_masuk && $item->jam_masuk <= $batasKeterlambatan;
        })->count();
        
        $terlambat = $absensi->filter(function($item) use ($batasKeterlambatan) {
            return $item->jam_masuk && $item->jam_masuk > $batasKeterlambatan;
        })->count();

        return view('superadmin.absensi.index', compact(
            'absensi',
            'karyawan',
            'divisiList',
            'totalHadir',
            'tepatWaktu',
            'terlambat',
            'jamMasuk',
            'toleransi',
            'batasKeterlambatan'
        ));
    }

    // Filter absensi (AJAX)
    public function filter(Request $request)
    {
        return $this->index($request);
    }

    // Tampilkan form tambah absensi
    public function create()
    {
        $karyawan = User::where('role', 'karyawan')
                       ->where('status', 'aktif')
                       ->orderBy('name')
                       ->get();

        return view('superadmin.absensi.create', compact('karyawan'));
    }

    // Simpan absensi baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'jam_masuk' => 'nullable|date_format:H:i',
            'jam_keluar' => 'nullable|date_format:H:i|after:jam_masuk',
            'lokasi' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'user_id.required' => 'Karyawan harus dipilih',
            'user_id.exists' => 'Karyawan tidak valid',
            'tanggal.required' => 'Tanggal harus diisi',
            'tanggal.date' => 'Format tanggal tidak valid',
            'jam_masuk.date_format' => 'Format jam masuk harus HH:MM',
            'jam_keluar.date_format' => 'Format jam keluar harus HH:MM',
            'jam_keluar.after' => 'Jam keluar harus lebih besar dari jam masuk',
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format foto harus jpeg, png, atau jpg',
            'foto.max' => 'Ukuran foto maksimal 2MB',
        ]);

        // Cek duplikasi absensi
        $exists = Absensi::where('user_id', $validated['user_id'])
                        ->whereDate('tanggal', $validated['tanggal'])
                        ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Absensi untuk karyawan ini pada tanggal yang sama sudah ada!');
        }

        // Upload foto jika ada
        if ($request->hasFile('foto')) {
            $foto = $request->file('foto');
            $filename = 'absensi_' . time() . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();
            $path = $foto->storeAs('absensi', $filename, 'public');
            $validated['foto'] = $path;
        }

        // Simpan data
        $absensi = Absensi::create($validated);

        $user = User::find($validated['user_id']);

        return redirect()->route('superadmin.absensi.index')
            ->with('success', "Absensi {$user->name} tanggal " . Carbon::parse($validated['tanggal'])->format('d M Y') . " berhasil ditambahkan");
    }

    // Tampilkan form edit absensi
    public function edit($id)
    {
        $absensi = Absensi::with('user')->findOrFail($id);
        
        $karyawan = User::where('role', 'karyawan')
                       ->where('status', 'aktif')
                       ->orderBy('name')
                       ->get();

        return view('superadmin.absensi.edit', compact('absensi', 'karyawan'));
    }

    // Update absensi
    public function update(Request $request, $id)
    {
        $absensi = Absensi::findOrFail($id);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'tanggal' => 'required|date',
            'jam_masuk' => 'nullable|date_format:H:i',
            'jam_keluar' => 'nullable|date_format:H:i|after:jam_masuk',
            'lokasi' => 'nullable|string|max:255',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'user_id.required' => 'Karyawan harus dipilih',
            'user_id.exists' => 'Karyawan tidak valid',
            'tanggal.required' => 'Tanggal harus diisi',
            'tanggal.date' => 'Format tanggal tidak valid',
            'jam_masuk.date_format' => 'Format jam masuk harus HH:MM',
            'jam_keluar.date_format' => 'Format jam keluar harus HH:MM',
            'jam_keluar.after' => 'Jam keluar harus lebih besar dari jam masuk',
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Format foto harus jpeg, png, atau jpg',
            'foto.max' => 'Ukuran foto maksimal 2MB',
        ]);

        // Cek duplikasi absensi (kecuali data yang sedang diedit)
        $exists = Absensi::where('user_id', $validated['user_id'])
                        ->whereDate('tanggal', $validated['tanggal'])
                        ->where('id', '!=', $id)
                        ->exists();

        if ($exists) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'Absensi untuk karyawan ini pada tanggal yang sama sudah ada!');
        }

        // Upload foto baru jika ada
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($absensi->foto && \Storage::disk('public')->exists($absensi->foto)) {
                \Storage::disk('public')->delete($absensi->foto);
            }

            $foto = $request->file('foto');
            $filename = 'absensi_' . time() . '_' . uniqid() . '.' . $foto->getClientOriginalExtension();
            $path = $foto->storeAs('absensi', $filename, 'public');
            $validated['foto'] = $path;
        }

        // Update data
        $absensi->update($validated);

        $user = User::find($validated['user_id']);

        return redirect()->route('superadmin.absensi.index')
            ->with('success', "Absensi {$user->name} tanggal " . Carbon::parse($validated['tanggal'])->format('d M Y') . " berhasil diperbarui");
    }

    // Detail absensi
    public function show($id)
    {
        $absensi = Absensi::with('user')->findOrFail($id);
        return view('superadmin.absensi.show', compact('absensi'));
    }

    // Hapus absensi
    public function destroy($id)
    {
        $absensi = Absensi::findOrFail($id);
        $namaKaryawan = $absensi->user->name;
        $tanggal = $absensi->tanggal->format('d M Y');

        // Hapus foto jika ada
        if ($absensi->foto && \Storage::disk('public')->exists($absensi->foto)) {
            \Storage::disk('public')->delete($absensi->foto);
        }

        $absensi->delete();

        return redirect()->route('superadmin.absensi.index')
            ->with('success', "Absensi {$namaKaryawan} tanggal {$tanggal} berhasil dihapus");
    }

    // Export ke Excel
    public function export(Request $request)
    {
        // Ambil filter dari request
        $filters = [
            'tanggal' => $request->tanggal,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'user_id' => $request->user_id,
            'divisi' => $request->divisi,
        ];

        // Generate nama file
        $filename = 'Absensi_';
        
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $filename .= Carbon::parse($request->tanggal_mulai)->format('d-m-Y') . '_sampai_' . Carbon::parse($request->tanggal_selesai)->format('d-m-Y');
        } elseif ($request->filled('tanggal')) {
            $filename .= Carbon::parse($request->tanggal)->format('d-m-Y');
        } else {
            $filename .= Carbon::today()->format('d-m-Y');
        }
        
        $filename .= '.xlsx';

        // Export
        return Excel::download(new AbsensiExport($filters), $filename);
    }
}