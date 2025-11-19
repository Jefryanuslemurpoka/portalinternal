@extends('layouts.app')

@section('title', 'Gaji Karyawan')
@section('page-title', 'Manajemen Gaji Karyawan')

@section('content')
<div class="space-y-6">

    <!-- Header Actions -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Daftar Gaji Karyawan</h2>
            <p class="text-gray-600 text-sm">Kelola data gaji karyawan</p>
        </div>
        <a href="{{ route('finance.gaji.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-lg shadow-lg transition">
            <i class="fas fa-plus mr-2"></i>Tambah Data Gaji
        </a>
    </div>

    <!-- Content Placeholder -->
    <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
        <i class="fas fa-wallet text-6xl text-gray-300 mb-4"></i>
        <h3 class="text-xl font-bold text-gray-700 mb-2">Modul Gaji Karyawan</h3>
        <p class="text-gray-500">Halaman ini sedang dalam pengembangan</p>
    </div>

</div>
@endsection