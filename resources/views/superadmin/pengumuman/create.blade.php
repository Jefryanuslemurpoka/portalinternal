@extends('layouts.app')

@section('title', 'Tambah Pengumuman')
@section('page-title', 'Tambah Pengumuman')

@section('content')
<div class="max-w-4xl mx-auto">

    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('superadmin.pengumuman.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-800">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar Pengumuman
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-teal-600 to-cyan-600 px-6 py-4">
            <h3 class="text-xl font-bold text-white">
                <i class="fas fa-bullhorn mr-2"></i>Form Tambah Pengumuman
            </h3>
        </div>

        <!-- Form -->
        <form action="{{ route('superadmin.pengumuman.store') }}" method="POST" class="p-6">
            @csrf

            <div class="space-y-6">

                <!-- Info Box -->
                <div class="bg-teal-50 border border-teal-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-teal-600 mt-1 mr-3"></i>
                        <div>
                            <p class="text-sm text-teal-800 font-semibold mb-1">Tips Pengumuman Efektif</p>
                            <ul class="text-xs text-teal-700 space-y-1">
                                <li>• Gunakan judul yang jelas dan menarik perhatian</li>
                                <li>• Tulis konten yang singkat, padat, dan mudah dipahami</li>
                                <li>• Cantumkan informasi penting seperti tanggal, waktu, dan lokasi</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Judul -->
                <div>
                    <label for="judul" class="form-label">
                        <i class="fas fa-heading mr-2"></i>Judul Pengumuman <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="judul" id="judul" value="{{ old('judul') }}" 
                           class="form-input @error('judul') border-red-500 @enderror" 
                           placeholder="Contoh: Rapat Koordinasi Bulanan, Libur Nasional, Update Sistem"
                           required>
                    @error('judul')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">
                        <span id="judulCount">0</span>/255 karakter
                    </p>
                </div>

                <!-- Tanggal -->
                <div>
                    <label for="tanggal" class="form-label">
                        <i class="fas fa-calendar mr-2"></i>Tanggal Pengumuman <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" 
                           class="form-input @error('tanggal') border-red-500 @enderror" required>
                    @error('tanggal')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Konten -->
                <div>
                    <label for="konten" class="form-label">
                        <i class="fas fa-align-left mr-2"></i>Konten Pengumuman <span class="text-red-500">*</span>
                    </label>
                    <textarea name="konten" id="konten" rows="10" 
                              class="form-input @error('konten') border-red-500 @enderror" 
                              placeholder="Tulis isi pengumuman dengan detail..."
                              required>{{ old('konten') }}</textarea>
                    @error('konten')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-gray-500 mt-1">
                        <span id="kontenCount">0</span> karakter
                    </p>
                </div>

                <!-- Preview Box -->
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                    <h4 class="text-sm font-bold text-gray-700 mb-3">
                        <i class="fas fa-eye mr-2"></i>Preview Pengumuman
                    </h4>
                    <div class="bg-white rounded-lg p-4 border border-gray-200">
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-teal-500 to-cyan-500 rounded-full flex items-center justify-center text-white">
                                <i class="fas fa-bullhorn"></i>
                            </div>
                            <div>
                                <h5 id="previewJudul" class="font-bold text-gray-900">
                                    (Judul akan muncul disini)
                                </h5>
                                <p id="previewTanggal" class="text-xs text-gray-500">
                                    <i class="fas fa-calendar mr-1"></i>
                                    {{ date('d M Y') }}
                                </p>
                            </div>
                        </div>
                        <p id="previewKonten" class="text-sm text-gray-700">
                            (Konten akan muncul disini)
                        </p>
                    </div>
                </div>

            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('superadmin.pengumuman.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane mr-2"></i>Publikasikan Pengumuman
                </button>
            </div>

        </form>

    </div>

</div>
@endsection

@push('scripts')
<script>
    // Character Counter & Live Preview
    const judulInput = document.getElementById('judul');
    const kontenInput = document.getElementById('konten');
    const tanggalInput = document.getElementById('tanggal');
    const judulCount = document.getElementById('judulCount');
    const kontenCount = document.getElementById('kontenCount');
    const previewJudul = document.getElementById('previewJudul');
    const previewKonten = document.getElementById('previewKonten');
    const previewTanggal = document.getElementById('previewTanggal');

    judulInput.addEventListener('input', function() {
        judulCount.textContent = this.value.length;
        previewJudul.textContent = this.value || '(Judul akan muncul disini)';
    });

    kontenInput.addEventListener('input', function() {
        kontenCount.textContent = this.value.length;
        previewKonten.textContent = this.value || '(Konten akan muncul disini)';
    });

    tanggalInput.addEventListener('change', function() {
        const date = new Date(this.value);
        const options = { day: 'numeric', month: 'long', year: 'numeric' };
        previewTanggal.innerHTML = '<i class="fas fa-calendar mr-1"></i>' + date.toLocaleDateString('id-ID', options);
    });
</script>
@endpush