<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\LaporanNotaris;
use App\Models\LaporanNotarisDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanGabunganExport;
use App\Exports\LaporanNotarisDetailExport;
use ZipArchive;

class LaporanNotarisController extends Controller
{
    // ========================================
    // INDEX - Daftar Semua Laporan Bulanan
    // ========================================
    public function index(Request $request)
    {
        $tahun = $request->get('tahun', date('Y'));
        
        $laporans = LaporanNotaris::with('creator')
            ->where('tahun', $tahun)
            ->orderBy('bulan', 'desc')
            ->paginate(12);

        $tahunList = LaporanNotaris::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return view('karyawan.laporan-notaris.index', compact('laporans', 'tahun', 'tahunList'));
    }

    // ========================================
    // CREATE - Form Input Laporan Bulanan
    // ========================================
    public function create()
    {
        return view('karyawan.laporan-notaris.create');
    }

    // ========================================
    // STORE - Simpan Laporan Bulanan
    // ========================================
    public function store(Request $request)
    {
        $request->validate([
            'bulan' => 'required|date_format:Y-m|unique:laporan_notaris,bulan',
            'details' => 'required|array|min:1',
            'details.*.tanggal' => 'required|date',
            'details.*.gamal' => 'required|integer|min:0',
            'details.*.eny' => 'required|integer|min:0',
            'details.*.nyoman' => 'required|integer|min:0',
            'details.*.otty' => 'required|integer|min:0',
            'details.*.kartika' => 'required|integer|min:0',
            'details.*.retno' => 'required|integer|min:0',
            'details.*.neltje' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            $bulan = $request->bulan;
            $date = Carbon::parse($bulan . '-01');
            $namaBulan = $date->locale('id')->isoFormat('MMMM YYYY');

            // Hitung Total Per Notaris
            $totalGamal = collect($request->details)->sum('gamal');
            $totalEny = collect($request->details)->sum('eny');
            $totalNyoman = collect($request->details)->sum('nyoman');
            $totalOtty = collect($request->details)->sum('otty');
            $totalKartika = collect($request->details)->sum('kartika');
            $totalRetno = collect($request->details)->sum('retno');
            $totalNeltje = collect($request->details)->sum('neltje');
            $grandTotal = $totalGamal + $totalEny + $totalNyoman + $totalOtty + $totalKartika + $totalRetno + $totalNeltje;

            // Simpan Laporan Utama
            $laporan = LaporanNotaris::create([
                'bulan' => $bulan,
                'tahun' => $date->year,
                'nama_bulan' => $namaBulan,
                'created_by' => auth()->id(),
                'total_gamal' => $totalGamal,
                'total_eny' => $totalEny,
                'total_nyoman' => $totalNyoman,
                'total_otty' => $totalOtty,
                'total_kartika' => $totalKartika,
                'total_retno' => $totalRetno,
                'total_neltje' => $totalNeltje,
                'grand_total' => $grandTotal,
            ]);

            // Simpan Detail Harian
            foreach ($request->details as $detail) {
                LaporanNotarisDetail::create([
                    'laporan_notaris_id' => $laporan->id,
                    'tanggal' => $detail['tanggal'],
                    'gamal' => $detail['gamal'],
                    'eny' => $detail['eny'],
                    'nyoman' => $detail['nyoman'],
                    'otty' => $detail['otty'],
                    'kartika' => $detail['kartika'],
                    'retno' => $detail['retno'],
                    'neltje' => $detail['neltje'],
                    'is_libur' => isset($detail['is_libur']) ? true : false,
                ]);
            }

            DB::commit();
            return redirect()->route('karyawan.laporan-notaris.show', $laporan->id)
                ->with('success', 'Laporan berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyimpan laporan: ' . $e->getMessage())->withInput();
        }
    }

    // ========================================
    // SHOW - Detail Laporan (Tabel Gabungan)
    // ========================================
    public function show($id)
    {
        $laporan = LaporanNotaris::with(['details' => function($q) {
            $q->orderBy('tanggal', 'asc');
        }])->findOrFail($id);

        return view('karyawan.laporan-notaris.show', compact('laporan'));
    }

    // ========================================
    // EDIT - Form Edit Laporan
    // ========================================
    public function edit($id)
    {
        $laporan = LaporanNotaris::with('details')->findOrFail($id);
        return view('karyawan.laporan-notaris.edit', compact('laporan'));
    }

    // ========================================
    // UPDATE - Update Laporan
    // ========================================
    public function update(Request $request, $id)
    {
        $request->validate([
            'details' => 'required|array|min:1',
            'details.*.tanggal' => 'required|date',
            'details.*.gamal' => 'required|integer|min:0',
            'details.*.eny' => 'required|integer|min:0',
            'details.*.nyoman' => 'required|integer|min:0',
            'details.*.otty' => 'required|integer|min:0',
            'details.*.kartika' => 'required|integer|min:0',
            'details.*.retno' => 'required|integer|min:0',
            'details.*.neltje' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            $laporan = LaporanNotaris::findOrFail($id);

            // Hapus detail lama
            $laporan->details()->delete();

            // Hitung Total Baru
            $totalGamal = collect($request->details)->sum('gamal');
            $totalEny = collect($request->details)->sum('eny');
            $totalNyoman = collect($request->details)->sum('nyoman');
            $totalOtty = collect($request->details)->sum('otty');
            $totalKartika = collect($request->details)->sum('kartika');
            $totalRetno = collect($request->details)->sum('retno');
            $totalNeltje = collect($request->details)->sum('neltje');
            $grandTotal = $totalGamal + $totalEny + $totalNyoman + $totalOtty + $totalKartika + $totalRetno + $totalNeltje;

            // Update Laporan Utama
            $laporan->update([
                'total_gamal' => $totalGamal,
                'total_eny' => $totalEny,
                'total_nyoman' => $totalNyoman,
                'total_otty' => $totalOtty,
                'total_kartika' => $totalKartika,
                'total_retno' => $totalRetno,
                'total_neltje' => $totalNeltje,
                'grand_total' => $grandTotal,
            ]);

            // Simpan Detail Baru
            foreach ($request->details as $detail) {
                LaporanNotarisDetail::create([
                    'laporan_notaris_id' => $laporan->id,
                    'tanggal' => $detail['tanggal'],
                    'gamal' => $detail['gamal'],
                    'eny' => $detail['eny'],
                    'nyoman' => $detail['nyoman'],
                    'otty' => $detail['otty'],
                    'kartika' => $detail['kartika'],
                    'retno' => $detail['retno'],
                    'neltje' => $detail['neltje'],
                    'is_libur' => isset($detail['is_libur']) ? true : false,
                ]);
            }

            DB::commit();
            return redirect()->route('karyawan.laporan-notaris.show', $laporan->id)
                ->with('success', 'Laporan berhasil diupdate!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal update laporan: ' . $e->getMessage())->withInput();
        }
    }

    // ========================================
    // DESTROY - Hapus Laporan
    // ========================================
    public function destroy($id)
    {
        try {
            $laporan = LaporanNotaris::findOrFail($id);
            $laporan->delete();

            return redirect()->route('karyawan.laporan-notaris.index')
                ->with('success', 'Laporan berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus laporan: ' . $e->getMessage());
        }
    }

    // ========================================
    // NOTARIS DETAIL - Laporan Per Notaris
    // ========================================
    public function notarisDetail($id, $notaris)
    {
        $laporan = LaporanNotaris::with('details')->findOrFail($id);
        $notarisList = LaporanNotaris::getNotarisList();

        if (!isset($notarisList[$notaris])) {
            abort(404, 'Notaris tidak ditemukan');
        }

        $namaNotaris = $notarisList[$notaris];

        return view('karyawan.laporan-notaris.notaris-detail', compact('laporan', 'notaris', 'namaNotaris'));
    }

    // ========================================
    // GRAFIK - Perbandingan Notaris
    // ========================================
    public function grafik($id)
    {
        $laporan = LaporanNotaris::findOrFail($id);
        $notarisList = LaporanNotaris::getNotarisList();

        // Ambil data bulanan untuk trend (1 tahun penuh)
        $trendData = LaporanNotaris::where('tahun', $laporan->tahun)
            ->orderBy('bulan', 'asc')
            ->get()
            ->map(function($item) {
                $date = Carbon::parse($item->bulan . '-01');
                return [
                    'bulan' => $date->format('M'), // Jan, Feb, Mar...
                    'bulan_full' => $date->locale('id')->isoFormat('MMMM'), // Januari, Februari...
                    'gamal' => $item->total_gamal,
                    'eny' => $item->total_eny,
                    'nyoman' => $item->total_nyoman,
                    'otty' => $item->total_otty,
                    'kartika' => $item->total_kartika,
                    'retno' => $item->total_retno,
                    'neltje' => $item->total_neltje,
                ];
            });

        return view('karyawan.laporan-notaris.grafik', compact('laporan', 'notarisList', 'trendData'));
    }

    // ========================================
    // DOWNLOAD ZIP - Unduh Laporan Lengkap
    // ========================================
    public function downloadZip($id)
    {
        set_time_limit(300); // Set timeout 5 menit untuk proses besar
        
        try {
            $laporan = LaporanNotaris::with('details')->findOrFail($id);
            $notarisList = LaporanNotaris::getNotarisList();
            
            // Nama file ZIP yang akan didownload
            $zipFileName = 'Laporan_Notaris_' . str_replace(' ', '_', $laporan->nama_bulan) . '.zip';
            
            // Buat ZIP langsung di memory menggunakan response stream
            $zip = new ZipArchive();
            $zipPath = sys_get_temp_dir() . '/' . $zipFileName;
            
            if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
                throw new \Exception('Tidak dapat membuat file ZIP');
            }

            // 1. Tambahkan Laporan Gabungan ke ZIP
            $gabunganFileName = 'Laporan_Gabungan_' . str_replace(' ', '_', $laporan->nama_bulan) . '.xlsx';
            $gabunganContent = Excel::raw(new LaporanGabunganExport($laporan), \Maatwebsite\Excel\Excel::XLSX);
            $zip->addFromString($gabunganFileName, $gabunganContent);

            // 2. Tambahkan Detail Per Notaris ke ZIP
            foreach ($notarisList as $key => $nama) {
                $notarisFileName = 'Laporan_' . str_replace(' ', '_', $nama) . '_' . str_replace(' ', '_', $laporan->nama_bulan) . '.xlsx';
                $notarisContent = Excel::raw(new LaporanNotarisDetailExport($laporan, $key, $nama), \Maatwebsite\Excel\Excel::XLSX);
                $zip->addFromString($notarisFileName, $notarisContent);
            }

            $zip->close();

            // 3. Download file ZIP dan hapus setelahnya
            return response()->download($zipPath, $zipFileName, [
                'Content-Type' => 'application/zip',
            ])->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            \Log::error('Download ZIP Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengunduh laporan: ' . $e->getMessage());
        }
    }
}