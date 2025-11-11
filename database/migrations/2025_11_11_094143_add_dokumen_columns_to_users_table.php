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
        Schema::table('users', function (Blueprint $table) {
            $table->string('foto_ktp')->nullable()->after('foto');
            $table->string('foto_npwp')->nullable()->after('foto_ktp');
            $table->string('foto_bpjs')->nullable()->after('foto_npwp');
            $table->string('dokumen_kontrak')->nullable()->after('foto_bpjs');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['foto_ktp', 'foto_npwp', 'foto_bpjs', 'dokumen_kontrak']);
        });
    }
};