<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('salary_slips', function (Blueprint $table) {
            // Cek kolom yang belum ada, tambahkan jika belum ada
            if (!Schema::hasColumn('salary_slips', 'tanggal_bayar')) {
                $table->date('tanggal_bayar')->nullable()->after('periode_end');
            }
            if (!Schema::hasColumn('salary_slips', 'hari_cuti')) {
                $table->integer('hari_cuti')->default(0)->after('hari_sakit');
            }
            if (!Schema::hasColumn('salary_slips', 'jumlah_terlambat')) {
                $table->integer('jumlah_terlambat')->default(0)->after('upah_lembur');
            }
            if (!Schema::hasColumn('salary_slips', 'menit_terlambat')) {
                $table->integer('menit_terlambat')->default(0)->after('jumlah_terlambat');
            }
            if (!Schema::hasColumn('salary_slips', 'detail_tunjangan')) {
                $table->json('detail_tunjangan')->nullable()->after('menit_terlambat');
            }
            if (!Schema::hasColumn('salary_slips', 'detail_potongan')) {
                $table->json('detail_potongan')->nullable()->after('detail_tunjangan');
            }
            if (!Schema::hasColumn('salary_slips', 'generated_at')) {
                $table->timestamp('generated_at')->nullable()->after('payment_notes');
            }
        });
    }

    public function down()
    {
        Schema::table('salary_slips', function (Blueprint $table) {
            $table->dropColumn([
                'tanggal_bayar',
                'hari_cuti',
                'jumlah_terlambat',
                'menit_terlambat',
                'detail_tunjangan',
                'detail_potongan',
                'generated_at'
            ]);
        });
    }
};