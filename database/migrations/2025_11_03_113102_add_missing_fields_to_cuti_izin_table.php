<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cuti_izin', function (Blueprint $table) {
            // Cek apakah kolom sudah ada sebelum menambahkan
            if (!Schema::hasColumn('cuti_izin', 'tanggal_pengajuan')) {
                $table->timestamp('tanggal_pengajuan')->nullable()->after('jenis');
            }
            
            if (!Schema::hasColumn('cuti_izin', 'jumlah_hari')) {
                $table->integer('jumlah_hari')->default(0)->after('tanggal_selesai');
            }
            
            if (!Schema::hasColumn('cuti_izin', 'file_pendukung')) {
                $table->string('file_pendukung')->nullable()->after('alasan');
            }
            
            if (!Schema::hasColumn('cuti_izin', 'catatan_admin')) {
                $table->text('catatan_admin')->nullable()->after('keterangan_approval');
            }
            
            if (!Schema::hasColumn('cuti_izin', 'tanggal_approval')) {
                $table->timestamp('tanggal_approval')->nullable()->after('catatan_admin');
            }
        });
    }

    public function down(): void
    {
        Schema::table('cuti_izin', function (Blueprint $table) {
            $columns = ['tanggal_pengajuan', 'jumlah_hari', 'file_pendukung', 'catatan_admin', 'tanggal_approval'];
            
            foreach ($columns as $column) {
                if (Schema::hasColumn('cuti_izin', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};