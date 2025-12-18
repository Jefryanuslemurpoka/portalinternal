@extends('layouts.app')

@section('title', 'Pemasukan')
@section('page-title', 'Manajemen Pemasukan Perusahaan')

@section('content')
<div class="space-y-6">

    <!-- Header Actions -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Daftar Pemasukan</h2>
            <p class="text-gray-600 text-sm">Kelola data pemasukan perusahaan</p>
        </div>
        <div class="flex gap-3">
            <button onclick="exportData()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">
                <i class="fas fa-file-excel mr-2"></i>Export Excel
            </button>
            <a href="{{ route('finance.pemasukan.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-lg shadow-lg transition">
                <i class="fas fa-plus mr-2"></i>Tambah Pemasukan
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm mb-1">Total Pemasukan Bulan Ini</p>
                    <h3 class="text-2xl font-bold">Rp 125.500.000</h3>
                </div>
                <div class="bg-orange-700/40 p-3 rounded-lg">
                    <i class="fas fa-arrow-up text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="bg-green-700 bg-opacity-50 px-2 py-1 rounded">
                    <i class="fas fa-arrow-up mr-1"></i>18.3%
                </span>
                <span class="ml-2 text-green-100">vs bulan lalu</span>
            </div>
        </div>

        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm mb-1">Pemasukan Pending</p>
                    <h3 class="text-2xl font-bold">Rp 15.750.000</h3>
                </div>
                <div class="bg-orange-700/40 p-3 rounded-lg">
                    <i class="fas fa-hourglass-half text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 text-sm text-blue-100">
                <span class="font-semibold">8</span> transaksi menunggu konfirmasi
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm mb-1">Total Transaksi</p>
                    <h3 class="text-2xl font-bold">243</h3>
                </div>
                <div class="bg-orange-700/40 p-3 rounded-lg">
                    <i class="fas fa-receipt text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 text-sm text-purple-100">
                Bulan ini
            </div>
        </div>

        <div class="bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-teal-100 text-sm mb-1">Sumber Terbanyak</p>
                    <h3 class="text-xl font-bold">Penjualan</h3>
                </div>
                <div class="bg-orange-700/40 p-3 rounded-lg">
                    <i class="fas fa-chart-pie text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 text-sm text-teal-100">
                <span class="font-semibold">65%</span> dari total pemasukan
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-filter mr-2 text-teal-600"></i>Filter & Pencarian
            </h3>
            <button onclick="resetFilter()" class="text-sm text-teal-600 hover:text-teal-700">
                <i class="fas fa-redo mr-1"></i>Reset Filter
            </button>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Pencarian</label>
                <div class="relative">
                    <input type="text" id="searchInput" placeholder="Cari nomor invoice, deskripsi, pelanggan..." 
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sumber Pemasukan</label>
                <select id="filterSumber" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    <option value="">Semua Sumber</option>
                    <option value="penjualan">Penjualan</option>
                    <option value="jasa">Jasa/Layanan</option>
                    <option value="investasi">Investasi</option>
                    <option value="bunga">Bunga Bank</option>
                    <option value="hibah">Hibah/Donasi</option>
                    <option value="lainnya">Lainnya</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select id="filterStatus" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    <option value="">Semua Status</option>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="received">Received</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Periode</label>
                <select id="filterPeriode" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    <option value="bulan_ini">Bulan Ini</option>
                    <option value="bulan_lalu">Bulan Lalu</option>
                    <option value="3_bulan">3 Bulan Terakhir</option>
                    <option value="6_bulan">6 Bulan Terakhir</option>
                    <option value="tahun_ini">Tahun Ini</option>
                    <option value="custom">Custom Range</option>
                </select>
            </div>
        </div>

        <div id="customDateRange" class="hidden mt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
                <input type="date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Akhir</label>
                <input type="date" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="p-6 border-b border-gray-200 flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Data Pemasukan</h3>
                <p class="text-sm text-gray-500 mt-1">Menampilkan 243 dari 1,856 transaksi</p>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-sm text-gray-600">Tampilkan:</span>
                <select class="px-3 py-1 border border-gray-300 rounded-lg text-sm">
                    <option>10</option>
                    <option selected>25</option>
                    <option>50</option>
                    <option>100</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <input type="checkbox" class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            No. Invoice
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Tanggal
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Sumber
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Deskripsi
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Pelanggan/Pihak
                        </th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Nominal
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <!-- Row 1 -->
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <input type="checkbox" class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-file-invoice text-green-600"></i>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">INV-IN-2024-0243</div>
                                    <div class="text-xs text-gray-500">SO-2024-0189</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <div>16 Des 2024</div>
                            <div class="text-xs text-gray-400">15:20 WIB</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-shopping-cart mr-1"></i> Penjualan
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 font-medium">Penjualan Produk Premium</div>
                            <div class="text-xs text-gray-500">20 unit Product A, 15 unit Product B</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <div class="font-medium">PT Mitra Sejahtera</div>
                            <div class="text-xs text-gray-400">0821-9876-5432</div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="text-sm font-bold text-gray-900">Rp 45.000.000</div>
                            <div class="text-xs text-gray-500">Transfer Bank</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i> Received
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="viewDetail(243)" class="text-blue-600 hover:text-blue-800 transition" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="printInvoice(243)" class="text-purple-600 hover:text-purple-800 transition" title="Print">
                                    <i class="fas fa-print"></i>
                                </button>
                                <button onclick="downloadInvoice(243)" class="text-teal-600 hover:text-teal-800 transition" title="Download">
                                    <i class="fas fa-download"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 2 -->
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <input type="checkbox" class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-file-invoice text-blue-600"></i>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">INV-IN-2024-0242</div>
                                    <div class="text-xs text-gray-500">SO-2024-0188</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <div>15 Des 2024</div>
                            <div class="text-xs text-gray-400">11:45 WIB</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                <i class="fas fa-handshake mr-1"></i> Jasa/Layanan
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 font-medium">Jasa Konsultasi IT</div>
                            <div class="text-xs text-gray-500">Project Development Q4 2024</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <div class="font-medium">CV Teknologi Maju</div>
                            <div class="text-xs text-gray-400">0812-3456-7890</div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="text-sm font-bold text-gray-900">Rp 28.500.000</div>
                            <div class="text-xs text-gray-500">Virtual Account</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                <i class="fas fa-hourglass-half mr-1"></i> Pending
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="viewDetail(242)" class="text-blue-600 hover:text-blue-800 transition" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="editData(242)" class="text-yellow-600 hover:text-yellow-800 transition" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="confirmPayment(242)" class="text-green-600 hover:text-green-800 transition" title="Konfirmasi Pembayaran">
                                    <i class="fas fa-check-circle"></i>
                                </button>
                                <button onclick="deleteData(242)" class="text-red-600 hover:text-red-800 transition" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 3 -->
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <input type="checkbox" class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-file-invoice text-teal-600"></i>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">INV-IN-2024-0241</div>
                                    <div class="text-xs text-gray-500">SO-2024-0187</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <div>14 Des 2024</div>
                            <div class="text-xs text-gray-400">09:30 WIB</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-chart-line mr-1"></i> Investasi
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 font-medium">Return Investasi Deposito</div>
                            <div class="text-xs text-gray-500">Periode November 2024</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <div class="font-medium">Bank Mandiri</div>
                            <div class="text-xs text-gray-400">Deposito 12 Bulan</div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="text-sm font-bold text-gray-900">Rp 12.750.000</div>
                            <div class="text-xs text-gray-500">Auto Credit</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i> Received
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="viewDetail(241)" class="text-blue-600 hover:text-blue-800 transition" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="printInvoice(241)" class="text-purple-600 hover:text-purple-800 transition" title="Print">
                                    <i class="fas fa-print"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 4 -->
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <input type="checkbox" class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-file-invoice text-blue-600"></i>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">INV-IN-2024-0240</div>
                                    <div class="text-xs text-gray-500">SO-2024-0186</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <div>13 Des 2024</div>
                            <div class="text-xs text-gray-400">14:15 WIB</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-shopping-cart mr-1"></i> Penjualan
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 font-medium">Penjualan Produk Reguler</div>
                            <div class="text-xs text-gray-500">50 unit Product C</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <div class="font-medium">Toko Berkah Jaya</div>
                            <div class="text-xs text-gray-400">0856-7890-1234</div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="text-sm font-bold text-gray-900">Rp 18.250.000</div>
                            <div class="text-xs text-gray-500">Transfer Bank</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                <i class="fas fa-check mr-1"></i> Confirmed
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="viewDetail(240)" class="text-blue-600 hover:text-blue-800 transition" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="confirmPayment(240)" class="text-green-600 hover:text-green-800 transition" title="Terima Pembayaran">
                                    <i class="fas fa-money-bill-wave"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 5 -->
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <input type="checkbox" class="rounded border-gray-300 text-teal-600 focus:ring-teal-500">
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-file-invoice text-orange-600"></i>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">INV-IN-2024-0239</div>
                                    <div class="text-xs text-gray-500">-</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <div>12 Des 2024</div>
                            <div class="text-xs text-gray-400">10:00 WIB</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-university mr-1"></i> Bunga Bank
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 font-medium">Bunga Tabungan</div>
                            <div class="text-xs text-gray-500">Periode November 2024</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <div class="font-medium">Bank BCA</div>
                            <div class="text-xs text-gray-400">Rekening Operasional</div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="text-sm font-bold text-gray-900">Rp 3.250.000</div>
                            <div class="text-xs text-gray-500">Auto Credit</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i> Received
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="viewDetail(239)" class="text-blue-600 hover:text-blue-800 transition" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="printInvoice(239)" class="text-purple-600 hover:text-purple-800 transition" title="Print">
                                    <i class="fas fa-print"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-600">Menampilkan</span>
                <span class="font-semibold text-gray-900">1-25</span>
                <span class="text-sm text-gray-600">dari</span>
                <span class="font-semibold text-gray-900">243</span>
                <span class="text-sm text-gray-600">data</span>
            </div>
            
            <div class="flex items-center gap-2">
                <button class="px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-600 hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="px-4 py-2 bg-teal-600 text-white rounded-lg text-sm font-medium">1</button>
                <button class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-600 hover:bg-gray-100">2</button>
                <button class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-600 hover:bg-gray-100">3</button>
                <span class="px-2 text-gray-600">...</span>
                <button class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-600 hover:bg-gray-100">10</button>
                <button class="px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-600 hover:bg-gray-100">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Pemasukan per Sumber -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Pemasukan per Sumber</h3>
                    <p class="text-sm text-gray-500">Bulan Desember 2024</p>
                </div>
                <div class="bg-teal-100 p-2 rounded-lg">
                    <i class="fas fa-chart-pie text-teal-600"></i>
                </div>
            </div>
            
            <div class="space-y-4">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-blue-500 rounded"></div>
                            <span class="text-sm font-medium text-gray-700">Penjualan</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900">Rp 81.575.000 (65%)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: 65%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-purple-500 rounded"></div>
                            <span class="text-sm font-medium text-gray-700">Jasa/Layanan</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900">Rp 28.500.000 (23%)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-purple-500 h-2 rounded-full" style="width: 23%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-green-500 rounded"></div>
                            <span class="text-sm font-medium text-gray-700">Investasi</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900">Rp 12.750.000 (10%)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: 10%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-yellow-500 rounded"></div>
                            <span class="text-sm font-medium text-gray-700">Bunga Bank</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900">Rp 3.250.000 (2%)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-yellow-500 h-2 rounded-full" style="width: 2%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tren Pemasukan -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Tren Pemasukan</h3>
                    <p class="text-sm text-gray-500">6 Bulan Terakhir</p>
                </div>
                <div class="bg-green-100 p-2 rounded-lg">
                    <i class="fas fa-chart-line text-green-600"></i>
                </div>
            </div>
            
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div>
                        <p class="text-sm font-medium text-gray-700">Desember 2024</p>
                        <p class="text-xs text-gray-500">Target: Rp 120.000.000</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-green-600">Rp 125.500.000</p>
                        <p class="text-xs text-green-600 flex items-center justify-end">
                            <i class="fas fa-arrow-up mr-1"></i> 4.6% dari target
                        </p>
                    </div>
                </div>

                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div>
                        <p class="text-sm font-medium text-gray-700">November 2024</p>
                        <p class="text-xs text-gray-500">Target: Rp 110.000.000</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-gray-900">Rp 106.250.000</p>
                        <p class="text-xs text-red-600 flex items-center justify-end">
                            <i class="fas fa-arrow-down mr-1"></i> 3.4% dari target
                        </p>
                    </div>
                </div>

                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div>
                        <p class="text-sm font-medium text-gray-700">Oktober 2024</p>
                        <p class="text-xs text-gray-500">Target: Rp 115.000.000</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-gray-900">Rp 118.750.000</p>
                        <p class="text-xs text-green-600 flex items-center justify-end">
                            <i class="fas fa-arrow-up mr-1"></i> 3.3% dari target
                        </p>
                    </div>
                </div>

                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div>
                        <p class="text-sm font-medium text-gray-700">September 2024</p>
                        <p class="text-xs text-gray-500">Target: Rp 105.000.000</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-gray-900">Rp 98.500.000</p>
                        <p class="text-xs text-red-600 flex items-center justify-end">
                            <i class="fas fa-arrow-down mr-1"></i> 6.2% dari target
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-indigo-100 text-sm font-medium">Top Pelanggan</h4>
                <i class="fas fa-trophy text-2xl text-yellow-300"></i>
            </div>
            <h3 class="text-2xl font-bold mb-2">PT Mitra Sejahtera</h3>
            <p class="text-indigo-100 text-sm">Total: Rp 85.000.000</p>
            <p class="text-indigo-100 text-xs mt-1">45 Transaksi bulan ini</p>
        </div>

        <div class="bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-cyan-100 text-sm font-medium">Metode Pembayaran</h4>
                <i class="fas fa-credit-card text-2xl"></i>
            </div>
            <div class="space-y-2">
                <div class="flex justify-between items-center">
                    <span class="text-sm">Transfer Bank</span>
                    <span class="font-bold">72%</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm">Virtual Account</span>
                    <span class="font-bold">20%</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm">Cash</span>
                    <span class="font-bold">8%</span>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-emerald-500 to-emerald-500 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-emerald-100 text-sm font-medium">Rata-rata Transaksi</h4>
                <i class="fas fa-calculator text-2xl"></i>
            </div>
            <h3 class="text-2xl font-bold mb-2">Rp 516.461</h3>
            <p class="text-emerald-100 text-sm">Per transaksi bulan ini</p>
            <div class="mt-3 flex items-center text-sm">
                <span class="bg-emerald-700 bg-opacity-50 px-2 py-1 rounded">
                    <i class="fas fa-arrow-up mr-1"></i>8.3%
                </span>
                <span class="ml-2 text-emerald-100">vs bulan lalu</span>
            </div>
        </div>
    </div>

</div>

<!-- Modal Detail Pemasukan -->
<div id="modalDetail" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-gradient-to-r from-teal-600 to-teal-700 text-white p-6 rounded-t-2xl">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-bold">Detail Pemasukan</h3>
                    <p class="text-teal-100 text-sm mt-1">INV-IN-2024-0243</p>
                </div>
                <button onclick="closeModal()" class="text-white hover:text-gray-200 transition">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
        </div>

        <div class="p-6 space-y-6">
            <!-- Status Badge -->
            <div class="flex items-center justify-between bg-green-50 border border-green-200 rounded-lg p-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-check-circle text-white text-xl"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">Status: Pembayaran Diterima</p>
                        <p class="text-sm text-gray-600">Dana telah masuk ke rekening perusahaan</p>
                    </div>
                </div>
            </div>

            <!-- Informasi Umum -->
            <div>
                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-info-circle text-teal-600 mr-2"></i>
                    Informasi Umum
                </h4>
                <div class="grid grid-cols-2 gap-4 bg-gray-50 rounded-lg p-4">
                    <div>
                        <p class="text-sm text-gray-600">Nomor Invoice</p>
                        <p class="font-semibold text-gray-900">INV-IN-2024-0243</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Nomor SO</p>
                        <p class="font-semibold text-gray-900">SO-2024-0189</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Tanggal</p>
                        <p class="font-semibold text-gray-900">16 Desember 2024</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Jatuh Tempo</p>
                        <p class="font-semibold text-green-600">30 Desember 2024</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Sumber</p>
                        <p class="font-semibold text-gray-900">Penjualan</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Dibuat Oleh</p>
                        <p class="font-semibold text-gray-900">Siti Nurhaliza</p>
                    </div>
                </div>
            </div>

            <!-- Informasi Pelanggan -->
            <div>
                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-user-tie text-teal-600 mr-2"></i>
                    Informasi Pelanggan
                </h4>
                <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                    <div class="flex justify-between">
                        <p class="text-sm text-gray-600">Nama Pelanggan</p>
                        <p class="font-semibold text-gray-900">PT Mitra Sejahtera</p>
                    </div>
                    <div class="flex justify-between">
                        <p class="text-sm text-gray-600">Kontak</p>
                        <p class="font-semibold text-gray-900">0821-9876-5432</p>
                    </div>
                    <div class="flex justify-between">
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="font-semibold text-gray-900">procurement@mitrasejahtera.com</p>
                    </div>
                    <div class="flex justify-between">
                        <p class="text-sm text-gray-600">Alamat</p>
                        <p class="font-semibold text-gray-900 text-right">Jl. Gatot Subroto No. 88, Jakarta</p>
                    </div>
                </div>
            </div>

            <!-- Detail Item -->
            <div>
                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-list text-teal-600 mr-2"></i>
                    Detail Item
                </h4>
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Item</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600">Qty</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600">Harga</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900">Product A - Premium Edition</td>
                                <td class="px-4 py-3 text-center text-sm text-gray-900">20 unit</td>
                                <td class="px-4 py-3 text-right text-sm text-gray-900">Rp 1.500.000</td>
                                <td class="px-4 py-3 text-right text-sm font-semibold text-gray-900">Rp 30.000.000</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900">Product B - Standard Edition</td>
                                <td class="px-4 py-3 text-center text-sm text-gray-900">15 unit</td>
                                <td class="px-4 py-3 text-right text-sm text-gray-900">Rp 1.000.000</td>
                                <td class="px-4 py-3 text-right text-sm font-semibold text-gray-900">Rp 15.000.000</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Ringkasan Pembayaran -->
            <div>
                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-calculator text-teal-600 mr-2"></i>
                    Ringkasan Pembayaran
                </h4>
                <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                    <div class="flex justify-between text-sm">
                        <p class="text-gray-600">Subtotal</p>
                        <p class="font-semibold text-gray-900">Rp 45.000.000</p>
                    </div>
                    <div class="flex justify-between text-sm">
                        <p class="text-gray-600">PPN 11%</p>
                        <p class="font-semibold text-gray-900">Rp 0</p>
                    </div>
                    <div class="flex justify-between text-sm">
                        <p class="text-gray-600">Biaya Admin</p>
                        <p class="font-semibold text-gray-900">Rp 0</p>
                    </div>
                    <div class="flex justify-between text-sm">
                        <p class="text-gray-600">Diskon</p>
                        <p class="font-semibold text-green-600">- Rp 0</p>
                    </div>
                    <div class="border-t border-gray-300 pt-3 flex justify-between">
                        <p class="font-bold text-gray-900">Total Pemasukan</p>
                        <p class="font-bold text-xl text-green-600">Rp 45.000.000</p>
                    </div>
                    <div class="bg-green-100 border border-green-200 rounded-lg p-3 mt-3">
                        <div class="flex justify-between text-sm">
                            <p class="text-green-800 font-medium">Metode Pembayaran</p>
                            <p class="text-green-900 font-semibold">Transfer Bank</p>
                        </div>
                        <div class="flex justify-between text-sm mt-1">
                            <p class="text-green-800 font-medium">Tanggal Diterima</p>
                            <p class="text-green-900 font-semibold">16 Des 2024, 15:20 WIB</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Catatan -->
            <div>
                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-sticky-note text-teal-600 mr-2"></i>
                    Catatan
                </h4>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm text-gray-700">Pembayaran untuk pembelian produk premium dan standard. Dana telah diterima melalui transfer bank ke rekening perusahaan pada tanggal 16 Desember 2024.</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3 pt-4 border-t border-gray-200">
                <button onclick="printInvoice(243)" class="flex-1 bg-purple-600 hover:bg-purple-700 text-white py-3 rounded-lg font-semibold transition">
                    <i class="fas fa-print mr-2"></i>Print Invoice
                </button>
                <button onclick="downloadInvoice(243)" class="flex-1 bg-teal-600 hover:bg-teal-700 text-white py-3 rounded-lg font-semibold transition">
                    <i class="fas fa-download mr-2"></i>Download PDF
                </button>
                <button onclick="closeModal()" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-semibold transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Filter Functions
document.getElementById('filterPeriode').addEventListener('change', function() {
    if (this.value === 'custom') {
        document.getElementById('customDateRange').classList.remove('hidden');
    } else {
        document.getElementById('customDateRange').classList.add('hidden');
    }
});

function resetFilter() {
    document.getElementById('searchInput').value = '';
    document.getElementById('filterSumber').value = '';
    document.getElementById('filterStatus').value = '';
    document.getElementById('filterPeriode').value = 'bulan_ini';
    document.getElementById('customDateRange').classList.add('hidden');
}

function exportData() {
    alert('Export Excel sedang diproses...');
}

// Modal Functions
function viewDetail(id) {
    document.getElementById('modalDetail').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('modalDetail').classList.add('hidden');
}

function editData(id) {
    window.location.href = `/finance/pemasukan/${id}/edit`;
}

function deleteData(id) {
    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        alert('Data berhasil dihapus');
    }
}

function confirmPayment(id) {
    if (confirm('Konfirmasi bahwa pembayaran telah diterima?')) {
        alert('Pembayaran berhasil dikonfirmasi');
        closeModal();
    }
}

function printInvoice(id) {
    alert('Mencetak invoice...');
}

function downloadInvoice(id) {
    alert('Download invoice...');
}

// Close modal when clicking outside
document.getElementById('modalDetail').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});
</script>

@endsection