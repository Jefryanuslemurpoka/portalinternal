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
                <p class="text-purple-100">{{ now()->format('l, d F Y') }}</p>
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
                            <span class="badge badge-success">Tepat Waktu</span>
                        @else
                            <span class="badge badge-danger">Terlambat</span>
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
                        <span class="badge badge-info">Selesai</span>
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
            
            <!-- Camera Preview -->
            <div class="mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 text-center">
                    <i class="fas fa-camera mr-2"></i>Ambil Foto untuk Absensi
                </h3>
                
                <div class="relative">
                    <video id="camera" autoplay playsinline class="w-full rounded-lg border-4 border-gray-200 hidden"></video>
                    <canvas id="canvas" class="w-full rounded-lg border-4 border-purple-500 hidden"></canvas>
                    <div id="placeholder" class="w-full aspect-video bg-gray-100 rounded-lg flex items-center justify-center border-4 border-dashed border-gray-300">
                        <div class="text-center">
                            <i class="fas fa-camera text-6xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500">Kamera akan aktif saat Anda klik tombol absensi</p>
                        </div>
                    </div>
                </div>

                <div id="cameraControls" class="mt-4 flex justify-center space-x-3 hidden">
                    <button type="button" onclick="capturePhoto()" class="btn btn-primary">
                        <i class="fas fa-camera mr-2"></i>Ambil Foto
                    </button>
                    <button type="button" onclick="retakePhoto()" class="btn btn-secondary hidden" id="retakeBtn">
                        <i class="fas fa-redo mr-2"></i>Foto Ulang
                    </button>
                </div>
            </div>

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
                        <span id="locationStatus" class="badge"></span>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-center space-x-4">
                @if(!$absensiHariIni)
                    <button type="button" onclick="startCheckIn()" id="checkInBtn" class="btn btn-primary px-8 py-4 text-lg">
                        <i class="fas fa-sign-in-alt mr-2"></i>Check-in Sekarang
                    </button>
                @elseif(!$absensiHariIni->jam_keluar)
                    <button type="button" onclick="startCheckOut()" id="checkOutBtn" class="btn btn-danger px-8 py-4 text-lg">
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
                   class="btn btn-secondary w-full">
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
    let camera = document.getElementById('camera');
    let canvas = document.getElementById('canvas');
    let placeholder = document.getElementById('placeholder');
    let cameraControls = document.getElementById('cameraControls');
    let locationInfo = document.getElementById('locationInfo');
    let retakeBtn = document.getElementById('retakeBtn');
    let stream = null;
    let photoData = null;
    let userLocation = null;
    let isCheckIn = false;

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

    // Start Check-in Process
    function startCheckIn() {
        isCheckIn = true;
        initializeAbsensi();
    }

    // Start Check-out Process
    function startCheckOut() {
        isCheckIn = false;
        initializeCheckOut();
    }

    // Initialize Absensi (Check-in)
    function initializeAbsensi() {
        // Get location
        getLocation();
        
        // Start camera
        startCamera();
    }

    // Initialize Check-out (no camera needed)
    function initializeCheckOut() {
        getLocation();
        
        // Show confirmation after location is ready
        setTimeout(() => {
            if (userLocation) {
                confirmCheckOut();
            }
        }, 2000);
    }

    // Get user location
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    userLocation = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                    
                    document.getElementById('userLat').textContent = userLocation.lat.toFixed(6);
                    document.getElementById('userLng').textContent = userLocation.lng.toFixed(6);
                    
                    // Calculate distance
                    const distance = calculateDistance(
                        userLocation.lat,
                        userLocation.lng,
                        lokasiKantor.lat,
                        lokasiKantor.lng
                    );
                    
                    document.getElementById('distance').textContent = Math.round(distance);
                    
                    const locationStatus = document.getElementById('locationStatus');
                    if (distance <= lokasiKantor.radius) {
                        locationStatus.textContent = 'Dalam Radius';
                        locationStatus.className = 'badge badge-success';
                    } else {
                        locationStatus.textContent = 'Diluar Radius';
                        locationStatus.className = 'badge badge-danger';
                    }
                    
                    locationInfo.classList.remove('hidden');
                },
                (error) => {
                    alert('Gagal mendapatkan lokasi: ' + error.message);
                }
            );
        } else {
            alert('Browser tidak mendukung geolocation');
        }
    }

    // Start camera
    function startCamera() {
        navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } })
            .then((mediaStream) => {
                stream = mediaStream;
                camera.srcObject = stream;
                
                placeholder.classList.add('hidden');
                camera.classList.remove('hidden');
                cameraControls.classList.remove('hidden');
            })
            .catch((error) => {
                alert('Gagal mengakses kamera: ' + error.message);
            });
    }

    // Capture photo
    function capturePhoto() {
        const context = canvas.getContext('2d');
        canvas.width = camera.videoWidth;
        canvas.height = camera.videoHeight;
        context.drawImage(camera, 0, 0);
        
        photoData = canvas.toDataURL('image/png');
        
        // Stop camera
        if (stream) {
            stream.getTracks().forEach(track => track.stop());
        }
        
        // Show canvas, hide camera
        camera.classList.add('hidden');
        canvas.classList.remove('hidden');
        retakeBtn.classList.remove('hidden');
        
        // Auto submit check-in
        setTimeout(() => {
            submitCheckIn();
        }, 500);
    }

    // Retake photo
    function retakePhoto() {
        canvas.classList.add('hidden');
        retakeBtn.classList.add('hidden');
        photoData = null;
        startCamera();
    }

    // Submit check-in
    function submitCheckIn() {
        if (!photoData) {
            alert('Silakan ambil foto terlebih dahulu');
            return;
        }
        
        if (!userLocation) {
            alert('Lokasi belum terdeteksi');
            return;
        }
        
        // Show loading
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
                latitude: userLocation.lat,
                longitude: userLocation.lng,
                foto: photoData
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

    // Confirm check-out
    function confirmCheckOut() {
        Swal.fire({
            title: 'Konfirmasi Check-out',
            text: 'Apakah Anda yakin ingin check-out?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Ya, Check-out',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                submitCheckOut();
            }
        });
    }

    // Submit check-out
    function submitCheckOut() {
        if (!userLocation) {
            alert('Lokasi belum terdeteksi');
            return;
        }
        
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
                latitude: userLocation.lat,
                longitude: userLocation.lng
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