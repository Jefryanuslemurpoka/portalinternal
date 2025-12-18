@extends('layouts.app')

@section('title', 'Anggaran')
@section('page-title', 'Manajemen Anggaran')

@section('content')
<div class="space-y-6">

    <!-- Header Actions -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Manajemen Anggaran</h2>
            <p class="text-gray-600 text-sm">Monitor dan kelola anggaran perusahaan</p>
        </div>
        <div class="flex gap-3">
            <button onclick="exportData()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">
                <i class="fas fa-file-excel mr-2"></i>Export Excel
            </button>
            <a href="{{ route('finance.anggaran.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-3 rounded-lg shadow-lg transition">
                <i class="fas fa-plus mr-2"></i>Tambah Anggaran
            </a>
        </div>
    </div>

    <!-- Period Selector -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Periode Anggaran</label>
                    <select id="periodSelector" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 text-lg font-semibold">
                        <option value="2024">Tahun 2024</option>
                        <option value="2023">Tahun 2023</option>
                        <option value="2025">Tahun 2025</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Departemen</label>
                    <select id="deptSelector" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                        <option value="">Semua Departemen</option>
                        <option value="operasional">Operasional</option>
                        <option value="marketing">Marketing</option>
                        <option value="hrd">HRD</option>
                        <option value="it">IT</option>
                        <option value="finance">Finance</option>
                    </select>
                </div>
            </div>
            <div class="text-right">
                <p class="text-sm text-gray-600">Status Anggaran 2024</p>
                <p class="text-2xl font-bold text-gray-900">Rp 1.2 Miliar</p>
                <p class="text-sm text-green-600 font-medium">
                    <i class="fas fa-arrow-down mr-1"></i>Terpakai 68.5% (Rp 822 Juta)
                </p>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm mb-1">Total Anggaran</p>
                    <h3 class="text-2xl font-bold">Rp 1.2 M</h3>
                </div>
                <div class="bg-orange-700/40 p-3 rounded-lg">
                    <i class="fas fa-wallet text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 text-sm text-blue-100">
                Tahun 2024
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm mb-1">Anggaran Terpakai</p>
                    <h3 class="text-2xl font-bold">Rp 822 Jt</h3>
                </div>
                <div class="bg-orange-700/40 p-3 rounded-lg">
                    <i class="fas fa-chart-line text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="bg-orange-700 bg-opacity-50 px-2 py-1 rounded">68.5%</span>
                <span class="ml-2 text-orange-100">dari total</span>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm mb-1">Sisa Anggaran</p>
                    <h3 class="text-2xl font-bold">Rp 378 Jt</h3>
                </div>
                <div class="bg-orange-700/40 p-3 rounded-lg">
                    <i class="fas fa-money-bill-wave text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center text-sm">
                <span class="bg-green-700 bg-opacity-50 px-2 py-1 rounded">31.5%</span>
                <span class="ml-2 text-green-100">tersisa</span>
            </div>
        </div>

        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm mb-1">Over Budget</p>
                    <h3 class="text-2xl font-bo 25%ld">3 Kategori</h3>
                </div>
                <div class="bg-orange-700/40 p-3 rounded-lg">
                    <i class="fas fa-exclamation-triangle text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 text-sm text-red-100">
                Memerlukan perhatian
            </div>
        </div>
    </div>

    <!-- Budget Overview Chart -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Overview Anggaran 2024</h3>
                <p class="text-sm text-gray-500">Perbandingan anggaran vs realisasi per kategori</p>
            </div>
            <div class="flex gap-4 text-sm">
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-blue-500 rounded"></div>
                    <span class="text-gray-600">Anggaran</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-orange-500 rounded"></div>
                    <span class="text-gray-600">Realisasi</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="w-4 h-4 bg-green-500 rounded"></div>
                    <span class="text-gray-600">Sisa</span>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Anggaran</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Realisasi</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Sisa</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Progress</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <!-- Row 1 - Operasional -->
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-cog text-blue-600"></i>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">Operasional</div>
                                    <div class="text-xs text-gray-500">Biaya operasional harian</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="text-sm font-bold text-gray-900">Rp 450.000.000</div>
                            <div class="text-xs text-gray-500">37.5% dari total</div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="text-sm font-bold text-orange-600">Rp 315.000.000</div>
                            <div class="text-xs text-gray-500">s/d Desember</div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="text-sm font-bold text-green-600">Rp 135.000.000</div>
                            <div class="text-xs text-gray-500">30% tersisa</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <div class="flex-1 bg-gray-200 rounded-full h-2">
                                    <div class="bg-orange-500 h-2 rounded-full" style="width: 70%"></div>
                                </div>
                                <span class="text-sm font-semibold text-gray-900 w-12">70%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i> On Track
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="viewDetail('operasional')" class="text-blue-600 hover:text-blue-800 transition" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="editBudget('operasional')" class="text-yellow-600 hover:text-yellow-800 transition" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 2 - Marketing -->
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-pink-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-bullhorn text-pink-600"></i>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">Marketing</div>
                                    <div class="text-xs text-gray-500">Promosi & periklanan</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="text-sm font-bold text-gray-900">Rp 250.000.000</div>
                            <div class="text-xs text-gray-500">20.8% dari total</div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="text-sm font-bold text-orange-600">Rp 218.000.000</div>
                            <div class="text-xs text-gray-500">s/d Desember</div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="text-sm font-bold text-green-600">Rp 32.000.000</div>
                            <div class="text-xs text-gray-500">12.8% tersisa</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <div class="flex-1 bg-gray-200 rounded-full h-2">
                                    <div class="bg-orange-500 h-2 rounded-full" style="width: 87.2%"></div>
                                </div>
                                <span class="text-sm font-semibold text-gray-900 w-12">87%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">
                                <i class="fas fa-exclamation-circle mr-1"></i> Warning
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="viewDetail('marketing')" class="text-blue-600 hover:text-blue-800 transition" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="editBudget('marketing')" class="text-yellow-600 hover:text-yellow-800 transition" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 3 - Gaji & Tunjangan -->
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-users text-purple-600"></i>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">Gaji & Tunjangan</div>
                                    <div class="text-xs text-gray-500">Kompensasi karyawan</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="text-sm font-bold text-gray-900">Rp 300.000.000</div>
                            <div class="text-xs text-gray-500">25% dari total</div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="text-sm font-bold text-orange-600">Rp 175.000.000</div>
                            <div class="text-xs text-gray-500">s/d Desember</div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="text-sm font-bold text-green-600">Rp 125.000.000</div>
                            <div class="text-xs text-gray-500">41.7% tersisa</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <div class="flex-1 bg-gray-200 rounded-full h-2">
                                    <div class="bg-orange-500 h-2 rounded-full" style="width: 58.3%"></div>
                                </div>
                                <span class="text-sm font-semibold text-gray-900 w-12">58%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i> On Track
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="viewDetail('gaji')" class="text-blue-600 hover:text-blue-800 transition" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="editBudget('gaji')" class="text-yellow-600 hover:text-yellow-800 transition" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 4 - IT & Teknologi -->
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-laptop text-indigo-600"></i>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">IT & Teknologi</div>
                                    <div class="text-xs text-gray-500">Software & hardware</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="text-sm font-bold text-gray-900">Rp 120.000.000</div>
                            <div class="text-xs text-gray-500">10% dari total</div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="text-sm font-bold text-red-600">Rp 135.000.000</div>
                            <div class="text-xs text-gray-500">s/d Desember</div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="text-sm font-bold text-red-600">-Rp 15.000.000</div>
                            <div class="text-xs text-red-500">Over 12.5%</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <div class="flex-1 bg-gray-200 rounded-full h-2">
                                    <div class="bg-red-500 h-2 rounded-full" style="width: 100%"></div>
                                </div>
                                <span class="text-sm font-semibold text-red-600 w-12">112%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-1"></i> Over Budget
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="viewDetail('it')" class="text-blue-600 hover:text-blue-800 transition" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="editBudget('it')" class="text-yellow-600 hover:text-yellow-800 transition" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Row 5 - Utilitas -->
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-lightbulb text-green-600"></i>
                                </div>
                                <div>
                                    <div class="font-semibold text-gray-900">Utilitas</div>
                                    <div class="text-xs text-gray-500">Listrik, air, internet</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="text-sm font-bold text-gray-900">Rp 80.000.000</div>
                            <div class="text-xs text-gray-500">6.7% dari total</div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="text-sm font-bold text-orange-600">Rp 54.000.000</div>
                            <div class="text-xs text-gray-500">s/d Desember</div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="text-sm font-bold text-green-600">Rp 26.000.000</div>
                            <div class="text-xs text-gray-500">32.5% tersisa</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <div class="flex-1 bg-gray-200 rounded-full h-2">
                                    <div class="bg-orange-500 h-2 rounded-full" style="width: 67.5%"></div>
                                </div>
                                <span class="text-sm font-semibold text-gray-900 w-12">68%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-1"></i> On Track
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <button onclick="viewDetail('utilitas')" class="text-blue-600 hover:text-blue-800 transition" title="Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="editBudget('utilitas')" class="text-yellow-600 hover:text-yellow-800 transition" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
                <tfoot class="bg-gray-100">
                    <tr class="font-bold">
                        <td class="px-6 py-4 text-gray-900">TOTAL</td>
                        <td class="px-6 py-4 text-right text-gray-900">Rp 1.200.000.000</td>
                        <td class="px-6 py-4 text-right text-orange-600">Rp 822.000.000</td>
                        <td class="px-6 py-4 text-right text-green-600">Rp 378.000.000</td>
                        <td class="px-6 py-4 text-center">
                            <span class="text-orange-600">68.5%</span>
                        </td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Monthly Trend & Department Breakdown -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Monthly Trend -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Tren Penggunaan Bulanan</h3>
                    <p class="text-sm text-gray-500">Realisasi anggaran per bulan 2024</p>
                </div>
                <div class="bg-blue-100 p-2 rounded-lg">
                    <i class="fas fa-chart-bar text-blue-600"></i>
                </div>
            </div>

            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div class="flex items-center gap-3">
                        <div class="text-sm font-medium text-gray-700 w-20">Januari</div>
                        <div class="flex-1">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full" style="width: 62%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="text-right ml-4">
                        <p class="text-sm font-bold text-gray-900">Rp 62 Jt</p>
                        <p class="text-xs text-gray-500">62%</p>
                    </div>
                </div>

                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div class="flex items-center gap-3">
                        <div class="text-sm font-medium text-gray-700 w-20">Februari</div>
                        <div class="flex-1">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full" style="width: 58%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="text-right ml-4">
                        <p class="text-sm font-bold text-gray-900">Rp 58 Jt</p>
                        <p class="text-xs text-gray-500">58%</p>
                    </div>
                </div>

                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div class="flex items-center gap-3">
                        <div class="text-sm font-medium text-gray-700 w-20">Maret</div>
                        <div class="flex-1">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full" style="width: 71%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="text-right ml-4">
                        <p class="text-sm font-bold text-gray-900">Rp 71 Jt</p>
                        <p class="text-xs text-gray-500">71%</p>
                    </div>
                </div>

                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div class="flex items-center gap-3">
                        <div class="text-sm font-medium text-gray-700 w-20">April</div>
                        <div class="flex-1">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-500 h-2 rounded-full" style="width: 65%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="text-right ml-4">
                        <p class="text-sm font-bold text-gray-900">Rp 65 Jt</p>
                        <p class="text-xs text-gray-500">65%</p>
                    </div>
                </div>

                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div class="flex items-center gap-3">
                        <div class="text-sm font-medium text-gray-700 w-20">Mei</div>
                        <div class="flex-1">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-orange-500 h-2 rounded-full" style="width: 78%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="text-right ml-4">
                        <p class="text-sm font-bold text-gray-900">Rp 78 Jt</p>
                        <p class="text-xs text-orange-500">78%</p>
                    </div>
                </div>

                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                    <div class="flex items-center gap-3">
                        <div class="text-sm font-medium text-gray-700 w-20">Juni</div>
                        <div class="flex-1">
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-orange-500 h-2 rounded-full" style="width: 82%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="text-right ml-4">
                        <p class="text-sm font-bold text-gray-900">Rp 82 Jt</p>
                        <p class="text-xs text-orange-500">82%</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Department Breakdown -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Breakdown per Departemen</h3>
                    <p class="text-sm text-gray-500">Persentase anggaran tahun 2024</p>
                </div>
                <div class="bg-purple-100 p-2 rounded-lg">
                    <i class="fas fa-chart-pie text-purple-600"></i>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-blue-500 rounded"></div>
                            <span class="text-sm font-medium text-gray-700">Operasional</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900">Rp 450 Jt (37.5%)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-500 h-2 rounded-full" style="width: 37.5%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-purple-500 rounded"></div>
                            <span class="text-sm font-medium text-gray-700">Gaji & Tunjangan</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900">Rp 300 Jt (25%)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-purple-500 h-2 rounded-full" style="width: 25%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-pink-500 rounded"></div>
                            <span class="text-sm font-medium text-gray-700">Marketing</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900">Rp 250 Jt (20.8%)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-pink-500 h-2 rounded-full" style="width: 20.8%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-indigo-500 rounded"></div>
                            <span class="text-sm font-medium text-gray-700">IT & Teknologi</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900">Rp 120 Jt (10%)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-indigo-500 h-2 rounded-full" style="width: 10%"></div>
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-green-500 rounded"></div>
                            <span class="text-sm font-medium text-gray-700">Utilitas</span>
                        </div>
                        <span class="text-sm font-bold text-gray-900">Rp 80 Jt (6.7%)</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-500 h-2 rounded-full" style="width: 6.7%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts & Warnings -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-800">Peringatan & Notifikasi</h3>
                <p class="text-sm text-gray-500">Item yang memerlukan perhatian</p>
            </div>
            <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-semibold">3 Peringatan</span>
        </div>

        <div class="space-y-3">
            <div class="flex items-start gap-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-exclamation-triangle text-red-600"></i>
                </div>
                <div class="flex-1">
                    <h4 class="font-semibold text-gray-900">Anggaran IT & Teknologi Melampaui Batas</h4>
                    <p class="text-sm text-gray-600 mt-1">Realisasi sebesar Rp 135 Jt telah melebihi anggaran yang dialokasikan (Rp 120 Jt). Segera lakukan revisi anggaran atau evaluasi pengeluaran.</p>
                    <div class="flex items-center gap-4 mt-3">
                        <span class="text-xs text-red-600 font-medium">Over Budget: 12.5%</span>
                        <button onclick="viewDetail('it')" class="text-sm text-red-600 hover:text-red-800 font-medium">
                            Lihat Detail →
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex items-start gap-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-yellow-600"></i>
                </div>
                <div class="flex-1">
                    <h4 class="font-semibold text-gray-900">Anggaran Marketing Mendekati Batas</h4>
                    <p class="text-sm text-gray-600 mt-1">Penggunaan anggaran marketing telah mencapai 87.2%. Perhatikan pengeluaran di bulan terakhir agar tidak melebihi alokasi.</p>
                    <div class="flex items-center gap-4 mt-3">
                        <span class="text-xs text-yellow-600 font-medium">Terpakai: 87.2%</span>
                        <button onclick="viewDetail('marketing')" class="text-sm text-yellow-600 hover:text-yellow-800 font-medium">
                            Lihat Detail →
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex items-start gap-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-info-circle text-blue-600"></i>
                </div>
                <div class="flex-1">
                    <h4 class="font-semibold text-gray-900">Revisi Anggaran Q1 2025 Diperlukan</h4>
                    <p class="text-sm text-gray-600 mt-1">Waktu untuk menyusun anggaran kuartal pertama tahun 2025 akan segera tiba. Segera siapkan usulan anggaran dari masing-masing departemen.</p>
                    <div class="flex items-center gap-4 mt-3">
                        <span class="text-xs text-blue-600 font-medium">Deadline: 31 Desember 2024</span>
                        <button class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                            Mulai Penyusunan →
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-teal-600 to-teal-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-teal-100 text-sm font-medium">Efisiensi Anggaran</h4>
                <i class="fas fa-percentage text-2xl"></i>
            </div>
            <h3 class="text-3xl font-bold mb-2">68.5%</h3>
            <p class="text-teal-100 text-sm">Rata-rata penggunaan anggaran</p>
            <div class="mt-3 flex items-center text-sm">
                <span class="bg-teal-700 bg-opacity-50 px-2 py-1 rounded">
                    Target: 70%
                </span>
            </div>
        </div>

        <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-indigo-100 text-sm font-medium">Proyeksi Akhir Tahun</h4>
                <i class="fas fa-chart-line text-2xl"></i>
            </div>
            <h3 class="text-2xl font-bold mb-2">Rp 1.15 M</h3>
            <p class="text-indigo-100 text-sm">Estimasi total penggunaan 2024</p>
            <div class="mt-3 flex items-center text-sm">
                <span class="bg-indigo-700 bg-opacity-50 px-2 py-1 rounded">
                    <i class="fas fa-arrow-down mr-1"></i>4.2% di bawah target
                </span>
            </div>
        </div>

        <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <h4 class="text-amber-100 text-sm font-medium">Budget Variance</h4>
                <i class="fas fa-balance-scale text-2xl"></i>
            </div>
            <h3 class="text-2xl font-bold mb-2">+3.8%</h3>
            <p class="text-amber-100 text-sm">Variance dari perencanaan awal</p>
            <div class="mt-3 flex items-center text-sm">
                <span class="bg-amber-700 bg-opacity-50 px-2 py-1 rounded">
                    Dalam batas wajar
                </span>
            </div>
        </div>
    </div>

</div>

<!-- Modal Detail Anggaran -->
<div id="modalDetail" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-gradient-to-r from-teal-600 to-teal-700 text-white p-6 rounded-t-2xl">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-2xl font-bold">Detail Anggaran Operasional</h3>
                    <p class="text-teal-100 text-sm mt-1">Tahun 2024</p>
                </div>
                <button onclick="closeModal()" class="text-white hover:text-gray-200 transition">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>
        </div>

        <div class="p-6 space-y-6">
            <!-- Summary Badge -->
            <div class="grid grid-cols-3 gap-4">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-center">
                    <p class="text-sm text-gray-600 mb-1">Total Anggaran</p>
                    <p class="text-xl font-bold text-blue-600">Rp 450 Jt</p>
                </div>
                <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 text-center">
                    <p class="text-sm text-gray-600 mb-1">Realisasi</p>
                    <p class="text-xl font-bold text-orange-600">Rp 315 Jt</p>
                </div>
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                    <p class="text-sm text-gray-600 mb-1">Sisa</p>
                    <p class="text-xl font-bold text-green-600">Rp 135 Jt</p>
                </div>
            </div>

            <!-- Progress Bar -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium text-gray-700">Progress Penggunaan</span>
                    <span class="text-sm font-bold text-gray-900">70%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-4">
                    <div class="bg-orange-500 h-4 rounded-full flex items-center justify-end px-2" style="width: 70%">
                        <span class="text-xs text-white font-semibold">70%</span>
                    </div>
                </div>
            </div>

            <!-- Monthly Breakdown -->
            <div>
                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-calendar-alt text-teal-600 mr-2"></i>
                    Rincian Penggunaan Bulanan
                </h4>
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Bulan</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600">Budget</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600">Realisasi</th>
                                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600">Variance</th>
                                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600">%</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900">Januari</td>
                                <td class="px-4 py-3 text-right text-sm text-gray-900">Rp 37.5 Jt</td>
                                <td class="px-4 py-3 text-right text-sm font-semibold text-gray-900">Rp 35.2 Jt</td>
                                <td class="px-4 py-3 text-right text-sm font-semibold text-green-600">-Rp 2.3 Jt</td>
                                <td class="px-4 py-3 text-center text-sm font-semibold text-gray-900">94%</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900">Februari</td>
                                <td class="px-4 py-3 text-right text-sm text-gray-900">Rp 37.5 Jt</td>
                                <td class="px-4 py-3 text-right text-sm font-semibold text-gray-900">Rp 33.8 Jt</td>
                                <td class="px-4 py-3 text-right text-sm font-semibold text-green-600">-Rp 3.7 Jt</td>
                                <td class="px-4 py-3 text-center text-sm font-semibold text-gray-900">90%</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900">Maret</td>
                                <td class="px-4 py-3 text-right text-sm text-gray-900">Rp 37.5 Jt</td>
                                <td class="px-4 py-3 text-right text-sm font-semibold text-gray-900">Rp 38.9 Jt</td>
                                <td class="px-4 py-3 text-right text-sm font-semibold text-red-600">+Rp 1.4 Jt</td>
                                <td class="px-4 py-3 text-center text-sm font-semibold text-gray-900">104%</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900">April</td>
                                <td class="px-4 py-3 text-right text-sm text-gray-900">Rp 37.5 Jt</td>
                                <td class="px-4 py-3 text-right text-sm font-semibold text-gray-900">Rp 36.2 Jt</td>
                                <td class="px-4 py-3 text-right text-sm font-semibold text-green-600">-Rp 1.3 Jt</td>
                                <td class="px-4 py-3 text-center text-sm font-semibold text-gray-900">97%</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900">Mei</td>
                                <td class="px-4 py-3 text-right text-sm text-gray-900">Rp 37.5 Jt</td>
                                <td class="px-4 py-3 text-right text-sm font-semibold text-gray-900">Rp 39.1 Jt</td>
                                <td class="px-4 py-3 text-right text-sm font-semibold text-red-600">+Rp 1.6 Jt</td>
                                <td class="px-4 py-3 text-center text-sm font-semibold text-gray-900">104%</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900">Juni</td>
                                <td class="px-4 py-3 text-right text-sm text-gray-900">Rp 37.5 Jt</td>
                                <td class="px-4 py-3 text-right text-sm font-semibold text-gray-900">Rp 35.8 Jt</td>
                                <td class="px-4 py-3 text-right text-sm font-semibold text-green-600">-Rp 1.7 Jt</td>
                                <td class="px-4 py-3 text-center text-sm font-semibold text-gray-900">95%</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900">Juli - Des</td>
                                <td class="px-4 py-3 text-right text-sm text-gray-900">Rp 225 Jt</td>
                                <td class="px-4 py-3 text-right text-sm font-semibold text-gray-900">Rp 96 Jt</td>
                                <td class="px-4 py-3 text-right text-sm font-semibold text-green-600">-Rp 129 Jt</td>
                                <td class="px-4 py-3 text-center text-sm font-semibold text-gray-900">43%</td>
                            </tr>
                        </tbody>
                        <tfoot class="bg-gray-100 font-bold">
                            <tr>
                                <td class="px-4 py-3 text-sm text-gray-900">TOTAL</td>
                                <td class="px-4 py-3 text-right text-sm text-gray-900">Rp 450 Jt</td>
                                <td class="px-4 py-3 text-right text-sm text-orange-600">Rp 315 Jt</td>
                                <td class="px-4 py-3 text-right text-sm text-green-600">-Rp 135 Jt</td>
                                <td class="px-4 py-3 text-center text-sm text-gray-900">70%</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- Sub Categories -->
            <div>
                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-layer-group text-teal-600 mr-2"></i>
                    Sub Kategori Operasional
                </h4>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-box text-blue-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">ATK & Supplies</p>
                                <p class="text-xs text-gray-500">Budget: Rp 120 Jt</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-gray-900">Rp 85 Jt</p>
                            <p class="text-xs text-green-600">71% terpakai</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-tools text-purple-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Maintenance</p>
                                <p class="text-xs text-gray-500">Budget: Rp 180 Jt</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-gray-900">Rp 125 Jt</p>
                            <p class="text-xs text-green-600">69% terpakai</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-car text-green-600 text-sm"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Transport & Logistik</p>
                                <p class="text-xs text-gray-500">Budget: Rp 150 Jt</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-gray-900">Rp 105 Jt</p>
                            <p class="text-xs text-green-600">70% terpakai</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div>
                <h4 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-sticky-note text-teal-600 mr-2"></i>
                    Catatan
                </h4>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm text-gray-700">Penggunaan anggaran operasional masih dalam batas normal dan on track. Proyeksi hingga akhir tahun diperkirakan tidak akan melebihi alokasi yang telah ditentukan.</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3 pt-4 border-t border-gray-200">
                <button onclick="editBudget('operasional')" class="flex-1 bg-yellow-600 hover:bg-yellow-700 text-white py-3 rounded-lg font-semibold transition">
                    <i class="fas fa-edit mr-2"></i>Edit Anggaran
                </button>
                <button onclick="exportReport('operasional')" class="flex-1 bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-semibold transition">
                    <i class="fas fa-file-export mr-2"></i>Export Report
                </button>
                <button onclick="closeModal()" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-semibold transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Period and Department Selector
document.getElementById('periodSelector').addEventListener('change', function() {
    console.log('Period changed to:', this.value);
    // Implementasi filter berdasarkan periode
});

document.getElementById('deptSelector').addEventListener('change', function() {
    console.log('Department changed to:', this.value);
    // Implementasi filter berdasarkan departemen
});

// Export Functions
function exportData() {
    alert('Export Excel sedang diproses...');
}

function exportReport(category) {
    alert(`Export report untuk kategori ${category} sedang diproses...`);
}

// Modal Functions
function viewDetail(category) {
    document.getElementById('modalDetail').classList.remove('hidden');
    console.log('Viewing detail for:', category);
    // Update modal content based on category
}

function closeModal() {
    document.getElementById('modalDetail').classList.add('hidden');
}

function editBudget(category) {
    window.location.href = `/finance/anggaran/${category}/edit`;
}

// Close modal when clicking outside
document.getElementById('modalDetail').addEventListener('click', function(e) {
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