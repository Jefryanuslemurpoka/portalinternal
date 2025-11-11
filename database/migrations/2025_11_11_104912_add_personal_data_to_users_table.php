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
            $table->string('nik', 16)->nullable()->after('name');
            $table->string('gelar')->nullable()->after('name');
            $table->text('alamat')->nullable()->after('email');
            $table->string('tempat_lahir')->nullable()->after('alamat');
            $table->date('tanggal_lahir')->nullable()->after('tempat_lahir');
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable()->after('tanggal_lahir');
            $table->string('nomor_rekening')->nullable()->after('jenis_kelamin');
            $table->date('aktif_dari')->nullable()->after('status');
            $table->date('aktif_sampai')->nullable()->after('aktif_dari');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'nik',
                'gelar',
                'alamat',
                'tempat_lahir',
                'tanggal_lahir',
                'jenis_kelamin',
                'nomor_rekening',
                'aktif_dari',
                'aktif_sampai'
            ]);
        });
    }
};