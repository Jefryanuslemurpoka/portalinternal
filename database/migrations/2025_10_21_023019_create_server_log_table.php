// ============================================
// 6. create_server_log_table.php
// ============================================
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('server_log', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('aktivitas');
            $table->enum('status', ['normal', 'warning', 'error'])->default('normal');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('server_log');
    }
};