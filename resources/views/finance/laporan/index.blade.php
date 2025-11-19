@extends('layouts.app')

@section('title', 'Laporan Keuangan')
@section('page-title', 'Laporan Keuangan')

@section('content')
<div class="space-y-6">

    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Laporan Keuangan</h2>
            <p class="text-gray-600 text-sm">Lihat dan export laporan keuangan</p>
        </div>
        <a href="{{ route('finance.laporan.export') }}" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-lg shadow-lg transition">
            <i class="fas fa-download mr-2"></i>Export Laporan
        </a>
    </div>

    <!-- Quick Links -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <a href="{{ route('finance.laporan.bulanan') }}" class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-teal-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-teal-600 text-xl"></i>
                </div>
                <div>
                    <p class="font-semibold text-gray-900">Laporan Bulanan</p>
                    <p class="text-sm text-gray-500">Per bulan</p>
                </div>
            </div>
        </a>

        <a href="{{ route('finance.laporan.tahunan') }}" class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-cyan-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-calendar text-cyan-600 text-xl"></i>
                </div>
                <div>
                    <p class="font-semibold text-gray-900">Laporan Tahunan</p>
                    <p class="text-sm text-gray-500">Per tahun</p>
                </div>
            </div>
        </a>

        <a href="{{ route('finance.laporan.export') }}" class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition">
            <div class="flex items-center space-x-4">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-file-excel text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="font-semibold text-gray-900">Export Data</p>
                    <p class="text-sm text-gray-500">Excel / PDF</p>
                </div>
            </div>
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-lg p-12 text-center">
        <i class="fas fa-file-invoice-dollar text-6xl text-gray-300 mb-4"></i>
        <h3 class="text-xl font-bold text-gray-700 mb-2">Modul Laporan Keuangan</h3>
        <p class="text-gray-500">Halaman ini sedang dalam pengembangan</p>
    </div>

</div>
@endsection