@extends('layouts.app')

@section('title', 'Pengaturan Sistem')
@section('page-title', 'Pengaturan Sistem')

@section('content')
<div class="space-y-6">

    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-gray-700 to-gray-900 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-2">
                    <i class="fas fa-cog mr-2"></i>Pengaturan Sistem
                </h2>
                <p class="text-gray-300">Kelola konfigurasi jam kerja, lokasi kantor, dan informasi perusahaan</p>
            </div>
            <div class="hidden md:block">
                <i class="fas fa-sliders-h text-6xl text-white/20"></i>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="border-b border-gray-200">
            <nav class="flex space-x-1 px-6" aria-label="Tabs">
                <button onclick="switchTab('jamKerja')" id="tab-jamKerja" 
                        class="tab-button active px-6 py-4 text-sm font-medium border-b-2">
                    <i class="fas fa-clock mr-2"></i>Jam Kerja
                </button>
                <button onclick="switchTab('lokasi')" id="tab-lokasi" 
                        class="tab-button px-6 py-4 text-sm font-medium border-b-2">
                    <i class="fas fa-map-marker-alt mr-2"></i>Lokasi Kantor
                </button>
                <button onclick="switchTab('general')" id="tab-general" 
                        class="tab-button px-6 py-4 text-sm font-medium border-b-2">
                    <i class="fas fa-building mr-2"></i>Info Perusahaan
                </button>
            </nav>
        </div>

        <!-- Tab Content: Jam Kerja -->
        <div id="content-jamKerja" class="tab-content p-6">
            <form action="{{ route('superadmin.pengaturan.jam-kerja') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="max-w-2xl">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Pengaturan Jam Kerja</h3>
                    
                    <div class="space-y-6">

                        <!-- Info Box -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-start">
                                <i class="fas fa-info-circle text-blue-600 mt-1 mr-3"></i>
                                <div>
                                    <p class="text-sm text-blue-800 font-semibold mb-1">Informasi Jam Kerja</p>
                                    <ul class="text-xs text-blue-700 space-y-1">
                                        <li>• Jam kerja akan digunakan sebagai acuan absensi karyawan</li>
                                        <li>• Toleransi keterlambatan akan ditambahkan ke jam masuk</li>
                                        <li>• Karyawan yang absen setelah toleransi dianggap terlambat</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Jam Masuk -->
                        <div>
                            <label for="jam_masuk" class="form-label">
                                <i class="fas fa-sign-in-alt mr-2"></i>Jam Masuk <span class="text-red-500">*</span>
                            </label>
                            <input type="time" name="jam_masuk" id="jam_masuk" 
                                   value="{{ old('jam_masuk', $jamMasuk) }}" 
                                   class="form-input @error('jam_masuk') border-red-500 @enderror" required>
                            @error('jam_masuk')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jam Keluar -->
                        <div>
                            <label for="jam_keluar" class="form-label">
                                <i class="fas fa-sign-out-alt mr-2"></i>Jam Keluar <span class="text-red-500">*</span>
                            </label>
                            <input type="time" name="jam_keluar" id="jam_keluar" 
                                   value="{{ old('jam_keluar', $jamKeluar) }}" 
                                   class="form-input @error('jam_keluar') border-red-500 @enderror" required>
                            @error('jam_keluar')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Toleransi Keterlambatan -->
                        <div>
                            <label for="toleransi_keterlambatan" class="form-label">
                                <i class="fas fa-hourglass-half mr-2"></i>Toleransi Keterlambatan (Menit) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="toleransi_keterlambatan" id="toleransi_keterlambatan" 
                                   value="{{ old('toleransi_keterlambatan', $toleransiKeterlambatan) }}" 
                                   class="form-input @error('toleransi_keterlambatan') border-red-500 @enderror" 
                                   min="0" max="60" required>
                            @error('toleransi_keterlambatan')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Batas waktu toleransi (0-60 menit)</p>
                        </div>

                        <!-- Preview -->
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <h4 class="text-sm font-bold text-gray-700 mb-2">Preview Jam Kerja</h4>
                            <div class="flex items-center space-x-4 text-sm text-gray-700">
                                <div class="flex items-center">
                                    <i class="fas fa-sign-in-alt text-green-600 mr-2"></i>
                                    <span>Masuk: <strong id="preview-masuk">{{ $jamMasuk }}</strong></span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-sign-out-alt text-red-600 mr-2"></i>
                                    <span>Keluar: <strong id="preview-keluar">{{ $jamKeluar }}</strong></span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-hourglass-half text-yellow-600 mr-2"></i>
                                    <span>Toleransi: <strong id="preview-toleransi">{{ $toleransiKeterlambatan }}</strong> menit</span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end mt-8 pt-6 border-t border-gray-200">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i>Simpan Pengaturan
                        </button>
                    </div>

                </div>
            </form>
        </div>

        <!-- Tab Content: Lokasi Kantor -->
        <div id="content-lokasi" class="tab-content hidden p-6">
            <form action="{{ route('superadmin.pengaturan.lokasi') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="max-w-4xl">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Pengaturan Lokasi Kantor</h3>
                    
                    <div class="space-y-6">

                        <!-- Info Box -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="flex items-start">
                                <i class="fas fa-info-circle text-blue-600 mt-1 mr-3"></i>
                                <div>
                                    <p class="text-sm text-blue-800 font-semibold mb-1">Informasi Lokasi</p>
                                    <ul class="text-xs text-blue-700 space-y-1">
                                        <li>• Lokasi kantor digunakan untuk validasi absensi berbasis GPS</li>
                                        <li>• Radius menentukan jarak maksimal karyawan boleh absen</li>
                                        <li>• Gunakan Google Maps untuk mendapatkan koordinat yang akurat</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Left Column -->
                            <div class="space-y-6">
                                
                                <!-- Nama Lokasi -->
                                <div>
                                    <label for="nama_lokasi" class="form-label">
                                        <i class="fas fa-tag mr-2"></i>Nama Lokasi <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="nama_lokasi" id="nama_lokasi" 
                                           value="{{ old('nama_lokasi', $lokasiKantor['nama']) }}" 
                                           class="form-input @error('nama_lokasi') border-red-500 @enderror" 
                                           placeholder="Contoh: Kantor Pusat" required>
                                    @error('nama_lokasi')
                                        <p class="form-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Alamat -->
                                <div>
                                    <label for="alamat" class="form-label">
                                        <i class="fas fa-map-marked-alt mr-2"></i>Alamat Lengkap <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="alamat" id="alamat" rows="3" 
                                              class="form-input @error('alamat') border-red-500 @enderror" 
                                              placeholder="Masukkan alamat lengkap kantor" required>{{ old('alamat', $lokasiKantor['alamat']) }}</textarea>
                                    @error('alamat')
                                        <p class="form-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Radius -->
                                <div>
                                    <label for="radius" class="form-label">
                                        <i class="fas fa-circle-notch mr-2"></i>Radius Absensi (Meter) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="radius" id="radius" 
                                           value="{{ old('radius', $lokasiKantor['radius']) }}" 
                                           class="form-input @error('radius') border-red-500 @enderror" 
                                           min="10" max="1000" required>
                                    @error('radius')
                                        <p class="form-error">{{ $message }}</p>
                                    @enderror
                                    <p class="text-xs text-gray-500 mt-1">Jarak maksimal untuk absensi (10-1000 meter)</p>
                                </div>

                            </div>

                            <!-- Right Column -->
                            <div class="space-y-6">
                                
                                <!-- Latitude -->
                                <div>
                                    <label for="latitude" class="form-label">
                                        <i class="fas fa-location-arrow mr-2"></i>Latitude <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="latitude" id="latitude" 
                                           value="{{ old('latitude', $lokasiKantor['latitude']) }}" 
                                           class="form-input @error('latitude') border-red-500 @enderror" 
                                           placeholder="Contoh: -6.200000" required>
                                    @error('latitude')
                                        <p class="form-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Longitude -->
                                <div>
                                    <label for="longitude" class="form-label">
                                        <i class="fas fa-location-arrow mr-2"></i>Longitude <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="longitude" id="longitude" 
                                           value="{{ old('longitude', $lokasiKantor['longitude']) }}" 
                                           class="form-input @error('longitude') border-red-500 @enderror" 
                                           placeholder="Contoh: 106.816666" required>
                                    @error('longitude')
                                        <p class="form-error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Get Location Button -->
                                <div>
                                    <button type="button" onclick="getMyLocation()" class="btn btn-secondary w-full">
                                        <i class="fas fa-crosshairs mr-2"></i>Gunakan Lokasi Saat Ini
                                    </button>
                                    <p class="text-xs text-gray-500 mt-2">
                                        <i class="fas fa-lightbulb mr-1"></i>
                                        Atau cari di <a href="https://www.google.com/maps" target="_blank" class="text-blue-600 hover:underline">Google Maps</a>
                                    </p>
                                </div>

                            </div>

                        </div>

                        <!-- Map Preview -->
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <h4 class="text-sm font-bold text-gray-700 mb-2">Preview Lokasi</h4>
                            <div class="flex items-center justify-center space-x-4 text-sm text-gray-700">
                                <div>
                                    <i class="fas fa-map-marker-alt text-red-600 mr-2"></i>
                                    <strong id="preview-nama">{{ $lokasiKantor['nama'] }}</strong>
                                </div>
                                <div>
                                    <i class="fas fa-circle-notch text-blue-600 mr-2"></i>
                                    Radius: <strong id="preview-radius">{{ $lokasiKantor['radius'] }}</strong>m
                                </div>
                                <a href="https://www.google.com/maps?q={{ $lokasiKantor['latitude'] }},{{ $lokasiKantor['longitude'] }}" 
                                   target="_blank" 
                                   id="preview-maps"
                                   class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-external-link-alt mr-1"></i>Lihat di Maps
                                </a>
                            </div>
                        </div>

                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end mt-8 pt-6 border-t border-gray-200">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i>Simpan Pengaturan
                        </button>
                    </div>

                </div>
            </form>
        </div>

        <!-- Tab Content: Info Perusahaan -->
        <div id="content-general" class="tab-content hidden p-6">
            <form action="{{ route('superadmin.pengaturan.general') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="max-w-2xl">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Perusahaan</h3>
                    
                    <div class="space-y-6">

                        <!-- Nama Perusahaan -->
                        <div>
                            <label for="nama_perusahaan" class="form-label">
                                <i class="fas fa-building mr-2"></i>Nama Perusahaan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_perusahaan" id="nama_perusahaan" 
                                   value="{{ old('nama_perusahaan', $namaPerusahaan) }}" 
                                   class="form-input @error('nama_perusahaan') border-red-500 @enderror" 
                                   placeholder="Contoh: PT. Portal Internal" required>
                            @error('nama_perusahaan')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email Perusahaan -->
                        <div>
                            <label for="email_perusahaan" class="form-label">
                                <i class="fas fa-envelope mr-2"></i>Email Perusahaan <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email_perusahaan" id="email_perusahaan" 
                                   value="{{ old('email_perusahaan', $emailPerusahaan) }}" 
                                   class="form-input @error('email_perusahaan') border-red-500 @enderror" 
                                   placeholder="info@perusahaan.com" required>
                            @error('email_perusahaan')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Telepon Perusahaan -->
                        <div>
                            <label for="telepon_perusahaan" class="form-label">
                                <i class="fas fa-phone mr-2"></i>Telepon Perusahaan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="telepon_perusahaan" id="telepon_perusahaan" 
                                   value="{{ old('telepon_perusahaan', $teleponPerusahaan) }}" 
                                   class="form-input @error('telepon_perusahaan') border-red-500 @enderror" 
                                   placeholder="021-12345678" required>
                            @error('telepon_perusahaan')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Preview -->
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <h4 class="text-sm font-bold text-gray-700 mb-3">Preview Informasi</h4>
                            <div class="space-y-2 text-sm text-gray-700">
                                <div class="flex items-center">
                                    <i class="fas fa-building w-5 mr-2 text-gray-500"></i>
                                    <strong id="preview-nama-perusahaan">{{ $namaPerusahaan }}</strong>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-envelope w-5 mr-2 text-gray-500"></i>
                                    <span id="preview-email-perusahaan">{{ $emailPerusahaan }}</span>
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-phone w-5 mr-2 text-gray-500"></i>
                                    <span id="preview-telepon-perusahaan">{{ $teleponPerusahaan }}</span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end mt-8 pt-6 border-t border-gray-200">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-2"></i>Simpan Pengaturan
                        </button>
                    </div>

                </div>
            </form>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    // Tab Switching
    function switchTab(tabName) {
        // Hide all contents
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });
        
        // Remove active class from all buttons
        document.querySelectorAll('.tab-button').forEach(button => {
            button.classList.remove('active', 'text-blue-600', 'border-blue-600');
            button.classList.add('text-gray-500', 'border-transparent');
        });
        
        // Show selected content
        document.getElementById('content-' + tabName).classList.remove('hidden');
        
        // Add active class to selected button
        const activeButton = document.getElementById('tab-' + tabName);
        activeButton.classList.add('active', 'text-blue-600', 'border-blue-600');
        activeButton.classList.remove('text-gray-500', 'border-transparent');
    }

    // Live Preview Jam Kerja
    document.getElementById('jam_masuk')?.addEventListener('change', function() {
        document.getElementById('preview-masuk').textContent = this.value;
    });

    document.getElementById('jam_keluar')?.addEventListener('change', function() {
        document.getElementById('preview-keluar').textContent = this.value;
    });

    document.getElementById('toleransi_keterlambatan')?.addEventListener('input', function() {
        document.getElementById('preview-toleransi').textContent = this.value;
    });

    // Live Preview Lokasi
    document.getElementById('nama_lokasi')?.addEventListener('input', function() {
        document.getElementById('preview-nama').textContent = this.value;
    });

    document.getElementById('radius')?.addEventListener('input', function() {
        document.getElementById('preview-radius').textContent = this.value;
    });

    document.getElementById('latitude')?.addEventListener('input', updateMapsLink);
    document.getElementById('longitude')?.addEventListener('input', updateMapsLink);

    function updateMapsLink() {
        const lat = document.getElementById('latitude').value;
        const lng = document.getElementById('longitude').value;
        if (lat && lng) {
            document.getElementById('preview-maps').href = `https://www.google.com/maps?q=${lat},${lng}`;
        }
    }

    // Get Current Location
    function getMyLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                document.getElementById('latitude').value = position.coords.latitude.toFixed(6);
                document.getElementById('longitude').value = position.coords.longitude.toFixed(6);
                updateMapsLink();
                alert('Lokasi berhasil diambil!');
            }, function(error) {
                alert('Gagal mendapatkan lokasi: ' + error.message);
            });
        } else {
            alert('Browser tidak mendukung geolocation');
        }
    }

    // Live Preview Info Perusahaan
    document.getElementById('nama_perusahaan')?.addEventListener('input', function() {
        document.getElementById('preview-nama-perusahaan').textContent = this.value;
    });

    document.getElementById('email_perusahaan')?.addEventListener('input', function() {
        document.getElementById('preview-email-perusahaan').textContent = this.value;
    });

    document.getElementById('telepon_perusahaan')?.addEventListener('input', function() {
        document.getElementById('preview-telepon-perusahaan').textContent = this.value;
    });

    // Initialize default tab
    switchTab('jamKerja');
</script>

<style>
    .tab-button {
        transition: all 0.3s ease;
    }
    
    .tab-button.active {
        color: #2563eb;
        border-color: #2563eb;
    }
    
    .tab-button:not(.active) {
        color: #6b7280;
        border-color: transparent;
    }
    
    .tab-button:hover:not(.active) {
        color: #374151;
        background-color: #f3f4f6;
    }
</style>
@endpush