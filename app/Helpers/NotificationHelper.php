<?php

namespace App\Helpers;

use App\Models\Notification;
use App\Models\User;

    class NotificationHelper
    {
        /**
         * Create notification untuk pengajuan cuti baru
         */
    public static function cutiDiajukan($cutiIzin)
    {
        // Notifikasi untuk admin DAN super_admin
        $admins = User::whereIn('role', ['admin', 'super_admin'])->get();
        
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'cuti',
                'title' => 'Pengajuan ' . ucfirst($cutiIzin->jenis) . ' Baru',
                'message' => $cutiIzin->user->name . ' mengajukan ' . $cutiIzin->jenis . ' dari ' . 
                            date('d M Y', strtotime($cutiIzin->tanggal_mulai)) . ' s/d ' . 
                            date('d M Y', strtotime($cutiIzin->tanggal_selesai)),
                'url' => route('superadmin.cutiizin.index') // ✅ Pastikan route ini benar
            ]);
        }

        return true;
    }

    /**
     * Create notification untuk approval cuti
     */
    public static function cutiDisetujui($cutiIzin)
    {
        Notification::create([
            'user_id' => $cutiIzin->user_id,
            'type' => 'approval',
            'title' => ucfirst($cutiIzin->jenis) . ' Disetujui',
            'message' => 'Pengajuan ' . $cutiIzin->jenis . ' Anda dari ' . 
                        date('d M Y', strtotime($cutiIzin->tanggal_mulai)) . ' s/d ' . 
                        date('d M Y', strtotime($cutiIzin->tanggal_selesai)) . ' telah disetujui',
            'url' => route('karyawan.cutiizin.index')
        ]);

        return true;
    }

    /**
     * Create notification untuk rejection cuti
     */
    public static function cutiDitolak($cutiIzin)
    {
        Notification::create([
            'user_id' => $cutiIzin->user_id,
            'type' => 'approval',
            'title' => ucfirst($cutiIzin->jenis) . ' Ditolak',
            'message' => 'Pengajuan ' . $cutiIzin->jenis . ' Anda dari ' . 
                        date('d M Y', strtotime($cutiIzin->tanggal_mulai)) . ' s/d ' . 
                        date('d M Y', strtotime($cutiIzin->tanggal_selesai)) . ' ditolak. ' .
                        ($cutiIzin->catatan_admin ? 'Alasan: ' . $cutiIzin->catatan_admin : ''),
            'url' => route('karyawan.cutiizin.index')
        ]);

        return true;
    }

    /**
     * Create notification untuk pengumuman baru
     */
    public static function pengumumanBaru($pengumuman)
    {
        // Notifikasi untuk semua karyawan
        $karyawan = User::where('role', 'karyawan')
            ->where('status', 'aktif')
            ->get();
        
        foreach ($karyawan as $user) {
            Notification::create([
                'user_id' => $user->id,
                'type' => 'pengumuman',
                'title' => 'Pengumuman Baru: ' . $pengumuman->judul,
                'message' => substr(strip_tags($pengumuman->isi), 0, 100) . '...',
                'url' => route('karyawan.pengumuman.show', $pengumuman->id)
            ]);
        }

        return true;
    }

    /**
     * Create notification custom
     */
    public static function create($userId, $type, $title, $message, $url = null)
    {
        Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'url' => $url
        ]);

        return true;
    }

    /**
     * Create notification untuk multiple users
     */
    public static function createForMultiple($userIds, $type, $title, $message, $url = null)
    {
        foreach ($userIds as $userId) {
            self::create($userId, $type, $title, $message, $url);
        }

        return true;
    }
}