@extends('layouts.app')

@section('title', 'Pengeluaran')
@section('page-title', 'Manajemen Pengeluaran Perusahaan')

@section('content')
<div class="space-y-6">

    <!-- Header Actions -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Daftar Pengeluaran</h2>
            <p class="text-gray-600 text-sm">Kelola data pengeluaran perusahaan</p>
        </div>
        <div class="flex gap-3">
            <button onclick="exportData()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">
                <i class="fas fa-file-excel mr-2"></i>Export Excel
            </button>
            <a href="{{ route('finance.pengeluaran.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-lg shadow-lg transition">
                <i class="fas fa-plus mr-2"></i>Tambah Pengeluaran
            </a>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm mb-1">Total Pengeluaran Bulan Ini</p>
                    <h3 class="text-2xl font-bold">Rp 45.750.000</h3>
                </div>
               <div class="bg-orange-700/40 p-3 rounded-lg">
                    <i class="fas fa-clock text-2xl text-white"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="bg-red-700 bg-opacity-50 px-2 py-1 rounded">
                    <i class="fas fa-arrow-up mr-1"></i>12.5%
                </span>
                <span class="ml-2 text-red-100">vs bulan lalu</span>
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm mb-1">Pengeluaran Pending</p>
                    <h3 class="text-2xl font-bold">Rp 8.250.000</h3>
                </div>
                <div class="bg-orange-700/40 p-3 rounded-lg">
                    <i class="fas fa-clock text-2xl text-white"></i>
                </div>
            </div>
            <div class="mt-4 text-sm text-orange-100">
                <span class="font-semibold">12</span> transaksi menunggu approval
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm mb-1">Total Transaksi</p>
                    <h3 class="text-2xl font-bold">156</h3>
                </div>
                <div class="bg-orange-700/40 p-3 rounded-lg">
                    <i class="fas fa-receipt text-2xl text-white"></i>
                </div>
            </div>
            <div class="mt-4 text-sm text-purple-100">
                Bulan ini
            </div>
        </div>

        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm mb-1">Kategori Terbanyak</p>
                    <h3 class="text-xl font-bold">Operasional</h3>
                </div>
                <div class="bg-orange-700/40 p-3 rounded-lg">
                    <i class="fas fa-gears text-2xl text-white"></i>
                </div>
            </div>
            <div class="mt-4 text-sm text-blue-100">
                <span class="font-semibold">45%</span> dari total pengeluaran
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
                    <input type="text" id="searchInput" placeholder="Cari nomor invoice, deskripsi, supplier..." 
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                <select id="filterKategori" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    <option value="">Semua Kategori</option>
                    <option value="operasional">Operasional</option>
                    <option value="gaji">Gaji & Tunjangan</option>
                    <option value="inventaris">Inventaris</option>
                    <option value="marketing">Marketing</option>
                    <option value="utilitas">Utilitas</option>
                    <option value="pajak">Pajak</option>
                    <option value="lainnya">Lainnya</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select id="filterStatus" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                    <option value="">Semua Status</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="paid">Paid</option>
                    <option value="rejected">Rejected</option>
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
                <h3 class="text-lg font-semibold text-gray-800">Data Pengeluaran</h3>
                <p class="text-sm text-gray-500 mt-1">Menampilkan 156 dari 1,248 transaksi</p>
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
                            Kategori
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Deskripsi
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Supplier/Vendor
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
                                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-file-invoice text-red-600"></i>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">INV-2024-0156</div>
                                    <div class="text-xs text-gray-500">PO-2024-0089</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <div>16 Des 2024</div>
                            <div class="text-xs text-gray-400">14:30 WIB</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <i class="fas fa-cog mr-1"></i> Operasional
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 font-medium">Pembelian ATK Kantor</div>
                            <div class="text-xs text-gray-500">Kertas A4, Pulpen, Folder</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <div class="font-medium">PT Graha Stationery</div>
                            <div class="text-xs text-gray-400">0812-3456-7890</div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="text-sm font-bold text-gray-900">Rp 2.450.000</div>
                            <div class="text-xs text-gray-500">+ PPN 11%</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                <i class="fas fa-clock mr-1"></i> Pending
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="viewDetail(156)" class="text-blue-600 hover:text-blue-800 transition" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="editData(156)" class="text-yellow-600 hover:text-yellow-800 transition" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="approveData(156)" class="text-green-600 hover:text-green-800 transition" title="Approve">
                                    <i class="fas fa-check-circle"></i>
                                </button>
                                <button onclick="deleteData(156)" class="text-red-600 hover:text-red-800 transition" title="Hapus">
                                    <i class="fas fa-trash"></i>
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
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-file-invoice text-green-600"></i>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">INV-2024-0155</div>
                                    <div class="text-xs text-gray-500">PO-2024-0088</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <div>15 Des 2024</div>
                            <div class="text-xs text-gray-400">10:15 WIB</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                <i class="fas fa-users mr-1"></i> Gaji & Tunjangan
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 font-medium">Gaji Karyawan Desember 2024</div>
                            <div class="text-xs text-gray-500">45 Karyawan</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <div class="font-medium">Internal (Payroll)</div>
                            <div class="text-xs text-gray-400">HRD Department</div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="text-sm font-bold text-gray-900">Rp 35.000.000</div>
                            <div class="text-xs text-gray-500">Transfer Bank</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i> Paid
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="viewDetail(155)" class="text-blue-600 hover:text-blue-800 transition" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="printInvoice(155)" class="text-purple-600 hover:text-purple-800 transition" title="Print">
                                    <i class="fas fa-print"></i>
                                </button>
                                <button onclick="downloadInvoice(155)" class="text-teal-600 hover:text-teal-800 transition" title="Download">
                                    <i class="fas fa-download"></i>
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
                                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-file-invoice text-orange-600"></i>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">INV-2024-0154</div>
                                    <div class="text-xs text-gray-500">PO-2024-0087</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <div>14 Des 2024</div>
                            <div class="text-xs text-gray-400">16:45 WIB</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <i class="fas fa-lightbulb mr-1"></i> Utilitas
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 font-medium">Pembayaran Listrik & Air</div>
                            <div class="text-xs text-gray-500">Bulan Desember 2024</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <div class="font-medium">PT PLN & PDAM</div>
                            <div class="text-xs text-gray-400">Utilitas Kantor</div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="text-sm font-bold text-gray-900">Rp 4.250.000</div>
                            <div class="text-xs text-gray-500">Virtual Account</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                <i class="fas fa-check mr-1"></i> Approved
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="viewDetail(154)" class="text-blue-600 hover:text-blue-800 transition" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="processPayment(154)" class="text-green-600 hover:text-green-800 transition" title="Proses Pembayaran">
                                    <i class="fas fa-money-bill-wave"></i>
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
                                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-file-invoice text-red-600"></i>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">INV-2024-0153</div>
                                    <div class="text-xs text-gray-500">PO-2024-0086</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <div>13 Des 2024</div>
                            <div class="text-xs text-gray-400">09:20 WIB</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-pink-100 text-pink-800">
                                <i class="fas fa-bullhorn mr-1"></i> Marketing
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 font-medium">Biaya Iklan Google Ads</div>
                            <div class="text-xs text-gray-500">Kampanye Desember 2024</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <div class="font-medium">Google Ads</div>
                            <div class="text-xs text-gray-400">Digital Marketing</div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="text-sm font-bold text-gray-900">Rp 8.500.000</div>
                            <div class="text-xs text-gray-500">Credit Card</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i> Paid
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="viewDetail(153)" class="text-blue-600 hover:text-blue-800 transition" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="printInvoice(153)" class="text-purple-600 hover:text-purple-800 transition" title="Print">
                                    <i class="fas fa-print"></i>
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
                                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-file-invoice text-gray-600"></i>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">INV-2024-0152</div>
                                    <div class="text-xs text-gray-500">PO-2024-0085</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <div>12 Des 2024</div>
                            <div class="text-xs text-gray-400">11:00 WIB</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <i class="fas fa-landmark mr-1"></i> Pajak
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 font-medium">PPh 21 Karyawan</div>
                            <div class="text-xs text-gray-500">November 2024</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            <div class="font-medium">Kantor Pajak</div>
                            <div class="text-xs text-gray-400">Direktorat Jenderal Pajak</div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="text-sm font-bold text-gray-900">Rp 5.750.000</div>
                            <div class="text-xs text-gray-500">e-Billing</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-1"></i> Rejected
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="viewDetail(152)" class="text-blue-600 hover:text-blue-800 transition" title="Lihat Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="editData(152)" class="text-yellow-600 hover:text-yellow-800 transition" title="Edit & Resubmit">
                                    <i class="fas fa-edit"></i>
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
                <span class="font-semibold text-gray-900">156</span>
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
                <button class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-600 hover:bg-gray-100">7</button>
                <button class="px-3 py-2 border border-gray-300 rounded-lg text-sm text-gray-600 hover:bg-gray-100">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Pengeluaran per Kategori -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Pengeluaran per Kategori</h3>
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
                            <span class="text-sm font-medium text-gray-700">Operasional</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900">Rp 20.587.500 (45%)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: 45%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-purple-500 rounded"></div>
                            <span class="text-sm font-medium text-gray-700">Gaji & Tunjangan</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900">Rp 35.000.000 (30%)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-purple-500 h-2 rounded-full" style="width: 30%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-pink-500 rounded"></div>
                            <span class="text-sm font-medium text-gray-700">Marketing</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900">Rp 8.500.000 (12%)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-pink-500 h-2 rounded-full" style="width: 12%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-green-500 rounded"></div>
                            <span class="text-sm font-medium text-gray-700">Utilitas</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900">Rp 4.250.000 (8%)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: 8%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-red-500 rounded"></div>
                            <span class="text-sm font-medium text-gray-700">Pajak</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900">Rp 5.750.000 (5%)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-red-500 h-2 rounded-full" style="width: 5%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tren Pengeluaran -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Tren Pengeluaran</h3>
                    <p class="text-sm text-gray-500">6 Bulan Terakhir</p>
                </div>
                <div class="bg-red-100 p-2 rounded-lg">
                    <i class="fas fa-chart-line text-red-600"></i>
                </div>
            </div>
            
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div>
                        <p class="text-sm font-medium text-gray-700">Desember 2024</p>
                        <p class="text-xs text-gray-500">Target: Rp 50.000.000</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-red-600">Rp 45.750.000</p>
                        <p class="text-xs text-green-600 flex items-center justify-end">
                            <i class="fas fa-arrow-down mr-1"></i> 8.5% dari target
                        </p>
                    </div>
                </div>

                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div>
                        <p class="text-sm font-medium text-gray-700">November 2024</p>
                        <p class="text-xs text-gray-500">Target: Rp 50.000.000</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-gray-900">Rp 48.250.000</p>
                        <p class="text-xs text-green-600 flex items-center justify-end">
                            <i class="fas fa-arrow-down mr-1"></i> 3.5% dari target
                        </p>
                    </div>
                </div>

                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div>
                        <p class="text-sm font-medium text-gray-700">Oktober 2024</p>
                        <p class="text-xs text-gray-500">Target: Rp 50.000.000</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-gray-900">Rp 52.100.000</p>
                        <p class="text-xs text-red-600 flex items-center justify-end">
                            <i class="fas fa-arrow-up mr-1"></i> 4.2% dari target
                        </p>
                    </div>
                </div>

                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div>
                        <p class="text-sm font-medium text-gray-700">September 2024</p>
                        <p class="text-xs text-gray-500">Target: Rp 50.000.000</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-bold text-gray-900">Rp 46.800.000</p>
                        <p class="text-xs text-green-600 flex items-center justify-end">
                            <i class="fas fa-arrow-down mr-1"></i> 6.4% dari target
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
                <h4 class="text-indigo-100 text-sm font-medium">Top Vendor</h4>
                <i class="fas fa-trophy text-2xl text-yellow-300"></i>
            </div>
            <h3 class="text-2xl font-bold mb-2">PT Graha Stationery</h3>
            <p class="text-indigo-100 text-sm">Total: Rp 15.250.000</p>
            <p class="text-indigo-100 text-xs mt-1">23 Transaksi bulan ini</p>
        </div>

        <div class="bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-cyan-100 text-sm font-medium">Metode Pembayaran</h4>
                <i class="fas fa-credit-card text-2xl"></i>
            </div>
            <div class="space-y-2">
                <div class="flex justify-between items-center">
                    <span class="text-sm">Transfer Bank</span>
                    <span class="font-bold">68%</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm">Virtual Account</span>
                    <span class="font-bold">22%</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm">Cash</span>
                    <span class="font-bold">10%</span>
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

<!-- Modal Detail Pengeluaran -->
<div id="modalDetail" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-3xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-gradient-to-r from-teal-600 to-teal-700 text-white p-6 rounded-t-2xl">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-bold">Detail Pengeluaran</h3>
                    <p class="text-teal-100 text-sm mt-1">INV-2024-0156</p>
                </div>
                <button onclick="closeModal()" class="text-white hover:text-gray-200 transition">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
        </div>

        <div class="p-6 space-y-6">
            <!-- Status Badge -->
            <div class="flex items-center justify-between bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-yellow-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-clock text-white text-xl"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">Status: Pending Approval</p>
                        <p class="text-sm text-gray-600">Menunggu persetujuan dari Finance Manager</p>
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
                        <p class="font-semibold text-gray-900">INV-2024-0156</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Nomor PO</p>
                        <p class="font-semibold text-gray-900">PO-2024-0089</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Tanggal</p>
                        <p class="font-semibold text-gray-900">16 Desember 2024</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Jatuh Tempo</p>
                        <p class="font-semibold text-red-600">30 Desember 2024</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Kategori</p>
                        <p class="font-semibold text-gray-900">Operasional</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Dibuat Oleh</p>
                        <p class="font-semibold text-gray-900">Ahmad Wijaya</p>
                    </div>
                </div>
            </div>

            <!-- Informasi Vendor -->
            <div>
                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-building text-teal-600 mr-2"></i>
                    Informasi Vendor
                </h4>
                <div class="bg-gray-50 rounded-lg p-4 space-y-2">
                    <div class="flex justify-between">
                        <p class="text-sm text-gray-600">Nama Vendor</p>
                        <p class="font-semibold text-gray-900">PT Graha Stationery</p>
                    </div>
                    <div class="flex justify-between">
                        <p class="text-sm text-gray-600">Kontak</p>
                        <p class="font-semibold text-gray-900">0812-3456-7890</p>
                    </div>
                    <div class="flex justify-between">
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="font-semibold text-gray-900">sales@grahastationery.com</p>
                    </div>
                    <div class="flex justify-between">
                        <p class="text-sm text-gray-600">Alamat</p>
                        <p class="font-semibold text-gray-900 text-right">Jl. Sudirman No. 123, Jakarta</p>
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
                                <td class="px-4 py-3 text-sm text-gray-900">Kertas A4 80gr</td>
                                <td class="px-4 py-3 text-center text-sm text-gray-900">50 rim</td>
                                <td class="px-4 py-3 text-right text-sm text-gray-900">Rp 35.000</td>
                                <td class="px-4 py-3 text-right text-sm font-semibold text-gray-900">Rp 1.750.000</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900">Pulpen Pilot</td>
                                <td class="px-4 py-3 text-center text-sm text-gray-900">100 pcs</td>
                                <td class="px-4 py-3 text-right text-sm text-gray-900">Rp 3.500</td>
                                <td class="px-4 py-3 text-right text-sm font-semibold text-gray-900">Rp 350.000</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900">Folder Plastik</td>
                                <td class="px-4 py-3 text-center text-sm text-gray-900">150 pcs</td>
                                <td class="px-4 py-3 text-right text-sm text-gray-900">Rp 2.000</td>
                                <td class="px-4 py-3 text-right text-sm font-semibold text-gray-900">Rp 300.000</td>
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
                        <p class="font-semibold text-gray-900">Rp 2.400.000</p>
                    </div>
                    <div class="flex justify-between text-sm">
                        <p class="text-gray-600">PPN 11%</p>
                        <p class="font-semibold text-gray-900">Rp 264.000</p>
                    </div>
                    <div class="flex justify-between text-sm">
                        <p class="text-gray-600">Biaya Admin</p>
                        <p class="font-semibold text-gray-900">Rp 6.000</p>
                    </div>
                    <div class="flex justify-between text-sm">
                        <p class="text-gray-600">Diskon</p>
                        <p class="font-semibold text-green-600">- Rp 220.000</p>
                    </div>
                    <div class="border-t border-gray-300 pt-3 flex justify-between">
                        <p class="font-bold text-gray-900">Total Pembayaran</p>
                        <p class="font-bold text-xl text-teal-600">Rp 2.450.000</p>
                    </div>
                </div>
            </div>

            <!-- Catatan -->
            <div>
                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-sticky-note text-teal-600 mr-2"></i>
                    Catatan
                </h4>
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
                    <p class="text-sm text-gray-700">Pembelian ATK untuk kebutuhan kantor bulan Desember 2024. Pembayaran dapat dilakukan melalui transfer bank atau virtual account.</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3 pt-4 border-t border-gray-200">
                <button onclick="approveData(156)" class="flex-1 bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-semibold transition">
                    <i class="fas fa-check-circle mr-2"></i>Approve
                </button>
                <button onclick="rejectData(156)" class="flex-1 bg-red-600 hover:bg-red-700 text-white py-3 rounded-lg font-semibold transition">
                    <i class="fas fa-times-circle mr-2"></i>Reject
                </button>
                <button onclick="printInvoice(156)" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-semibold transition">
                    <i class="fas fa-print"></i>
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
    document.getElementById('filterKategori').value = '';
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
    window.location.href = `/finance/pengeluaran/${id}/edit`;
}

function deleteData(id) {
    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
        alert('Data berhasil dihapus');
    }
}

function approveData(id) {
    if (confirm('Apakah Anda yakin ingin approve pengeluaran ini?')) {
        alert('Pengeluaran berhasil di-approve');
        closeModal();
    }
}

function rejectData(id) {
    const reason = prompt('Masukkan alasan reject:');
    if (reason) {
        alert('Pengeluaran berhasil di-reject');
        closeModal();
    }
}

function processPayment(id) {
    if (confirm('Proses pembayaran untuk invoice ini?')) {
        alert('Pembayaran sedang diproses');
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