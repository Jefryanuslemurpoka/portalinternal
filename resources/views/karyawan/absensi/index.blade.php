@extends('layouts.app')

@section('title', 'Absensi')
@section('page-title', 'Absensi')

@section('content')
<div class="space-y-6">

    <!-- Info Box -->
    <div class="bg-gradient-to-r from-purple-600 to-pink-600 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-2">
                    <i class="fas fa-clipboard-check mr-2"></i>Absensi Hari Ini
                </h2>
                <p class="text-purple-100">{{ now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</p>
            </div>
            <div class="hidden md:block">
                <div class="text-5xl font-bold">
                    <span id="currentTime">{{ now()->format('H:i') }}</span>
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
                <div class="flex items-center justify-between p-4 {{ $absensiHariIni ? 'bg-green-50 border-green-200' : 'bg-gray-50 border-gray-200' }} border rounded-lg">
                    <div class="flex items-center space-x-3">
                        @if($absensiHariIni)
                            <i class="fas fa-check-circle text-green-600 text-2xl"></i>
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
                        @if(strtotime($absensiHariIni->jam_masuk) <= strtotime($jamMasuk))
                            <span class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Tepat Waktu</span>
                        @else
                            <span class="px-3 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full">Terlambat</span>
                        @endif
                    @endif
                </div>

                <!-- Check-out Status -->
                <div class="flex items-center justify-between p-4 {{ $absensiHariIni && $absensiHariIni->jam_keluar ? 'bg-blue-50 border-blue-200' : 'bg-gray-50 border-gray-200' }} border rounded-lg">
                    <div class="flex items-center space-x-3">
                        @if($absensiHariIni && $absensiHariIni->jam_keluar)
                            <i class="fas fa-check-circle text-blue-600 text-2xl"></i>
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
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">Selesai</span>
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
                
                <div class="flex items-center justify-between p-4 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-sign-in-alt text-blue-600 text-xl"></i>
                        <div>
                            <p class="text-sm text-gray-600">Jam Masuk</p>
                            <p class="font-bold text-gray-900">{{ $jamMasuk }}</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between p-4 bg-red-50 border border-red-200 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-sign-out-alt text-red-600 text-xl"></i>
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
            
            <!-- Location Info -->
            <div id="locationInfo" class="mb-6 hidden">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-start">
                        <i class="fas fa-map-marker-alt text-blue-600 text-xl mt-1 mr-3"></i>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-blue-900 mb-1">Lokasi Terdeteksi</p>
                            <p class="text-xs text-blue-700">Latitude: <span id="userLat">-</span></p>
                            <p class="text-xs text-blue-700">Longitude: <span id="userLng">-</span></p>
                            <p class="text-xs text-blue-700 mt-1">Jarak dari kantor: <span id="distance">-</span> meter</p>
                        </div>
                        <span id="locationStatus" class="px-3 py-1 text-xs font-semibold rounded-full"></span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-center space-x-4">
                @if(!$absensiHariIni)
                    <button type="button" onclick="doCheckIn()" id="checkInBtn" class="px-8 py-4 bg-green-600 hover:bg-green-700 text-white text-lg font-semibold rounded-lg shadow-lg transition duration-200">
                        <i class="fas fa-sign-in-alt mr-2"></i>Check-in Sekarang
                    </button>
                @elseif(!$absensiHariIni->jam_keluar)
                    <button type="button" onclick="doCheckOut()" id="checkOutBtn" class="px-8 py-4 bg-red-600 hover:bg-red-700 text-white text-lg font-semibold rounded-lg shadow-lg transition duration-200">
                        <i class="fas fa-sign-out-alt mr-2"></i>Check-out Sekarang
                    </button>
                @else
                    <div class="text-center">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-check text-green-600 text-3xl"></i>
                        </div>
                        <p class="text-lg font-bold text-gray-900">Absensi Hari Ini Selesai</p>
                        <p class="text-sm text-gray-600">Terima kasih sudah melakukan absensi</p>
                    </div>
                @endif
            </div>

        </div>
    </div>

    <!-- Lokasi Kantor Info -->
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
                   class="block w-full px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-center rounded-lg transition duration-200">
                    <i class="fas fa-map-marked-alt mr-2"></i>Lihat di Google Maps
                </a>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    // Global variables
    let userLocation = null;

    const lokasiKantor = {
        lat: {{ $lokasiKantor['latitude'] }},
        lng: {{ $lokasiKantor['longitude'] }},
        radius: {{ $lokasiKantor['radius'] }}
    };

    // Update current time
    setInterval(() => {
        const now = new Date();
        document.getElementById('currentTime').textContent = 
            now.getHours().toString().padStart(2, '0') + ':' + 
            now.getMinutes().toString().padStart(2, '0');
    }, 1000);

    // Check-in Process
    function doCheckIn() {
        // Show loading
        Swal.fire({
            title: 'Mendeteksi Lokasi...',
            text: 'Mohon tunggu',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Get location
        getLocation((location) => {
            Swal.close();
            
            if (location) {
                // Confirm check-in
                Swal.fire({
                    title: 'Konfirmasi Check-in',
                    html: `
                        <p>Lokasi Anda terdeteksi</p>
                        <p class="text-sm text-gray-600 mt-2">Jarak dari kantor: <strong>${Math.round(location.distance)}m</strong></p>
                        <p class="text-sm ${location.inRadius ? 'text-green-600' : 'text-red-600'}">
                            ${location.inRadius ? '✓ Dalam radius kantor' : '✗ Diluar radius kantor'}
                        </p>
                    `,
                    icon: location.inRadius ? 'question' : 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#10b981',
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

    // Check-out Process
    function doCheckOut() {
        // Show loading
        Swal.fire({
            title: 'Mendeteksi Lokasi...',
            text: 'Mohon tunggu',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // Get location
        getLocation((location) => {
            Swal.close();
            
            if (location) {
                // Confirm check-out
                Swal.fire({
                    title: 'Konfirmasi Check-out',
                    html: `
                        <p>Lokasi Anda terdeteksi</p>
                        <p class="text-sm text-gray-600 mt-2">Jarak dari kantor: <strong>${Math.round(location.distance)}m</strong></p>
                        <p class="text-sm ${location.inRadius ? 'text-green-600' : 'text-red-600'}">
                            ${location.inRadius ? '✓ Dalam radius kantor' : '✗ Diluar radius kantor'}
                        </p>
                    `,
                    icon: location.inRadius ? 'question' : 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
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

    // Get user location
    function getLocation(callback) {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const location = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                    
                    // Calculate distance
                    const distance = calculateDistance(
                        location.lat,
                        location.lng,
                        lokasiKantor.lat,
                        lokasiKantor.lng
                    );
                    
                    location.distance = distance;
                    location.inRadius = distance <= lokasiKantor.radius;
                    
                    // Display location info
                    document.getElementById('userLat').textContent = location.lat.toFixed(6);
                    document.getElementById('userLng').textContent = location.lng.toFixed(6);
                    document.getElementById('distance').textContent = Math.round(distance);
                    
                    const locationStatus = document.getElementById('locationStatus');
                    if (location.inRadius) {
                        locationStatus.textContent = 'Dalam Radius';
                        locationStatus.className = 'px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full';
                    } else {
                        locationStatus.textContent = 'Diluar Radius';
                        locationStatus.className = 'px-3 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full';
                    }
                    
                    document.getElementById('locationInfo').classList.remove('hidden');
                    
                    callback(location);
                },
                (error) => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Mendapatkan Lokasi',
                        text: error.message,
                        confirmButtonColor: '#ef4444'
                    });
                    callback(null);
                }
            );
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Browser Tidak Mendukung',
                text: 'Browser Anda tidak mendukung geolocation',
                confirmButtonColor: '#ef4444'
            });
            callback(null);
        }
    }

    // Submit check-in
    function submitCheckIn(location) {
        Swal.fire({
            title: 'Memproses...',
            text: 'Mohon tunggu',
            allowOutsideClick: false,
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
                    confirmButtonColor: '#10b981'
                }).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Check-in Gagal',
                    text: data.message,
                    confirmButtonColor: '#ef4444'
                });
            }
        })
        .catch(error => {
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Terjadi kesalahan: ' + error.message,
                confirmButtonColor: '#ef4444'
            });
        });
    }

    // Submit check-out
    function submitCheckOut(location) {
        Swal.fire({
            title: 'Memproses...',
            text: 'Mohon tunggu',
            allowOutsideClick: false,
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
                    confirmButtonColor: '#10b981'
                }).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Check-out Gagal',
                    text: data.message,
                    confirmButtonColor: '#ef4444'
                });
            }
        })
        .catch(error => {
            Swal.close();
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Terjadi kesalahan: ' + error.message,
                confirmButtonColor: '#ef4444'
            });
        });
    }

    // Calculate distance between two coordinates (Haversine formula)
    function calculateDistance(lat1, lon1, lat2, lon2) {
        const R = 6371000; // Earth radius in meters
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

<!-- SweetAlert2 CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush