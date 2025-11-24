@extends('layouts.app')

@section('title', 'Pengaturan Sistem')
@section('page-title', 'Pengaturan Sistem')

@section('content')
<div class="space-y-4 sm:space-y-6">

    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-teal-600 to-cyan-600 rounded-lg sm:rounded-xl shadow-lg p-4 sm:p-6 text-white">
        <div class="flex items-center justify-between gap-4">
            <div class="flex-1 min-w-0">
                <h2 class="text-lg sm:text-xl md:text-2xl font-bold mb-1 sm:mb-2">
                    <i class="fas fa-cog mr-2"></i>Pengaturan Sistem
                </h2>
                <p class="text-xs sm:text-sm md:text-base text-teal-50">Kelola konfigurasi jam kerja, lokasi kantor, dan informasi perusahaan</p>
            </div>
            <div class="hidden lg:block flex-shrink-0">
                <i class="fas fa-sliders-h text-4xl xl:text-6xl text-white/20"></i>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="bg-white rounded-lg sm:rounded-xl shadow-lg overflow-hidden">
        <div class="border-b border-gray-200 overflow-x-auto scrollbar-hide">
            <nav class="flex space-x-0 px-2 sm:px-4 md:px-6" aria-label="Tabs">
                <button onclick="switchTab('jamKerja')" id="tab-jamKerja" 
                        class="tab-button active flex-1 sm:flex-none px-3 sm:px-4 md:px-6 py-3 sm:py-4 text-xs sm:text-sm font-medium border-b-2 whitespace-nowrap">
                    <i class="fas fa-clock mr-1 sm:mr-2"></i><span>Jam Kerja</span>
                </button>
                <button onclick="switchTab('lokasi')" id="tab-lokasi" 
                        class="tab-button flex-1 sm:flex-none px-3 sm:px-4 md:px-6 py-3 sm:py-4 text-xs sm:text-sm font-medium border-b-2 whitespace-nowrap">
                    <i class="fas fa-map-marker-alt mr-1 sm:mr-2"></i><span>Lokasi</span>
                </button>
                <button onclick="switchTab('general')" id="tab-general" 
                        class="tab-button flex-1 sm:flex-none px-3 sm:px-4 md:px-6 py-3 sm:py-4 text-xs sm:text-sm font-medium border-b-2 whitespace-nowrap">
                    <i class="fas fa-building mr-1 sm:mr-2"></i><span>Info Perusahaan</span>
                </button>
            </nav>
        </div>

        <!-- Tab Content: Jam Kerja -->
        <div id="content-jamKerja" class="tab-content p-4 sm:p-6 lg:p-8">
            <form action="{{ route('superadmin.pengaturan.jam-kerja') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="max-w-3xl mx-auto">
                    <h3 class="text-base sm:text-lg md:text-xl font-bold text-gray-900 mb-4 sm:mb-6">Pengaturan Jam Kerja</h3>
                    
                    <div class="space-y-4 sm:space-y-6">

                        <!-- Info Box -->
                        <div class="bg-teal-50 border border-teal-200 rounded-lg p-3 sm:p-4">
                            <div class="flex items-start gap-2 sm:gap-3">
                                <i class="fas fa-info-circle text-teal-600 mt-0.5 sm:mt-1 flex-shrink-0 text-sm sm:text-base"></i>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs sm:text-sm text-teal-800 font-semibold mb-1">Informasi Jam Kerja</p>
                                    <ul class="text-xs text-teal-700 space-y-0.5 sm:space-y-1">
                                        <li>• Jam kerja akan digunakan sebagai acuan absensi karyawan</li>
                                        <li>• Toleransi keterlambatan akan ditambahkan ke jam masuk</li>
                                        <li>• Karyawan yang absen setelah toleransi dianggap terlambat</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Jam Masuk -->
                        <div>
                            <label for="jam_masuk" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                                <i class="fas fa-sign-in-alt mr-1 sm:mr-2 text-teal-600"></i>Jam Masuk <span class="text-red-500">*</span>
                            </label>
                            <input type="time" name="jam_masuk" id="jam_masuk" 
                                   value="{{ old('jam_masuk', $jamMasuk) }}" 
                                   class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 @error('jam_masuk') border-red-500 @enderror" required>
                            @error('jam_masuk')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jam Keluar -->
                        <div>
                            <label for="jam_keluar" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                                <i class="fas fa-sign-out-alt mr-1 sm:mr-2 text-teal-600"></i>Jam Keluar <span class="text-red-500">*</span>
                            </label>
                            <input type="time" name="jam_keluar" id="jam_keluar" 
                                   value="{{ old('jam_keluar', $jamKeluar) }}" 
                                   class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 @error('jam_keluar') border-red-500 @enderror" required>
                            @error('jam_keluar')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jam Keluar Sabtu -->
                        <div>
                            <label for="jam_sabtu_keluar" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                                <i class="fas fa-calendar-day mr-1 sm:mr-2 text-orange-600"></i>Jam Keluar Sabtu (Setengah Hari) <span class="text-red-500">*</span>
                            </label>
                            <input type="time" name="jam_sabtu_keluar" id="jam_sabtu_keluar" 
                                   value="{{ old('jam_sabtu_keluar', $jamSabtuKeluar ?? '12:00') }}" 
                                   class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 @error('jam_sabtu_keluar') border-red-500 @enderror" required>
                            @error('jam_sabtu_keluar')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="fas fa-info-circle mr-1"></i>Hari Sabtu kerja sampai jam ini saja (setengah hari)
                            </p>
                        </div>

                        <!-- Toleransi Keterlambatan -->
                        <div>
                            <label for="toleransi_keterlambatan" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                                <i class="fas fa-hourglass-half mr-1 sm:mr-2 text-teal-600"></i>Toleransi Keterlambatan (Menit) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="toleransi_keterlambatan" id="toleransi_keterlambatan" 
                                   value="{{ old('toleransi_keterlambatan', $toleransiKeterlambatan) }}" 
                                   class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 @error('toleransi_keterlambatan') border-red-500 @enderror" 
                                   min="0" max="60" required>
                            @error('toleransi_keterlambatan')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Batas waktu toleransi (0-60 menit)</p>
                        </div>

                        <!-- Info Box Jadwal Kerja -->
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 sm:p-4">
                            <div class="flex items-start gap-2 sm:gap-3">
                                <i class="fas fa-calendar-week text-blue-600 mt-0.5 sm:mt-1 flex-shrink-0 text-sm sm:text-base"></i>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs sm:text-sm text-blue-800 font-semibold mb-1">Jadwal Kerja Mingguan</p>
                                    <ul class="text-xs text-blue-700 space-y-0.5 sm:space-y-1">
                                        <li>• <strong>Senin - Jumat:</strong> Jam <span id="info-masuk">{{ $jamMasuk }}</span> - <span id="info-keluar">{{ $jamKeluar }}</span></li>
                                        <li>• <strong>Sabtu:</strong> Jam <span id="info-masuk-sabtu">{{ $jamMasuk }}</span> - <span id="info-sabtu">{{ $jamSabtuKeluar ?? '12:00' }}</span> (Setengah Hari)</li>
                                        <li>• <strong>Minggu:</strong> LIBUR (tidak dihitung dalam perhitungan kehadiran)</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Preview Jam Kerja -->
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 sm:p-4">
                            <h4 class="text-xs sm:text-sm font-bold text-gray-700 mb-2 sm:mb-3">Preview Jam Kerja</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-4 text-xs sm:text-sm text-gray-700">
                                <div class="flex items-center gap-2 bg-white p-2 rounded">
                                    <i class="fas fa-sign-in-alt text-teal-600 flex-shrink-0"></i>
                                    <span class="truncate">Masuk: <strong id="preview-masuk">{{ $jamMasuk }}</strong></span>
                                </div>
                                <div class="flex items-center gap-2 bg-white p-2 rounded">
                                    <i class="fas fa-sign-out-alt text-cyan-600 flex-shrink-0"></i>
                                    <span class="truncate">Keluar: <strong id="preview-keluar">{{ $jamKeluar }}</strong></span>
                                </div>
                                <div class="flex items-center gap-2 bg-white p-2 rounded">
                                    <i class="fas fa-calendar-day text-orange-600 flex-shrink-0"></i>
                                    <span class="truncate">Sabtu: <strong id="preview-sabtu">{{ $jamSabtuKeluar ?? '12:00' }}</strong></span>
                                </div>
                                <div class="flex items-center gap-2 bg-white p-2 rounded">
                                    <i class="fas fa-hourglass-half text-teal-500 flex-shrink-0"></i>
                                    <span class="truncate">Toleransi: <strong id="preview-toleransi">{{ $toleransiKeterlambatan }}</strong> mnt</span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end mt-6 sm:mt-8 pt-4 sm:pt-6 border-t border-gray-200">
                        <button type="submit" class="w-full sm:w-auto bg-gradient-to-r from-teal-600 to-cyan-600 hover:from-teal-700 hover:to-cyan-700 text-white font-semibold rounded-lg px-4 sm:px-6 py-2.5 sm:py-3 transition shadow-md text-sm sm:text-base">
                            <i class="fas fa-save mr-2"></i>Simpan Pengaturan
                        </button>
                    </div>

                </div>
            </form>
        </div>

        <!-- Tab Content: Lokasi Kantor -->
        <div id="content-lokasi" class="tab-content hidden p-4 sm:p-6 lg:p-8">
            <form action="{{ route('superadmin.pengaturan.lokasi') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="max-w-5xl mx-auto">
                    <h3 class="text-base sm:text-lg md:text-xl font-bold text-gray-900 mb-4 sm:mb-6">Pengaturan Lokasi Kantor</h3>
                    
                    <div class="space-y-4 sm:space-y-6">

                        <!-- Info Box -->
                        <div class="bg-teal-50 border border-teal-200 rounded-lg p-3 sm:p-4">
                            <div class="flex items-start gap-2 sm:gap-3">
                                <i class="fas fa-info-circle text-teal-600 mt-0.5 sm:mt-1 flex-shrink-0 text-sm sm:text-base"></i>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs sm:text-sm text-teal-800 font-semibold mb-1">Informasi Lokasi</p>
                                    <ul class="text-xs text-teal-700 space-y-0.5 sm:space-y-1">
                                        <li>• Lokasi kantor digunakan untuk validasi absensi berbasis GPS</li>
                                        <li>• Radius menentukan jarak maksimal karyawan boleh absen</li>
                                        <li>• <strong>Nonaktifkan validasi radius untuk WFH</strong> (karyawan bisa absen dari mana saja)</li>
                                        <li>• Gunakan Google Maps untuk mendapatkan koordinat yang akurat</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Toggle Validasi Radius GPS -->
                        <div class="bg-gradient-to-r from-blue-50 to-cyan-50 border-2 border-blue-200 rounded-lg p-3 sm:p-4">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4">
                                <div class="flex-1">
                                    <label class="flex items-start sm:items-center cursor-pointer">
                                        <div class="relative flex-shrink-0">
                                            <input type="checkbox" name="validasi_radius_aktif" id="validasi_radius_aktif" 
                                                   value="1" 
                                                   {{ old('validasi_radius_aktif', $validasiRadiusAktif ?? true) ? 'checked' : '' }}
                                                   class="sr-only peer"
                                                   onchange="toggleRadiusField()">
                                            <div class="w-11 h-6 sm:w-14 sm:h-7 bg-gray-300 rounded-full peer peer-checked:bg-teal-600 peer-focus:ring-4 peer-focus:ring-teal-300 transition-all"></div>
                                            <div class="absolute left-0.5 top-0.5 sm:left-1 sm:top-1 bg-white w-5 h-5 rounded-full transition-all peer-checked:translate-x-5 sm:peer-checked:translate-x-7 shadow-md"></div>
                                        </div>
                                        <div class="ml-3 sm:ml-4 flex-1 min-w-0">
                                            <span class="text-xs sm:text-sm md:text-base font-bold text-gray-900 block">
                                                <i class="fas fa-shield-alt mr-1 sm:mr-2 text-teal-600"></i>Validasi Radius GPS
                                            </span>
                                            <p class="text-xs text-gray-600 mt-0.5 sm:mt-1">
                                                <span id="status-text">
                                                    {{ old('validasi_radius_aktif', $validasiRadiusAktif ?? true) ? 'Aktif - Karyawan harus berada dalam radius kantor' : 'Nonaktif - Karyawan bisa absen dari mana saja (WFH)' }}
                                                </span>
                                            </p>
                                        </div>
                                    </label>
                                </div>
                                <div class="flex-shrink-0 self-start sm:self-auto">
                                    <span id="status-badge" class="inline-flex items-center px-2.5 sm:px-3 py-1 rounded-full text-xs font-semibold {{ old('validasi_radius_aktif', $validasiRadiusAktif ?? true) ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                        <i class="fas fa-{{ old('validasi_radius_aktif', $validasiRadiusAktif ?? true) ? 'check-circle' : 'times-circle' }} mr-1"></i>
                                        <span id="status-badge-text">{{ old('validasi_radius_aktif', $validasiRadiusAktif ?? true) ? 'Aktif' : 'Nonaktif' }}</span>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
                            
                            <!-- Left Column -->
                            <div class="space-y-4 sm:space-y-6">
                                
                                <!-- Nama Lokasi -->
                                <div>
                                    <label for="nama_lokasi" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                                        <i class="fas fa-tag mr-1 sm:mr-2 text-teal-600"></i>Nama Lokasi <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="nama_lokasi" id="nama_lokasi" 
                                           value="{{ old('nama_lokasi', $lokasiKantor['nama']) }}" 
                                           class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 @error('nama_lokasi') border-red-500 @enderror" 
                                           placeholder="Contoh: Kantor Pusat" required>
                                    @error('nama_lokasi')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Alamat -->
                                <div>
                                    <label for="alamat" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                                        <i class="fas fa-map-marked-alt mr-1 sm:mr-2 text-teal-600"></i>Alamat Lengkap <span class="text-red-500">*</span>
                                    </label>
                                    <textarea name="alamat" id="alamat" rows="3" 
                                              class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 @error('alamat') border-red-500 @enderror" 
                                              placeholder="Masukkan alamat lengkap kantor" required>{{ old('alamat', $lokasiKantor['alamat']) }}</textarea>
                                    @error('alamat')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Radius (AUTO DISABLED) -->
                                <div id="radius-field-wrapper">
                                    <label for="radius" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                                        <i class="fas fa-circle-notch mr-1 sm:mr-2 text-teal-600"></i>Radius Absensi (Meter) <span class="text-red-500" id="radius-required">*</span>
                                    </label>
                                    <input type="number" name="radius" id="radius" 
                                           value="{{ old('radius', $lokasiKantor['radius']) }}" 
                                           class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 @error('radius') border-red-500 @enderror transition-all" 
                                           min="10" max="1000"
                                           {{ old('validasi_radius_aktif', $validasiRadiusAktif ?? true) ? 'required' : 'readonly' }}>
                                    @error('radius')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                    <p class="text-xs mt-1" id="radius-help-text">
                                        <span class="text-gray-500">Jarak maksimal untuk absensi (10-1000 meter)</span>
                                    </p>
                                </div>

                            </div>

                            <!-- Right Column -->
                            <div class="space-y-4 sm:space-y-6">
                                
                                <!-- Latitude -->
                                <div>
                                    <label for="latitude" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                                        <i class="fas fa-location-arrow mr-1 sm:mr-2 text-teal-600"></i>Latitude <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="latitude" id="latitude" 
                                           value="{{ old('latitude', $lokasiKantor['latitude']) }}" 
                                           class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 @error('latitude') border-red-500 @enderror" 
                                           placeholder="Contoh: -6.200000" required>
                                    @error('latitude')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Longitude -->
                                <div>
                                    <label for="longitude" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                                        <i class="fas fa-location-arrow mr-1 sm:mr-2 text-teal-600"></i>Longitude <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="longitude" id="longitude" 
                                           value="{{ old('longitude', $lokasiKantor['longitude']) }}" 
                                           class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 @error('longitude') border-red-500 @enderror" 
                                           placeholder="Contoh: 106.816666" required>
                                    @error('longitude')
                                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Get Location Button -->
                                <div>
                                    <button type="button" onclick="getMyLocation()" class="w-full bg-teal-100 text-teal-700 hover:bg-teal-200 font-semibold rounded-lg px-4 py-2.5 transition text-sm sm:text-base">
                                        <i class="fas fa-crosshairs mr-2"></i>Gunakan Lokasi Saat Ini
                                    </button>
                                    <p class="text-xs text-gray-500 mt-2 text-center sm:text-left">
                                        <i class="fas fa-lightbulb mr-1"></i>
                                        Atau cari di <a href="https://www.google.com/maps" target="_blank" class="text-teal-600 hover:underline">Google Maps</a>
                                    </p>
                                </div>

                            </div>

                        </div>

                        <!-- Map Preview -->
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 sm:p-4">
                            <h4 class="text-xs sm:text-sm font-bold text-gray-700 mb-2 sm:mb-3">Preview Lokasi</h4>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-2 sm:gap-4 text-xs sm:text-sm text-gray-700">
                                <div class="flex items-center gap-2 bg-white p-2 rounded">
                                    <i class="fas fa-map-marker-alt text-teal-600 flex-shrink-0"></i>
                                    <strong id="preview-nama" class="truncate">{{ $lokasiKantor['nama'] }}</strong>
                                </div>
                                <div class="flex items-center gap-2 bg-white p-2 rounded">
                                    <i class="fas fa-circle-notch text-cyan-600 flex-shrink-0"></i>
                                    <span class="truncate">Radius: <strong id="preview-radius">{{ $lokasiKantor['radius'] }}</strong>m</span>
                                </div>
                                <a href="https://www.google.com/maps?q={{ $lokasiKantor['latitude'] }},{{ $lokasiKantor['longitude'] }}" 
                                   target="_blank" 
                                   id="preview-maps"
                                   class="flex items-center justify-center gap-2 bg-teal-50 text-teal-600 hover:bg-teal-100 p-2 rounded transition">
                                    <i class="fas fa-external-link-alt"></i>
                                    <span>Lihat di Maps</span>
                                </a>
                            </div>
                        </div>

                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end mt-6 sm:mt-8 pt-4 sm:pt-6 border-t border-gray-200">
                        <button type="submit" class="w-full sm:w-auto bg-gradient-to-r from-teal-600 to-cyan-600 hover:from-teal-700 hover:to-cyan-700 text-white font-semibold rounded-lg px-4 sm:px-6 py-2.5 sm:py-3 transition shadow-md text-sm sm:text-base">
                            <i class="fas fa-save mr-2"></i>Simpan Pengaturan
                        </button>
                    </div>

                </div>
            </form>
        </div>

        <!-- Tab Content: Info Perusahaan -->
        <div id="content-general" class="tab-content hidden p-4 sm:p-6lg:p-8">
            <form action="{{ route('superadmin.pengaturan.general') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="max-w-3xl mx-auto">
                    <h3 class="text-base sm:text-lg md:text-xl font-bold text-gray-900 mb-4 sm:mb-6">Informasi Perusahaan</h3>
                    
                    <div class="space-y-4 sm:space-y-6">

                        <!-- Nama Perusahaan -->
                        <div>
                            <label for="nama_perusahaan" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                                <i class="fas fa-building mr-1 sm:mr-2 text-teal-600"></i>Nama Perusahaan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nama_perusahaan" id="nama_perusahaan" 
                                   value="{{ old('nama_perusahaan', $namaPerusahaan) }}" 
                                   class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 @error('nama_perusahaan') border-red-500 @enderror" 
                                   placeholder="Contoh: PT. Portal Internal" required>
                            @error('nama_perusahaan')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email Perusahaan -->
                        <div>
                            <label for="email_perusahaan" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                                <i class="fas fa-envelope mr-1 sm:mr-2 text-teal-600"></i>Email Perusahaan <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email_perusahaan" id="email_perusahaan" 
                                   value="{{ old('email_perusahaan', $emailPerusahaan) }}" 
                                   class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 @error('email_perusahaan') border-red-500 @enderror" 
                                   placeholder="info@perusahaan.com" required>
                            @error('email_perusahaan')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Telepon Perusahaan -->
                        <div>
                            <label for="telepon_perusahaan" class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">
                                <i class="fas fa-phone mr-1 sm:mr-2 text-teal-600"></i>Telepon Perusahaan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="telepon_perusahaan" id="telepon_perusahaan" 
                                   value="{{ old('telepon_perusahaan', $teleponPerusahaan) }}" 
                                   class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 @error('telepon_perusahaan') border-red-500 @enderror" 
                                   placeholder="021-12345678" required>
                            @error('telepon_perusahaan')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Preview -->
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-3 sm:p-4">
                            <h4 class="text-xs sm:text-sm font-bold text-gray-700 mb-2 sm:mb-3">Preview Informasi</h4>
                            <div class="space-y-2 text-xs sm:text-sm text-gray-700">
                                <div class="flex items-center gap-2 bg-white p-2 rounded">
                                    <i class="fas fa-building w-5 text-teal-600 flex-shrink-0"></i>
                                    <strong id="preview-nama-perusahaan" class="truncate">{{ $namaPerusahaan }}</strong>
                                </div>
                                <div class="flex items-center gap-2 bg-white p-2 rounded">
                                    <i class="fas fa-envelope w-5 text-cyan-600 flex-shrink-0"></i>
                                    <span id="preview-email-perusahaan" class="break-all">{{ $emailPerusahaan }}</span>
                                </div>
                                <div class="flex items-center gap-2 bg-white p-2 rounded">
                                    <i class="fas fa-phone w-5 text-teal-500 flex-shrink-0"></i>
                                    <span id="preview-telepon-perusahaan">{{ $teleponPerusahaan }}</span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end mt-6 sm:mt-8 pt-4 sm:pt-6 border-t border-gray-200">
                        <button type="submit" class="w-full sm:w-auto bg-gradient-to-r from-teal-600 to-cyan-600 hover:from-teal-700 hover:to-cyan-700 text-white font-semibold rounded-lg px-4 sm:px-6 py-2.5 sm:py-3 transition shadow-md text-sm sm:text-base">
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
            button.classList.remove('active', 'text-teal-600', 'border-teal-600');
            button.classList.add('text-gray-500', 'border-transparent');
        });
        
        // Show selected content
        document.getElementById('content-' + tabName).classList.remove('hidden');
        
        // Add active class to selected button
        const activeButton = document.getElementById('tab-' + tabName);
        activeButton.classList.add('active', 'text-teal-600', 'border-teal-600');
        activeButton.classList.remove('text-gray-500', 'border-transparent');
    }

    // Toggle Validasi Radius GPS
    function toggleRadiusField() {
        const toggle = document.getElementById('validasi_radius_aktif');
        const radiusInput = document.getElementById('radius');
        const radiusRequired = document.getElementById('radius-required');
        const radiusHelpText = document.getElementById('radius-help-text');
        const statusText = document.getElementById('status-text');
        const statusBadge = document.getElementById('status-badge');
        const statusBadgeText = document.getElementById('status-badge-text');
        
        if (toggle.checked) {
            // AKTIF - Enable radius field
            radiusInput.removeAttribute('readonly');
            radiusInput.setAttribute('required', 'required');
            radiusInput.classList.remove('bg-gray-100', 'cursor-not-allowed', 'opacity-60');
            radiusInput.classList.add('bg-white');
            radiusRequired.classList.remove('hidden');
            radiusHelpText.innerHTML = '<span class="text-gray-500">Jarak maksimal untuk absensi (10-1000 meter)</span>';
            
            statusText.textContent = 'Aktif - Karyawan harus berada dalam radius kantor';
            statusBadge.className = 'inline-flex items-center px-2.5 sm:px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700';
            statusBadgeText.innerHTML = '<i class="fas fa-check-circle mr-1"></i>Aktif';
        } else {
            // NONAKTIF - Disable radius field
            radiusInput.setAttribute('readonly', 'readonly');
            radiusInput.removeAttribute('required');
            radiusInput.classList.add('bg-gray-100', 'cursor-not-allowed', 'opacity-60');
            radiusInput.classList.remove('bg-white');
            radiusRequired.classList.add('hidden');
            radiusHelpText.innerHTML = '<span class="text-orange-600"><i class="fas fa-info-circle mr-1"></i>Radius tidak digunakan karena validasi GPS nonaktif (Mode WFH)</span>';
            
            statusText.textContent = 'Nonaktif - Karyawan bisa absen dari mana saja (WFH)';
            statusBadge.className = 'inline-flex items-center px-2.5 sm:px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700';
            statusBadgeText.innerHTML = '<i class="fas fa-times-circle mr-1"></i>Nonaktif';
        }
    }

    // Live Preview Jam Kerja
    document.getElementById('jam_masuk')?.addEventListener('change', function() {
        document.getElementById('preview-masuk').textContent = this.value;
        document.getElementById('info-masuk').textContent = this.value;
        document.getElementById('info-masuk-sabtu').textContent = this.value;
    });

    document.getElementById('jam_keluar')?.addEventListener('change', function() {
        document.getElementById('preview-keluar').textContent = this.value;
        document.getElementById('info-keluar').textContent = this.value;
    });

    document.getElementById('jam_sabtu_keluar')?.addEventListener('change', function() {
        document.getElementById('preview-sabtu').textContent = this.value;
        document.getElementById('info-sabtu').textContent = this.value;
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
            const button = event.target;
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Mengambil lokasi...';
            button.disabled = true;
            
            navigator.geolocation.getCurrentPosition(function(position) {
                document.getElementById('latitude').value = position.coords.latitude.toFixed(6);
                document.getElementById('longitude').value = position.coords.longitude.toFixed(6);
                updateMapsLink();
                button.innerHTML = '<i class="fas fa-check mr-2"></i>Lokasi berhasil diambil!';
                button.classList.add('bg-green-100', 'text-green-700');
                button.classList.remove('bg-teal-100', 'text-teal-700');
                
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.disabled = false;
                    button.classList.remove('bg-green-100', 'text-green-700');
                    button.classList.add('bg-teal-100', 'text-teal-700');
                }, 2000);
            }, function(error) {
                button.innerHTML = '<i class="fas fa-times mr-2"></i>Gagal mendapatkan lokasi';
                button.classList.add('bg-red-100', 'text-red-700');
                button.classList.remove('bg-teal-100', 'text-teal-700');
                
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.disabled = false;
                    button.classList.remove('bg-red-100', 'text-red-700');
                    button.classList.add('bg-teal-100', 'text-teal-700');
                }, 2000);
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

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        switchTab('jamKerja');
        toggleRadiusField();
    });
</script>

<style>
    /* Tab Button Styles */
    .tab-button {
        transition: all 0.3s ease;
    }
    
    .tab-button.active {
        color: #0d9488;
        border-color: #0d9488;
    }
    
    .tab-button:not(.active) {
        color: #6b7280;
        border-color: transparent;
    }
    
    .tab-button:hover:not(.active) {
        color: #374151;
        background-color: #f3f4f6;
    }

    /* Custom Toggle Switch */
    input[type="checkbox"]:checked + div {
        background-color: #0d9488;
    }

    input[type="checkbox"]:focus + div {
        box-shadow: 0 0 0 4px rgba(13, 148, 136, 0.2);
    }

    /* Hide Scrollbar */
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    /* Focus visible styles for accessibility */
    button:focus-visible,
    input:focus-visible,
    textarea:focus-visible {
        outline: 2px solid #0d9488;
        outline-offset: 2px;
    }

    /* Mobile Optimization */
    @media (max-width: 640px) {
        input[type="time"],
        input[type="number"],
        input[type="text"],
        input[type="email"],
        textarea,
        select {
            font-size: 16px !important; /* Prevent zoom on iOS */
        }
    }
</style>
@endpush