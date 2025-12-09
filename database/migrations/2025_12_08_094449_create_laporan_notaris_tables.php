<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel Master Laporan Bulanan
        Schema::create('laporan_notaris', function (Blueprint $table) {
            $table->id();
            $table->string('bulan'); // Format: YYYY-MM (contoh: 2025-08)
            $table->year('tahun');
            $table->string('nama_bulan'); // Agustus, September, dst
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->integer('total_gamal')->default(0);
            $table->integer('total_eny')->default(0);
            $table->integer('total_nyoman')->default(0);
            $table->integer('total_otty')->default(0);
            $table->integer('total_kartika')->default(0);
            $table->integer('total_retno')->default(0);
            $table->integer('total_neltje')->default(0);
            $table->integer('grand_total')->default(0);
            $table->timestamps();
        });

        // Tabel Detail Harian
        Schema::create('laporan_notaris_detail', function (Blueprint $table) {
            $table->id();
            $table->foreignId('laporan_notaris_id')->constrained('laporan_notaris')->onDelete('cascade');
            $table->date('tanggal');
            $table->integer('gamal')->default(0);
            $table->integer('eny')->default(0);
            $table->integer('nyoman')->default(0);
            $table->integer('otty')->default(0);
            $table->integer('kartika')->default(0);
            $table->integer('retno')->default(0);
            $table->integer('neltje')->default(0);
            $table->integer('total')->default(0);
            $table->boolean('is_libur')->default(false); // Tandai hari libur/merah
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_notaris_detail');
        Schema::dropIfExists('laporan_notaris');
    }
};