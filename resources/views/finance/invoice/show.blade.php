@extends('layouts.app')

@section('title', 'Detail Invoice')
@section('page-title', 'Detail Invoice')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Detail Invoice</h2>
            <p class="text-gray-600 text-sm">Informasi lengkap invoice</p>
        </div>
        <a href="#" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-5 py-3 rounded-lg transition">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <!-- Invoice Summary -->
    <div class="bg-white rounded-2xl shadow-lg p-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <div>
                <p class="text-sm text-gray-500">No. Invoice</p>
                <p class="text-lg font-semibold text-teal-600">INV-2024-001</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Tanggal Invoice</p>
                <p class="text-gray-800 font-medium">15 Des 2024</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Status</p>
                <span class="inline-flex items-center px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">
                    <i class="fas fa-check-circle mr-1"></i>Dibayar
                </span>
            </div>

        </div>
    </div>

    <!-- Customer & Project -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-building mr-2 text-teal-600"></i>Customer
            </h3>
            <div class="space-y-2 text-sm">
                <p><span class="text-gray-500">Nama:</span> <span class="font-medium text-gray-800">PT Maju Jaya</span></p>
                <p><span class="text-gray-500">Email:</span> maju@example.com</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-briefcase mr-2 text-teal-600"></i>Project
            </h3>
            <div class="space-y-2 text-sm">
                <p><span class="text-gray-500">Nama Project:</span> <span class="font-medium text-gray-800">Website Development</span></p>
                <p><span class="text-gray-500">Jatuh Tempo:</span> 30 Des 2024</p>
            </div>
        </div>

    </div>

    <!-- Invoice Detail -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Deskripsi</th>
                    <th class="px-6 py-4 text-right text-sm font-semibold text-gray-700">Jumlah</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-sm">
                <tr>
                    <td class="px-6 py-4">Pengembangan Website Company Profile</td>
                    <td class="px-6 py-4 text-right font-medium">Rp 50.000.000</td>
                </tr>
            </tbody>
            <tfoot class="bg-gray-50">
                <tr>
                    <td class="px-6 py-4 text-right font-semibold text-gray-700">Total</td>
                    <td class="px-6 py-4 text-right text-lg font-bold text-gray-800">Rp 50.000.000</td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Notes -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-2">
            <i class="fas fa-sticky-note mr-2 text-teal-600"></i>Catatan
        </h3>
        <p class="text-gray-700 text-sm">
            Pembayaran telah diterima melalui transfer bank.
        </p>
    </div>

    <!-- Actions -->
    <div class="flex justify-end space-x-4">
        <button onclick="window.location.href='/finance/invoice-edit'"
            class="px-6 py-3 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition">
            <i class="fas fa-edit mr-2"></i>Edit
        </button>

        <button class="px-6 py-3 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition">
            <i class="fas fa-download mr-2"></i>Download PDF
        </button>
    </div>

</div>
@endsection
