@extends('layouts.app')

@section('title', 'Edit Surat')
@section('page-title', 'Edit Surat')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('karyawan.surat.index') }}" class="inline-flex items-center text-gray-600 hover:text-teal-600 transition font-medium">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Log Book Surat
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-teal-500 to-cyan-600 px-8 py-6">
            <h3 class="text-xl font-bold text-white flex items-center">
                <i class="fas fa-envelope-open-text mr-3"></i>
                Form Edit Surat
            </h3>
            <p class="text-sm text-white/90 mt-2">Perbarui data surat yang sudah ada</p>
        </div>

        <!-- Form -->
        <form action="{{ route('karyawan.surat.update', $surat->id) }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf
            @method('PUT')

            <!-- Grid Layout: Kiri Form, Kanan Info & Tombol -->
            <div class="grid grid-cols-1 lg:grid-cols-10 gap-8">
                
                <!-- KIRI: Form Input (6 kolom dari 10) -->
                <div class="lg:col-span-6 space-y-6 lg:pr-8">

                    <!-- Nomor Surat -->
                    <div>
                        <label for="nomor_surat" class="form-label text-sm font-semibold text-gray-700 mb-2 block">
                            <i class="fas fa-hashtag mr-2"></i>Nomor Surat <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="nomor_surat" id="nomor_surat" value="{{ old('nomor_surat', $surat->nomor_surat) }}" 
                               class="form-input @error('nomor_surat') border-red-500 @enderror" 
                               placeholder="Contoh: 001/SK/X/2024" required>
                        @error('nomor_surat')
                            <p class="form-error mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jenis Surat -->
                    <div>
                        <label for="jenis" class="form-label text-sm font-semibold text-gray-700 mb-2 block">
                            <i class="fas fa-tag mr-2"></i>Jenis Surat <span class="text-red-500">*</span>
                        </label>
                        <select name="jenis" id="jenis" class="form-input @error('jenis') border-red-500 @enderror" required>
                            <option value="">-- Pilih Jenis Surat --</option>
                            <option value="masuk" {{ old('jenis', $surat->jenis) == 'masuk' ? 'selected' : '' }}>Surat Masuk</option>
                            <option value="keluar" {{ old('jenis', $surat->jenis) == 'keluar' ? 'selected' : '' }}>Surat Keluar</option>
                        </select>
                        @error('jenis')
                            <p class="form-error mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Surat -->
                    <div>
                        <label for="tanggal" class="form-label text-sm font-semibold text-gray-700 mb-2 block">
                            <i class="fas fa-calendar mr-2"></i>Tanggal Surat <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', $surat->tanggal->format('Y-m-d')) }}" 
                               class="form-input @error('tanggal') border-red-500 @enderror" required>
                        @error('tanggal')
                            <p class="form-error mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Pengirim -->
                    <div>
                        <label for="pengirim" class="form-label text-sm font-semibold text-gray-700 mb-2 block">
                            <i class="fas fa-user-tie mr-2"></i>Pengirim <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="pengirim" id="pengirim" value="{{ old('pengirim', $surat->pengirim) }}" 
                               class="form-input @error('pengirim') border-red-500 @enderror" 
                               placeholder="Nama pengirim atau instansi" required>
                        @error('pengirim')
                            <p class="form-error mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Penerima -->
                    <div>
                        <label for="penerima" class="form-label text-sm font-semibold text-gray-700 mb-2 block">
                            <i class="fas fa-user-check mr-2"></i>Penerima <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="penerima" id="penerima" value="{{ old('penerima', $surat->penerima) }}" 
                               class="form-input @error('penerima') border-red-500 @enderror" 
                               placeholder="Nama penerima atau instansi" required>
                        @error('penerima')
                            <p class="form-error mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Perihal -->
                    <div>
                        <label for="perihal" class="form-label text-sm font-semibold text-gray-700 mb-2 block">
                            <i class="fas fa-file-alt mr-2"></i>Perihal <span class="text-red-500">*</span>
                        </label>
                        <textarea name="perihal" id="perihal" rows="4" 
                                  class="form-input @error('perihal') border-red-500 @enderror" 
                                  placeholder="Perihal atau isi ringkas surat" required>{{ old('perihal', $surat->perihal) }}</textarea>
                        @error('perihal')
                            <p class="form-error mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- File Saat Ini -->
                    @if($surat->file_path)
                    <div class="bg-teal-50 border-2 border-teal-200 rounded-xl p-4 mb-6">
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                            <div class="flex items-center space-x-3">
                                <div class="bg-teal-500 p-3 rounded-lg">
                                    <i class="fas fa-file-pdf text-white text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-teal-800">File Saat Ini:</p>
                                    <p class="text-xs text-teal-600 break-all">{{ basename($surat->file_path) }}</p>
                                </div>
                            </div>
                            <a href="{{ route('karyawan.surat.download', $surat->id) }}" 
                               class="w-full sm:w-auto px-4 py-2 bg-teal-600 text-white rounded-lg hover:bg-teal-700 text-sm text-center whitespace-nowrap transition shadow-md">
                                <i class="fas fa-download mr-2"></i>Download
                            </a>
                        </div>
                    </div>
                    @endif

                    <!-- Upload File Baru -->
                    <div>
                        <label class="form-label text-sm font-semibold text-gray-700 mb-2 block">
                            <i class="fas fa-paperclip mr-2"></i>Upload File Baru (Opsional)
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 hover:border-teal-400 hover:bg-gray-50 transition cursor-pointer" onclick="document.getElementById('file_path').click()">
                            <input type="file" name="file_path" id="file_path" 
                                   accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                   class="hidden"
                                   onchange="previewFileName(event)">
                            <div class="text-center">
                                <i class="fas fa-cloud-upload-alt text-5xl text-teal-400 mb-4"></i>
                                <p class="text-sm text-gray-600 font-medium mb-2">Klik untuk upload file baru</p>
                                <p class="text-xs text-gray-500 mb-1">Format: PDF, DOC, DOCX, JPG, PNG (Max: 5MB)</p>
                                <p class="text-xs text-gray-400 italic">File lama akan diganti jika Anda upload file baru</p>
                                <p id="fileName" class="text-sm text-teal-600 font-semibold mt-3 hidden"></p>
                            </div>
                        </div>
                        @error('file_path')
                            <p class="form-error mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <!-- KANAN: Info & Tombol Aksi (4 kolom dari 10) -->
                <div class="lg:col-span-4 lg:pl-4">

                    <!-- Info Card -->
                    <div class="bg-gradient-to-br from-teal-50 to-cyan-50 border-2 border-teal-200 rounded-xl p-6 mt-6 lg:mt-0">
                        <div class="flex items-center mb-4">
                            <div class="bg-teal-500 p-3 rounded-lg mr-3">
                                <i class="fas fa-info-circle text-white text-xl"></i>
                            </div>
                            <h3 class="font-bold text-gray-800">Informasi</h3>
                        </div>
                        <ul class="space-y-3 text-sm text-gray-700">
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-teal-600 mt-0.5 mr-3"></i>
                                <span>Nomor surat harus unik dan tidak boleh duplikat</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-teal-600 mt-0.5 mr-3"></i>
                                <span>Pastikan data yang diubah sudah sesuai</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-teal-600 mt-0.5 mr-3"></i>
                                <span>Upload file baru jika diperlukan</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-teal-600 mt-0.5 mr-3"></i>
                                <span>File lama akan otomatis terganti</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Riwayat Surat Card -->
                    <div class="bg-white border-2 border-gray-200 rounded-xl p-6 mt-6">
                        <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-history text-teal-600 mr-2"></i>
                            Detail Surat
                        </h3>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <span class="text-gray-600">Dibuat pada:</span>
                                <span class="font-semibold text-gray-800">{{ $surat->created_at->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <span class="text-gray-600">Terakhir diubah:</span>
                                <span class="font-semibold text-gray-800">{{ $surat->updated_at->format('d M Y') }}</span>
                            </div>
                            @if($surat->file_path)
                            <div class="flex justify-between items-center p-3 bg-teal-50 rounded-lg">
                                <span class="text-gray-600">Status File:</span>
                                <span class="font-semibold text-teal-600">
                                    <i class="fas fa-check-circle mr-1"></i>Tersedia
                                </span>
                            </div>
                            @else
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <span class="text-gray-600">Status File:</span>
                                <span class="font-semibold text-gray-400">
                                    <i class="fas fa-times-circle mr-1"></i>Tidak ada
                                </span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="space-y-4 pt-2 mt-6">
                        <button type="submit" class="w-full px-6 py-4 bg-gradient-to-r from-teal-500 to-cyan-600 hover:from-teal-600 hover:to-cyan-700 text-white font-semibold rounded-xl transition shadow-lg hover:shadow-xl">
                            <i class="fas fa-save mr-2"></i>Update Surat
                        </button>
                        <a href="{{ route('karyawan.surat.index') }}" class="block w-full px-6 py-4 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-xl transition text-center">
                            <i class="fas fa-times mr-2"></i>Batal
                        </a>
                    </div>

                </div>

            </div>

        </form>

    </div>

</div>
@endsection

@push('scripts')
<script>
    // Preview File Name
    function previewFileName(event) {
        const file = event.target.files[0];
        if (file) {
            // Validasi ukuran file (max 5MB)
            if (file.size > 5242880) {
                alert('Ukuran file terlalu besar! Maksimal 5MB');
                event.target.value = '';
                return;
            }

            const fileName = file.name;
            const fileNameElement = document.getElementById('fileName');
            fileNameElement.innerHTML = `<i class="fas fa-file-alt mr-2"></i>File baru terpilih: ${fileName}`;
            fileNameElement.classList.remove('hidden');
        }
    }
</script>
@endpush