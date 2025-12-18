@extends('layouts.app')

@section('title', 'Invoice')
@section('page-title', 'Manajemen Invoice')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Daftar Invoice</h2>
            <p class="text-gray-600 text-sm">Kelola invoice perusahaan</p>
        </div>
            <a href="{{ url('finance/invoice/create') }}" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-lg shadow-lg transition">
                <i class="fas fa-plus mr-2"></i>Buat Invoice
            </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Total Invoice</p>
                    <h3 class="text-2xl font-bold text-gray-800">24</h3>
                </div>
                <div class="bg-blue-100 p-4 rounded-lg">
                    <i class="fas fa-file-invoice text-2xl text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Belum Dibayar</p>
                    <h3 class="text-2xl font-bold text-yellow-600">8</h3>
                </div>
                <div class="bg-yellow-100 p-4 rounded-lg">
                    <i class="fas fa-clock text-2xl text-yellow-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Sudah Dibayar</p>
                    <h3 class="text-2xl font-bold text-green-600">14</h3>
                </div>
                <div class="bg-green-100 p-4 rounded-lg">
                    <i class="fas fa-check-circle text-2xl text-green-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm mb-1">Jatuh Tempo</p>
                    <h3 class="text-2xl font-bold text-red-600">2</h3>
                </div>
                <div class="bg-red-100 p-4 rounded-lg">
                    <i class="fas fa-exclamation-triangle text-2xl text-red-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-search mr-1"></i>Cari Invoice
                </label>
                <input type="text" placeholder="No. Invoice, Customer, Project..." 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-filter mr-1"></i>Status
                </label>
                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                    <option value="">Semua Status</option>
                    <option value="draft">Draft</option>
                    <option value="sent">Terkirim</option>
                    <option value="paid">Dibayar</option>
                    <option value="overdue">Jatuh Tempo</option>
                    <option value="cancelled">Dibatalkan</option>
                </select>
            </div>

            <!-- Button -->
            <div class="flex items-end">
                <button class="w-full bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
               <thead class="bg-teal-600 text-white">
                <tr>
                    <th class="px-6 py-4 text-sm font-semibold tracking-wide">No. Invoice</th>
                    <th class="px-6 py-4 text-sm font-semibold tracking-wide">Customer</th>
                    <th class="px-6 py-4 text-sm font-semibold tracking-wide">Project</th>
                    <th class="px-6 py-4 text-sm font-semibold tracking-wide">Tanggal</th>
                    <th class="px-6 py-4 text-sm font-semibold tracking-wide">Jatuh Tempo</th>
                    <th class="px-6 py-4 text-sm font-semibold tracking-wide">Jumlah</th>
                    <th class="px-6 py-4 text-sm font-semibold tracking-wide">Status</th>
                    <th class="px-6 py-4 text-sm font-semibold tracking-wide text-center">Aksi</th>
                </tr>
            </thead>

                <tbody class="divide-y divide-gray-200">
                    <!-- Row 1 -->
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <span class="font-semibold text-teal-600">INV-2024-001</span>
                        </td>
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-medium text-gray-800">PT Maju Jaya</p>
                                <p class="text-sm text-gray-500">maju@example.com</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-gray-700">Website Development</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-gray-700">15 Des 2024</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-gray-700">30 Des 2024</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-semibold text-gray-800">Rp 50.000.000</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                <i class="fas fa-check-circle mr-1"></i>Dibayar
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center space-x-2">
                                <button onclick="window.location.href='/finance/invoice-show'" class="text-blue-600 hover:text-blue-800 transition" title="Lihat">
                                <i class="fas fa-eye"></i>
                                </button>
                               <button onclick="window.location.href='/finance/invoice-edit'" class="text-yellow-600 hover:text-yellow-800 transition" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-purple-600 hover:text-purple-800 transition" title="Download">
                                    <i class="fas fa-download"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-800 transition" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 2 -->
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <span class="font-semibold text-teal-600">INV-2024-002</span>
                        </td>
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-medium text-gray-800">CV Sejahtera</p>
                                <p class="text-sm text-gray-500">sejahtera@example.com</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-gray-700">Mobile App</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-gray-700">10 Des 2024</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-gray-700">25 Des 2024</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-semibold text-gray-800">Rp 75.000.000</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">
                                <i class="fas fa-clock mr-1"></i>Terkirim
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center space-x-2">
                                <button class="text-blue-600 hover:text-blue-800 transition" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="text-yellow-600 hover:text-yellow-800 transition" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-purple-600 hover:text-purple-800 transition" title="Download">
                                    <i class="fas fa-download"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-800 transition" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 3 -->
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <span class="font-semibold text-teal-600">INV-2024-003</span>
                        </td>
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-medium text-gray-800">PT Digital Solusi</p>
                                <p class="text-sm text-gray-500">digital@example.com</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-gray-700">ERP System</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-gray-700">05 Des 2024</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-red-600 font-semibold">10 Des 2024</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-semibold text-gray-800">Rp 120.000.000</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-semibold">
                                <i class="fas fa-exclamation-triangle mr-1"></i>Jatuh Tempo
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center space-x-2">
                                <button class="text-blue-600 hover:text-blue-800 transition" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="text-yellow-600 hover:text-yellow-800 transition" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-purple-600 hover:text-purple-800 transition" title="Download">
                                    <i class="fas fa-download"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-800 transition" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 4 -->
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <span class="font-semibold text-teal-600">INV-2024-004</span>
                        </td>
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-medium text-gray-800">UD Karya Mandiri</p>
                                <p class="text-sm text-gray-500">karya@example.com</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-gray-700">Sistem POS</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-gray-700">12 Des 2024</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-gray-700">27 Des 2024</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-semibold text-gray-800">Rp 35.000.000</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                                <i class="fas fa-check-circle mr-1"></i>Dibayar
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center space-x-2">
                                <button class="text-blue-600 hover:text-blue-800 transition" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="text-yellow-600 hover:text-yellow-800 transition" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-purple-600 hover:text-purple-800 transition" title="Download">
                                    <i class="fas fa-download"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-800 transition" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 5 -->
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <span class="font-semibold text-teal-600">INV-2024-005</span>
                        </td>
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-medium text-gray-800">PT Teknologi Nusantara</p>
                                <p class="text-sm text-gray-500">teknologi@example.com</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-gray-700">Cloud Infrastructure</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-gray-700">08 Des 2024</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-gray-700">23 Des 2024</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-semibold text-gray-800">Rp 90.000.000</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
                                <i class="fas fa-file-alt mr-1"></i>Draft
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center space-x-2">
                                <button class="text-blue-600 hover:text-blue-800 transition" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="text-yellow-600 hover:text-yellow-800 transition" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-purple-600 hover:text-purple-800 transition" title="Download">
                                    <i class="fas fa-download"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-800 transition" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 6 -->
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <span class="font-semibold text-teal-600">INV-2024-006</span>
                        </td>
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-medium text-gray-800">CV Mitra Bisnis</p>
                                <p class="text-sm text-gray-500">mitra@example.com</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-gray-700">Konsultasi IT</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-gray-700">14 Des 2024</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-gray-700">29 Des 2024</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-semibold text-gray-800">Rp 25.000.000</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">
                                <i class="fas fa-clock mr-1"></i>Terkirim
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center space-x-2">
                                <button class="text-blue-600 hover:text-blue-800 transition" title="Lihat">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="text-yellow-600 hover:text-yellow-800 transition" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-purple-600 hover:text-purple-800 transition" title="Download">
                                    <i class="fas fa-download"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-800 transition" title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-600">
                    Menampilkan <span class="font-semibold">1</span> sampai <span class="font-semibold">6</span> dari <span class="font-semibold">24</span> data
                </div>
                <div class="flex space-x-2">
                    <button class="px-4 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-100 transition disabled:opacity-50" disabled>
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="px-4 py-2 bg-teal-600 text-white rounded-lg font-semibold">1</button>
                    <button class="px-4 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-100 transition">2</button>
                    <button class="px-4 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-100 transition">3</button>
                    <button class="px-4 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-100 transition">4</button>
                    <button class="px-4 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-100 transition">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection