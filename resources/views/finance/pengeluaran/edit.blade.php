@extends('layouts.app')

@section('title', 'Edit Pengeluaran')
@section('page-title', 'Edit Data Pengeluaran')

@section('content')
{{-- Data dummy static untuk demo tampilan --}}
@php
    $pengeluaran = [
        'no_invoice' => 'INV-202412-0001',
        'no_po' => 'PO-202412-0001',
        'tanggal' => '2024-12-01',
        'tanggal_jatuh_tempo' => '2024-12-31',
        'kategori' => 'operasional',
        'metode_pembayaran' => 'transfer',
        'deskripsi' => 'Pembelian ATK dan perlengkapan kantor untuk bulan Desember 2024',
        'nama_vendor' => 'PT. Sejahtera Abadi',
        'kontak_vendor' => '021-12345678',
        'email_vendor' => 'vendor@sejahtera.com',
        'alamat_vendor' => 'Jl. Sudirman No. 123, Jakarta Pusat 10220',
        'ppn_persen' => 11,
        'biaya_admin' => 5000,
        'diskon' => 50000,
        'catatan' => 'Pembayaran dilakukan melalui transfer bank BCA. Invoice sudah diterima dan diverifikasi oleh bagian keuangan.',
        'items' => [
            ['nama' => 'Kertas A4 80gsm (1 Rim)', 'qty' => 50, 'harga' => 40000],
            ['nama' => 'Tinta Printer Canon GI-790 Black', 'qty' => 10, 'harga' => 150000],
            ['nama' => 'Bolpoint Pilot G2 0.7mm', 'qty' => 100, 'harga' => 3000],
        ],
        'lampiran' => [
            'pengeluaran/invoice-202412-0001.pdf',
            'pengeluaran/kwitansi-202412-0001.jpg',
            'pengeluaran/po-dokumen.pdf'
        ]
    ];
@endphp

<div class="space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <a href="{{ route('finance.pengeluaran.index') }}" class="text-teal-600 hover:text-teal-700 font-medium inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Pengeluaran
            </a>
            <h2 class="text-2xl font-bold text-gray-800 mt-2">Edit Pengeluaran</h2>
            <p class="text-gray-600 text-sm">Perbarui data pengeluaran yang sudah ada</p>
        </div>
        <div class="bg-teal-100 p-4 rounded-xl">
            <i class="fas fa-edit text-3xl text-teal-600"></i>
        </div>
    </div>

    <form action="{{ route('finance.pengeluaran.update', 1) }}" method="POST" enctype="multipart/form-data" id="formPengeluaran">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Main Form (Left & Center) -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Informasi Dasar -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200">
                        <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-info-circle text-blue-600"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Informasi Dasar</h3>
                            <p class="text-sm text-gray-500">Data umum pengeluaran</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nomor Invoice <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" name="no_invoice" id="no_invoice" 
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent" 
                                    placeholder="INV-2024-XXXX" 
                                    value="{{ $pengeluaran['no_invoice'] }}" 
                                    required>
                                <i class="fas fa-hashtag absolute left-3 top-4 text-gray-400"></i>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Format: INV-YYYY-XXXX</p>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nomor PO (Opsional)
                            </label>
                            <div class="relative">
                                <input type="text" name="no_po" id="no_po" 
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent" 
                                    placeholder="PO-2024-XXXX"
                                    value="{{ $pengeluaran['no_po'] }}">
                                <i class="fas fa-file-alt absolute left-3 top-4 text-gray-400"></i>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Tanggal Transaksi <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="date" name="tanggal" id="tanggal" 
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent" 
                                    value="{{ $pengeluaran['tanggal'] }}"
                                    required>
                                <i class="fas fa-calendar absolute left-3 top-4 text-gray-400"></i>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Tanggal Jatuh Tempo <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="date" name="tanggal_jatuh_tempo" id="tanggal_jatuh_tempo" 
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent" 
                                    value="{{ $pengeluaran['tanggal_jatuh_tempo'] }}"
                                    required>
                                <i class="fas fa-calendar-check absolute left-3 top-4 text-gray-400"></i>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Kategori Pengeluaran <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select name="kategori" id="kategori" 
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent appearance-none" 
                                    required>
                                    <option value="">-- Pilih Kategori --</option>
                                    <option value="operasional" {{ $pengeluaran['kategori'] == 'operasional' ? 'selected' : '' }}>Operasional</option>
                                    <option value="gaji" {{ $pengeluaran['kategori'] == 'gaji' ? 'selected' : '' }}>Gaji & Tunjangan</option>
                                    <option value="inventaris" {{ $pengeluaran['kategori'] == 'inventaris' ? 'selected' : '' }}>Inventaris</option>
                                    <option value="marketing" {{ $pengeluaran['kategori'] == 'marketing' ? 'selected' : '' }}>Marketing</option>
                                    <option value="utilitas" {{ $pengeluaran['kategori'] == 'utilitas' ? 'selected' : '' }}>Utilitas</option>
                                    <option value="pajak" {{ $pengeluaran['kategori'] == 'pajak' ? 'selected' : '' }}>Pajak</option>
                                    <option value="pemeliharaan" {{ $pengeluaran['kategori'] == 'pemeliharaan' ? 'selected' : '' }}>Pemeliharaan</option>
                                    <option value="transportasi" {{ $pengeluaran['kategori'] == 'transportasi' ? 'selected' : '' }}>Transportasi</option>
                                    <option value="lainnya" {{ $pengeluaran['kategori'] == 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                                </select>
                                <i class="fas fa-layer-group absolute left-3 top-4 text-gray-400"></i>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Metode Pembayaran <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <select name="metode_pembayaran" id="metode_pembayaran" 
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent appearance-none" 
                                    required>
                                    <option value="">-- Pilih Metode --</option>
                                    <option value="transfer" {{ $pengeluaran['metode_pembayaran'] == 'transfer' ? 'selected' : '' }}>Transfer Bank</option>
                                    <option value="virtual_account" {{ $pengeluaran['metode_pembayaran'] == 'virtual_account' ? 'selected' : '' }}>Virtual Account</option>
                                    <option value="cash" {{ $pengeluaran['metode_pembayaran'] == 'cash' ? 'selected' : '' }}>Cash</option>
                                    <option value="credit_card" {{ $pengeluaran['metode_pembayaran'] == 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                                    <option value="debit_card" {{ $pengeluaran['metode_pembayaran'] == 'debit_card' ? 'selected' : '' }}>Debit Card</option>
                                    <option value="cek" {{ $pengeluaran['metode_pembayaran'] == 'cek' ? 'selected' : '' }}>Cek/Giro</option>
                                </select>
                                <i class="fas fa-credit-card absolute left-3 top-4 text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Deskripsi Pengeluaran <span class="text-red-500">*</span>
                        </label>
                        <textarea name="deskripsi" id="deskripsi" rows="3" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent" 
                            placeholder="Deskripsikan detail pengeluaran..." required>{{ $pengeluaran['deskripsi'] }}</textarea>
                    </div>
                </div>

                <!-- Informasi Vendor/Supplier -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-building text-purple-600"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Informasi Vendor/Supplier</h3>
                            <p class="text-sm text-gray-500">Data pihak penerima pembayaran</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nama Vendor/Supplier <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="text" name="nama_vendor" id="nama_vendor" 
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent" 
                                    placeholder="PT. Nama Vendor atau Nama Supplier" 
                                    value="{{ $pengeluaran['nama_vendor'] }}"
                                    required>
                                <i class="fas fa-store absolute left-3 top-4 text-gray-400"></i>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nomor Kontak
                            </label>
                            <div class="relative">
                                <input type="text" name="kontak_vendor" id="kontak_vendor" 
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent" 
                                    placeholder="0812-3456-7890"
                                    value="{{ $pengeluaran['kontak_vendor'] }}">
                                <i class="fas fa-phone absolute left-3 top-4 text-gray-400"></i>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Email Vendor
                            </label>
                            <div class="relative">
                                <input type="email" name="email_vendor" id="email_vendor" 
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent" 
                                    placeholder="vendor@email.com"
                                    value="{{ $pengeluaran['email_vendor'] }}">
                                <i class="fas fa-envelope absolute left-3 top-4 text-gray-400"></i>
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Alamat Vendor
                            </label>
                            <textarea name="alamat_vendor" id="alamat_vendor" rows="2" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent" 
                                placeholder="Alamat lengkap vendor/supplier">{{ $pengeluaran['alamat_vendor'] }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Detail Item Pengeluaran -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-list text-green-600"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">Detail Item Pengeluaran</h3>
                                <p class="text-sm text-gray-500">Edit item yang dibeli</p>
                            </div>
                        </div>
                        <button type="button" onclick="addItem()" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                            <i class="fas fa-plus mr-2"></i>Tambah Item
                        </button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full" id="itemTable">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600">Nama Item</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600" style="width: 100px;">Qty</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600" style="width: 150px;">Harga Satuan</th>
                                    <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600" style="width: 150px;">Subtotal</th>
                                    <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600" style="width: 80px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="itemTableBody" class="divide-y divide-gray-200">
                                @foreach($pengeluaran['items'] as $index => $item)
                                <tr class="item-row">
                                    <td class="px-4 py-3">
                                        <input type="text" name="items[{{ $index }}][nama]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500" placeholder="Nama item" value="{{ $item['nama'] }}" required>
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="number" name="items[{{ $index }}][qty]" class="item-qty w-full px-3 py-2 border border-gray-300 rounded-lg text-sm text-center focus:ring-2 focus:ring-teal-500" placeholder="0" min="1" value="{{ $item['qty'] }}" required>
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="number" name="items[{{ $index }}][harga]" class="item-harga w-full px-3 py-2 border border-gray-300 rounded-lg text-sm text-right focus:ring-2 focus:ring-teal-500" placeholder="0" min="0" value="{{ $item['harga'] }}" required>
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="text" class="item-subtotal w-full px-3 py-2 border border-gray-200 rounded-lg text-sm text-right bg-gray-50 font-semibold text-gray-900" readonly value="Rp 0">
                                    </td>
                                    <td class="px-4 py-3 text-center">
                                        <button type="button" onclick="removeItem(this)" class="text-red-600 hover:text-red-800 transition">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Lampiran -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200">
                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-paperclip text-orange-600"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Lampiran Dokumen</h3>
                            <p class="text-sm text-gray-500">Upload invoice, kwitansi, atau dokumen pendukung</p>
                        </div>
                    </div>

                    <!-- Existing Files -->
                    <div class="mb-4">
                        <p class="text-sm font-semibold text-gray-700 mb-2">Lampiran yang sudah ada:</p>
                        <div class="space-y-2">
                            @foreach($pengeluaran['lampiran'] as $index => $file)
                            <div class="flex items-center justify-between bg-gray-50 border border-gray-200 rounded-lg p-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-file-pdf text-teal-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ basename($file) }}</p>
                                        <a href="#" class="text-xs text-teal-600 hover:text-teal-700">Lihat File</a>
                                    </div>
                                </div>
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="delete_files[]" value="{{ $file }}" class="rounded text-red-600">
                                    <span class="text-xs text-red-600">Hapus</span>
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-8 text-center hover:border-teal-500 transition">
                        <input type="file" name="lampiran[]" id="lampiran" class="hidden" multiple accept=".pdf,.jpg,.jpeg,.png,.doc,.docx" onchange="handleFileSelect(this)">
                        <label for="lampiran" class="cursor-pointer">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                            <p class="text-sm font-medium text-gray-700">Klik untuk upload lampiran baru</p>
                            <p class="text-xs text-gray-500 mt-1">PDF, JPG, PNG, DOC (Max. 5MB per file)</p>
                        </label>
                    </div>

                    <div id="filePreview" class="mt-4 space-y-2"></div>
                </div>

                <!-- Catatan Tambahan -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-200">
                        <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-sticky-note text-yellow-600"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Catatan Tambahan</h3>
                            <p class="text-sm text-gray-500">Informasi tambahan (opsional)</p>
                        </div>
                    </div>

                    <textarea name="catatan" id="catatan" rows="4" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent" 
                        placeholder="Tambahkan catatan atau keterangan khusus untuk pengeluaran ini...">{{ $pengeluaran['catatan'] }}</textarea>
                </div>

            </div>

            <!-- Summary (Right Sidebar) -->
            <div class="lg:col-span-1">
                <div class="sticky top-6 space-y-6">
                    
                    <!-- Ringkasan Pembayaran -->
                    <div class="bg-gradient-to-br from-teal-600 to-teal-600 rounded-xl shadow-lg p-6 text-white">
                        <div class="flex items-center justify-between mb-4 pb-4 border-b border-teal-500">
                            <h3 class="text-lg font-bold">Ringkasan Pembayaran</h3>
                            <i class="fas fa-calculator text-2xl"></i>
                        </div>

                        <div class="space-y-3">
                            <div class="flex justify-between items-center">
                                <span class="text-teal-100 text-sm">Subtotal Item</span>
                                <span class="font-bold" id="displaySubtotal">Rp 0</span>
                            </div>

                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-2">
                                    <span class="text-teal-100 text-sm">PPN</span>
                                    <select name="ppn_persen" id="ppn_persen" class="bg-teal-500 border-teal-400 text-white text-xs rounded px-2 py-1" onchange="calculateTotal()">
                                        <option value="0" {{ $pengeluaran['ppn_persen'] == 0 ? 'selected' : '' }}>0%</option>
                                        <option value="11" {{ $pengeluaran['ppn_persen'] == 11 ? 'selected' : '' }}>11%</option>
                                        <option value="12" {{ $pengeluaran['ppn_persen'] == 12 ? 'selected' : '' }}>12%</option>
                                    </select>
                                </div>
                                <span class="font-bold" id="displayPPN">Rp 0</span>
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-teal-100 text-sm">Biaya Admin</span>
                                <input type="number" name="biaya_admin" id="biaya_admin" 
                                    class="bg-teal-500 border-teal-400 text-white text-sm rounded px-2 py-1 w-32 text-right" 
                                    value="{{ $pengeluaran['biaya_admin'] }}" 
                                    min="0" onchange="calculateTotal()">
                            </div>

                            <div class="flex justify-between items-center">
                                <span class="text-teal-100 text-sm">Diskon</span>
                                <input type="number" name="diskon" id="diskon" 
                                    class="bg-teal-500 border-teal-400 text-white text-sm rounded px-2 py-1 w-32 text-right" 
                                    value="{{ $pengeluaran['diskon'] }}" 
                                    min="0" onchange="calculateTotal()">
                            </div>

                            <div class="border-t border-teal-500 pt-3 mt-3">
                                <div class="flex justify-between items-center">
                                    <span class="font-bold text-lg">Total Pembayaran</span>
                                    <span class="font-bold text-2xl" id="displayTotal">Rp 0</span>
                                </div>
                            </div>
                        </div>

                        <input type="hidden" name="subtotal" id="subtotal" value="0">
                        <input type="hidden" name="ppn" id="ppn" value="0">
                        <input type="hidden" name="total" id="total" value="0">
                    </div>

                    <!-- Quick Tips -->
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                        <div class="flex items-center gap-2 mb-3">
                            <i class="fas fa-lightbulb text-blue-600"></i>
                            <h4 class="font-bold text-blue-900">Tips</h4>
                        </div>
                        <ul class="space-y-2 text-sm text-blue-800">
                            <li class="flex items-start gap-2">
                                <i class="fas fa-check-circle text-blue-600 mt-0.5"></i>
                                <span>Periksa perubahan dengan teliti sebelum menyimpan</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fas fa-check-circle text-blue-600 mt-0.5"></i>
                                <span>Centang "Hapus" untuk menghapus lampiran lama</span>
                            </li>
                            <li class="flex items-start gap-2">
                                <i class="fas fa-check-circle text-blue-600 mt-0.5"></i>
                                <span>Upload lampiran baru jika diperlukan</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Action Buttons -->
                    <div class="bg-white rounded-xl shadow-lg p-6 space-y-3">
                        <button type="submit" class="w-full bg-teal-600 hover:bg-teal-700 text-white py-3 rounded-lg font-semibold transition flex items-center justify-center gap-2">
                            <i class="fas fa-save"></i>
                            <span>Update Pengeluaran</span>
                        </button>
                        
                        <a href="{{ route('finance.pengeluaran.index') }}" class="w-full bg-white border-2 border-gray-300 hover:bg-gray-50 text-gray-700 py-3 rounded-lg font-semibold transition flex items-center justify-center gap-2">
                            <i class="fas fa-times"></i>
                            <span>Batal</span>
                        </a>
                    </div>

                </div>
            </div>

        </div>
    </form>

</div>

<script>
let itemCount = {{ count($pengeluaran['items']) }};

// Add new item row
function addItem() {
    const tbody = document.getElementById('itemTableBody');
    const newRow = `
        <tr class="item-row">
            <td class="px-4 py-3">
                <input type="text" name="items[${itemCount}][nama]" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-teal-500" placeholder="Nama item" required>
            </td>
            <td class="px-4 py-3">
                <input type="number" name="items[${itemCount}][qty]" class="item-qty w-full px-3 py-2 border border-gray-300 rounded-lg text-sm text-center focus:ring-2 focus:ring-teal-500" placeholder="0" min="1" value="1" required>
            </td>
            <td class="px-4 py-3">
                <input type="number" name="items[${itemCount}][harga]" class="item-harga w-full px-3 py-2 border border-gray-300 rounded-lg text-sm text-right focus:ring-2 focus:ring-teal-500" placeholder="0" min="0" required>
            </td>
            <td class="px-4 py-3">
                <input type="text" class="item-subtotal w-full px-3 py-2 border border-gray-200 rounded-lg text-sm text-right bg-gray-50 font-semibold text-gray-900" readonly value="Rp 0">
            </td>
            <td class="px-4 py-3 text-center">
                <button type="button" onclick="removeItem(this)" class="text-red-600 hover:text-red-800 transition">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `;
    tbody.insertAdjacentHTML('beforeend', newRow);
    itemCount++;
    
    attachItemEventListeners();
}

// Remove item row
function removeItem(button) {
    const rows = document.querySelectorAll('.item-row');
    if (rows.length > 1) {
        button.closest('tr').remove();
        calculateTotal();
    } else {
        alert('Minimal harus ada 1 item!');
    }
}

// Format currency
function formatRupiah(angka) {
    return 'Rp ' + parseInt(angka).toLocaleString('id-ID');
}

// Calculate item subtotal
function calculateItemSubtotal(row) {
    const qty = parseFloat(row.querySelector('.item-qty').value) || 0;
    const harga = parseFloat(row.querySelector('.item-harga').value) || 0;
    const subtotal = qty * harga;
    
    row.querySelector('.item-subtotal').value = formatRupiah(subtotal);
    calculateTotal();
}

// Calculate total
function calculateTotal() {
    let subtotal = 0;
    
    document.querySelectorAll('.item-row').forEach(row => {
        const qty = parseFloat(row.querySelector('.item-qty').value) || 0;
        const harga = parseFloat(row.querySelector('.item-harga').value) || 0;
        subtotal += (qty * harga);
    });
    
    const ppnPersen = parseFloat(document.getElementById('ppn_persen').value) || 0;
    const ppn = subtotal * (ppnPersen / 100);
    const biayaAdmin = parseFloat(document.getElementById('biaya_admin').value) || 0;
    const diskon = parseFloat(document.getElementById('diskon').value) || 0;
    
    const total = subtotal + ppn + biayaAdmin - diskon;
    
    document.getElementById('displaySubtotal').textContent = formatRupiah(subtotal);
    document.getElementById('displayPPN').textContent = formatRupiah(ppn);
    document.getElementById('displayTotal').textContent = formatRupiah(total);
    
    document.getElementById('subtotal').value = subtotal;
    document.getElementById('ppn').value = ppn;
    document.getElementById('total').value = total;
}

// Attach event listeners to item inputs
function attachItemEventListeners() {
    document.querySelectorAll('.item-qty, .item-harga').forEach(input => {
        input.removeEventListener('input', handleItemInput);
        input.addEventListener('input', handleItemInput);
    });
}

function handleItemInput(e) {
    calculateItemSubtotal(e.target.closest('tr'));
}

// Handle file selection
function handleFileSelect(input) {
    const files = input.files;
    const preview = document.getElementById('filePreview');
    preview.innerHTML = '';
    
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const fileSize = (file.size / 1024 / 1024).toFixed(2);
        
        if (fileSize > 5) {
            alert(`File ${file.name} terlalu besar! Maksimal 5MB per file.`);
            continue;
        }
        
        const fileItem = document.createElement('div');
        fileItem.className = 'flex items-center justify-between bg-gray-50 border border-gray-200 rounded-lg p-3';
        fileItem.innerHTML = `
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-file text-teal-600"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-900">${file.name}</p>
                    <p class="text-xs text-gray-500">${fileSize} MB</p>
                </div>
            </div>
            <button type="button" onclick="removeFile(this, ${i})" class="text-red-600 hover:text-red-800 transition">
                <i class="fas fa-times"></i>
            </button>
        `;
        preview.appendChild(fileItem);
    }
}

// Remove file from selection
function removeFile(button, index) {
    const input = document.getElementById('lampiran');
    const dt = new DataTransfer();
    const files = input.files;
    
    for (let i = 0; i < files.length; i++) {
        if (i !== index) {
            dt.items.add(files[i]);
        }
    }
    
    input.files = dt.files;
    handleFileSelect(input);
}

// Form validation before submit
document.getElementById('formPengeluaran').addEventListener('submit', function(e) {
    const items = document.querySelectorAll('.item-row');
    let hasValidItem = false;
    
    items.forEach(row => {
        const qty = parseFloat(row.querySelector('.item-qty').value) || 0;
        const harga = parseFloat(row.querySelector('.item-harga').value) || 0;
        if (qty > 0 && harga > 0) {
            hasValidItem = true;
        }
    });
    
    if (!hasValidItem) {
        e.preventDefault();
        alert('Minimal harus ada 1 item dengan qty dan harga yang valid!');
        return false;
    }
    
    const total = parseFloat(document.getElementById('total').value) || 0;
    if (total <= 0) {
        e.preventDefault();
        alert('Total pembayaran harus lebih dari 0!');
        return false;
    }
    
    const submitBtn = this.querySelector('button[type="submit"]');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengupdate...';
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    attachItemEventListeners();
    
    // Calculate existing items
    document.querySelectorAll('.item-row').forEach(row => {
        calculateItemSubtotal(row);
    });
    
    // Final total calculation
    calculateTotal();
});

// Confirm before leaving if form has data
let formModified = false;
document.getElementById('formPengeluaran').addEventListener('input', function() {
    formModified = true;
});

window.addEventListener('beforeunload', function(e) {
    if (formModified) {
        e.preventDefault();
        e.returnValue = '';
    }
});

document.getElementById('formPengeluaran').addEventListener('submit', function() {
    formModified = false;
});

// Drag and drop file upload
const dropZone = document.querySelector('.border-dashed');
if (dropZone) {
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
    });
    
    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }
    
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });
    
    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });
    
    function highlight(e) {
        dropZone.classList.add('border-teal-500', 'bg-teal-50');
    }
    
    function unhighlight(e) {
        dropZone.classList.remove('border-teal-500', 'bg-teal-50');
    }
    
    dropZone.addEventListener('drop', handleDrop, false);
    
    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        document.getElementById('lampiran').files = files;
        handleFileSelect(document.getElementById('lampiran'));
    }
}

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    if ((e.ctrlKey || e.metaKey) && e.key === 's') {
        e.preventDefault();
        document.getElementById('formPengeluaran').requestSubmit();
    }
    
    if ((e.ctrlKey || e.metaKey) && e.key === 'i') {
        e.preventDefault();
        addItem();
    }
});

// Auto calculate when PPN percentage changes
document.getElementById('ppn_persen').addEventListener('change', calculateTotal);

// Auto format number inputs
document.querySelectorAll('input[type="number"]').forEach(input => {
    input.addEventListener('wheel', function(e) {
        e.preventDefault();
    });
});

// Payment method info
document.getElementById('metode_pembayaran').addEventListener('change', function() {
    const metode = this.value;
    const biayaAdminInput = document.getElementById('biaya_admin');
    
    const biayaAdminDefaults = {
        'transfer': 0,
        'virtual_account': 4000,
        'cash': 0,
        'credit_card': 10000,
        'debit_card': 5000,
        'cek': 15000
    };
    
    if (biayaAdminDefaults[metode] !== undefined) {
        biayaAdminInput.value = biayaAdminDefaults[metode];
        calculateTotal();
    }
});

// Real-time validation
document.querySelectorAll('input[required], select[required], textarea[required]').forEach(field => {
    field.addEventListener('blur', function() {
        if (!this.value) {
            this.classList.add('border-red-500');
            this.classList.remove('border-gray-300');
        } else {
            this.classList.remove('border-red-500');
            this.classList.add('border-gray-300');
        }
    });
    
    field.addEventListener('input', function() {
        if (this.value) {
            this.classList.remove('border-red-500');
            this.classList.add('border-gray-300');
        }
    });
});

console.log('%c✅ Form edit initialized successfully!', 'color: #10b981; font-weight: bold;');
</script>

@endsection