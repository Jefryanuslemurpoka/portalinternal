<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Izin;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;
use App\Helpers\AttendanceHelper;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RekapAbsensiExport;

class RekapController extends Controller
{
    // Tampilkan halaman rekap
    public function index(Request $request)
    {
        $user = Auth::user();
        $periode = $request->get('periode', 'bulanan'); // harian, mingguan, bulanan
        
        // Jam kerja dari Setting
        $jamMasuk = Setting::get('jam_masuk', '08:00');

        // Get periode kerja saat ini (15 - 14)
        $currentPeriod = AttendanceHelper::getCurrentPeriod();

        // ✅ Tanggal mulai sistem (November 2025)
        $systemStartDate = Carbon::create(2025, 11, 1)->startOfDay();

        // Get data based on periode
        if ($periode === 'harian') {
            // Harian: periode kerja saat ini (15 - 14)
            $absensi = $this->getHarian($user->id);
            $startDate = Carbon::parse($currentPeriod['start']);
            $endDate = Carbon::parse($currentPeriod['end']);
        } elseif ($periode === 'mingguan') {
            // Mingguan: 4 minggu terakhir
            $absensi = $this->getMingguan($user->id);
            $startDate = Carbon::now()->subWeeks(4)->startOfWeek();
            $endDate = Carbon::now()->endOfWeek();
        } else {
            // Bulanan: Dari November 2025 sampai sekarang
            $absensi = $this->getBulanan($user->id);
            $startDate = $systemStartDate; // ✅ Mulai dari November 2025
            $endDate = Carbon::now()->endOfMonth();
        }
        
        // ✅ Pastikan startDate tidak lebih awal dari November 2025
        if ($startDate->lessThan($systemStartDate)) {
            $startDate = $systemStartDate;
        }

        // ✅ Batasi endDate sampai hari ini saja (tidak boleh masa depan)
        $today = Carbon::now()->endOfDay();
        if ($endDate->greaterThan($today)) {
            $endDate = $today;
        }

        // ✅ Generate SEMUA hari dalam periode (termasuk Minggu, Izin, Alpha)
        $absensi = $this->generateCompleteAttendance($user->id, $absensi, $startDate, $endDate);

        // Statistik - EXCLUDE MINGGU dan IZIN/CUTI yang APPROVED
        $totalHariKerja = AttendanceHelper::countWorkingDays(
            $user->id, 
            $startDate->format('Y-m-d'), 
            $endDate->format('Y-m-d')
        );

        // Filter absensi yang valid (exclude minggu dan izin)
        $absensiValid = $absensi->filter(function($item) use ($user) {
            // Skip jika data libur, izin, atau cuti
            if (in_array($item->status, ['libur', 'izin', 'cuti'])) {
                return false;
            }
            return AttendanceHelper::isWorkingDay($user->id, $item->tanggal);
        });

        $totalHadir = $absensiValid->count();
        $tepatWaktu = $absensiValid->filter(function($item) {
            return $item->jam_masuk && !AttendanceHelper::isLate($item->jam_masuk, $item->tanggal);
        })->count();
        $terlambat = $absensiValid->filter(function($item) {
            return $item->jam_masuk && AttendanceHelper::isLate($item->jam_masuk, $item->tanggal);
        })->count();

        // Hitung tidak hadir (hari kerja - hadir)
        $tidakHadir = $totalHariKerja - $totalHadir;

        return view('karyawan.rekap.index', compact(
            'absensi',
            'periode',
            'totalHariKerja',
            'totalHadir',
            'tepatWaktu',
            'terlambat',
            'tidakHadir',
            'jamMasuk',
            'startDate',
            'endDate'
        ));
    }

    // Rekap Harian (periode kerja saat ini: 15 - 14)
    public function harian(Request $request)
    {
        $user = Auth::user();
        $jamMasuk = Setting::get('jam_masuk', '08:00');

        $currentPeriod = AttendanceHelper::getCurrentPeriod();
        $startDate = Carbon::parse($currentPeriod['start']);
        $endDate = Carbon::parse($currentPeriod['end']);
        
        // ✅ Batasi sampai hari ini
        $today = Carbon::now()->endOfDay();
        if ($endDate->greaterThan($today)) {
            $endDate = $today;
        }
        
        $absensi = $this->getHarian($user->id);
        
        // ✅ Generate complete attendance
        $absensi = $this->generateCompleteAttendance($user->id, $absensi, $startDate, $endDate);

        // Filter absensi yang valid
        $absensiValid = $absensi->filter(function($item) use ($user) {
            if (in_array($item->status, ['libur', 'izin', 'cuti'])) {
                return false;
            }
            return AttendanceHelper::isWorkingDay($user->id, $item->tanggal);
        });

        $totalHariKerja = AttendanceHelper::countWorkingDays(
            $user->id,
            $currentPeriod['start'],
            $currentPeriod['end']
        );

        $totalHadir = $absensiValid->count();
        $tepatWaktu = $absensiValid->filter(function($item) {
            return $item->jam_masuk && !AttendanceHelper::isLate($item->jam_masuk, $item->tanggal);
        })->count();
        $terlambat = $absensiValid->filter(function($item) {
            return $item->jam_masuk && AttendanceHelper::isLate($item->jam_masuk, $item->tanggal);
        })->count();
        $tidakHadir = $totalHariKerja - $totalHadir;

        return view('karyawan.rekap.harian', compact(
            'absensi',
            'totalHariKerja',
            'totalHadir',
            'tepatWaktu',
            'terlambat',
            'tidakHadir',
            'jamMasuk',
            'currentPeriod'
        ));
    }

    // Rekap Mingguan (4 minggu terakhir)
    public function mingguan(Request $request)
    {
        $user = Auth::user();
        $jamMasuk = Setting::get('jam_masuk', '08:00');

        $absensi = $this->getMingguan($user->id);

        // ✅ Pastikan tidak lebih awal dari November 2025
        $startDate = Carbon::now()->subWeeks(4)->startOfWeek();
        $systemStart = Carbon::create(2025, 11, 1);
        
        if ($startDate->lessThan($systemStart)) {
            $startDate = $systemStart;
        }
        
        $endDate = Carbon::now()->endOfWeek();
        
        // ✅ Batasi sampai hari ini
        $today = Carbon::now()->endOfDay();
        if ($endDate->greaterThan($today)) {
            $endDate = $today;
        }
        
        // ✅ Generate complete attendance
        $absensi = $this->generateCompleteAttendance($user->id, $absensi, $startDate, $endDate);

        $absensiValid = $absensi->filter(function($item) use ($user) {
            if (in_array($item->status, ['libur', 'izin', 'cuti'])) {
                return false;
            }
            return AttendanceHelper::isWorkingDay($user->id, $item->tanggal);
        });

        $totalHariKerja = AttendanceHelper::countWorkingDays(
            $user->id,
            $startDate->format('Y-m-d'),
            $endDate->format('Y-m-d')
        );

        $totalHadir = $absensiValid->count();
        $tepatWaktu = $absensiValid->filter(function($item) {
            return $item->jam_masuk && !AttendanceHelper::isLate($item->jam_masuk, $item->tanggal);
        })->count();
        $terlambat = $absensiValid->filter(function($item) {
            return $item->jam_masuk && AttendanceHelper::isLate($item->jam_masuk, $item->tanggal);
        })->count();
        $tidakHadir = $totalHariKerja - $totalHadir;

        return view('karyawan.rekap.mingguan', compact(
            'absensi',
            'totalHariKerja',
            'totalHadir',
            'tepatWaktu',
            'terlambat',
            'tidakHadir',
            'jamMasuk'
        ));
    }

    // Rekap Bulanan (dari November 2025)
    public function bulanan(Request $request)
    {
        $user = Auth::user();
        $rekapBulanan = [];
        
        // ✅ Hitung bulan dari November 2025 sampai sekarang
        $startMonth = Carbon::create(2025, 11, 1);
        $currentMonth = Carbon::now();
        
        $monthsDiff = $startMonth->diffInMonths($currentMonth) + 1; // +1 untuk include bulan sekarang
        
        for ($i = $monthsDiff - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            
            // Skip jika sebelum November 2025
            if ($date->year < 2025 || ($date->year == 2025 && $date->month < 11)) {
                continue;
            }
            
            // Hitung periode 15-14 untuk bulan ini
            $monthPeriod = AttendanceHelper::getCurrentPeriod($date->day(20)); // Use day 20 to ensure correct period
            
            $absensi = Absensi::where('user_id', $user->id)
                ->whereBetween('tanggal', [$monthPeriod['start'], $monthPeriod['end']])
                ->get();

            $absensiValid = $absensi->filter(function($item) use ($user) {
                return AttendanceHelper::isWorkingDay($user->id, $item->tanggal);
            });

            $totalHariKerja = AttendanceHelper::countWorkingDays(
                $user->id,
                $monthPeriod['start'],
                $monthPeriod['end']
            );

            $totalHadir = $absensiValid->count();
            $tepatWaktu = $absensiValid->filter(function($item) {
                return $item->jam_masuk && !AttendanceHelper::isLate($item->jam_masuk, $item->tanggal);
            })->count();
            $terlambat = $absensiValid->filter(function($item) {
                return $item->jam_masuk && AttendanceHelper::isLate($item->jam_masuk, $item->tanggal);
            })->count();

            $rekapBulanan[] = [
                'bulan' => $date->format('F Y'),
                'bulan_short' => $date->format('M Y'),
                'periode' => $monthPeriod['start_formatted'] . ' - ' . $monthPeriod['end_formatted'],
                'total_hari_kerja' => $totalHariKerja,
                'total_hadir' => $totalHadir,
                'tepat_waktu' => $tepatWaktu,
                'terlambat' => $terlambat,
                'tidak_hadir' => $totalHariKerja - $totalHadir,
            ];
        }

        return view('karyawan.rekap.bulanan', compact('rekapBulanan'));
    }

    // Helper: Get Harian Data (Periode 15-14)
    private function getHarian($userId)
    {
        $currentPeriod = AttendanceHelper::getCurrentPeriod();
        
        return Absensi::where('user_id', $userId)
            ->whereBetween('tanggal', [$currentPeriod['start'], $currentPeriod['end']])
            ->orderBy('tanggal', 'desc')
            ->get();
    }

    // Helper: Get Mingguan Data
    private function getMingguan($userId)
    {
        // ✅ Pastikan tidak lebih awal dari November 2025
        $startWeek = Carbon::now()->subWeeks(4)->startOfWeek();
        $systemStart = Carbon::create(2025, 11, 1);
        
        if ($startWeek->lessThan($systemStart)) {
            $startWeek = $systemStart;
        }
        
        return Absensi::where('user_id', $userId)
            ->where('tanggal', '>=', $startWeek)
            ->where('tanggal', '<=', Carbon::now()->endOfWeek())
            ->orderBy('tanggal', 'desc')
            ->get();
    }

    // Helper: Get Bulanan Data
    private function getBulanan($userId)
    {
        // ✅ Mulai dari November 2025
        return Absensi::where('user_id', $userId)
            ->where('tanggal', '>=', '2025-11-01')
            ->where('tanggal', '<=', Carbon::now()->endOfMonth())
            ->orderBy('tanggal', 'desc')
            ->get();
    }

    /**
     * ✅ Generate COMPLETE attendance record
     * Includes: Absensi + Minggu (Libur) + Izin/Cuti
     * HANYA dari November 2025 sampai hari ini
     * Libur (Minggu) hanya yang sudah lewat
     */
    private function generateCompleteAttendance($userId, $absensi, $startDate, $endDate)
    {
        // Convert existing absensi to keyed collection
        $absensiKeyed = $absensi->keyBy(function($item) {
            return $item->tanggal->format('Y-m-d');
        });
        
        // Get approved izin/cuti dalam periode
        $dataIzin = $this->getApprovedIzin($userId, $startDate, $endDate);
        
        $allRecords = collect();
        $currentDate = $startDate->copy();
        $today = Carbon::now()->endOfDay(); // ✅ Batasan hari ini
        $systemStartDate = Carbon::create(2025, 11, 1)->startOfDay(); // ✅ Batasan mulai sistem
        
        // Loop through all dates in range
        while ($currentDate <= $endDate) {
            // ✅ SKIP jika tanggal sebelum November 2025
            if ($currentDate->lessThan($systemStartDate)) {
                $currentDate->addDay();
                continue;
            }
            
            // ✅ SKIP jika tanggal di masa depan (termasuk hari Minggu yang belum terjadi)
            if ($currentDate->greaterThan($today)) {
                $currentDate->addDay();
                continue;
            }
            
            $dateKey = $currentDate->format('Y-m-d');
            
            // 1. Cek apakah sudah ada data absensi
            if ($absensiKeyed->has($dateKey)) {
                $allRecords->push($absensiKeyed->get($dateKey));
            }
            // 2. Cek apakah ada izin/cuti di tanggal ini
            elseif ($izin = $this->getIzinForDate($dataIzin, $currentDate)) {
                $allRecords->push($this->createIzinRecord($userId, $currentDate, $izin));
            }
            // 3. Cek apakah hari Minggu (Libur) - HANYA yang sudah lewat
            elseif ($currentDate->dayOfWeek == 0 && $currentDate->isPast()) {
                $allRecords->push($this->createLiburRecord($userId, $currentDate));
            }
            // 4. Hari kerja tapi tidak ada data (Alpha) - OPTIONAL
            // elseif ($currentDate->isPast() && !$currentDate->isToday()) {
            //     $allRecords->push($this->createAlphaRecord($userId, $currentDate));
            // }
            
            $currentDate->addDay();
        }
        
        // Sort by tanggal descending
        return $allRecords->sortByDesc(function($item) {
            return $item->tanggal->timestamp;
        })->values();
    }

    /**
     * Get approved izin/cuti dalam periode
     */
    private function getApprovedIzin($userId, $startDate, $endDate)
    {
        // Cek apakah model Izin exists
        if (!class_exists(Izin::class)) {
            return collect();
        }
        
        return Izin::where('user_id', $userId)
            ->whereIn('status', ['approved', 'disetujui']) // sesuaikan dengan sistem Anda
            ->where(function($query) use ($startDate, $endDate) {
                $query->whereBetween('tanggal_mulai', [$startDate, $endDate])
                      ->orWhereBetween('tanggal_selesai', [$startDate, $endDate])
                      ->orWhere(function($q) use ($startDate, $endDate) {
                          $q->where('tanggal_mulai', '<=', $startDate)
                            ->where('tanggal_selesai', '>=', $endDate);
                      });
            })
            ->get();
    }

    /**
     * Cek apakah tanggal ini ada izin
     */
    private function getIzinForDate($dataIzin, $tanggal)
    {
        return $dataIzin->first(function($izin) use ($tanggal) {
            $mulai = Carbon::parse($izin->tanggal_mulai);
            $selesai = Carbon::parse($izin->tanggal_selesai);
            return $tanggal->between($mulai, $selesai);
        });
    }

    /**
     * Create Izin/Cuti record
     */
    private function createIzinRecord($userId, $tanggal, $izin)
    {
        $record = new Absensi();
        $record->id = null;
        $record->user_id = $userId;
        $record->tanggal = $tanggal->copy();
        $record->status = strtolower($izin->jenis_izin ?? 'izin'); // 'izin' atau 'cuti'
        $record->jam_masuk = null;
        $record->jam_keluar = null;
        $record->foto_masuk = null;
        $record->foto_keluar = null;
        $record->lokasi_masuk = null;
        $record->lokasi_keluar = null;
        $record->keterangan = $izin->keterangan ?? 'Sedang ' . ($izin->jenis_izin ?? 'izin');
        $record->exists = false;
        
        return $record;
    }

    /**
     * Create Libur (Minggu) record
     */
    private function createLiburRecord($userId, $tanggal)
    {
        $record = new Absensi();
        $record->id = null;
        $record->user_id = $userId;
        $record->tanggal = $tanggal->copy();
        $record->status = 'libur';
        $record->jam_masuk = null;
        $record->jam_keluar = null;
        $record->foto_masuk = null;
        $record->foto_keluar = null;
        $record->lokasi_masuk = null;
        $record->lokasi_keluar = null;
        $record->keterangan = 'Hari Libur';
        $record->exists = false;
        
        return $record;
    }

    /**
     * Create Alpha (tidak hadir) record - OPTIONAL
     */
    private function createAlphaRecord($userId, $tanggal)
    {
        $record = new Absensi();
        $record->id = null;
        $record->user_id = $userId;
        $record->tanggal = $tanggal->copy();
        $record->status = 'alpha';
        $record->jam_masuk = null;
        $record->jam_keluar = null;
        $record->foto_masuk = null;
        $record->foto_keluar = null;
        $record->lokasi_masuk = null;
        $record->lokasi_keluar = null;
        $record->keterangan = 'Tidak Hadir';
        $record->exists = false;
        
        return $record;
    }

    // Method baru untuk Export Excel
    public function exportRekap(Request $request)
    {
        $user = Auth::user();
        $periode = $request->get('periode', 'bulanan'); // harian, mingguan, bulanan
        
        // Jam kerja dari Setting
        $jamMasuk = Setting::get('jam_masuk', '08:00');

        // Get periode kerja saat ini (15 - 14)
        $currentPeriod = AttendanceHelper::getCurrentPeriod();

        // ✅ Tanggal mulai sistem (November 2025)
        $systemStartDate = Carbon::create(2025, 11, 1)->startOfDay();

        // Get data based on periode
        if ($periode === 'harian') {
            // Harian: periode kerja saat ini (15 - 14)
            $absensi = $this->getHarian($user->id);
            $startDate = Carbon::parse($currentPeriod['start']);
            $endDate = Carbon::parse($currentPeriod['end']);
        } elseif ($periode === 'mingguan') {
            // Mingguan: 4 minggu terakhir
            $absensi = $this->getMingguan($user->id);
            $startDate = Carbon::now()->subWeeks(4)->startOfWeek();
            $endDate = Carbon::now()->endOfWeek();
        } else {
            // Bulanan: Dari November 2025 sampai sekarang
            $absensi = $this->getBulanan($user->id);
            $startDate = $systemStartDate; // ✅ Mulai dari November 2025
            $endDate = Carbon::now()->endOfMonth();
        }
        
        // ✅ Pastikan startDate tidak lebih awal dari November 2025
        if ($startDate->lessThan($systemStartDate)) {
            $startDate = $systemStartDate;
        }

        // ✅ Batasi endDate sampai hari ini saja
        $today = Carbon::now()->endOfDay();
        if ($endDate->greaterThan($today)) {
            $endDate = $today;
        }

        // ✅ Generate SEMUA hari dalam periode
        $absensi = $this->generateCompleteAttendance($user->id, $absensi, $startDate, $endDate);

        // Statistik - EXCLUDE MINGGU dan IZIN/CUTI yang APPROVED
        $totalHariKerja = AttendanceHelper::countWorkingDays(
            $user->id, 
            $startDate->format('Y-m-d'), 
            $endDate->format('Y-m-d')
        );

        // Filter absensi yang valid
        $absensiValid = $absensi->filter(function($item) use ($user) {
            if (in_array($item->status, ['libur', 'izin', 'cuti'])) {
                return false;
            }
            return AttendanceHelper::isWorkingDay($user->id, $item->tanggal);
        });

        $totalHadir = $absensiValid->count();
        $tepatWaktu = $absensiValid->filter(function($item) {
            return $item->jam_masuk && !AttendanceHelper::isLate($item->jam_masuk, $item->tanggal);
        })->count();
        $terlambat = $absensiValid->filter(function($item) {
            return $item->jam_masuk && AttendanceHelper::isLate($item->jam_masuk, $item->tanggal);
        })->count();

        // Generate filename
        $periodeTeks = strtoupper($periode);
        $filename = 'Rekap_Absensi_' . $user->name . '_' . $periodeTeks . '_' . date('Ymd_His') . '.xlsx';

        // Export Excel
        return Excel::download(
            new RekapAbsensiExport(
                $absensi, 
                $user, 
                $periode, 
                $startDate, 
                $endDate, 
                $jamMasuk, 
                $totalHadir, 
                $tepatWaktu, 
                $terlambat
            ),
            $filename
        );
    }
}