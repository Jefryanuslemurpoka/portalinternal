<?php

namespace App\Helpers;

use Carbon\Carbon;
use App\Models\Setting;
use App\Models\CutiIzin;

class AttendanceHelper
{
    /**
     * Cek apakah tanggal adalah hari Minggu (LIBUR)
     */
    public static function isSunday($date)
    {
        return Carbon::parse($date)->dayOfWeek === Carbon::SUNDAY;
    }

    /**
     * Cek apakah tanggal adalah hari Sabtu (setengah hari)
     */
    public static function isSaturday($date)
    {
        return Carbon::parse($date)->dayOfWeek === Carbon::SATURDAY;
    }

    /**
     * Cek apakah user sedang izin/cuti/sakit di tanggal tertentu
     */
    public static function isOnLeave($userId, $date)
    {
        return CutiIzin::where('user_id', $userId)
            ->where('status', 'disetujui')
            ->where('tanggal_mulai', '<=', $date)
            ->where('tanggal_selesai', '>=', $date)
            ->exists();
    }

    /**
     * Cek apakah tanggal termasuk hari kerja
     * Return false jika: Minggu ATAU sedang izin/cuti/sakit yang approved
     */
    public static function isWorkingDay($userId, $date)
    {
        // Minggu = bukan hari kerja
        if (self::isSunday($date)) {
            return false;
        }

        // Sedang izin/cuti/sakit approved = bukan hari kerja
        if (self::isOnLeave($userId, $date)) {
            return false;
        }

        return true;
    }

    /**
     * Get jam keluar berdasarkan hari
     * Sabtu: jam_sabtu_keluar (default 12:00)
     * Lainnya: jam_keluar (default 17:00)
     */
    public static function getExpectedCheckoutTime($date)
    {
        if (self::isSaturday($date)) {
            return Setting::get('jam_sabtu_keluar', '12:00');
        }
        
        return Setting::get('jam_keluar', '17:00');
    }

    /**
     * Hitung total hari kerja dalam periode (15 - 14 bulan depan)
     * Exclude: Minggu dan tanggal yang sedang izin/cuti/sakit
     */
    public static function countWorkingDays($userId, $startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $workingDays = 0;

        while ($start <= $end) {
            if (self::isWorkingDay($userId, $start)) {
                $workingDays++;
            }
            $start->addDay();
        }

        return $workingDays;
    }

    /**
     * Get periode kerja (15 bulan ini - 14 bulan depan)
     * Parameter $date: tanggal referensi (default: hari ini)
     */
    public static function getCurrentPeriod($date = null)
    {
        $referenceDate = $date ? Carbon::parse($date) : Carbon::now();
        
        // Jika tanggal >= 15, periode dimulai tanggal 15 bulan ini
        // Jika tanggal < 15, periode dimulai tanggal 15 bulan lalu
        if ($referenceDate->day >= 15) {
            $startDate = $referenceDate->copy()->day(15);
            $endDate = $referenceDate->copy()->addMonth()->day(14);
        } else {
            $startDate = $referenceDate->copy()->subMonth()->day(15);
            $endDate = $referenceDate->copy()->day(14);
        }

        return [
            'start' => $startDate->format('Y-m-d'),
            'end' => $endDate->format('Y-m-d'),
            'start_formatted' => $startDate->format('d M Y'),
            'end_formatted' => $endDate->format('d M Y'),
        ];
    }

    /**
     * Cek apakah karyawan terlambat
     */
    public static function isLate($jamMasuk, $date)
    {
        $jamMasukSetting = Setting::get('jam_masuk', '08:00');
        $toleransi = (int) Setting::get('toleransi_keterlambatan', 15);
        
        $batasKeterlambatan = Carbon::createFromFormat('H:i', $jamMasukSetting)
            ->addMinutes($toleransi)
            ->format('H:i:s');
        
        return $jamMasuk > $batasKeterlambatan;
    }

    /**
     * Get status kehadiran karyawan untuk tanggal tertentu
     * Return: 'hadir', 'terlambat', 'tidak_hadir', 'izin', 'libur'
     */
    public static function getAttendanceStatus($userId, $date, $jamMasuk = null)
    {
        // Minggu = libur
        if (self::isSunday($date)) {
            return 'libur';
        }

        // Cek izin/cuti/sakit
        if (self::isOnLeave($userId, $date)) {
            return 'izin';
        }

        // Cek absensi
        if ($jamMasuk) {
            if (self::isLate($jamMasuk, $date)) {
                return 'terlambat';
            }
            return 'hadir';
        }

        // Tidak ada record absensi dan bukan hari libur = tidak hadir
        return 'tidak_hadir';
    }
}