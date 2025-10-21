<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@portal.com',
            'password' => Hash::make('admin123'),
            'role' => 'super_admin',
            'divisi' => 'IT',
            'status' => 'aktif',
        ]);

        // Dummy Karyawan 1
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@portal.com',
            'password' => Hash::make('karyawan123'),
            'role' => 'karyawan',
            'divisi' => 'IT',
            'status' => 'aktif',
        ]);

        // Dummy Karyawan 2
        User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti@portal.com',
            'password' => Hash::make('karyawan123'),
            'role' => 'karyawan',
            'divisi' => 'HRD',
            'status' => 'aktif',
        ]);

        // Dummy Karyawan 3
        User::create([
            'name' => 'Joko Widodo',
            'email' => 'joko@portal.com',
            'password' => Hash::make('karyawan123'),
            'role' => 'karyawan',
            'divisi' => 'Marketing',
            'status' => 'aktif',
        ]);

        // Dummy Karyawan 4
        User::create([
            'name' => 'Ani Yudhoyono',
            'email' => 'ani@portal.com',
            'password' => Hash::make('karyawan123'),
            'role' => 'karyawan',
            'divisi' => 'Finance',
            'status' => 'aktif',
        ]);

        // Dummy Karyawan 5 (Non-aktif)
        User::create([
            'name' => 'Dewi Lestari',
            'email' => 'dewi@portal.com',
            'password' => Hash::make('karyawan123'),
            'role' => 'karyawan',
            'divisi' => 'HRD',
            'status' => 'nonaktif',
        ]);
    }
}