<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Salary;
use App\Models\SalarySlip;
use App\Models\SalaryComponent;
use App\Models\SalaryHistory;
use App\Services\SalaryCalculatorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GajiController extends Controller
{
    protected $salaryCalculator;

    public function __construct(SalaryCalculatorService $salaryCalculator)
    {
        $this->salaryCalculator = $salaryCalculator;
    }

    /**
     * Display a listing of salary slips
     */
    public function index(Request $request)
    {
        $query = SalarySlip::with(['user'])->orderBy('created_at', 'desc');

        // Filter by periode
        if ($request->filled('tahun')) {
            $query->where('tahun', $request->tahun);
        }
        if ($request->filled('bulan')) {
            $query->where('bulan', $request->bulan);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nik', 'like', "%{$search}%");
            });
        }

        $slips = $query->paginate(15);

        // Data untuk filter
        $users = User::where('status', 'aktif')->orderBy('name')->get();
        $tahunList = SalarySlip::selectRaw('DISTINCT tahun')->orderBy('tahun', 'desc')->pluck('tahun');

        return view('finance.gaji.index', compact('slips', 'users', 'tahunList'));
    }

    /**
     * Show the form for creating a new salary slip
     */
    public function create()
    {
        $users = User::where('status', 'aktif')
            ->with(['salary' => function ($q) {
                $q->active()->current();
            }])
            ->orderBy('name')
            ->get();

        $currentMonth = now()->month;
        $currentYear = now()->year;

        return view('finance.gaji.create', compact('users', 'currentMonth', 'currentYear'));
    }

    /**
     * Generate salary slip
     */
    public function generate(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'tahun' => 'required|integer|min:2020|max:2099',
            'bulan' => 'required|integer|min:1|max:12',
        ]);

        try {
            $user = User::findOrFail($request->user_id);
            $slip = $this->salaryCalculator->generateSlip($user, $request->tahun, $request->bulan);

            return redirect()
                ->route('finance.gaji.show', $slip->id)
                ->with('success', 'Slip gaji berhasil di-generate!');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal generate slip gaji: ' . $e->getMessage());
        }
    }

    /**
     * Generate slip untuk semua karyawan
     */
    public function generateAll(Request $request)
    {
        $request->validate([
            'tahun' => 'required|integer|min:2020|max:2099',
            'bulan' => 'required|integer|min:1|max:12',
        ]);

        try {
            $results = $this->salaryCalculator->generateSlipForAllUsers($request->tahun, $request->bulan);

            $message = sprintf(
                'Berhasil generate %d slip gaji. Gagal: %d',
                count($results['success']),
                count($results['failed'])
            );

            return redirect()
                ->route('finance.gaji.index', [
                    'tahun' => $request->tahun,
                    'bulan' => $request->bulan
                ])
                ->with('success', $message);

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal generate slip gaji: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified salary slip
     */
    public function show($id)
    {
        $slip = SalarySlip::with(['user', 'approvedBy'])->findOrFail($id);
        
        return view('finance.gaji.show', compact('slip'));
    }

    /**
     * Show the form for editing the specified salary slip
     */
    public function edit($id)
    {
        $slip = SalarySlip::with('user')->findOrFail($id);

        if (!$slip->canEdit()) {
            return redirect()
                ->route('finance.gaji.show', $id)
                ->with('error', 'Slip gaji tidak dapat diedit karena statusnya ' . $slip->status_label);
        }

        $components = SalaryComponent::active()->get();

        return view('finance.gaji.edit', compact('slip', 'components'));
    }

    /**
     * Update the specified salary slip
     */
    public function update(Request $request, $id)
    {
        $slip = SalarySlip::findOrFail($id);

        if (!$slip->canEdit()) {
            return back()->with('error', 'Slip gaji tidak dapat diedit.');
        }

        $request->validate([
            'gaji_pokok' => 'required|numeric|min:0',
            'total_tunjangan' => 'required|numeric|min:0',
            'total_potongan' => 'required|numeric|min:0',
            'hari_hadir' => 'required|integer|min:0',
            'hari_izin' => 'required|integer|min:0',
            'hari_sakit' => 'required|integer|min:0',
            'hari_alpha' => 'required|integer|min:0',
            'jam_lembur' => 'nullable|numeric|min:0',
        ]);

        $gajiKotor = $request->gaji_pokok + $request->total_tunjangan;
        $gajiBersih = $gajiKotor - $request->total_potongan;

        $slip->update([
            'gaji_pokok' => $request->gaji_pokok,
            'total_tunjangan' => $request->total_tunjangan,
            'total_potongan' => $request->total_potongan,
            'gaji_kotor' => $gajiKotor,
            'gaji_bersih' => $gajiBersih,
            'hari_hadir' => $request->hari_hadir,
            'hari_izin' => $request->hari_izin,
            'hari_sakit' => $request->hari_sakit,
            'hari_alpha' => $request->hari_alpha,
            'jam_lembur' => $request->jam_lembur ?? 0,
            'upah_lembur' => ($request->jam_lembur ?? 0) * 50000, // TODO: ambil dari setting
        ]);

        return redirect()
            ->route('finance.gaji.show', $id)
            ->with('success', 'Slip gaji berhasil diupdate!');
    }

    /**
     * Submit slip untuk approval
     */
    public function submit($id)
    {
        $slip = SalarySlip::findOrFail($id);

        if (!$slip->isDraft()) {
            return back()->with('error', 'Slip gaji hanya bisa disubmit dari status draft.');
        }

        $slip->update(['status' => 'pending']);

        // TODO: Send notification to approver

        return redirect()
            ->route('finance.gaji.show', $id)
            ->with('success', 'Slip gaji berhasil disubmit untuk persetujuan!');
    }

    /**
     * Approve salary slip
     */
    public function approve(Request $request, $id)
    {
        $slip = SalarySlip::findOrFail($id);

        if (!$slip->canApprove()) {
            return back()->with('error', 'Slip gaji tidak dapat diapprove.');
        }

        $slip->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        // TODO: Send notification to user

        return redirect()
            ->route('finance.gaji.show', $id)
            ->with('success', 'Slip gaji berhasil diapprove!');
    }

    /**
     * Reject salary slip
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $slip = SalarySlip::findOrFail($id);

        if (!$slip->canApprove()) {
            return back()->with('error', 'Slip gaji tidak dapat direject.');
        }

        $slip->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()
            ->route('finance.gaji.show', $id)
            ->with('success', 'Slip gaji berhasil direject!');
    }

    /**
     * Mark as paid
     */
    public function markAsPaid(Request $request, $id)
    {
        $request->validate([
            'payment_date' => 'required|date',
            'payment_method' => 'required|string',
            'payment_notes' => 'nullable|string|max:500',
        ]);

        $slip = SalarySlip::findOrFail($id);

        if (!$slip->canPay()) {
            return back()->with('error', 'Slip gaji hanya bisa dibayar jika sudah approved.');
        }

        $slip->update([
            'status' => 'paid',
            'payment_date' => $request->payment_date,
            'payment_method' => $request->payment_method,
            'payment_notes' => $request->payment_notes,
        ]);

        return redirect()
            ->route('finance.gaji.show', $id)
            ->with('success', 'Slip gaji berhasil ditandai sebagai sudah dibayar!');
    }

    /**
     * Delete salary slip
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

    /**
     * Export slip to PDF
     */
    public function exportPdf($id)
    {
        $slip = SalarySlip::with('user')->findOrFail($id);
        
        // TODO: Implement PDF export using DomPDF or similar
        // For now, just return a view that can be printed
        return view('finance.gaji.pdf', compact('slip'));
    }

    /**
     * Dashboard statistics
     */
    public function dashboard()
    {
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $stats = [
            'total_karyawan' => User::where('status', 'aktif')->count(),
            'total_gaji_bulan_ini' => SalarySlip::forPeriode($currentYear, $currentMonth)
                ->sum('gaji_bersih'),
            'slip_pending' => SalarySlip::status('pending')->count(),
            'slip_unpaid' => SalarySlip::whereIn('status', ['approved'])->count(),
        ];

        // Grafik gaji per bulan (6 bulan terakhir)
        $chartData = SalarySlip::select(
                DB::raw('tahun'),
                DB::raw('bulan'),
                DB::raw('SUM(gaji_bersih) as total')
            )
            ->where('status', 'paid')
            ->where('tahun', '>=', now()->subMonths(6)->year)
            ->groupBy('tahun', 'bulan')
            ->orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->limit(6)
            ->get();

        // Slip terbaru
        $recentSlips = SalarySlip::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('finance.gaji.dashboard', compact('stats', 'chartData', 'recentSlips'));
    }

    /**
     * Master Gaji - List
     */
    public function masterIndex()
    {
        $salaries = Salary::with('user')
            ->active()
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('finance.gaji.master.index', compact('salaries'));
    }

    /**
     * Master Gaji - Create
     */
    public function masterCreate()
    {
        $users = User::where('status', 'aktif')
            ->whereDoesntHave('salary', function ($q) {
                $q->active();
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

        // Nonaktifkan gaji lama jika ada
        Salary::where('user_id', $request->user_id)
            ->active()
            ->update(['status' => 'nonaktif']);

        $salary = Salary::create($request->all());

        // Log history
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
     * Master Gaji - Edit
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

        // Log perubahan jika gaji pokok berubah
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
    
    if ($slipCount > 0) {
        // Hapus tetap jalan, tapi kasih warning
        $userName = $salary->user->name;
        
        // Log history sebelum hapus
        SalaryHistory::create([
            'user_id' => $salary->user_id,
            'field_changed' => 'delete_salary',
            'old_value' => $salary->gaji_pokok,
            'new_value' => 0,
            'effective_date' => now(),
            'changed_by' => auth()->id(),
            'reason' => 'Penghapusan data gaji',
        ]);
        
        $salary->delete();
        
        return redirect()
            ->route('finance.gaji.master.index')
            ->with('warning', 
                'Data gaji untuk ' . $userName . ' berhasil dihapus. Terdapat ' . $slipCount . ' slip gaji yang masih tersimpan.'
            );
    }
    
    // Log history sebelum hapus
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
    
    return redirect()
        ->route('finance.gaji.master.index')
        ->with('success', 'Data gaji untuk ' . $userName . ' berhasil dihapus!');
}
}