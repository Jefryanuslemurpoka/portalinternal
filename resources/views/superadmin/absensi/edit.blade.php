@extends('layouts.app')

@section('title', 'Edit Absensi')
@section('page-title', 'Edit Absensi')

@section('content')
<div class="max-w-4xl mx-auto space-y-4 sm:space-y-6">

    <!-- Breadcrumb -->
    <nav class="flex text-xs sm:text-sm text-gray-600">
        <a href="{{ route('superadmin.dashboard') }}" class="hover:text-teal-600 transition">Dashboard</a>
        <span class="mx-2">/</span>
        <a href="{{ route('superadmin.absensi.index') }}" class="hover:text-teal-600 transition">Manajemen Absensi</a>
        <span class="mx-2">/</span>
        <span class="text-gray-900 font-semibold">Edit Absensi</span>
    </nav>

    <!-- Form Card -->
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg overflow-hidden">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-teal-500 to-cyan-600 px-4 sm:px-6 py-4 sm:py-5">
            <h2 class="text-lg sm:text-xl font-bold text-white flex items-center">
                <i class="fas fa-edit mr-2 sm:mr-3"></i>
                Edit Data Absensi
            </h2>
            <p class="text-xs sm:text-sm text-white/80 mt-1">Edit data absensi {{ $absensi->user->name }}</p>
        </div>

        <!-- Alert Error -->
        @if($errors->any())
        <div class="m-4 sm:m-6 p-3 sm:p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
            <div class="flex items-start">
                <i class="fas fa-exclamation-circle text-red-500 mt-0.5 mr-3"></i>
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-red-800 mb-1">Terjadi Kesalahan:</h3>
                    <ul class="text-xs sm:text-sm text-red-700 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="m-4 sm:m-6 p-3 sm:p-4 bg-red-50 border-l-4 border-red-500 rounded-lg">
            <div class="flex items-start">
                <i class="fas fa-exclamation-circle text-red-500 mt-0.5 mr-3"></i>
                <p class="text-xs sm:text-sm text-red-700">{{ session('error') }}</p>
            </div>
        </div>
        @endif

        <!-- Form -->
        <form action="{{ route('superadmin.absensi.update', $absensi->id) }}" method="POST" enctype="multipart/form-data" class="p-4 sm:p-6 space-y-4 sm:space-y-6">
            @csrf
            @method('PUT')

            <!-- Pilih Karyawan -->
            <div>
                <label class="form-label text-sm sm:text-base">
                    Karyawan <span class="text-red-500">*</span>
                </label>
                <select name="user_id" id="user_id" class="form-input text-sm sm:text-base" required>
                    <option value="">-- Pilih Karyawan --</option>
                    @foreach($karyawan as $k)
                        <option value="{{ $k->id }}" {{ (old('user_id', $absensi->user_id) == $k->id) ? 'selected' : '' }}>
                            {{ $k->name }} - {{ $k->divisi }}
                        </option>
                    @endforeach
                </select>
                <p class="text-xs text-gray-500 mt-1">Pilih karyawan yang akan diabsenkan</p>
            </div>

            <!-- Tanggal -->
            <div>
                <label class="form-label text-sm sm:text-base">
                    Tanggal <span class="text-red-500">*</span>
                </label>
                <input type="date" name="tanggal" id="tanggal" 
                       value="{{ old('tanggal', $absensi->tanggal->format('Y-m-d')) }}" 
                       class="form-input text-sm sm:text-base" 
                       max="{{ date('Y-m-d') }}"
                       required>
                <p class="text-xs text-gray-500 mt-1">Pilih tanggal absensi (maksimal hari ini)</p>
            </div>

            <!-- Jam Masuk & Keluar -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="form-label text-sm sm:text-base">
                        Jam Masuk
                    </label>
                    <input type="time" name="jam_masuk" id="jam_masuk" 
                           value="{{ old('jam_masuk', $absensi->jam_masuk ? substr($absensi->jam_masuk, 0, 5) : '') }}" 
                           class="form-input text-sm sm:text-base">
                    <p class="text-xs text-gray-500 mt-1">Opsional, kosongkan jika tidak ada</p>
                </div>

                <div>
                    <label class="form-label text-sm sm:text-base">
                        Jam Keluar
                    </label>
                    <input type="time" name="jam_keluar" id="jam_keluar" 
                           value="{{ old('jam_keluar', $absensi->jam_keluar ? substr($absensi->jam_keluar, 0, 5) : '') }}" 
                           class="form-input text-sm sm:text-base">
                    <p class="text-xs text-gray-500 mt-1">Opsional, harus lebih besar dari jam masuk</p>
                </div>
            </div>

            <!-- Lokasi -->
            <div>
                <label class="form-label text-sm sm:text-base">
                    Lokasi (Koordinat)
                </label>
                <input type="text" name="lokasi" id="lokasi" 
                       value="{{ old('lokasi', $absensi->lokasi) }}" 
                       placeholder="Contoh: -6.2088, 106.8456"
                       class="form-input text-sm sm:text-base">
                <p class="text-xs text-gray-500 mt-1">Format: latitude, longitude (opsional)</p>
            </div>

            <!-- Upload Foto -->
            <div>
                <label class="form-label text-sm sm:text-base">
                    Foto Absensi
                </label>
                
                @if($absensi->foto)
                <div class="mb-3">
                    <p class="text-xs sm:text-sm font-medium text-gray-700 mb-2">Foto Saat Ini:</p>
                    <div class="relative inline-block">
                        <img src="{{ asset('storage/' . $absensi->foto) }}" alt="Foto Absensi" class="max-w-full sm:max-w-xs h-auto rounded-lg border-2 border-gray-200">
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Upload foto baru untuk mengganti foto ini</p>
                </div>
                @endif

                <div class="mt-2">
                    <input type="file" name="foto" id="foto" 
                           accept="image/jpeg,image/png,image/jpg"
                           class="hidden" 
                           onchange="previewImage(event)">
                    <label for="foto" class="inline-flex items-center px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg cursor-pointer transition text-sm">
                        <i class="fas fa-camera mr-2"></i>
                        <span>{{ $absensi->foto ? 'Ganti Foto' : 'Pilih Foto' }}</span>
                    </label>
                    <p class="text-xs text-gray-500 mt-2">Format: JPG, JPEG, PNG. Maksimal 2MB</p>
                </div>

                <!-- Preview Foto Baru -->
                <div id="preview-container" class="hidden mt-4">
                    <p class="text-xs sm:text-sm font-medium text-gray-700 mb-2">Preview Foto Baru:</p>
                    <div class="relative inline-block">
                        <img id="preview-image" src="" alt="Preview" class="max-w-full sm:max-w-xs h-auto rounded-lg border-2 border-gray-200">
                        <button type="button" onclick="removeImage()" class="absolute -top-2 -right-2 w-7 h-7 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center transition">
                            <i class="fas fa-times text-sm"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row items-center gap-3 pt-4 border-t border-gray-200">
                <a href="{{ route('superadmin.absensi.index') }}" class="w-full sm:w-auto px-5 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition text-center text-sm">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <button type="submit" class="w-full sm:w-auto px-5 py-2.5 bg-gradient-to-r from-teal-500 to-cyan-600 hover:from-teal-600 hover:to-cyan-700 text-white font-semibold rounded-lg transition shadow-md text-sm">
                    <i class="fas fa-save mr-2"></i>Update Absensi
                </button>
            </div>

        </form>

    </div>

</div>
@endsection

@push('scripts')
<script>
    // Preview image
    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            // Validasi ukuran file (max 2MB)
            if (file.size > 2048000) {
                alert('Ukuran file terlalu besar! Maksimal 2MB');
                event.target.value = '';
                return;
            }

            // Validasi tipe file
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!allowedTypes.includes(file.type)) {
                alert('Format file tidak valid! Gunakan JPG, JPEG, atau PNG');
                event.target.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-image').src = e.target.result;
                document.getElementById('preview-container').classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }
    }

    // Remove image
    function removeImage() {
        document.getElementById('foto').value = '';
        document.getElementById('preview-container').classList.add('hidden');
        document.getElementById('preview-image').src = '';
    }

    // Validasi jam keluar lebih besar dari jam masuk
    document.getElementById('jam_keluar').addEventListener('change', function() {
        const jamMasuk = document.getElementById('jam_masuk').value;
        const jamKeluar = this.value;

        if (jamMasuk && jamKeluar) {
            if (jamKeluar <= jamMasuk) {
                alert('Jam keluar harus lebih besar dari jam masuk!');
                this.value = '';
            }
        }
    });
</script>
@endpush