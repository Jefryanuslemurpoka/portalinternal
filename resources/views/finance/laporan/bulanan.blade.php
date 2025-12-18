@extends('layouts.app')

@section('title', 'Laporan Bulanan')
@section('page-title', 'Laporan Keuangan Bulanan')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <a href="{{ route('finance.laporan.index') }}" class="text-teal-600 hover:text-teal-700 font-medium inline-flex items-center mb-4">
                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Laporan
            </a>
            <h2 class="text-2xl font-bold text-gray-800">Laporan Keuangan Bulanan</h2>
            <p class="text-gray-600 text-sm">Detail laporan keuangan per bulan</p>
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
                <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                <select id="tahun" onchange="loadMonthlyReport()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    <option value="2024" selected>2024</option>
                    <option value="2023">2023</option>
                    <option value="2022">2022</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                <select id="bulan" onchange="loadMonthlyReport()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    <option value="12" selected>Desember</option>
                    <option value="11">November</option>
                    <option value="10">Oktober</option>
                    <option value="9">September</option>
                    <option value="8">Agustus</option>
                    <option value="7">Juli</option>
                    <option value="6">Juni</option>
                    <option value="5">Mei</option>
                    <option value="4">April</option>
                    <option value="3">Maret</option>
                    <option value="2">Februari</option>
                    <option value="1">Januari</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Departemen</label>
                <select id="departemen" onchange="loadMonthlyReport()" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    <option value="all" selected>Semua Departemen</option>
                    <option value="operasional">Operasional</option>
                    <option value="marketing">Marketing</option>
                    <option value="hrd">HRD</option>
                    <option value="it">IT</option>
                    <option value="finance">Finance</option>
                </select>
            </div>
            <div class="flex items-end">
                <button onclick="loadMonthlyReport()" class="w-full bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-sync-alt mr-2"></i>Refresh Data
                </button>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-blue-100 text-sm mb-1">Total Pemasukan</p>
                    <h3 class="text-2xl font-bold">Rp 1.8 M</h3>
                </div>
                <<div class="bg-orange-700/40 p-3 rounded-lg">
                    <i class="fas fa-arrow-down text-2xl"></i>
                </div>
            </div>
            <div class="flex items-center text-sm">
                <span class="bg-blue-700 bg-opacity-50 px-2 py-1 rounded">
                    <i class="fas fa-arrow-up mr-1"></i>12.5%
                </span>
                <span class="ml-2 text-blue-100">dari bulan lalu</span>
            </div>
        </div>

        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-red-100 text-sm mb-1">Total Pengeluaran</p>
                    <h3 class="text-2xl font-bold">Rp 1.2 M</h3>
                </div>
                <div class="bg-orange-700/40 p-3 rounded-lg">
                    <i class="fas fa-arrow-up text-2xl"></i>
                </div>
            </div>
            <div class="flex items-center text-sm">
                <span class="bg-red-700 bg-opacity-50 px-2 py-1 rounded">
                    <i class="fas fa-arrow-up mr-1"></i>8.3%
                </span>
                <span class="ml-2 text-red-100">dari bulan lalu</span>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-green-100 text-sm mb-1">Saldo Akhir</p>
                    <h3 class="text-2xl font-bold">Rp 600 Jt</h3>
                </div>
                <div class="bg-orange-700/40 p-3 rounded-lg">
                    <i class="fas fa-wallet text-2xl"></i>
                </div>
            </div>
            <div class="flex items-center text-sm">
                <span class="bg-green-700 bg-opacity-50 px-2 py-1 rounded">
                    <i class="fas fa-arrow-up mr-1"></i>18.2%
                </span>
                <span class="ml-2 text-green-100">dari bulan lalu</span>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-purple-100 text-sm mb-1">Total Transaksi</p>
                    <h3 class="text-2xl font-bold">1,247</h3>
                </div>
                <div class="bg-orange-700/40 p-3 rounded-lg">
                    <i class="fas fa-exchange-alt text-2xl"></i>
                </div>
            </div>
            <div class="flex items-center text-sm">
                <span class="bg-purple-700 bg-opacity-50 px-2 py-1 rounded">
                    <i class="fas fa-arrow-up mr-1"></i>24 transaksi
                </span>
                <span class="ml-2 text-purple-100">dari bulan lalu</span>
            </div>
        </div>
    </div>

    <!-- Grafik Pemasukan vs Pengeluaran -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Chart Harian -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Tren Harian</h3>
                    <p class="text-sm text-gray-500">Desember 2024</p>
                </div>
                <div class="bg-teal-100 p-2 rounded-lg">
                    <i class="fas fa-chart-line text-teal-600"></i>
                </div>
            </div>

            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    <span class="text-xs text-gray-600 w-16">Minggu 1</span>
                    <div class="flex-1 space-y-1">
                        <div class="flex items-center gap-2">
                            <div class="flex-1 bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full" style="width: 75%"></div>
                            </div>
                            <span class="text-xs text-gray-600 w-20 text-right">Rp 450 Jt</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="flex-1 bg-gray-200 rounded-full h-2">
                                <div class="bg-red-500 h-2 rounded-full" style="width: 60%"></div>
                            </div>
                            <span class="text-xs text-gray-600 w-20 text-right">Rp 300 Jt</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <span class="text-xs text-gray-600 w-16">Minggu 2</span>
                    <div class="flex-1 space-y-1">
                        <div class="flex items-center gap-2">
                            <div class="flex-1 bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full" style="width: 70%"></div>
                            </div>
                            <span class="text-xs text-gray-600 w-20 text-right">Rp 420 Jt</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="flex-1 bg-gray-200 rounded-full h-2">
                                <div class="bg-red-500 h-2 rounded-full" style="width: 55%"></div>
                            </div>
                            <span class="text-xs text-gray-600 w-20 text-right">Rp 275 Jt</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <span class="text-xs text-gray-600 w-16">Minggu 3</span>
                    <div class="flex-1 space-y-1">
                        <div class="flex items-center gap-2">
                            <div class="flex-1 bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full" style="width: 80%"></div>
                            </div>
                            <span class="text-xs text-gray-600 w-20 text-right">Rp 480 Jt</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="flex-1 bg-gray-200 rounded-full h-2">
                                <div class="bg-red-500 h-2 rounded-full" style="width: 62%"></div>
                            </div>
                            <span class="text-xs text-gray-600 w-20 text-right">Rp 310 Jt</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <span class="text-xs text-gray-600 w-16">Minggu 4</span>
                    <div class="flex-1 space-y-1">
                        <div class="flex items-center gap-2">
                            <div class="flex-1 bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full" style="width: 85%"></div>
                            </div>
                            <span class="text-xs text-gray-600 w-20 text-right">Rp 510 Jt</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="flex-1 bg-gray-200 rounded-full h-2">
                                <div class="bg-red-500 h-2 rounded-full" style="width: 68%"></div>
                            </div>
                            <span class="text-xs text-gray-600 w-20 text-right">Rp 340 Jt</span>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-4 pt-3 border-t">
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-blue-500 rounded"></div>
                        <span class="text-xs text-gray-600">Pemasukan</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-red-500 rounded"></div>
                        <span class="text-xs text-gray-600">Pengeluaran</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Breakdown per Kategori -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Breakdown Pengeluaran</h3>
                    <p class="text-sm text-gray-500">Per Kategori</p>
                </div>
                <div class="bg-purple-100 p-2 rounded-lg">
                    <i class="fas fa-chart-pie text-purple-600"></i>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-purple-500 rounded"></div>
                            <span class="text-sm text-gray-700">Gaji & Tunjangan</span>
                        </div>
                        <span class="text-sm font-semibold text-gray-900">Rp 450 Jt (37.5%)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-purple-500 h-2 rounded-full" style="width: 37.5%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-blue-500 rounded"></div>
                            <span class="text-sm text-gray-700">Operasional</span>
                        </div>
                        <span class="text-sm font-semibold text-gray-900">Rp 315 Jt (26.3%)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: 26.3%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-pink-500 rounded"></div>
                            <span class="text-sm text-gray-700">Marketing</span>
                        </div>
                        <span class="text-sm font-semibold text-gray-900">Rp 218 Jt (18.2%)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-pink-500 h-2 rounded-full" style="width: 18.2%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-indigo-500 rounded"></div>
                            <span class="text-sm text-gray-700">IT & Teknologi</span>
                        </div>
                        <span class="text-sm font-semibold text-gray-900">Rp 135 Jt (11.3%)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-indigo-500 h-2 rounded-full" style="width: 11.3%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-green-500 rounded"></div>
                            <span class="text-sm text-gray-700">Utilitas</span>
                        </div>
                        <span class="text-sm font-semibold text-gray-900">Rp 54 Jt (4.5%)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: 4.5%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-gray-500 rounded"></div>
                            <span class="text-sm text-gray-700">Lain-lain</span>
                        </div>
                        <span class="text-sm font-semibold text-gray-900">Rp 28 Jt (2.3%)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-gray-500 h-2 rounded-full" style="width: 2.3%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Transaksi Pemasukan -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-teal-600 to-teal-600 text-white p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold">Detail Pemasukan Bulanan</h3>
                    <p class="text-blue-100 text-sm mt-1">Desember 2024</p>
                </div>
                <button onclick="exportPemasukan()" class="bg-white text-teal-600 px-4 py-2 rounded-lg font-semibold text-sm hover:bg-blue-50 transition">
                    <i class="fas fa-download mr-2"></i>Export
                </button>
            </div>
        </div>

        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Tanggal</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Kategori</th>
                            <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Keterangan</th>
                            <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">Jumlah</th>
                            <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">01 Des 2024</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-shopping-cart mr-1"></i>Penjualan
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">Penjualan produk periode 1-7 Des</td>
                            <td class="px-6 py-4 text-sm text-right font-semibold text-green-600">Rp 125.000.000</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>Verified
                                </span>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">08 Des 2024</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    <i class="fas fa-handshake mr-1"></i>Jasa
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">Pendapatan jasa konsultasi</td>
                            <td class="px-6 py-4 text-sm text-right font-semibold text-green-600">Rp 85.000.000</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>Verified
                                </span>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">15 Des 2024</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-shopping-cart mr-1"></i>Penjualan
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">Penjualan produk periode 8-14 Des</td>
                            <td class="px-6 py-4 text-sm text-right font-semibold text-green-600">Rp 142.000.000</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>Verified
                                </span>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">20 Des 2024</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                    <i class="fas fa-coins mr-1"></i>Lain-lain
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">Pendapatan bunga bank</td>
                            <td class="px-6 py-4 text-sm text-right font-semibold text-green-600">Rp 15.000.000</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-check-circle mr-1"></i>Verified
                                </span>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm text-gray-900">28 Des 2024</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <i class="fas fa-shopping-cart mr-1"></i>Penjualan
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">Penjualan produk periode 15-31 Des</td>
                            <td class="px-6 py-4 text-sm text-right font-semibold text-green-600">Rp 165.000.000</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    <i class="fas fa-clock mr-1"></i>Pending
                                </span>
                            </td>
                        </tr>
                        <tr class="bg-blue-50 font-bold">
                            <td colspan="3" class="px-6 py-4 text-gray-900">Total Pemasukan</td>
                            <td class="px-6 py-4 text-right text-blue-600 text-lg">Rp 532.000.000</td>
                            <td class="px-6 py-4"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

        <!-- Detail Transaksi Pengeluaran -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-teal-600 to-teal-600 text-white p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-bold">Detail Pengeluaran Bulanan</h3>
                    <p class="text-red-100 text-sm mt-1">Desember 2024</p>
                </div>
                <button onclick="exportPengeluaran()" class="bg-white text-teal-600 px-4 py-2 rounded-lg font-semibold text-sm hover:bg-red-50 transition">
                    <i class="fas fa-download mr-2"></i>Export
                </button>
            </div>
        </div>

        <div class="p-6 overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Tanggal</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Kategori</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Keterangan</th>
                        <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">Jumlah</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm">05 Des 2024</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs bg-purple-100 text-purple-800">
                                Gaji & Tunjangan
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">Pembayaran gaji karyawan</td>
                        <td class="px-6 py-4 text-right font-semibold text-red-600">Rp 450.000.000</td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">
                                Approved
                            </span>
                        </td>
                    </tr>

                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm">10 Des 2024</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs bg-blue-100 text-blue-800">
                                Operasional
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">Sewa kantor & utilitas</td>
                        <td class="px-6 py-4 text-right font-semibold text-red-600">Rp 120.000.000</td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2 py-1 rounded-full text-xs bg-green-100 text-green-800">
                                Approved
                            </span>
                        </td>
                    </tr>

                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm">18 Des 2024</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs bg-pink-100 text-pink-800">
                                Marketing
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm">Iklan digital & promosi</td>
                        <td class="px-6 py-4 text-right font-semibold text-red-600">Rp 85.000.000</td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-2 py-1 rounded-full text-xs bg-yellow-100 text-yellow-800">
                                Pending
                            </span>
                        </td>
                    </tr>

                    <tr class="bg-red-50 font-bold">
                        <td colspan="3" class="px-6 py-4">Total Pengeluaran</td>
                        <td class="px-6 py-4 text-right text-red-600 text-lg">Rp 655.000.000</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Catatan & Ringkasan Manajemen -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Catatan Keuangan</h3>
            <ul class="space-y-3 text-sm text-gray-700">
                <li>• Pemasukan meningkat signifikan dari penjualan produk.</li>
                <li>• Pengeluaran marketing naik karena kampanye akhir tahun.</li>
                <li>• Saldo akhir bulan masih dalam kondisi sehat.</li>
            </ul>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Status Persetujuan</h3>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">Laporan Bulanan</p>
                    <p class="text-lg font-bold text-green-600">Disetujui</p>
                </div>
                <div class="text-right text-sm text-gray-500">
                    <p>Disetujui oleh</p>
                    <p class="font-semibold text-gray-800">Finance Manager</p>
                    <p>31 Des 2024</p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
