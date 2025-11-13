<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambah kolom phone setelah email
            $table->string('phone', 20)->nullable()->after('email');
            
            // Tambah kolom sisa_cuti setelah aktif_sampai
            $table->integer('sisa_cuti')->default(12)->nullable()->after('aktif_sampai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'sisa_cuti']);
        });
    }
};