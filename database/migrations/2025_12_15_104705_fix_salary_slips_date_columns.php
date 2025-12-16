<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('salary_slips', function (Blueprint $table) {
            // Ubah dari DATETIME ke DATE
            $table->date('periode_start')->change();
            $table->date('periode_end')->change();
            
            // Pastikan kolom ini ada dan bertipe DATE
            if (!Schema::hasColumn('salary_slips', 'tanggal_bayar')) {
                $table->date('tanggal_bayar')->nullable();
            } else {
                $table->date('tanggal_bayar')->nullable()->change();
            }
        });
    }

    public function down()
    {
        Schema::table('salary_slips', function (Blueprint $table) {
            $table->dateTime('periode_start')->change();
            $table->dateTime('periode_end')->change();
            $table->dateTime('tanggal_bayar')->nullable()->change();
        });
    }
};