@extends('layouts.app')

@section('title', 'Anggaran')
@section('page-title', 'Manajemen Anggaran')

@section('content')
<div class="space-y-6">

    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Daftar Anggaran</h2>
            <p class="text-gray-600 text-sm">Kelola anggaran perusahaan</p>
        </div>
        <a href="{{ route('finance.anggaran.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-lg shadow-lg transition">
            <i class="fas fa-plus mr-2"></i>Tambah Anggaran
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
        <i class="fas fa-chart-pie text-6xl text-gray-300 mb-4"></i>
        <h3 class="text-xl font-bold text-gray-700 mb-2">Modul Anggaran</h3>
        <p class="text-gray-500">Halaman ini sedang dalam pengembangan</p>
    </div>

</div>
@endsection