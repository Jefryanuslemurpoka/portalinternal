@extends('layouts.app')

@section('title', 'Buat Invoice')
@section('page-title', 'Buat Invoice Baru')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <a href="javascript:history.back()" class="text-teal-600 hover:text-teal-700 font-medium inline-flex items-center mb-4">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
            <h2 class="text-2xl font-bold text-gray-800">Buat Invoice Baru</h2>
            <p class="text-gray-600 text-sm">Isi data invoice dengan lengkap dan benar</p>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-2xl shadow-lg p-6 space-y-6">

        <!-- Informasi Invoice -->
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-file-invoice mr-2 text-teal-600"></i>Informasi Invoice
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">No. Invoice</label>
                    <input type="text" value="INV-2024-007" readonly
                        class="w-full px-4 py-2 border rounded-lg bg-gray-100 text-gray-600">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Invoice</label>
                    <input type="date"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jatuh Tempo</label>
                    <input type="date"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>
            </div>
        </div>

        <!-- Data Customer -->
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-user mr-2 text-teal-600"></i>Data Customer
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Customer</label>
                    <input type="text" placeholder="PT / CV / Nama Customer"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email Customer</label>
                    <input type="email" placeholder="email@customer.com"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>
            </div>
        </div>

        <!-- Detail Project -->
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-briefcase mr-2 text-teal-600"></i>Detail Project
            </h3>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Project / Pekerjaan</label>
                <input type="text" placeholder="Contoh: Website Development"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>
        </div>

        <!-- Item Invoice -->
        <div>
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-list mr-2 text-teal-600"></i>Item Invoice
            </h3>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-semibold">Deskripsi</th>
                            <th class="px-4 py-3 text-right text-sm font-semibold">Qty</th>
                            <th class="px-4 py-3 text-right text-sm font-semibold">Harga</th>
                            <th class="px-4 py-3 text-right text-sm font-semibold">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b">
                            <td class="px-4 py-3">
                                <input type="text" placeholder="Deskripsi pekerjaan"
                                    class="w-full px-3 py-2 border rounded-lg">
                            </td>
                            <td class="px-4 py-3">
                                <input type="number" value="1"
                                    class="w-full px-3 py-2 border rounded-lg text-right">
                            </td>
                            <td class="px-4 py-3">
                                <input type="number" placeholder="0"
                                    class="w-full px-3 py-2 border rounded-lg text-right">
                            </td>
                            <td class="px-4 py-3 text-right font-semibold text-gray-700">
                                Rp 0
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <button class="mt-4 text-teal-600 hover:text-teal-800 text-sm font-semibold">
                <i class="fas fa-plus mr-1"></i>Tambah Item
            </button>
        </div>

        <!-- Catatan -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (Opsional)</label>
            <textarea rows="3" placeholder="Catatan tambahan untuk invoice"
                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-teal-500"></textarea>
        </div>

        <!-- Total -->
        <div class="flex justify-end">
            <div class="w-full md:w-1/3 space-y-2">
                <div class="flex justify-between text-sm">
                    <span>Subtotal</span>
                    <span>Rp 0</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span>Pajak</span>
                    <span>Rp 0</span>
                </div>
                <div class="flex justify-between font-bold text-lg border-t pt-2">
                    <span>Total</span>
                    <span>Rp 0</span>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex justify-end gap-3 pt-4 border-t">
            <button class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-100">
                Simpan Draft
            </button>
            <button class="px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white rounded-lg shadow-lg">
                Kirim Invoice
            </button>
        </div>

    </div>
</div>
@endsection
