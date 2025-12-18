@extends('layouts.app')

@section('title', 'Laporan Tahunan')
@section('page-title', 'Laporan Keuangan Tahunan')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <a href="{{ route('finance.laporan.index') }}" class="text-teal-600 hover:text-teal-700 font-medium inline-flex items-center mb-4">
                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Laporan
            </a>
            <h2 class="text-2xl font-bold text-gray-800">Laporan Keuangan Tahunan</h2>
            <p class="text-gray-600 text-sm">Ringkasan keuangan per tahun</p>
        </div>
        <div class="flex gap-3">
            <button onclick="printReport()" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-print mr-2"></i>Print
            </button>
            <button onclick="exportReport()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-download mr-2"></i>Export
            </button>
        </div>
    </div>

    <!-- Filter Tahun -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                <select class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-teal-500">
                    <option selected>2024</option>
                    <option>2023</option>
                    <option>2022</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Departemen</label>
                <select class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-teal-500">
                    <option selected>Semua Departemen</option>
                    <option>Operasional</option>
                    <option>Marketing</option>
                    <option>HRD</option>
                    <option>IT</option>
                    <option>Finance</option>
                </select>
            </div>

            <div class="flex items-end">
                <button class="w-full bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg">
                    <i class="fas fa-sync-alt mr-2"></i>Refresh Data
                </button>
            </div>
        </div>
    </div>

    <!-- Summary Tahunan -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white">
            <p class="text-sm text-blue-100">Total Pemasukan</p>
            <h3 class="text-2xl font-bold mt-2">Rp 21,6 M</h3>
            <span class="text-xs text-blue-100">Akumulasi 1 Tahun</span>
        </div>

        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl p-6 text-white">
            <p class="text-sm text-red-100">Total Pengeluaran</p>
            <h3 class="text-2xl font-bold mt-2">Rp 14,4 M</h3>
            <span class="text-xs text-red-100">Akumulasi 1 Tahun</span>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white">
            <p class="text-sm text-green-100">Saldo Akhir Tahun</p>
            <h3 class="text-2xl font-bold mt-2">Rp 7,2 M</h3>
            <span class="text-xs text-green-100">Net Profit</span>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white">
            <p class="text-sm text-purple-100">Total Transaksi</p>
            <h3 class="text-2xl font-bold mt-2">14.982</h3>
            <span class="text-xs text-purple-100">1 Tahun</span>
        </div>
    </div>

    <!-- Tren Bulanan dalam 1 Tahun -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-semibold mb-4">Tren Keuangan Tahunan</h3>

        <div class="space-y-3">
            @foreach (['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'] as $bulan)
            <div class="flex items-center gap-4">
                <span class="w-12 text-xs text-gray-600">{{ $bulan }}</span>
                <div class="flex-1 bg-gray-200 h-2 rounded-full">
                    <div class="bg-blue-500 h-2 rounded-full" style="width: {{ rand(50,90) }}%"></div>
                </div>
                <span class="text-xs text-gray-600 w-24 text-right">Pemasukan</span>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Rekap Tahunan -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 text-white p-6">
            <h3 class="text-xl font-bold">Rekap Keuangan Tahunan</h3>
            <p class="text-indigo-100 text-sm">Tahun 2024</p>
        </div>

        <div class="p-6 overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Bulan</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold">Pemasukan</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold">Pengeluaran</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold">Saldo</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @foreach (['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'] as $bulan)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $bulan }}</td>
                        <td class="px-6 py-4 text-right text-green-600 font-semibold">Rp {{ rand(120,180) }} Jt</td>
                        <td class="px-6 py-4 text-right text-red-600 font-semibold">Rp {{ rand(80,140) }} Jt</td>
                        <td class="px-6 py-4 text-right font-bold">Rp {{ rand(30,70) }} Jt</td>
                    </tr>
                    @endforeach

                    <tr class="bg-indigo-50 font-bold">
                        <td class="px-6 py-4">TOTAL</td>
                        <td class="px-6 py-4 text-right text-green-700">Rp 21,6 M</td>
                        <td class="px-6 py-4 text-right text-red-700">Rp 14,4 M</td>
                        <td class="px-6 py-4 text-right text-indigo-700">Rp 7,2 M</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
