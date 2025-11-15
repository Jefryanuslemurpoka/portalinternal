@extends('layouts.app')

@section('title', 'Tambah Absensi')
@section('page-title', 'Tambah Absensi')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Breadcrumb -->
    <nav class="flex text-sm text-gray-600">
        <a href="{{ route('superadmin.dashboard') }}" class="hover:text-teal-600 transition">Dashboard</a>
        <span class="mx-3">/</span>
        <a href="{{ route('superadmin.absensi.index') }}" class="hover:text-teal-600 transition">Manajemen Absensi</a>
        <span class="mx-3">/</span>
        <span class="text-gray-900 font-semibold">Tambah Absensi</span>
    </nav>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-teal-500 to-cyan-600 px-8 py-6">
            <h2 class="text-xl font-bold text-white flex items-center">
                <i class="fas fa-plus-circle mr-3"></i>
                Tambah Data Absensi
            </h2>
            <p class="text-sm text-white/90 mt-2">Isi form di bawah untuk menambah data absensi karyawan</p>
        </div>

        <!-- Alert Error -->
        @if($errors->any())
        <div class="m-8 p-5 bg-red-50 border-l-4 border-red-500 rounded-xl">
            <div class="flex items-start">
                <i class="fas fa-exclamation-circle text-red-500 text-xl mt-0.5 mr-4"></i>
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-red-800 mb-2">Terjadi Kesalahan:</h3>
                    <ul class="text-sm text-red-700 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        @endif

        @if(session('error'))
        <div class="m-8 p-5 bg-red-50 border-l-4 border-red-500 rounded-xl">
            <div class="flex items-start">
                <i class="fas fa-exclamation-circle text-red-500 text-xl mt-0.5 mr-4"></i>
                <p class="text-sm text-red-700">{{ session('error') }}</p>
            </div>
        </div>
        @endif

        <!-- Form -->
        <form action="{{ route('superadmin.absensi.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf

            <!-- Grid Layout: Kiri Form, Kanan Info & Tombol -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- KIRI: Form Input (2 kolom) -->
                <div class="lg:col-span-2 space-y-6">

                    <!-- Baris 1: Karyawan & Tanggal -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Pilih Karyawan -->
                        <div>
                            <label class="form-label text-sm font-semibold text-gray-700 mb-2 block">
                                Karyawan <span class="text-red-500">*</span>
                            </label>
                            <select name="user_id" id="user_id" class="form-input" required>
                                <option value="">-- Pilih Karyawan --</option>
                                @foreach($karyawan as $k)
                                    <option value="{{ $k->id }}" {{ old('user_id') == $k->id ? 'selected' : '' }}>
                                        {{ $k->name }} - {{ $k->divisi }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-2">Pilih karyawan yang akan diabsenkan</p>
                        </div>

                        <!-- Tanggal -->
                        <div>
                            <label class="form-label text-sm font-semibold text-gray-700 mb-2 block">
                                Tanggal <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal" id="tanggal" 
                                   value="{{ old('tanggal', date('Y-m-d')) }}" 
                                   class="form-input" 
                                   max="{{ date('Y-m-d') }}"
                                   required>
                            <p class="text-xs text-gray-500 mt-2">Pilih tanggal absensi (maksimal hari ini)</p>
                        </div>
                    </div>

                    <!-- Baris 3: Jam Masuk & Keluar -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="form-label text-sm font-semibold text-gray-700 mb-2 block">
                                Jam Masuk
                            </label>
                            <input type="time" name="jam_masuk" id="jam_masuk" 
                                   value="{{ old('jam_masuk') }}" 
                                   class="form-input">
                            <p class="text-xs text-gray-500 mt-2">Opsional, kosongkan jika tidak ada</p>
                        </div>

                        <div>
                            <label class="form-label text-sm font-semibold text-gray-700 mb-2 block">
                                Jam Keluar
                            </label>
                            <input type="time" name="jam_keluar" id="jam_keluar" 
                                   value="{{ old('jam_keluar') }}" 
                                   class="form-input">
                            <p class="text-xs text-gray-500 mt-2">Opsional, harus lebih besar dari jam masuk</p>
                        </div>
                    </div>

                    <!-- Baris 4: Keterangan -->
                    <div>
                        <label class="form-label text-sm font-semibold text-gray-700 mb-2 block">
                            Keterangan
                        </label>
                        <textarea name="keterangan" id="keterangan" rows="3" 
                                  class="form-input" 
                                  placeholder="Tambahkan catatan atau keterangan (opsional)">{{ old('keterangan') }}</textarea>
                        <p class="text-xs text-gray-500 mt-2">Opsional, berikan keterangan jika diperlukan (misal: alasan izin/sakit)</p>
                    </div>

                    <!-- Baris 5: Lokasi & Upload Foto -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Lokasi (Koordinat) -->
                        <div>
                            <label class="form-label text-sm font-semibold text-gray-700 mb-2 block">
                                Lokasi (Koordinat)
                            </label>
                            <div class="flex gap-3">
                                <input type="text" name="lokasi" id="lokasi" 
                                       value="{{ old('lokasi') }}" 
                                       placeholder="Contoh: -6.2088, 106.8456"
                                       class="form-input flex-1">
                                <button type="button" onclick="getLocation()" class="px-4 py-2 bg-teal-500 hover:bg-teal-600 text-white rounded-lg transition font-semibold whitespace-nowrap">
                                    <i class="fas fa-map-marker-alt"></i>
                                </button>
                            </div>
                            <p class="text-xs text-gray-500 mt-2">Format: latitude, longitude (opsional)</p>
                        </div>

                        <!-- Upload Foto -->
                        <div>
                            <label class="form-label text-sm font-semibold text-gray-700 mb-2 block">
                                Foto Absensi
                            </label>
                            <div>
                                <input type="file" name="foto" id="foto" 
                                       accept="image/jpeg,image/png,image/jpg"
                                       class="hidden" 
                                       onchange="previewImage(event)">
                                <label for="foto" class="inline-flex items-center px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-lg cursor-pointer transition">
                                    <i class="fas fa-camera mr-3"></i>
                                    <span>Pilih Foto</span>
                                </label>
                                <p class="text-xs text-gray-500 mt-2">JPG, JPEG, PNG. Max 2MB</p>
                            </div>
                        </div>
                    </div>

                    <!-- Preview Foto -->
                    <div id="preview-container" class="hidden">
                        <p class="text-sm font-medium text-gray-700 mb-3">Preview Foto:</p>
                        <div class="relative inline-block">
                            <img id="preview-image" src="" alt="Preview" class="max-w-xs h-auto rounded-xl border-2 border-gray-200 shadow-sm">
                            <button type="button" onclick="removeImage()" class="absolute -top-3 -right-3 w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center transition shadow-lg">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>

                </div>

                <!-- KANAN: Info & Tombol Aksi (1 kolom) -->
                <div class="lg:col-span-1 space-y-6">
                    
                    <!-- Info Card -->
                    <div class="bg-gradient-to-br from-teal-50 to-cyan-50 border-2 border-teal-200 rounded-xl p-6">
                        <div class="flex items-center mb-4">
                            <div class="bg-teal-500 p-3 rounded-lg mr-3">
                                <i class="fas fa-info-circle text-white text-xl"></i>
                            </div>
                            <h3 class="font-bold text-gray-800">Informasi</h3>
                        </div>
                        <ul class="space-y-3 text-sm text-gray-700">
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-teal-600 mt-0.5 mr-3"></i>
                                <span>Pastikan data karyawan sudah benar</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-teal-600 mt-0.5 mr-3"></i>
                                <span>Tanggal maksimal hari ini</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-teal-600 mt-0.5 mr-3"></i>
                                <span>Pilih status kehadiran yang sesuai</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-teal-600 mt-0.5 mr-3"></i>
                                <span>Jam keluar harus lebih besar dari jam masuk</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-teal-600 mt-0.5 mr-3"></i>
                                <span>Foto maksimal 2MB (JPG, PNG)</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Quick Stats -->
                    <div class="bg-white border-2 border-gray-200 rounded-xl p-6">
                        <h3 class="font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-chart-line text-teal-600 mr-2"></i>
                            Statistik Hari Ini
                        </h3>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                                <span class="text-sm text-gray-700">Total Hadir</span>
                                <span class="font-bold text-green-600 text-lg">{{ \App\Models\Absensi::whereDate('tanggal', date('Y-m-d'))->whereNotNull('jam_masuk')->count() }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-orange-50 rounded-lg">
                                <span class="text-sm text-gray-700">Belum Absen</span>
                                <span class="font-bold text-orange-600 text-lg">{{ \App\Models\User::where('role', 'karyawan')->where('status', 'aktif')->count() - \App\Models\Absensi::whereDate('tanggal', date('Y-m-d'))->whereNotNull('jam_masuk')->count() }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="space-y-4 pt-2">
                        <button type="submit" class="w-full px-6 py-4 bg-gradient-to-r from-teal-500 to-cyan-600 hover:from-teal-600 hover:to-cyan-700 text-white font-semibold rounded-xl transition shadow-lg hover:shadow-xl">
                            <i class="fas fa-save mr-2"></i>Simpan Absensi
                        </button>
                        <a href="{{ route('superadmin.absensi.index') }}" class="block w-full px-6 py-4 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-xl transition text-center">
                            <i class="fas fa-arrow-left mr-2"></i>Kembali
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

    // Get location
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude.toFixed(6);
                const lng = position.coords.longitude.toFixed(6);
                document.getElementById('lokasi').value = lat + ', ' + lng;
            }, function(error) {
                alert('Tidak dapat mendeteksi lokasi: ' + error.message);
            });
        } else {
            alert('Browser tidak mendukung geolocation');
        }
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