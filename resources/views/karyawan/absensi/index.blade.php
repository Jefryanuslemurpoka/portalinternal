@extends('layouts.app')

@section('title', 'Absensi')
@section('page-title', 'Absensi')

@section('content')
<div class="space-y-6">

    <!-- Info Box -->
    <div class="bg-gradient-to-r from-teal-600 to-cyan-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-2">
                    <i class="fas fa-clipboard-check mr-2"></i>Absensi Hari Ini
                </h2>
                <p class="text-teal-100">{{ now('Asia/Jakarta')->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</p>
            </div>
            <div class="hidden md:block">
                <div class="text-5xl font-bold">
                    <span id="currentTime">{{ now('Asia/Jakarta')->format('H:i') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Status & Info -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Status Absensi -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-info-circle mr-2"></i>Status Absensi
            </h3>

            <div class="space-y-4">
                
                <!-- Check-in Status -->
                <div class="flex items-center justify-between p-4 {{ $absensiHariIni ? 'bg-teal-50 border-teal-200' : 'bg-gray-50 border-gray-200' }} border rounded-lg">
                    <div class="flex items-center space-x-3">
                        @if($absensiHariIni)
                            <i class="fas fa-check-circle text-teal-600 text-2xl"></i>
                        @else
                            <i class="fas fa-times-circle text-gray-400 text-2xl"></i>
                        @endif
                        <div>
                            <p class="font-semibold text-gray-900">Check-in</p>
                            @if($absensiHariIni)
                                <p class="text-sm text-gray-600">{{ date('H:i', strtotime($absensiHariIni->jam_masuk)) }}</p>
                            @else
                                <p class="text-sm text-gray-500">Belum check-in</p>
                            @endif
                        </div>
                    </div>
                    @if($absensiHariIni)
                        @php
                            $batasKeterlambatan = \Carbon\Carbon::createFromFormat('H:i', $jamMasuk)
                                ->addMinutes($toleransi)
                                ->format('H:i:s');
                        @endphp
                        @if(strtotime($absensiHariIni->jam_masuk) <= strtotime($batasKeterlambatan))
                            <span class="px-3 py-1 bg-teal-100 text-teal-800 text-xs font-semibold rounded-full">Tepat Waktu</span>
                        @else
                            <span class="px-3 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full">Terlambat</span>
                        @endif
                    @endif
                </div>

                <!-- Check-out Status -->
                <div class="flex items-center justify-between p-4 {{ $absensiHariIni && $absensiHariIni->jam_keluar ? 'bg-cyan-50 border-cyan-200' : 'bg-gray-50 border-gray-200' }} border rounded-lg">
                    <div class="flex items-center space-x-3">
                        @if($absensiHariIni && $absensiHariIni->jam_keluar)
                            <i class="fas fa-check-circle text-cyan-600 text-2xl"></i>
                        @else
                            <i class="fas fa-times-circle text-gray-400 text-2xl"></i>
                        @endif
                        <div>
                            <p class="font-semibold text-gray-900">Check-out</p>
                            @if($absensiHariIni && $absensiHariIni->jam_keluar)
                                <p class="text-sm text-gray-600">{{ date('H:i', strtotime($absensiHariIni->jam_keluar)) }}</p>
                            @else
                                <p class="text-sm text-gray-500">Belum check-out</p>
                            @endif
                        </div>
                    </div>
                    @if($absensiHariIni && $absensiHariIni->jam_keluar)
                        <span class="px-3 py-1 bg-cyan-100 text-cyan-800 text-xs font-semibold rounded-full">Selesai</span>
                    @endif
                </div>

            </div>
        </div>

        <!-- Jam Kerja Info -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-clock mr-2"></i>Jam Kerja
            </h3>

            <div class="space-y-4">
                
                <div class="flex items-center justify-between p-4 bg-teal-50 border border-teal-200 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-sign-in-alt text-teal-600 text-xl"></i>
                        <div>
                            <p class="text-sm text-gray-600">Jam Masuk</p>
                            <p class="font-bold text-gray-900">{{ $jamMasuk }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between p-4 bg-cyan-50 border border-cyan-200 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-sign-out-alt text-cyan-600 text-xl"></i>
                        <div>
                            <p class="text-sm text-gray-600">Jam Keluar</p>
                            <p class="font-bold text-gray-900">{{ $jamKeluar }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-hourglass-half text-yellow-600 text-xl"></i>
                        <div>
                            <p class="text-sm text-gray-600">Toleransi</p>
                            <p class="font-bold text-gray-900">{{ $toleransi }} menit</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <!-- Absensi Actions -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="max-w-2xl mx-auto">
            
            <!-- ✅ Info Mode WFH (jika validasi radius nonaktif) -->
            @php
                $validasiRadiusAktif = App\Models\Setting::get('validasi_radius_aktif', true);
            @endphp
            
            @if(!$validasiRadiusAktif)
                <div class="mb-6">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-600 text-xl mt-1 mr-3"></i>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-blue-900 mb-1">Mode WFH Aktif</p>
                                <p class="text-xs text-blue-700">Validasi lokasi dinonaktifkan. Anda dapat absen dari mana saja tanpa verifikasi GPS.</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Location Info (hanya tampil jika validasi aktif) -->
            <div id="locationInfo" class="mb-6 hidden">
                <div class="bg-teal-50 border border-teal-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <i class="fas fa-map-marker-alt text-teal-600 text-xl mt-1 mr-3"></i>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-teal-900 mb-1">Lokasi Terdeteksi</p>
                            <p class="text-xs text-teal-700">Latitude: <span id="userLat">-</span></p>
                            <p class="text-xs text-teal-700">Longitude: <span id="userLng">-</span></p>
                            <p class="text-xs text-teal-700 mt-1">Jarak dari kantor: <span id="distance">-</span> meter</p>
                        </div>
                        <span id="locationStatus" class="px-3 py-1 text-xs font-semibold rounded-full"></span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-center space-x-4">
                @if(!$absensiHariIni)
                    <button type="button" onclick="doCheckIn()" id="checkInBtn" class="px-8 py-4 bg-teal-600 hover:bg-teal-700 text-white text-lg font-semibold rounded-lg shadow-lg transition duration-200">
                        <i class="fas fa-sign-in-alt mr-2"></i>Check-in Sekarang
                    </button>
                @elseif(!$absensiHariIni->jam_keluar)
                    <button type="button" onclick="doCheckOut()" id="checkOutBtn" class="px-8 py-4 bg-cyan-600 hover:bg-cyan-700 text-white text-lg font-semibold rounded-lg shadow-lg transition duration-200">
                        <i class="fas fa-sign-out-alt mr-2"></i>Check-out Sekarang
                    </button>
                @else
                    <div class="text-center">
                        <div class="w-16 h-16 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-check text-teal-600 text-3xl"></i>
                        </div>
                        <p class="text-lg font-bold text-gray-900">Absensi Hari Ini Selesai</p>
                        <p class="text-sm text-gray-600">Terima kasih sudah melakukan absensi</p>
                    </div>
                @endif
            </div>

        </div>
    </div>

    <!-- Lokasi Kantor Info (hanya tampil jika validasi aktif) -->
    @if($validasiRadiusAktif)
    <div class="bg-white rounded-xl shadow-lg p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">
            <i class="fas fa-building mr-2"></i>Lokasi Kantor
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-sm text-gray-600 mb-1">Nama Lokasi</p>
                <p class="font-semibold text-gray-900">{{ $lokasiKantor['nama'] }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Radius Absensi</p>
                <p class="font-semibold text-gray-900">{{ $lokasiKantor['radius'] }} meter</p>
            </div>
            <div class="md:col-span-2">
                <a href="https://www.google.com/maps?q={{ $lokasiKantor['latitude'] }},{{ $lokasiKantor['longitude'] }}" 
                   target="_blank" 
                   class="block w-full px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-center rounded-lg transition duration-200">
                    <i class="fas fa-map-marked-alt mr-2"></i>Lihat di Google Maps
                </a>
            </div>
        </div>
    </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
    // ✅ Cek apakah validasi radius aktif (dari backend)
    const validasiRadiusAktif = {{ $validasiRadiusAktif ? 'true' : 'false' }};

    // Global variables
    let userLocation = null;
    let isGettingLocation = false;

    const lokasiKantor = {
        lat: {{ $lokasiKantor['latitude'] }},
        lng: {{ $lokasiKantor['longitude'] }},
        radius: {{ $lokasiKantor['radius'] }}
    };

    // Update current time
    function updateClock() {
        const now = new Date();
        const hours = now.getHours().toString().padStart(2, '0');
        const minutes = now.getMinutes().toString().padStart(2, '0');
        document.getElementById('currentTime').textContent = hours + ':' + minutes;
    }
    
    setInterval(updateClock, 1000);
    updateClock();

    // ✅ Check-in Process (SKIP GEOLOCATION jika validasi nonaktif)
    function doCheckIn() {
        if (isGettingLocation) return;
        
        // Jika validasi radius NONAKTIF, langsung konfirmasi tanpa lokasi
        if (!validasiRadiusAktif) {
            Swal.fire({
                title: 'Konfirmasi Check-in',
                html: '<p>Anda akan melakukan check-in sekarang</p><p class="text-sm text-blue-600 mt-2">Mode WFH - Tanpa validasi lokasi</p>',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0d9488',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Check-in',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    submitCheckIn({
                        lat: 0,
                        lng: 0,
                        distance: 0,
                        inRadius: true
                    });
                }
            });
            return;
        }

        // Jika validasi AKTIF, jalankan geolocation
        getLocation((location) => {
            if (location) {
                Swal.fire({
                    title: 'Konfirmasi Check-in',
                    html: `
                        <p>Lokasi Anda terdeteksi</p>
                        <p class="text-sm text-gray-600 mt-2">Jarak dari kantor: <strong>${Math.round(location.distance)}m</strong></p>
                        <p class="text-sm ${location.inRadius ? 'text-teal-600' : 'text-red-600'}">
                            ${location.inRadius ? '✓ Dalam radius kantor' : '✗ Diluar radius kantor'}
                        </p>
                    `,
                    icon: location.inRadius ? 'question' : 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#0d9488',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Check-in',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        submitCheckIn(location);
                    }
                });
            }
        });
    }

    // ✅ Check-out Process (SKIP GEOLOCATION jika validasi nonaktif)
    function doCheckOut() {
        if (isGettingLocation) return;
        
        // Jika validasi radius NONAKTIF, langsung konfirmasi tanpa lokasi
        if (!validasiRadiusAktif) {
            Swal.fire({
                title: 'Konfirmasi Check-out',
                html: '<p>Anda akan melakukan check-out sekarang</p><p class="text-sm text-blue-600 mt-2">Mode WFH - Tanpa validasi lokasi</p>',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#06b6d4',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Check-out',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    submitCheckOut({
                        lat: 0,
                        lng: 0,
                        distance: 0,
                        inRadius: true
                    });
                }
            });
            return;
        }

        // Jika validasi AKTIF, jalankan geolocation
        getLocation((location) => {
            if (location) {
                Swal.fire({
                    title: 'Konfirmasi Check-out',
                    html: `
                        <p>Lokasi Anda terdeteksi</p>
                        <p class="text-sm text-gray-600 mt-2">Jarak dari kantor: <strong>${Math.round(location.distance)}m</strong></p>
                        <p class="text-sm ${location.inRadius ? 'text-teal-600' : 'text-red-600'}">
                            ${location.inRadius ? '✓ Dalam radius kantor' : '✗ Diluar radius kantor'}
                        </p>
                    `,
                    icon: location.inRadius ? 'question' : 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#06b6d4',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Check-out',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        submitCheckOut(location);
                    }
                });
            }
        });
    }

    // Get user location (hanya dipanggil jika validasi aktif)
    function getLocation(callback) {
        if (isGettingLocation) return;
        
        if (!navigator.geolocation) {
            Swal.fire({
                icon: 'error',
                title: 'Browser Tidak Mendukung',
                html: `
                    <div class="text-left">
                        <p class="mb-3">Browser Anda tidak mendukung fitur geolocation.</p>
                        <p class="text-sm text-gray-600">Silakan gunakan browser modern seperti:</p>
                        <ul class="text-sm text-gray-600 ml-4 mt-2">
                            <li>• Google Chrome (recommended)</li>
                            <li>• Mozilla Firefox</li>
                            <li>• Microsoft Edge</li>
                        </ul>
                    </div>
                `,
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Mengerti',
                allowOutsideClick: false,
                width: '500px'
            });
            callback(null);
            return;
        }

        isGettingLocation = true;

        let timerInterval;
        Swal.fire({
            title: 'Mendapatkan Lokasi...',
            html: `
                <div class="mb-3">
                    <p class="text-sm text-gray-600">Mohon izinkan akses lokasi browser Anda</p>
                </div>
                <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                    <p class="text-xs text-gray-700 font-semibold mb-2">📍 Instruksi:</p>
                    <ol class="text-xs text-gray-600 text-left ml-4">
                        <li>1. Klik "Allow" / "Izinkan" pada popup browser</li>
                        <li>2. Tunggu hingga GPS mendeteksi lokasi Anda</li>
                        <li>3. Proses maksimal <b id="timer">30</b> detik</li>
                    </ol>
                </div>
            `,
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            showCancelButton: true,
            cancelButtonText: 'Batal',
            cancelButtonColor: '#6b7280',
            width: '500px',
            didOpen: () => {
                Swal.showLoading();
                let timeLeft = 30;
                const timerElement = document.getElementById('timer');
                timerInterval = setInterval(() => {
                    timeLeft--;
                    if (timerElement) {
                        timerElement.textContent = timeLeft;
                    }
                    if (timeLeft <= 0) {
                        clearInterval(timerInterval);
                    }
                }, 1000);
            },
            willClose: () => {
                clearInterval(timerInterval);
            }
        }).then((result) => {
            if (result.dismiss === Swal.DismissReason.cancel) {
                isGettingLocation = false;
            }
        });

        navigator.geolocation.getCurrentPosition(
            (position) => {
                isGettingLocation = false;
                clearInterval(timerInterval);
                Swal.close();
                
                const location = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };
                
                const distance = calculateDistance(
                    location.lat,
                    location.lng,
                    lokasiKantor.lat,
                    lokasiKantor.lng
                );
                
                location.distance = distance;
                location.inRadius = distance <= lokasiKantor.radius;
                
                document.getElementById('userLat').textContent = location.lat.toFixed(6);
                document.getElementById('userLng').textContent = location.lng.toFixed(6);
                document.getElementById('distance').textContent = Math.round(distance);
                
                const locationStatus = document.getElementById('locationStatus');
                if (location.inRadius) {
                    locationStatus.textContent = 'Dalam Radius';
                    locationStatus.className = 'px-3 py-1 bg-teal-100 text-teal-800 text-xs font-semibold rounded-full';
                } else {
                    locationStatus.textContent = 'Diluar Radius';
                    locationStatus.className = 'px-3 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full';
                }
                
                document.getElementById('locationInfo').classList.remove('hidden');
                
                callback(location);
            },
            (error) => {
                isGettingLocation = false;
                clearInterval(timerInterval);
                
                let errorTitle = 'Gagal Mendapatkan Lokasi';
                let errorMessage = '';
                
                switch(error.code) {
                    case error.PERMISSION_DENIED:
                        errorMessage = `
                            <div class="text-left">
                                <p class="mb-3 font-semibold text-red-600">❌ Akses lokasi ditolak!</p>
                                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-3">
                                    <p class="text-sm font-semibold mb-2">🔧 Cara Mengizinkan Lokasi:</p>
                                    <ol class="text-sm text-gray-700 ml-4 space-y-1">
                                        <li>1. Klik ikon <strong>🔒 kunci</strong> di address bar (samping URL)</li>
                                        <li>2. Cari pengaturan <strong>"Location"</strong> atau <strong>"Lokasi"</strong></li>
                                        <li>3. Ubah menjadi <strong>"Allow"</strong> atau <strong>"Izinkan"</strong></li>
                                        <li>4. Refresh halaman (tekan F5)</li>
                                        <li>5. Coba absen lagi</li>
                                    </ol>
                                </div>
                                <p class="text-xs text-gray-500">💡 Tip: Pastikan GPS/Location di perangkat Anda juga aktif</p>
                            </div>
                        `;
                        break;
                    case error.POSITION_UNAVAILABLE:
                        errorMessage = `
                            <div class="text-left">
                                <p class="mb-3 font-semibold text-orange-600">📍 Informasi lokasi tidak tersedia</p>
                                <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 mb-3">
                                    <p class="text-sm font-semibold mb-2">🔧 Solusi:</p>
                                    <ol class="text-sm text-gray-700 ml-4 space-y-1">
                                        <li>1. Pastikan GPS/Location Service di perangkat <strong>AKTIF</strong></li>
                                        <li>2. Coba pindah ke area dengan sinyal GPS lebih baik</li>
                                        <li>3. Restart browser dan coba lagi</li>
                                    </ol>
                                </div>
                            </div>
                        `;
                        break;
                    case error.TIMEOUT:
                        errorMessage = `
                            <div class="text-left">
                                <p class="mb-3 font-semibold text-yellow-600">⏱️ Waktu habis!</p>
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-3">
                                    <p class="text-sm text-gray-700 mb-2">GPS memakan waktu terlalu lama untuk mendapatkan lokasi.</p>
                                    <p class="text-sm font-semibold mb-2">🔧 Solusi:</p>
                                    <ol class="text-sm text-gray-700 ml-4 space-y-1">
                                        <li>1. Pindah ke area outdoor atau dekat jendela</li>
                                        <li>2. Pastikan GPS aktif</li>
                                        <li>3. Coba beberapa saat lagi</li>
                                    </ol>
                                </div>
                            </div>
                        `;
                        break;
                    default:
                        errorMessage = `
                            <div class="text-left">
                                <p class="mb-3 font-semibold text-red-600">⚠️ Error tidak diketahui</p>
                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                    <p class="text-sm text-gray-700">${error.message}</p>
                                </div>
                            </div>
                        `;
                }
                
                Swal.fire({
                    icon: 'error',
                    title: errorTitle,
                    html: errorMessage,
                    confirmButtonColor: '#ef4444',
                    confirmButtonText: 'Tutup',
                    allowOutsideClick: false,
                    width: '600px',
                    customClass: {
                        popup: 'text-left'
                    }
                });
                callback(null);
            },
            {
                enableHighAccuracy: true,
                timeout: 30000,
                maximumAge: 0
            }
        );
    }

    // Submit check-in
    function submitCheckIn(location) {
        Swal.fire({
            title: 'Memproses...',
            text: 'Mohon tunggu',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        fetch('{{ route("karyawan.absensi.checkin") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                latitude: location.lat,
                longitude: location.lng
            })
        })
        .then(response => response.json())
        .then(data => {
            Swal.close();
            
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Check-in Berhasil!',
                    text: data.message,
                    confirmButtonColor: '#0d9488',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Check-in Gagal',
                    text: data.message,
                    confirmButtonColor: '#ef4444',
                    confirmButtonText: 'Mengerti'
                });
            }
        })
        .catch(error => {
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                html: '<p>Terjadi kesalahan saat mengirim data</p><p class="text-sm text-gray-600 mt-2">' + error.message + '</p>',
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Mengerti'
            });
        });
    }

    // Submit check-out
    function submitCheckOut(location) {
        Swal.fire({
            title: 'Memproses...',
            text: 'Mohon tunggu',
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
        fetch('{{ route("karyawan.absensi.checkout") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                latitude: location.lat,
                longitude: location.lng
            })
        })
        .then(response => response.json())
        .then(data => {
            Swal.close();
            
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Check-out Berhasil!',
                    text: data.message,
                    confirmButtonColor: '#0d9488',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Check-out Gagal',
                    text: data.message,
                    confirmButtonColor: '#ef4444',
                    confirmButtonText: 'Mengerti'
                });
            }
        })
        .catch(error => {
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                html: '<p>Terjadi kesalahan saat mengirim data</p><p class="text-sm text-gray-600 mt-2">' + error.message + '</p>',
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Mengerti'
            });
        });
    }

    // Calculate distance between two coordinates
    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371000;
        const dLat = (lat2 - lat1) * Math.PI / 180;
        const dLon = (lon2 - lon1) * Math.PI / 180;
        
        const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                  Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                  Math.sin(dLon / 2) * Math.sin(dLon / 2);
        
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        const distance = R * c;
        
        return distance;
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush