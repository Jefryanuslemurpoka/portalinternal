@extends('layouts.app')

@section('title', 'Tambah Surat')
@section('page-title', 'Tambah Surat')

@section('content')
<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

    <!-- Back Button -->
    <div class="mb-4 sm:mb-6">
        <a href="{{ route('superadmin.surat.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-800 text-sm sm:text-base">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Log Book Surat
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-lg sm:rounded-xl shadow-lg overflow-hidden">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-teal-500 to-cyan-600 px-4 sm:px-6 py-3 sm:py-4">
            <h3 class="text-lg sm:text-xl font-bold text-white">Form Tambah Surat</h3>
        </div>

        <!-- Form -->
        <form action="{{ route('superadmin.surat.store') }}" method="POST" enctype="multipart/form-data" class="p-4 sm:p-6">
            @csrf

            <div class="space-y-4 sm:space-y-6">

                <!-- Nomor Surat -->
                <div>
                    <label for="nomor_surat" class="form-label text-sm sm:text-base">
                        <i class="fas fa-hashtag mr-2"></i>Nomor Surat <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nomor_surat" id="nomor_surat" value="{{ old('nomor_surat') }}" 
                           class="form-input text-sm sm:text-base @error('nomor_surat') border-red-500 @enderror" 
                           placeholder="Contoh: 001/SK/X/2024" required>
                    @error('nomor_surat')
                        <p class="form-error text-xs sm:text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jenis Surat -->
                <div>
                    <label for="jenis" class="form-label text-sm sm:text-base">
                        <i class="fas fa-tag mr-2"></i>Jenis Surat <span class="text-red-500">*</span>
                    </label>
                    <select name="jenis" id="jenis" class="form-input text-sm sm:text-base @error('jenis') border-red-500 @enderror" required>
                        <option value="">-- Pilih Jenis Surat --</option>
                        <option value="masuk" {{ old('jenis') == 'masuk' ? 'selected' : '' }}>Surat Masuk</option>
                        <option value="keluar" {{ old('jenis') == 'keluar' ? 'selected' : '' }}>Surat Keluar</option>
                    </select>
                    @error('jenis')
                        <p class="form-error text-xs sm:text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Surat -->
                <div>
                    <label for="tanggal" class="form-label text-sm sm:text-base">
                        <i class="fas fa-calendar mr-2"></i>Tanggal Surat <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" 
                           class="form-input text-sm sm:text-base @error('tanggal') border-red-500 @enderror" required>
                    @error('tanggal')
                        <p class="form-error text-xs sm:text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Pengirim -->
                <div>
                    <label for="pengirim" class="form-label text-sm sm:text-base">
                        <i class="fas fa-user-tie mr-2"></i>Pengirim <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="pengirim" id="pengirim" value="{{ old('pengirim') }}" 
                           class="form-input text-sm sm:text-base @error('pengirim') border-red-500 @enderror" 
                           placeholder="Nama pengirim atau instansi" required>
                    @error('pengirim')
                        <p class="form-error text-xs sm:text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Penerima -->
                <div>
                    <label for="penerima" class="form-label text-sm sm:text-base">
                        <i class="fas fa-user-check mr-2"></i>Penerima <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="penerima" id="penerima" value="{{ old('penerima') }}" 
                           class="form-input text-sm sm:text-base @error('penerima') border-red-500 @enderror" 
                           placeholder="Nama penerima atau instansi" required>
                    @error('penerima')
                        <p class="form-error text-xs sm:text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Perihal -->
                <div>
                    <label for="perihal" class="form-label text-sm sm:text-base">
                        <i class="fas fa-file-alt mr-2"></i>Perihal <span class="text-red-500">*</span>
                    </label>
                    <textarea name="perihal" id="perihal" rows="3" 
                              class="form-input text-sm sm:text-base @error('perihal') border-red-500 @enderror" 
                              placeholder="Perihal atau isi ringkas surat" required>{{ old('perihal') }}</textarea>
                    @error('perihal')
                        <p class="form-error text-xs sm:text-sm">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Upload File -->
                <div>
                    <label class="form-label text-sm sm:text-base">
                        <i class="fas fa-paperclip mr-2"></i>Upload File Surat (Opsional)
                    </label>
                    <div class="file-upload">
                        <input type="file" name="file_path" id="file_path" 
                               accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                               onchange="previewFileName(event)">
                        <div class="text-center">
                            <i class="fas fa-cloud-upload-alt text-3xl sm:text-4xl text-teal-400 mb-3"></i>
                            <p class="text-xs sm:text-sm text-gray-600 mb-1">Klik untuk upload file</p>
                            <p class="text-xs text-gray-400">Format: PDF, DOC, DOCX, JPG, PNG (Max: 5MB)</p>
                            <p id="fileName" class="text-xs sm:text-sm text-teal-600 font-semibold mt-2 hidden"></p>
                        </div>
                    </div>
                    @error('file_path')
                        <p class="form-error text-xs sm:text-sm">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row justify-end gap-3 mt-6 sm:mt-8 pt-4 sm:pt-6 border-t border-gray-200">
                <a href="{{ route('superadmin.surat.index') }}" class="btn btn-secondary text-sm sm:text-base order-2 sm:order-1">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
                <button type="submit" class="bg-gradient-to-r from-teal-500 to-cyan-600 text-white px-4 sm:px-6 py-2 sm:py-2.5 rounded-lg hover:from-teal-600 hover:to-cyan-700 transition font-semibold shadow-md text-sm sm:text-base order-1 sm:order-2">
                    <i class="fas fa-save mr-2"></i>Simpan Surat
                </button>
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
            const fileName = file.name;
            const fileNameElement = document.getElementById('fileName');
            fileNameElement.textContent = `File terpilih: ${fileName}`;
            fileNameElement.classList.remove('hidden');
        }
    }
</script>
@endpush