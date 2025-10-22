@extends('layouts.app')

@section('title', 'Edit Surat')
@section('page-title', 'Edit Surat')

@section('content')
<div class="max-w-3xl mx-auto">

    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('superadmin.surat.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-800">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Log Book Surat
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-4">
            <h3 class="text-xl font-bold text-white">Form Edit Surat</h3>
        </div>

        <!-- Form -->
        <form action="{{ route('superadmin.surat.update', $surat->id) }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')

            <div class="space-y-6">

                <!-- Nomor Surat -->
                <div>
                    <label for="nomor_surat" class="form-label">
                        <i class="fas fa-hashtag mr-2"></i>Nomor Surat <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="nomor_surat" id="nomor_surat" value="{{ old('nomor_surat', $surat->nomor_surat) }}" 
                           class="form-input @error('nomor_surat') border-red-500 @enderror" 
                           placeholder="Contoh: 001/SK/X/2024" required>
                    @error('nomor_surat')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jenis Surat -->
                <div>
                    <label for="jenis" class="form-label">
                        <i class="fas fa-tag mr-2"></i>Jenis Surat <span class="text-red-500">*</span>
                    </label>
                    <select name="jenis" id="jenis" class="form-input @error('jenis') border-red-500 @enderror" required>
                        <option value="">-- Pilih Jenis Surat --</option>
                        <option value="masuk" {{ old('jenis', $surat->jenis) == 'masuk' ? 'selected' : '' }}>Surat Masuk</option>
                        <option value="keluar" {{ old('jenis', $surat->jenis) == 'keluar' ? 'selected' : '' }}>Surat Keluar</option>
                    </select>
                    @error('jenis')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tanggal Surat -->
                <div>
                    <label for="tanggal" class="form-label">
                        <i class="fas fa-calendar mr-2"></i>Tanggal Surat <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', $surat->tanggal->format('Y-m-d')) }}" 
                           class="form-input @error('tanggal') border-red-500 @enderror" required>
                    @error('tanggal')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Pengirim -->
                <div>
                    <label for="pengirim" class="form-label">
                        <i class="fas fa-user-tie mr-2"></i>Pengirim <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="pengirim" id="pengirim" value="{{ old('pengirim', $surat->pengirim) }}" 
                           class="form-input @error('pengirim') border-red-500 @enderror" 
                           placeholder="Nama pengirim atau instansi" required>
                    @error('pengirim')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Penerima -->
                <div>
                    <label for="penerima" class="form-label">
                        <i class="fas fa-user-check mr-2"></i>Penerima <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="penerima" id="penerima" value="{{ old('penerima', $surat->penerima) }}" 
                           class="form-input @error('penerima') border-red-500 @enderror" 
                           placeholder="Nama penerima atau instansi" required>
                    @error('penerima')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Perihal -->
                <div>
                    <label for="perihal" class="form-label">
                        <i class="fas fa-file-alt mr-2"></i>Perihal <span class="text-red-500">*</span>
                    </label>
                    <textarea name="perihal" id="perihal" rows="3" 
                              class="form-input @error('perihal') border-red-500 @enderror" 
                              placeholder="Perihal atau isi ringkas surat" required>{{ old('perihal', $surat->perihal) }}</textarea>
                    @error('perihal')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- File Saat Ini -->
                @if($surat->file_path)
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-file-pdf text-blue-600 text-2xl"></i>
                            <div>
                                <p class="text-sm font-semibold text-blue-800">File Saat Ini:</p>
                                <p class="text-xs text-blue-600">{{ basename($surat->file_path) }}</p>
                            </div>
                        </div>
                        <a href="{{ route('superadmin.surat.download', $surat->id) }}" 
                           class="px-3 py-1 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                            <i class="fas fa-download mr-1"></i>Download
                        </a>
                    </div>
                </div>
                @endif

                <!-- Upload File Baru -->
                <div>
                    <label class="form-label">
                        <i class="fas fa-paperclip mr-2"></i>Upload File Baru (Opsional)
                    </label>
                    <div class="file-upload">
                        <input type="file" name="file_path" id="file_path" 
                               accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                               onchange="previewFileName(event)">
                        <div class="text-center">
                            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-3"></i>
                            <p class="text-sm text-gray-600 mb-1">Klik untuk upload file baru</p>
                            <p class="text-xs text-gray-400">Format: PDF, DOC, DOCX, JPG, PNG (Max: 5MB)</p>
                            <p class="text-xs text-gray-500 mt-2">File lama akan diganti jika Anda upload file baru</p>
                            <p id="fileName" class="text-sm text-blue-600 font-semibold mt-2 hidden"></p>
                        </div>
                    </div>
                    @error('file_path')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('superadmin.surat.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-2"></i>Update Surat
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
        const fileName = event.target.files[0].name;
        const fileNameElement = document.getElementById('fileName');
        fileNameElement.textContent = `File baru terpilih: ${fileName}`;
        fileNameElement.classList.remove('hidden');
    }
</script>
@endpush