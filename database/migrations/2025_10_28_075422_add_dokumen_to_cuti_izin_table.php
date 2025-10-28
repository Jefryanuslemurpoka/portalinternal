<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cuti_izin', function (Blueprint $table) {
            $table->string('dokumen')->nullable()->after('alasan');
        });
    }

    public function down(): void
    {
        Schema::table('cuti_izin', function (Blueprint $table) {
            $table->dropColumn('dokumen');
        });
    }
};