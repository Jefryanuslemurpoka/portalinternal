@extends('layouts.app')

@section('title', 'Edit Anggaran')
@section('page-title', 'Edit Data Anggaran')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <a href="{{ route('finance.anggaran.index') }}" class="text-teal-600 hover:text-teal-700 font-medium inline-flex items-center mb-4">
                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar
            </a>
            <h2 class="text-2xl font-bold text-gray-800">Edit Data Anggaran</h2>
            <p class="text-gray-600 text-sm">Perbarui informasi anggaran yang sudah ada</p>
        </div>
        <div class="bg-teal-100 p-4 rounded-xl">
            <i class="fas fa-edit text-4xl text-teal-600"></i>
        </div>
    </div>

    <!-- Form -->
    <form id="formAnggaran" class="space-y-6">
        
        <!-- Informasi Periode -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center mb-6">
                <div class="bg-blue-100 p-3 rounded-lg mr-3">
                    <i class="fas fa-calendar-alt text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Informasi Periode</h3>
                    <p class="text-sm text-gray-500">Tentukan periode anggaran</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Periode Anggaran -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Periode Anggaran <span class="text-red-500">*</span>
                    </label>
                    <select id="periode" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        <option value="">Pilih Periode</option>
                        <option value="2024">Tahun 2024</option>
                        <option value="2025" selected>Tahun 2025</option>
                        <option value="2026">Tahun 2026</option>
                    </select>
                </div>

                <!-- Tipe Periode -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Tipe Periode <span class="text-red-500">*</span>
                    </label>
                    <select id="tipe_periode" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        <option value="">Pilih Tipe</option>
                        <option value="tahunan" selected>Tahunan</option>
                        <option value="kuartalan">Kuartalan</option>
                        <option value="bulanan">Bulanan</option>
                    </select>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select id="status" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        <option value="">Pilih Status</option>
                        <option value="draft">Draft</option>
                        <option value="proposed">Proposed</option>
                        <option value="approved" selected>Approved</option>
                        <option value="active">Active</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Informasi Kategori -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center mb-6">
                <div class="bg-purple-100 p-3 rounded-lg mr-3">
                    <i class="fas fa-layer-group text-purple-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Informasi Kategori Anggaran</h3>
                    <p class="text-sm text-gray-500">Detail kategori dan alokasi</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Kategori -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Nama Kategori <span class="text-red-500">*</span>
                    </label>
                    <select id="kategori" required onchange="updateIcon()"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        <option value="">Pilih Kategori</option>
                        <option value="operasional" data-icon="cog" data-color="blue" selected>Operasional</option>
                        <option value="marketing" data-icon="bullhorn" data-color="pink">Marketing</option>
                        <option value="gaji" data-icon="users" data-color="purple">Gaji & Tunjangan</option>
                        <option value="it" data-icon="laptop" data-color="indigo">IT & Teknologi</option>
                        <option value="utilitas" data-icon="lightbulb" data-color="green">Utilitas</option>
                        <option value="transport" data-icon="car" data-color="yellow">Transport & Logistik</option>
                        <option value="training" data-icon="graduation-cap" data-color="orange">Training & Development</option>
                        <option value="lainnya" data-icon="ellipsis-h" data-color="gray">Lainnya</option>
                    </select>
                </div>

                <!-- Departemen -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Departemen
                    </label>
                    <select id="departemen"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        <option value="">Semua Departemen</option>
                        <option value="operasional" selected>Operasional</option>
                        <option value="marketing">Marketing</option>
                        <option value="hrd">HRD</option>
                        <option value="it">IT</option>
                        <option value="finance">Finance</option>
                        <option value="produksi">Produksi</option>
                    </select>
                </div>

                <!-- Deskripsi Singkat -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Deskripsi Singkat <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="deskripsi_singkat" value="Biaya operasional harian dan administrasi" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                </div>
            </div>
        </div>

        <!-- Alokasi Anggaran -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center mb-6">
                <div class="bg-green-100 p-3 rounded-lg mr-3">
                    <i class="fas fa-wallet text-green-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Alokasi Anggaran</h3>
                    <p class="text-sm text-gray-500">Jumlah dan pembagian anggaran</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Total Anggaran -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Total Anggaran <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-3 text-gray-500 font-semibold">Rp</span>
                        <input type="number" id="total_anggaran" value="150000000" min="0" required onchange="hitungDistribusi()"
                            class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Total alokasi untuk kategori ini</p>
                </div>

                <!-- Persentase dari Total -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Persentase dari Total Budget
                    </label>
                    <div class="relative">
                        <input type="number" id="persentase" value="12.5" min="0" max="100" step="0.1" readonly
                            class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg">
                        <span class="absolute right-4 top-3 text-gray-500 font-semibold">%</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Otomatis dihitung dari total budget perusahaan</p>
                </div>

                <!-- Distribusi Bulanan -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Metode Distribusi
                    </label>
                    <div class="flex gap-4">
                        <label class="flex items-center">
                            <input type="radio" name="metode_distribusi" value="merata" checked onchange="hitungDistribusi()"
                                class="mr-2 text-teal-600 focus:ring-teal-500">
                            <span class="text-sm text-gray-700">Distribusi Merata</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="metode_distribusi" value="manual" onchange="hitungDistribusi()"
                                class="mr-2 text-teal-600 focus:ring-teal-500">
                            <span class="text-sm text-gray-700">Manual per Bulan</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Preview Distribusi -->
            <div class="mt-6 p-4 bg-gradient-to-br from-teal-50 to-green-50 rounded-lg border border-teal-200">
                <h4 class="text-sm font-semibold text-gray-700 mb-3">Preview Distribusi Bulanan</h4>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                    <div class="bg-white p-3 rounded-lg">
                        <p class="text-xs text-gray-600">Januari</p>
                        <p class="font-bold text-teal-600" id="dist-jan">Rp 12.5 Jt</p>
                    </div>
                    <div class="bg-white p-3 rounded-lg">
                        <p class="text-xs text-gray-600">Februari</p>
                        <p class="font-bold text-teal-600" id="dist-feb">Rp 12.5 Jt</p>
                    </div>
                    <div class="bg-white p-3 rounded-lg">
                        <p class="text-xs text-gray-600">Maret</p>
                        <p class="font-bold text-teal-600" id="dist-mar">Rp 12.5 Jt</p>
                    </div>
                    <div class="bg-white p-3 rounded-lg">
                        <p class="text-xs text-gray-600">April</p>
                        <p class="font-bold text-teal-600" id="dist-apr">Rp 12.5 Jt</p>
                    </div>
                    <div class="bg-white p-3 rounded-lg">
                        <p class="text-xs text-gray-600">Mei</p>
                        <p class="font-bold text-teal-600" id="dist-mei">Rp 12.5 Jt</p>
                    </div>
                    <div class="bg-white p-3 rounded-lg">
                        <p class="text-xs text-gray-600">Juni</p>
                        <p class="font-bold text-teal-600" id="dist-jun">Rp 12.5 Jt</p>
                    </div>
                    <div class="bg-white p-3 rounded-lg">
                        <p class="text-xs text-gray-600">Juli</p>
                        <p class="font-bold text-teal-600" id="dist-jul">Rp 12.5 Jt</p>
                    </div>
                    <div class="bg-white p-3 rounded-lg">
                        <p class="text-xs text-gray-600">Agustus</p>
                        <p class="font-bold text-teal-600" id="dist-agu">Rp 12.5 Jt</p>
                    </div>
                    <div class="bg-white p-3 rounded-lg">
                        <p class="text-xs text-gray-600">September</p>
                        <p class="font-bold text-teal-600" id="dist-sep">Rp 12.5 Jt</p>
                    </div>
                    <div class="bg-white p-3 rounded-lg">
                        <p class="text-xs text-gray-600">Oktober</p>
                        <p class="font-bold text-teal-600" id="dist-okt">Rp 12.5 Jt</p>
                    </div>
                    <div class="bg-white p-3 rounded-lg">
                        <p class="text-xs text-gray-600">November</p>
                        <p class="font-bold text-teal-600" id="dist-nov">Rp 12.5 Jt</p>
                    </div>
                    <div class="bg-white p-3 rounded-lg">
                        <p class="text-xs text-gray-600">Desember</p>
                        <p class="font-bold text-teal-600" id="dist-des">Rp 12.5 Jt</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sub Kategori (Optional) -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <div class="bg-orange-100 p-3 rounded-lg mr-3">
                        <i class="fas fa-sitemap text-orange-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Sub Kategori (Opsional)</h3>
                        <p class="text-sm text-gray-500">Breakdown anggaran ke sub kategori</p>
                    </div>
                </div>
                <button type="button" onclick="addSubKategori()" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-plus mr-2"></i>Tambah Sub Kategori
                </button>
            </div>

            <div id="subKategoriList" class="space-y-3">
                <!-- Existing sub kategori -->
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-folder text-teal-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">ATK & Supplies</p>
                            <p class="text-sm text-gray-500">Alat tulis kantor dan perlengkapan</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="text-right">
                            <p class="font-bold text-gray-900">Rp 25.0 Jt</p>
                        </div>
                        <button type="button" onclick="removeSubKategori(1)" class="text-red-600 hover:text-red-800">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-folder text-teal-600"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">Maintenance</p>
                            <p class="text-sm text-gray-500">Biaya pemeliharaan rutin</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="text-right">
                            <p class="font-bold text-gray-900">Rp 50.0 Jt</p>
                        </div>
                        <button type="button" onclick="removeSubKategori(2)" class="text-red-600 hover:text-red-800">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="mt-4 text-center text-gray-500 hidden" id="emptySubKategori">
                <i class="fas fa-folder-open text-4xl mb-2"></i>
                <p class="text-sm">Belum ada sub kategori. Klik "Tambah Sub Kategori" untuk menambahkan</p>
            </div>
        </div>

        <!-- Target & KPI -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center mb-6">
                <div class="bg-indigo-100 p-3 rounded-lg mr-3">
                    <i class="fas fa-bullseye text-indigo-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Target & Key Performance Indicator</h3>
                    <p class="text-sm text-gray-500">Indikator pencapaian anggaran</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Target Realisasi -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Target Realisasi (%)
                    </label>
                    <div class="relative">
                        <input type="number" id="target_realisasi" value="95" min="0" max="100" step="0.1"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        <span class="absolute right-4 top-3 text-gray-500 font-semibold">%</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Target penggunaan anggaran ideal</p>
                </div>

                <!-- Threshold Warning -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Warning Threshold (%)
                    </label>
                    <div class="relative">
                        <input type="number" id="warning_threshold" value="85" min="0" max="100" step="0.1"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        <span class="absolute right-4 top-3 text-gray-500 font-semibold">%</span>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Batas peringatan penggunaan anggaran</p>
                </div>
            </div>
        </div>

        <!-- Catatan & Justifikasi -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center mb-6">
                <div class="bg-yellow-100 p-3 rounded-lg mr-3">
                    <i class="fas fa-sticky-note text-yellow-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Catatan & Justifikasi</h3>
                    <p class="text-sm text-gray-500">Keterangan tambahan tentang anggaran</p>
                </div>
            </div>

            <div class="space-y-4">
                <!-- Justifikasi -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Justifikasi Anggaran
                    </label>
                    <textarea id="justifikasi" rows="3" placeholder="Jelaskan alasan dan tujuan pengajuan anggaran ini..."
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">Anggaran operasional diperlukan untuk mendukung kegiatan harian perusahaan termasuk administrasi, maintenance, dan kebutuhan rutin lainnya.</textarea>
                </div>

                <!-- Catatan Tambahan -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Catatan Tambahan
                    </label>
                    <textarea id="catatan" rows="3" placeholder="Tambahkan catatan atau keterangan lainnya..."
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">Pengalokasian anggaran ini sudah disesuaikan dengan kebutuhan operasional tahun sebelumnya dengan penyesuaian inflasi.</textarea>
                </div>
            </div>
        </div>

        <!-- Summary Card -->
        <div class="bg-gradient-to-br from-teal-600 to-teal-600 rounded-xl shadow-lg p-6 text-white">
            <h3 class="text-lg font-bold mb-4">Ringkasan Anggaran</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-teal bg-opacity-20 rounded-lg p-4">
                    <p class="text-teal-100 text-sm mb-1">Total Anggaran</p>
                    <p class="text-2xl font-bold" id="summary-total">Rp 150.0 Jt</p>
                </div>
                <div class="bg-teal bg-opacity-20 rounded-lg p-4">
                    <p class="text-teal-100 text-sm mb-1">Per Bulan (Rata-rata)</p>
                    <p class="text-2xl font-bold" id="summary-perbulan">Rp 12.5 Jt</p>
                </div>
                <div class="bg-teal bg-opacity-20 rounded-lg p-4">
                    <p class="text-teal-100 text-sm mb-1">Target Realisasi</p>
                    <p class="text-2xl font-bold" id="summary-target">95%</p>
                </div>
                <div class="bg-teal bg-opacity-20 rounded-lg p-4">
                    <p class="text-teal-100 text-sm mb-1">Status</p>
                    <p class="text-2xl font-bold" id="summary-status">Approved</p>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex justify-between items-center">
                <a href="{{ route('finance.anggaran.index') }}" class="text-gray-600 hover:text-gray-800 font-medium">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
                <div class="flex gap-3">
                    <button type="button" onclick="saveDraft()" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-semibold transition">
                        <i class="fas fa-save mr-2"></i>Simpan Draft
                    </button>
                    <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-8 py-3 rounded-lg font-semibold shadow-lg transition">
                        <i class="fas fa-check mr-2"></i>Update Anggaran
                    </button>
                </div>
            </div>
        </div>

    </form>

</div>

<!-- Modal Add Sub Kategori -->
<div id="modalSubKategori" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
        <div class="bg-gradient-to-r from-teal-600 to-teal-700 text-white p-6 rounded-t-xl">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold">Tambah Sub Kategori</h3>
                <button onclick="closeModalSubKategori()" class="text-white hover:text-gray-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>

        <div class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Sub Kategori</label>
                <input type="text" id="sub_nama" placeholder="Contoh: ATK & Supplies"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Alokasi Anggaran</label>
                <div class="relative">
                    <span class="absolute left-4 top-2 text-gray-500 font-semibold">Rp</span>
                    <input type="number" id="sub_anggaran" value="0" min="0"
                        class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
                <textarea id="sub_deskripsi" rows="2" placeholder="Deskripsi singkat sub kategori..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500"></textarea>
            </div>

            <div class="flex gap-3 pt-4">
                <button onclick="closeModalSubKategori()" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white py-2 rounded-lg transition">
                    Batal
                </button>
                <button onclick="saveSubKategori()" class="flex-1 bg-teal-600 hover:bg-teal-700 text-white py-2 rounded-lg transition">
                    <i class="fas fa-plus mr-2"></i>Tambah
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Initialize with existing data
let subKategoriList = [
    { id: 1, nama: 'ATK & Supplies', anggaran: 25000000, deskripsi: 'Alat tulis kantor dan perlengkapan' },
    { id: 2, nama: 'Maintenance', anggaran: 50000000, deskripsi: 'Biaya pemeliharaan rutin' }
];
let subKategoriCounter = 2;
const totalBudgetPerusahaan = 1200000000; // Rp 1.2 M (contoh)

// Format Rupiah
function formatRupiah(angka) {
    return 'Rp ' + parseInt(angka).toLocaleString('id-ID');
}

// Format Rupiah Short
function formatRupiahShort(angka) {
    if (angka >= 1000000000) {
        return 'Rp ' + (angka / 1000000000).toFixed(1) + ' M';
    } else if (angka >= 1000000) {
        return 'Rp ' + (angka / 1000000).toFixed(1) + ' Jt';
    }
    return formatRupiah(angka);
}

// Update Icon
function updateIcon() {
    const kategori = document.getElementById('kategori');
    // Icon will be updated in real implementation
}

// Hitung Distribusi
function hitungDistribusi() {
    const total = parseInt(document.getElementById('total_anggaran').value) || 0;
    const metode = document.querySelector('input[name="metode_distribusi"]:checked').value;
    
    let perBulan = 0;
    if (metode === 'merata') {
        perBulan = total / 12;
    }

    // Update preview distribusi
    const bulan = ['jan', 'feb', 'mar', 'apr', 'mei', 'jun', 'jul', 'agu', 'sep', 'okt', 'nov', 'des'];
    bulan.forEach(b => {
        document.getElementById(`dist-${b}`).textContent = formatRupiahShort(perBulan);
    });

    // Update persentase
    const persentase = (total / totalBudgetPerusahaan * 100).toFixed(1);
    document.getElementById('persentase').value = persentase;

    // Update summary
    updateSummary();
}

// Update Summary
function updateSummary() {
    const total = parseInt(document.getElementById('total_anggaran').value) || 0;
    const target = document.getElementById('target_realisasi').value;
    const status = document.getElementById('status').options[document.getElementById('status').selectedIndex].text;

    document.getElementById('summary-total').textContent = formatRupiahShort(total);
    document.getElementById('summary-perbulan').textContent = formatRupiahShort(total / 12);
    document.getElementById('summary-target').textContent = target + '%';
    document.getElementById('summary-status').textContent = status;
}

// Sub Kategori Functions
function addSubKategori() {
    document.getElementById('modalSubKategori').classList.remove('hidden');
}

function closeModalSubKategori() {
    document.getElementById('modalSubKategori').classList.add('hidden');
    document.getElementById('sub_nama').value = '';
    document.getElementById('sub_anggaran').value = '0';
    document.getElementById('sub_deskripsi').value = '';
}

function saveSubKategori() {
    const nama = document.getElementById('sub_nama').value;
    const anggaran = parseInt(document.getElementById('sub_anggaran').value) || 0;
    const deskripsi = document.getElementById('sub_deskripsi').value;

    if (!nama || anggaran <= 0) {
        alert('Mohon lengkapi nama dan anggaran sub kategori');
        return;
    }

    subKategoriCounter++;
    subKategoriList.push({
        id: subKategoriCounter,
        nama: nama,
        anggaran: anggaran,
        deskripsi: deskripsi
    });

    renderSubKategori();
    closeModalSubKategori();
}

function renderSubKategori() {
    const container = document.getElementById('subKategoriList');
    const emptyState = document.getElementById('emptySubKategori');

    if (subKategoriList.length === 0) {
        emptyState.classList.remove('hidden');
        container.innerHTML = '';
        return;
    }

    emptyState.classList.add('hidden');

    container.innerHTML = subKategoriList.map(sub => `
        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-folder text-teal-600"></i>
                </div>
                <div>
                    <p class="font-semibold text-gray-900">${sub.nama}</p>
                    <p class="text-sm text-gray-500">${sub.deskripsi || '-'}</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <div class="text-right">
                    <p class="font-bold text-gray-900">${formatRupiahShort(sub.anggaran)}</p>
                </div>
                <button type="button" onclick="removeSubKategori(${sub.id})" class="text-red-600 hover:text-red-800">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `).join('');
}

function removeSubKategori(id) {
    if (confirm('Hapus sub kategori ini?')) {
        subKategoriList = subKategoriList.filter(sub => sub.id !== id);
        renderSubKategori();
    }
}

// Form Submit
document.getElementById('formAnggaran').addEventListener('submit', function(e) {
    e.preventDefault();

    // Validasi
    const requiredFields = ['periode', 'tipe_periode', 'status', 'kategori', 'deskripsi_singkat', 'total_anggaran'];
    let isValid = true;

    requiredFields.forEach(field => {
        const input = document.getElementById(field);
        if (!input.value) {
            isValid = false;
            input.classList.add('border-red-500');
        } else {
            input.classList.remove('border-red-500');
        }
    });

    if (!isValid) {
        alert('Mohon lengkapi semua field yang wajib diisi');
        return;
    }

    const total = parseInt(document.getElementById('total_anggaran').value);
    if (total <= 0) {
        alert('Total anggaran harus lebih dari 0');
        return;
    }

    // Simulasi update
    if (confirm('Update data anggaran ini?')) {
        alert('Data anggaran berhasil diupdate!');
        window.location.href = '{{ route("finance.anggaran.index") }}';
    }
});

function saveDraft() {
    if (confirm('Simpan perubahan sebagai draft?')) {
        alert('Draft berhasil disimpan');
        window.location.href = '{{ route("finance.anggaran.index") }}';
    }
}

// Event Listeners
document.getElementById('total_anggaran').addEventListener('input', hitungDistribusi);
document.getElementById('target_realisasi').addEventListener('input', updateSummary);
document.getElementById('status').addEventListener('change', updateSummary);

// Close modal when clicking outside
document.getElementById('modalSubKategori').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModalSubKategori();
    }
});

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    hitungDistribusi();
    updateSummary();
});
</script>

@endsection