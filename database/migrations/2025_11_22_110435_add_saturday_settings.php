<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Insert default settings untuk hari Sabtu
        DB::table('settings')->insert([
            [
                'key' => 'jam_sabtu_keluar',
                'value' => '12:00',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'sabtu_setengah_hari',
                'value' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('settings')
            ->whereIn('key', ['jam_sabtu_keluar', 'sabtu_setengah_hari'])
            ->delete();
    }
};