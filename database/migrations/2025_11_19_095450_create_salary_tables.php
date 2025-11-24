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
        // Tabel Master Gaji Karyawan
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('gaji_pokok', 15, 2)->default(0);
            $table->decimal('tunjangan_jabatan', 15, 2)->default(0);
            $table->decimal('tunjangan_transport', 15, 2)->default(0);
            $table->decimal('tunjangan_makan', 15, 2)->default(0);
            $table->decimal('tunjangan_kehadiran', 15, 2)->default(0);
            $table->decimal('tunjangan_kesehatan', 15, 2)->default(0);
            $table->decimal('tunjangan_lainnya', 15, 2)->default(0);
            $table->date('effective_date'); // Tanggal berlaku
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            
            // Index untuk performa
            $table->index(['user_id', 'status']);
            $table->index('effective_date');
        });

        // Tabel Komponen Gaji (Tunjangan & Potongan Dinamis)
        Schema::create('salary_components', function (Blueprint $table) {
            $table->id();
            $table->string('nama_komponen');
            $table->enum('jenis', ['tunjangan', 'potongan']);
            $table->enum('tipe_perhitungan', ['nominal', 'persentase'])->default('nominal');
            $table->decimal('nilai', 15, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_taxable')->default(true); // Kena pajak atau tidak
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });

        // Tabel Slip Gaji Per Periode
        Schema::create('salary_slips', function (Blueprint $table) {
            $table->id();
            $table->string('slip_number')->unique(); // Nomor slip unik
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->year('tahun');
            $table->tinyInteger('bulan');
            $table->date('periode_start');
            $table->date('periode_end');
            
            // Data Gaji
            $table->decimal('gaji_pokok', 15, 2);
            $table->decimal('total_tunjangan', 15, 2)->default(0);
            $table->decimal('total_potongan', 15, 2)->default(0);
            $table->decimal('gaji_kotor', 15, 2); // Gaji sebelum potongan
            $table->decimal('gaji_bersih', 15, 2); // Gaji setelah potongan
            
            // Data Kehadiran
            $table->integer('hari_kerja')->default(0);
            $table->integer('hari_hadir')->default(0);
            $table->integer('hari_izin')->default(0);
            $table->integer('hari_sakit')->default(0);
            $table->integer('hari_alpha')->default(0);
            $table->decimal('jam_lembur', 8, 2)->default(0);
            $table->decimal('upah_lembur', 15, 2)->default(0);
            
            // Komponen Tambahan
            $table->json('tunjangan_detail')->nullable(); // Detail tunjangan
            $table->json('potongan_detail')->nullable(); // Detail potongan
            
            // Status & Approval
            $table->enum('status', ['draft', 'pending', 'approved', 'rejected', 'paid'])->default('draft');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamp('approved_at')->nullable();
            $table->text('rejection_reason')->nullable();
            
            // Payment
            $table->date('payment_date')->nullable();
            $table->string('payment_method')->nullable(); // Transfer, Cash, dll
            $table->text('payment_notes')->nullable();
            
            $table->timestamps();
            
            // Index
            $table->index(['user_id', 'tahun', 'bulan']);
            $table->index('status');
            $table->index('slip_number');
        });

        // Tabel Assignment Komponen ke User
        Schema::create('user_salary_components', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('salary_component_id')->constrained('salary_components')->onDelete('cascade');
            $table->decimal('custom_value', 15, 2)->nullable(); // Override nilai default
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->index(['user_id', 'is_active']);
        });

        // Tabel Histori Perubahan Gaji
        Schema::create('salary_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('field_changed'); // Kolom yang berubah
            $table->text('old_value')->nullable();
            $table->text('new_value')->nullable();
            $table->date('effective_date');
            $table->foreignId('changed_by')->constrained('users');
            $table->text('reason')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'created_at']);
        });

        // Tabel Setting Perhitungan Gaji
        Schema::create('salary_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value');
            $table->string('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_histories');
        Schema::dropIfExists('user_salary_components');
        Schema::dropIfExists('salary_slips');
        Schema::dropIfExists('salary_components');
        Schema::dropIfExists('salaries');
        Schema::dropIfExists('salary_settings');
    }
};