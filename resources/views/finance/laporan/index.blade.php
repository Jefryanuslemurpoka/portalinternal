@extends('layouts.app')

@section('title', 'Laporan Keuangan')
@section('page-title', 'Laporan Keuangan')

@section('content')
<div class="space-y-6">

    <!-- Header Actions -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Laporan Keuangan</h2>
            <p class="text-gray-600 text-sm">Generate dan kelola laporan keuangan perusahaan</p>
        </div>
        <div class="flex gap-3">
            <button onclick="printReport()" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition">
                <i class="fas fa-print mr-2"></i>Print
            </button>
            <button onclick="exportReport()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">
                <i class="fas fa-download mr-2"></i>Export
            </button>
        </div>
    </div>

    <!-- Period Selector -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Laporan</label>
                <select id="reportType" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    <option value="laba_rugi">Laba Rugi</option>
                    <option value="neraca">Neraca</option>
                    <option value="arus_kas">Arus Kas</option>
                    <option value="perubahan_modal">Perubahan Modal</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Periode</label>
                <select id="periodType" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    <option value="bulanan">Bulanan</option>
                    <option value="kuartalan">Kuartalan</option>
                    <option value="tahunan">Tahunan</option>
                    <option value="custom">Custom Range</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Bulan/Tahun</label>
                <select id="periodValue" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    <option value="2024-12">Desember 2024</option>
                    <option value="2024-11">November 2024</option>
                    <option value="2024-10">Oktober 2024</option>
                    <option value="2024">Tahun 2024</option>
                    <option value="2023">Tahun 2023</option>
                </select>
            </div>
            <div class="flex items-end">
                <button onclick="generateReport()" class="w-full bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-sync-alt mr-2"></i>Generate Laporan
                </button>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm mb-1">Total Pendapatan</p>
                    <h3 class="text-2xl font-bold">Rp 1.8 M</h3>
                </div>
                <div class="bg-orange-700/40 p-3 rounded-lg">
                    <i class="fas fa-arrow-up text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="bg-green-700 bg-opacity-50 px-2 py-1 rounded">
                    <i class="fas fa-arrow-up mr-1"></i>15.2%
                </span>
                <span class="ml-2 text-green-100">vs bulan lalu</span>
            </div>
        </div>

        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm mb-1">Total Beban</p>
                    <h3 class="text-2xl font-bold">Rp 1.2 M</h3>
                </div>
                <div class="bg-orange-700/40 p-3 rounded-lg">
                    <i class="fas fa-arrow-down text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="bg-red-700 bg-opacity-50 px-2 py-1 rounded">
                    <i class="fas fa-arrow-up mr-1"></i>8.7%
                </span>
                <span class="ml-2 text-red-100">vs bulan lalu</span>
            </div>
        </div>

        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm mb-1">Laba Bersih</p>
                    <h3 class="text-2xl font-bold">Rp 600 Jt</h3>
                </div>
                <div class="bg-orange-700/40 p-3 rounded-lg">
                    <i class="fas fa-chart-line text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="bg-blue-700 bg-opacity-50 px-2 py-1 rounded">
                    <i class="fas fa-arrow-up mr-1"></i>22.5%
                </span>
                <span class="ml-2 text-blue-100">vs bulan lalu</span>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm mb-1">Profit Margin</p>
                    <h3 class="text-2xl font-bold">33.3%</h3>
                </div>
                <div class="bg-orange-700/40 p-3 rounded-lg">
                    <i class="fas fa-percentage text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="bg-purple-700 bg-opacity-50 px-2 py-1 rounded">
                    Sehat
                </span>
                <span class="ml-2 text-purple-100">Target: 30%</span>
            </div>
        </div>
    </div>

    <!-- Laporan Laba Rugi -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-teal-600 to-teal-700 text-white p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold">Laporan Laba Rugi</h3>
                    <p class="text-teal-100 text-sm mt-1">Periode: Desember 2024</p>
                </div>
                <div class="flex gap-2">
                    <button onclick="viewComparison()" class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 font-semibold px-4 py-2 rounded-lg text-sm transition">
                        <i class="fas fa-balance-scale mr-2"></i>Bandingkan
                    </button>
                    <button onclick="exportLaporan('laba_rugi')" class="bg-green-500 hover:bg-green-600 text-white font-semibold px-4 py-2 rounded-lg text-sm transition">
                        <i class="fas fa-download mr-2"></i>Export
                    </button>
                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Keterangan</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">Nominal (Rp)</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">Persentase (%)</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <!-- PENDAPATAN -->
                        <tr class="bg-green-50">
                            <td class="px-6 py-3 font-bold text-gray-900">PENDAPATAN</td>
                            <td class="px-6 py-3"></td>
                            <td class="px-6 py-3"></td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 text-gray-700 pl-12">Pendapatan Penjualan</td>
                            <td class="px-6 py-3 text-right text-gray-900">1.650.000.000</td>
                            <td class="px-6 py-3 text-right text-gray-600">91.7%</td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 text-gray-700 pl-12">Pendapatan Jasa</td>
                            <td class="px-6 py-3 text-right text-gray-900">150.000.000</td>
                            <td class="px-6 py-3 text-right text-gray-600">8.3%</td>
                        </tr>
                        <tr class="bg-green-100 font-semibold">
                            <td class="px-6 py-3 text-gray-900">Total Pendapatan</td>
                            <td class="px-6 py-3 text-right text-gray-900">1.800.000.000</td>
                            <td class="px-6 py-3 text-right text-gray-900">100%</td>
                        </tr>

                        <!-- BEBAN OPERASIONAL -->
                        <tr class="bg-red-50">
                            <td class="px-6 py-3 font-bold text-gray-900">BEBAN OPERASIONAL</td>
                            <td class="px-6 py-3"></td>
                            <td class="px-6 py-3"></td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 text-gray-700 pl-12">Beban Gaji & Tunjangan</td>
                            <td class="px-6 py-3 text-right text-gray-900">450.000.000</td>
                            <td class="px-6 py-3 text-right text-gray-600">37.5%</td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 text-gray-700 pl-12">Beban Operasional</td>
                            <td class="px-6 py-3 text-right text-gray-900">315.000.000</td>
                            <td class="px-6 py-3 text-right text-gray-600">26.3%</td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 text-gray-700 pl-12">Beban Marketing</td>
                            <td class="px-6 py-3 text-right text-gray-900">218.000.000</td>
                            <td class="px-6 py-3 text-right text-gray-600">18.2%</td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 text-gray-700 pl-12">Beban IT & Teknologi</td>
                            <td class="px-6 py-3 text-right text-gray-900">135.000.000</td>
                            <td class="px-6 py-3 text-right text-gray-600">11.3%</td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 text-gray-700 pl-12">Beban Utilitas</td>
                            <td class="px-6 py-3 text-right text-gray-900">54.000.000</td>
                            <td class="px-6 py-3 text-right text-gray-600">4.5%</td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 text-gray-700 pl-12">Beban Lain-lain</td>
                            <td class="px-6 py-3 text-right text-gray-900">28.000.000</td>
                            <td class="px-6 py-3 text-right text-gray-600">2.3%</td>
                        </tr>
                        <tr class="bg-red-100 font-semibold">
                            <td class="px-6 py-3 text-gray-900">Total Beban Operasional</td>
                            <td class="px-6 py-3 text-right text-gray-900">1.200.000.000</td>
                            <td class="px-6 py-3 text-right text-gray-900">100%</td>
                        </tr>

                        <!-- LABA OPERASIONAL -->
                        <tr class="bg-blue-50 font-bold">
                            <td class="px-6 py-4 text-gray-900">LABA OPERASIONAL</td>
                            <td class="px-6 py-4 text-right text-blue-600 text-lg">600.000.000</td>
                            <td class="px-6 py-4 text-right text-blue-600 text-lg">33.3%</td>
                        </tr>

                        <!-- PENDAPATAN & BEBAN LAIN -->
                        <tr class="bg-gray-50">
                            <td class="px-6 py-3 font-bold text-gray-900">PENDAPATAN & BEBAN LAIN-LAIN</td>
                            <td class="px-6 py-3"></td>
                            <td class="px-6 py-3"></td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 text-gray-700 pl-12">Pendapatan Bunga</td>
                            <td class="px-6 py-3 text-right text-green-600">15.000.000</td>
                            <td class="px-6 py-3 text-right text-gray-600">-</td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 text-gray-700 pl-12">Beban Bunga</td>
                            <td class="px-6 py-3 text-right text-red-600">(8.000.000)</td>
                            <td class="px-6 py-3 text-right text-gray-600">-</td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 text-gray-700 pl-12">Pendapatan Lain-lain</td>
                            <td class="px-6 py-3 text-right text-green-600">5.000.000</td>
                            <td class="px-6 py-3 text-right text-gray-600">-</td>
                        </tr>
                        <tr class="bg-gray-100 font-semibold">
                            <td class="px-6 py-3 text-gray-900">Total Pendapatan/Beban Lain</td>
                            <td class="px-6 py-3 text-right text-gray-900">12.000.000</td>
                            <td class="px-6 py-3 text-right text-gray-900">-</td>
                        </tr>

                        <!-- LABA SEBELUM PAJAK -->
                        <tr class="bg-indigo-50 font-bold">
                            <td class="px-6 py-4 text-gray-900">LABA SEBELUM PAJAK</td>
                            <td class="px-6 py-4 text-right text-indigo-600 text-lg">612.000.000</td>
                            <td class="px-6 py-4 text-right text-indigo-600 text-lg">34.0%</td>
                        </tr>

                        <!-- PAJAK -->
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-3 text-gray-700 pl-12">Pajak Penghasilan (PPh)</td>
                            <td class="px-6 py-3 text-right text-red-600">(12.000.000)</td>
                            <td class="px-6 py-3 text-right text-gray-600">2.0%</td>
                        </tr>

                        <!-- LABA BERSIH -->
                        <tr class="bg-gradient-to-r from-green-500 to-green-600 text-white font-bold">
                            <td class="px-6 py-5 text-lg">LABA BERSIH</td>
                            <td class="px-6 py-5 text-right text-2xl">600.000.000</td>
                            <td class="px-6 py-5 text-right text-xl">33.3%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Quick Report Links -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition cursor-pointer" onclick="showReport('neraca')">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-balance-scale text-blue-600 text-xl"></i>
                </div>
                <span class="text-xs font-semibold text-blue-600 bg-blue-100 px-3 py-1 rounded-full">NERACA</span>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Laporan Neraca</h3>
            <p class="text-sm text-gray-600 mb-4">Posisi keuangan perusahaan (Aset, Liabilitas, Ekuitas)</p>
            <div class="flex items-center justify-between text-sm">
                <span class="text-gray-500">Per 31 Des 2024</span>
                <button class="text-blue-600 hover:text-blue-800 font-medium">
                    Lihat Detail →
                </button>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition cursor-pointer" onclick="showReport('arus_kas')">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-water text-green-600 text-xl"></i>
                </div>
                <span class="text-xs font-semibold text-green-600 bg-green-100 px-3 py-1 rounded-full">CASH FLOW</span>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Laporan Arus Kas</h3>
            <p class="text-sm text-gray-600 mb-4">Arus kas masuk dan keluar (Operasi, Investasi, Pendanaan)</p>
            <div class="flex items-center justify-between text-sm">
                <span class="text-gray-500">Desember 2024</span>
                <button class="text-green-600 hover:text-green-800 font-medium">
                    Lihat Detail →
                </button>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6 hover:shadow-xl transition cursor-pointer" onclick="showReport('perubahan_modal')">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-chart-pie text-purple-600 text-xl"></i>
                </div>
                <span class="text-xs font-semibold text-purple-600 bg-purple-100 px-3 py-1 rounded-full">EQUITY</span>
            </div>
            <h3 class="text-lg font-bold text-gray-900 mb-2">Perubahan Modal</h3>
            <p class="text-sm text-gray-600 mb-4">Perubahan ekuitas pemilik selama periode berjalan</p>
            <div class="flex items-center justify-between text-sm">
                <span class="text-gray-500">Tahun 2024</span>
                <button class="text-purple-600 hover:text-purple-800 font-medium">
                    Lihat Detail →
                </button>
            </div>
        </div>
    </div>

    <!-- Financial Ratios -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Rasio Keuangan</h3>
                <p class="text-sm text-gray-500">Analisis kesehatan keuangan perusahaan</p>
            </div>
            <button onclick="viewDetailRatio()" class="text-teal-600 hover:text-teal-700 font-medium text-sm">
                Lihat Detail →
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-gray-600">Profit Margin</span>
                    <i class="fas fa-percentage text-blue-500"></i>
                </div>
                <p class="text-2xl font-bold text-gray-900">33.3%</p>
                <p class="text-xs text-green-600 mt-1">
                    <i class="fas fa-arrow-up mr-1"></i>Baik (Target: 30%)
                </p>
            </div>

            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-gray-600">ROI (Return on Investment)</span>
                    <i class="fas fa-chart-line text-green-500"></i>
                </div>
                <p class="text-2xl font-bold text-gray-900">24.5%</p>
                <p class="text-xs text-green-600 mt-1">
                    <i class="fas fa-arrow-up mr-1"></i>Sangat Baik
                </p>
            </div>

            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-gray-600">Current Ratio</span>
                    <i class="fas fa-balance-scale text-purple-500"></i>
                </div>
                <p class="text-2xl font-bold text-gray-900">2.8</p>
                <p class="text-xs text-green-600 mt-1">
                    <i class="fas fa-check-circle mr-1"></i>Sehat
                </p>
            </div>

            <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm text-gray-600">Debt to Equity</span>
                    <i class="fas fa-university text-orange-500"></i>
                </div>
                <p class="text-2xl font-bold text-gray-900">0.45</p>
                <p class="text-xs text-green-600 mt-1">
                    <i class="fas fa-check-circle mr-1"></i>Rendah (Baik)
                </p>
            </div>
        </div>
    </div>

    <!-- Trend Analysis -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Revenue vs Expense Trend -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Tren Pendapatan vs Beban</h3>
                    <p class="text-sm text-gray-500">6 Bulan Terakhir</p>
                </div>
                <div class="bg-teal-100 p-2 rounded-lg">
                    <i class="fas fa-chart-area text-teal-600"></i>
                </div>
            </div>

            <div class="space-y-4">
                <div class="flex items-center gap-4">
                    <div class="text-sm font-medium text-gray-700 w-24">Jul 2024</div>
                    <div class="flex-1 space-y-1">
                        <div class="flex items-center gap-2">
                            <div class="flex-1 bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: 75%"></div>
                            </div>
                            <span class="text-xs text-gray-600 w-20 text-right">Rp 1.5M</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="flex-1 bg-gray-200 rounded-full h-2">
                                <div class="bg-red-500 h-2 rounded-full" style="width: 50%"></div>
                            </div>
                            <span class="text-xs text-gray-600 w-20 text-right">Rp 1.0M</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="text-sm font-medium text-gray-700 w-24">Aug 2024</div>
                    <div class="flex-1 space-y-1">
                        <div class="flex items-center gap-2">
                            <div class="flex-1 bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: 78%"></div>
                            </div>
                            <span class="text-xs text-gray-600 w-20 text-right">Rp 1.56M</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="flex-1 bg-gray-200 rounded-full h-2">
                                <div class="bg-red-500 h-2 rounded-full" style="width: 52%"></div>
                            </div>
                            <span class="text-xs text-gray-600 w-20 text-right">Rp 1.04M</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="text-sm font-medium text-gray-700 w-24">Sep 2024</div>
                    <div class="flex-1 space-y-1">
                        <div class="flex items-center gap-2">
                            <div class="flex-1 bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: 82%"></div>
                            </div>
                            <span class="text-xs text-gray-600 w-20 text-right">Rp 1.64M</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="flex-1 bg-gray-200 rounded-full h-2">
                                <div class="bg-red-500 h-2 rounded-full" style="width: 55%"></div>
                            </div>
                            <span class="text-xs text-gray-600 w-20 text-right">Rp 1.1M</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="text-sm font-medium text-gray-700 w-24">Oct 2024</div>
                    <div class="flex-1 space-y-1">
                        <div class="flex items-center gap-2">
                            <div class="flex-1 bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: 85%"></div>
                            </div>
                            <span class="text-xs text-gray-600 w-20 text-right">Rp 1.7M</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="flex-1 bg-gray-200 rounded-full h-2">
                                <div class="bg-red-500 h-2 rounded-full" style="width: 57%"></div>
                            </div>
                            <span class="text-xs text-gray-600 w-20 text-right">Rp 1.14M</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="text-sm font-medium text-gray-700 w-24">Nov 2024</div>
                    <div class="flex-1 space-y-1">
                        <div class="flex items-center gap-2">
                            <div class="flex-1 bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: 88%"></div>
                            </div>
                            <span class="text-xs text-gray-600 w-20 text-right">Rp 1.76M</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="flex-1 bg-gray-200 rounded-full h-2">
                                <div class="bg-red-500 h-2 rounded-full" style="width: 58%"></div>
                            </div>
                            <span class="text-xs text-gray-600 w-20 text-right">Rp 1.16M</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="text-sm font-medium text-gray-700 w-24">Dec 2024</div>
                    <div class="flex-1 space-y-1">
                        <div class="flex items-center gap-2">
                            <div class="flex-1 bg-gray-200 rounded-full h-2">
                                <div class="bg-green-500 h-2 rounded-full" style="width: 90%"></div>
                            </div>
                            <span class="text-xs text-gray-600 w-20 text-right">Rp 1.8M</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="flex-1 bg-gray-200 rounded-full h-2">
                                <div class="bg-red-500 h-2 rounded-full" style="width: 60%"></div>
                            </div>
                            <span class="text-xs text-gray-600 w-20 text-right">Rp 1.2M</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4 pt-2 border-t">
                    <div class="flex items-center gap-2 flex-1">
                        <div class="w-3 h-3 bg-green-500 rounded"></div>
                        <span class="text-xs text-gray-600">Pendapatan</span>
                    </div>
                    <div class="flex items-center gap-2 flex-1">
                        <div class="w-3 h-3 bg-red-500 rounded"></div>
                        <span class="text-xs text-gray-600">Beban</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profit Trend -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Tren Laba Bersih</h3>
                    <p class="text-sm text-gray-500">6 Bulan Terakhir</p>
                </div>
                <div class="bg-blue-100 p-2 rounded-lg">
                    <i class="fas fa-chart-line text-blue-600"></i>
                </div>
            </div>

            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div>
                        <p class="text-sm font-medium text-gray-700">Juli 2024</p>
                        <p class="text-xs text-gray-500">Margin: 31.5%</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-green-600">Rp 500 Jt</p>
                        <p class="text-xs text-gray-500">vs budget: +8%</p>
                    </div>
                </div>

                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div>
                        <p class="text-sm font-medium text-gray-700">Agustus 2024</p>
                        <p class="text-xs text-gray-500">Margin: 32.1%</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-green-600">Rp 520 Jt</p>
                        <p class="text-xs text-gray-500">vs budget: +10%</p>
                    </div>
                </div>

                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div>
                        <p class="text-sm font-medium text-gray-700">September 2024</p>
                        <p class="text-xs text-gray-500">Margin: 32.8%</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-green-600">Rp 540 Jt</p>
                        <p class="text-xs text-gray-500">vs budget: +12%</p>
                    </div>
                </div>

                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div>
                        <p class="text-sm font-medium text-gray-700">Oktober 2024</p>
                        <p class="text-xs text-gray-500">Margin: 32.9%</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-green-600">Rp 560 Jt</p>
                        <p class="text-xs text-gray-500">vs budget: +14%</p>
                    </div>
                </div>

                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div>
                        <p class="text-sm font-medium text-gray-700">November 2024</p>
                        <p class="text-xs text-gray-500">Margin: 33.0%</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-green-600">Rp 580 Jt</p>
                        <p class="text-xs text-gray-500">vs budget: +15%</p>
                    </div>
                </div>

                <div class="flex items-center justify-between p-3 bg-green-50 border border-green-200 rounded-lg">
                    <div>
                        <p class="text-sm font-semibold text-gray-900">Desember 2024</p>
                        <p class="text-xs text-green-600">Margin: 33.3%</p>
                    </div>
                    <div class="text-right">
                        <p class="text-lg font-bold text-green-600">Rp 600 Jt</p>
                        <p class="text-xs text-green-600 font-medium">vs budget: +16%</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Export & Download Options -->
    <div class="bg-gradient-to-r from-teal-600 to-teal-700 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-xl font-bold mb-2">Export Laporan Keuangan</h3>
                <p class="text-teal-100 text-sm">Download laporan dalam berbagai format untuk keperluan analisis dan dokumentasi</p>
            </div>
            <div class="flex gap-3">
                <button onclick="exportPDF()" class="bg-white text-teal-600 px-6 py-3 rounded-lg font-semibold hover:bg-teal-50 transition">
                    <i class="fas fa-file-pdf mr-2"></i>Export PDF
                </button>
                <button onclick="exportExcel()" class="bg-white text-teal-600 px-6 py-3 rounded-lg font-semibold hover:bg-teal-50 transition">
                    <i class="fas fa-file-excel mr-2"></i>Export Excel
                </button>
                <button onclick="exportCSV()" class="bg-white text-teal-600 px-6 py-3 rounded-lg font-semibold hover:bg-teal-50 transition">
                    <i class="fas fa-file-csv mr-2"></i>Export CSV
                </button>
            </div>
        </div>
    </div>

</div>

<!-- Modal Comparison -->
<div id="modalComparison" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-5xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-gradient-to-r from-teal-600 to-teal-700 text-white p-6 rounded-t-2xl">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-bold">Perbandingan Laporan Laba Rugi</h3>
                    <p class="text-teal-100 text-sm mt-1">Periode: Nov 2024 vs Des 2024</p>
                </div>
                <button onclick="closeModal()" class="text-white hover:text-gray-200 transition">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
        </div>

        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Keterangan</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">Nov 2024</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">Des 2024</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">Variance</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">%</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr class="bg-green-50">
                            <td class="px-6 py-3 font-bold text-gray-900">Total Pendapatan</td>
                            <td class="px-6 py-3 text-right text-gray-900">1.760.000.000</td>
                            <td class="px-6 py-3 text-right text-gray-900">1.800.000.000</td>
                            <td class="px-6 py-3 text-right text-green-600 font-semibold">40.000.000</td>
                            <td class="px-6 py-3 text-right text-green-600 font-semibold">+2.3%</td>
                        </tr>
                        <tr class="bg-red-50">
                            <td class="px-6 py-3 font-bold text-gray-900">Total Beban</td>
                            <td class="px-6 py-3 text-right text-gray-900">1.180.000.000</td>
                            <td class="px-6 py-3 text-right text-gray-900">1.200.000.000</td>
                            <td class="px-6 py-3 text-right text-red-600 font-semibold">20.000.000</td>
                            <td class="px-6 py-3 text-right text-red-600 font-semibold">+1.7%</td>
                        </tr>
                        <tr class="bg-blue-50">
                            <td class="px-6 py-3 font-bold text-gray-900">Laba Operasional</td>
                            <td class="px-6 py-3 text-right text-gray-900">580.000.000</td>
                            <td class="px-6 py-3 text-right text-gray-900">600.000.000</td>
                            <td class="px-6 py-3 text-right text-blue-600 font-semibold">20.000.000</td>
                            <td class="px-6 py-3 text-right text-blue-600 font-semibold">+3.4%</td>
                        </tr>
                        <tr class="bg-green-100 font-bold">
                            <td class="px-6 py-4 text-gray-900">Laba Bersih</td>
                            <td class="px-6 py-4 text-right text-gray-900 text-lg">580.000.000</td>
                            <td class="px-6 py-4 text-right text-gray-900 text-lg">600.000.000</td>
                            <td class="px-6 py-4 text-right text-green-600 text-lg">20.000.000</td>
                            <td class="px-6 py-4 text-right text-green-600 text-lg">+3.4%</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <h4 class="font-semibold text-gray-900 mb-2">Analisis Perbandingan:</h4>
                <ul class="space-y-1 text-sm text-gray-700">
                    <li>• Pendapatan meningkat 2.3% dari bulan sebelumnya, menunjukkan pertumbuhan yang konsisten</li>
                    <li>• Beban operasional meningkat 1.7%, masih dalam batas wajar dan lebih rendah dari kenaikan pendapatan</li>
                    <li>• Laba bersih meningkat 3.4%, mengindikasikan efisiensi operasional yang baik</li>
                    <li>• Profit margin tetap stabil di kisaran 33%, memenuhi target perusahaan</li>
                </ul>
            </div>

            <div class="flex gap-3 pt-4 border-t border-gray-200 mt-6">
                <button onclick="exportComparison()" class="flex-1 bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-semibold transition">
                    <i class="fas fa-file-export mr-2"></i>Export Comparison
                </button>
                <button onclick="closeModal()" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-semibold transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Report Type Change
document.getElementById('reportType').addEventListener('change', function() {
    console.log('Report type changed to:', this.value);
    // Implementasi untuk mengubah tampilan laporan berdasarkan jenis
});

document.getElementById('periodType').addEventListener('change', function() {
    console.log('Period type changed to:', this.value);
    // Implementasi untuk filter periode
});

// Generate Report
function generateReport() {
    const reportType = document.getElementById('reportType').value;
    const periodType = document.getElementById('periodType').value;
    const periodValue = document.getElementById('periodValue').value;
    
    console.log('Generating report:', { reportType, periodType, periodValue });
    alert(`Generate laporan ${reportType} untuk periode ${periodValue}`);
    // Implementasi generate laporan
}

// Show Different Reports
function showReport(type) {
    console.log('Showing report:', type);
    alert(`Menampilkan laporan ${type}`);
    // Implementasi untuk menampilkan jenis laporan berbeda
}

// View Comparison
function viewComparison() {
    document.getElementById('modalComparison').classList.remove('hidden');
}

// View Detail Ratio
function viewDetailRatio() {
    alert('Menampilkan detail analisis rasio keuangan');
}

// Export Functions
function exportReport() {
    alert('Export laporan sedang diproses...');
}

function exportLaporan(type) {
    alert(`Export laporan ${type} sedang diproses...`);
}

function exportPDF() {
    alert('Export PDF sedang diproses...');
}

function exportExcel() {
    alert('Export Excel sedang diproses...');
}

function exportCSV() {
    alert('Export CSV sedang diproses...');
}

function exportComparison() {
    alert('Export comparison sedang diproses...');
}

function printReport() {
    window.print();
}

// Modal Functions
function closeModal() {
    document.getElementById('modalComparison').classList.add('hidden');
}

// Close modal when clicking outside
document.getElementById('modalComparison').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

// ESC key to close modal
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
    }
});
</script>

@endsection