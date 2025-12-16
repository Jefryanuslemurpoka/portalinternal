<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Salary;
use App\Models\SalarySlip;
use App\Models\SalaryHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GajiController extends Controller
{
    // ==========================================
    // SLIP GAJI - READ ONLY (UNTUK SEMENTARA)
    // ==========================================

    /**
     * Display a listing of salary slips (Read Only)
     */
    public function index(Request $request)
    {
        $query = SalarySlip::with(['user'])->orderBy('created_at', 'desc');

        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }
        if ($request->filled('bulan')) {
            $query->where('bulan', $request->bulan);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        $slips = $query->paginate(15);
        $users = User::where('status', 'aktif')->orderBy('name')->get();
        $tahunList = SalarySlip::selectRaw('DISTINCT tahun')->orderBy('tahun', 'desc')->pluck('tahun');

        return view('finance.gaji.index', compact('slips', 'users', 'tahunList'));
    }

    /**
     * Display the specified salary slip (Read Only)
     */
    public function show($id)
    {
        $slip = SalarySlip::with(['user', 'approvedBy'])->findOrFail($id);
        return view('finance.gaji.show', compact('slip'));
    }

    /**
     * Delete salary slip (Only for draft status)
     */
    public function destroy($id)
    {
        $slip = SalarySlip::findOrFail($id);

        if (!$slip->isDraft()) {
            return back()->with('error', 'Hanya slip dengan status draft yang bisa dihapus.');
        }

        $slip->delete();

        return redirect()
            ->route('finance.gaji.index')
            ->with('success', 'Slip gaji berhasil dihapus!');
    }

    // ==========================================
    // MASTER GAJI - FULL CRUD (ACTIVE)
    // ==========================================

    /**
     * Master Gaji - Index
     */
    public function masterIndex(Request $request)
    {
        $query = Salary::with('user')->where('status', 'aktif');

        // Filter by search (nama atau NIK karyawan)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        $salaries = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('finance.gaji.master.index', compact('salaries'));
    }

    /**
     * Master Gaji - Create Form
     */
    public function masterCreate()
    {
        // Hanya tampilkan user yang belum punya master gaji aktif
        $users = User::where('status', 'aktif')
            ->whereDoesntHave('salary', function ($q) {
                $q->where('status', 'aktif');
            })
            ->orderBy('name')
            ->get();

        return view('finance.gaji.master.create', compact('users'));
    }

    /**
     * Master Gaji - Store
     */
    public function masterStore(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'gaji_pokok' => 'required|numeric|min:0',
            'tunjangan_jabatan' => 'nullable|numeric|min:0',
            'tunjangan_transport' => 'nullable|numeric|min:0',
            'tunjangan_makan' => 'nullable|numeric|min:0',
            'tunjangan_kehadiran' => 'nullable|numeric|min:0',
            'tunjangan_kesehatan' => 'nullable|numeric|min:0',
            'tunjangan_lainnya' => 'nullable|numeric|min:0',
            'effective_date' => 'required|date',
            'keterangan' => 'nullable|string|max:500',
        ]);

        // Nonaktifkan master gaji lama jika ada
        Salary::where('user_id', $request->user_id)
            ->where('status', 'aktif')
            ->update(['status' => 'nonaktif']);

        // Buat master gaji baru
        $salary = Salary::create($request->all());

        // Catat ke history
        SalaryHistory::create([
            'user_id' => $request->user_id,
            'field_changed' => 'gaji_pokok',
            'old_value' => null,
            'new_value' => $request->gaji_pokok,
            'effective_date' => $request->effective_date,
            'changed_by' => auth()->id(),
            'reason' => 'Penetapan gaji baru',
        ]);

        return redirect()
            ->route('finance.gaji.master.index')
            ->with('success', 'Data gaji berhasil ditambahkan!');
    }

    /**
     * Master Gaji - Edit Form
     */
    public function masterEdit($id)
    {
        $salary = Salary::with('user')->findOrFail($id);
        return view('finance.gaji.master.edit', compact('salary'));
    }

    /**
     * Master Gaji - Update
     */
    public function masterUpdate(Request $request, $id)
    {
        $salary = Salary::findOrFail($id);

        $request->validate([
            'gaji_pokok' => 'required|numeric|min:0',
            'tunjangan_jabatan' => 'nullable|numeric|min:0',
            'tunjangan_transport' => 'nullable|numeric|min:0',
            'tunjangan_makan' => 'nullable|numeric|min:0',
            'tunjangan_kehadiran' => 'nullable|numeric|min:0',
            'tunjangan_kesehatan' => 'nullable|numeric|min:0',
            'tunjangan_lainnya' => 'nullable|numeric|min:0',
            'keterangan' => 'nullable|string|max:500',
        ]);

        // Catat perubahan gaji pokok ke history
        if ($salary->gaji_pokok != $request->gaji_pokok) {
            SalaryHistory::create([
                'user_id' => $salary->user_id,
                'field_changed' => 'gaji_pokok',
                'old_value' => $salary->gaji_pokok,
                'new_value' => $request->gaji_pokok,
                'effective_date' => now(),
                'changed_by' => auth()->id(),
                'reason' => $request->keterangan ?? 'Update gaji',
            ]);
        }

        $salary->update($request->all());

        return redirect()
            ->route('finance.gaji.master.index')
            ->with('success', 'Data gaji berhasil diupdate!');
    }

    /**
     * Master Gaji - Delete
     */
    public function masterDestroy($id)
    {
        $salary = Salary::findOrFail($id);
        
        // Cek apakah ada slip gaji yang terkait
        $slipCount = SalarySlip::where('user_id', $salary->user_id)->count();
        
        // Catat ke history
        SalaryHistory::create([
            'user_id' => $salary->user_id,
            'field_changed' => 'delete_salary',
            'old_value' => $salary->gaji_pokok,
            'new_value' => 0,
            'effective_date' => now(),
            'changed_by' => auth()->id(),
            'reason' => 'Penghapusan data gaji',
        ]);
        
        $userName = $salary->user->name;
        $salary->delete();
        
        if ($slipCount > 0) {
            return redirect()
                ->route('finance.gaji.master.index')
                ->with('warning', 
                    'Data gaji untuk ' . $userName . ' berhasil dihapus. Terdapat ' . $slipCount . ' slip gaji yang masih tersimpan.'
                );
        }
        
        return redirect()
            ->route('finance.gaji.master.index')
            ->with('success', 'Data gaji untuk ' . $userName . ' berhasil dihapus!');
    }
}