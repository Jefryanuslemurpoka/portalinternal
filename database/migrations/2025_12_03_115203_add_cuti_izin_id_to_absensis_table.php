<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('absensi', function (Blueprint $table) {
            // Hapus ->after('status') karena kolom status tidak ada
            $table->unsignedBigInteger('cuti_izin_id')->nullable();
            $table->foreign('cuti_izin_id')->references('id')->on('cuti_izin')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensi', function (Blueprint $table) {
            $table->dropForeign(['cuti_izin_id']);
            $table->dropColumn('cuti_izin_id');
        });
    }
};