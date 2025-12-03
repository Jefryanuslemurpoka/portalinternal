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
                <i class="fas fa-info-circle mr-2"></i>Status Hari Ini
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
        <div class="max-w-3xl mx-auto">
            
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
            <div class="space-y-4">
                @if(!$absensiHariIni)
                    <!-- Tombol Hadir -->
                    <button type="button" onclick="doCheckIn()" id="checkInBtn" class="w-full px-8 py-4 bg-gradient-to-r from-teal-500 to-teal-600 hover:from-teal-600 hover:to-teal-700 text-white text-lg font-semibold rounded-xl shadow-lg transition duration-200 flex items-center justify-center">
                        <i class="fas fa-user-check mr-3 text-2xl"></i>
                        <div class="text-left">
                            <div class="font-bold">HADIR</div>
                            <div class="text-xs opacity-90">Absensi Kehadiran Normal</div>
                        </div>
                    </button>

                    <!-- Tombol Izin -->
                    <button type="button" onclick="showIzinForm()" class="w-full px-8 py-4 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white text-lg font-semibold rounded-xl shadow-lg transition duration-200 flex items-center justify-center">
                        <i class="fas fa-file-alt mr-3 text-2xl"></i>
                        <div class="text-left">
                            <div class="font-bold">IZIN</div>
                            <div class="text-xs opacity-90">Pengajuan Izin Tidak Masuk</div>
                        </div>
                    </button>

                    <!-- Tombol Cuti -->
                    <button type="button" onclick="showCutiForm()" class="w-full px-8 py-4 bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white text-lg font-semibold rounded-xl shadow-lg transition duration-200 flex items-center justify-center">
                        <i class="fas fa-umbrella-beach mr-3 text-2xl"></i>
                        <div class="text-left">
                            <div class="font-bold">CUTI</div>
                            <div class="text-xs opacity-90">Pengajuan Cuti</div>
                        </div>
                    </button>

                @elseif(!$absensiHariIni->jam_keluar)
                    <button type="button" onclick="doCheckOut()" id="checkOutBtn" class="w-full px-8 py-4 bg-gradient-to-r from-cyan-500 to-cyan-600 hover:from-cyan-600 hover:to-cyan-700 text-white text-lg font-semibold rounded-xl shadow-lg transition duration-200">
                        <i class="fas fa-sign-out-alt mr-2"></i>Check-out Sekarang
                    </button>
                @else
                    <div class="text-center py-6">
                        <div class="w-20 h-20 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-check text-teal-600 text-4xl"></i>
                        </div>
                        <p class="text-xl font-bold text-gray-900">Absensi Hari Ini Selesai</p>
                        <p class="text-sm text-gray-600 mt-2">Terima kasih sudah melakukan absensi</p>
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

<!-- ✅ SweetAlert WAJIB di-load SEBELUM script utama -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// ======================================================
// CONFIG DARI BACKEND
// ======================================================
const validasiRadiusAktif = {{ $validasiRadiusAktif ? 'true' : 'false' }};

const lokasiKantor = {!! $validasiRadiusAktif
    ? json_encode([
        'lat'    => $lokasiKantor['latitude'],
        'lng'    => $lokasiKantor['longitude'],
        'radius' => $lokasiKantor['radius']
    ])
    : 'null'
!!};

let isGettingLocation = false;

// ======================================================
// JAM REALTIME
// ======================================================
function updateClock(){
    const now = new Date();
    document.getElementById('currentTime') &&
    (document.getElementById('currentTime').textContent =
        now.getHours().toString().padStart(2,'0') + ':' +
        now.getMinutes().toString().padStart(2,'0'));
}
setInterval(updateClock,1000); updateClock();


// ======================================================
// SAFE FETCH JSON
// ======================================================
function fetchJson(url, options){

    return fetch(url, options)
        .then(res => res.text())
        .then(text => {
            try{
                return JSON.parse(text);
            }catch(err){
                console.error("SERVER RESPONSE:");
                console.error(text);
                throw new Error("Server tidak mengembalikan JSON valid (redirect / error / HTML)");
            }
        });
}


// ======================================================
// IZIN
// ======================================================
function showIzinForm(){

    Swal.fire({
        title:'Pengajuan Izin',
        html:`
            <div class="text-left space-y-4">
                <input type="date" id="izin_tanggal_mulai" class="w-full px-3 py-2 border rounded" required>
                <input type="date" id="izin_tanggal_selesai" class="w-full px-3 py-2 border rounded" required>
                <textarea id="izin_alasan" rows="3" class="w-full px-3 py-2 border rounded" placeholder="Alasan izin" required></textarea>
                <input type="file" id="izin_dokumen" accept=".jpg,.png,.pdf">
                <small class="text-gray-500">Max 2MB</small>
            </div>
        `,
        showCancelButton:true,
        confirmButtonText:'Ajukan',
        preConfirm:()=>{
            const mulai  = izin_tanggal_mulai.value;
            const selesai= izin_tanggal_selesai.value;
            const alasan = izin_alasan.value;
            const file   = izin_dokumen.files[0];

            if(!mulai||!selesai||!alasan){
                Swal.showValidationMessage("Lengkapi semua field!");
                return false;
            }

            if(selesai < mulai){
                Swal.showValidationMessage("Tanggal selesai harus setelah tanggal mulai!");
                return false;
            }

            if(file && file.size > 2048000){
                Swal.showValidationMessage("Ukuran file max 2MB!");
                return false;
            }

            return {mulai, selesai, alasan, file};
        }
    })
    .then(r=>{
        if(r.isConfirmed) submitIzin(r.value);
    });

}

// ======================================================
// CUTI
// ======================================================
function showCutiForm(){

    Swal.fire({
        title:'Pengajuan Cuti',
        html:`
            <div class="text-left space-y-4">
                <input type="date" id="cuti_tanggal_mulai" class="w-full px-3 py-2 border rounded" required>
                <input type="date" id="cuti_tanggal_selesai" class="w-full px-3 py-2 border rounded" required>
                <textarea id="cuti_alasan" rows="3" class="w-full px-3 py-2 border rounded" placeholder="Alasan cuti" required></textarea>
                <input type="file" id="cuti_dokumen" accept=".jpg,.png,.pdf">
                <small class="text-gray-500">Max 2MB</small>
            </div>
        `,
        showCancelButton:true,
        confirmButtonText:'Ajukan',
        preConfirm:()=>{

            const mulai   = cuti_tanggal_mulai.value;
            const selesai = cuti_tanggal_selesai.value;
            const alasan  = cuti_alasan.value;
            const file    = cuti_dokumen.files[0];

            if(!mulai || !selesai || !alasan){
                Swal.showValidationMessage("Lengkapi semua field!");
                return false;
            }

            if(selesai < mulai){
                Swal.showValidationMessage("Tanggal selesai harus setelah tanggal mulai!");
                return false;
            }

            if(file && file.size > 2048000){
                Swal.showValidationMessage("Ukuran file max 2MB!");
                return false;
            }

            return { mulai, selesai, alasan, file };
        }
    })
    .then(r=>{
        if(r.isConfirmed) submitCuti(r.value);
    });

}

function submitCuti(data){

    Swal.fire({title:'Memproses...',allowOutsideClick:false,didOpen:()=>Swal.showLoading()});

    const form = new FormData();
    form.append('jenis','cuti');
    form.append('tanggal_mulai', data.mulai);
    form.append('tanggal_selesai', data.selesai);
    form.append('alasan', data.alasan);
    if(data.file) form.append('dokumen', data.file);

    fetchJson('{{ route("karyawan.izin.store") }}', {
        method: 'POST',
        headers:{
            'X-CSRF-TOKEN':'{{ csrf_token() }}'
        },
        body: form
    })
    .then(r=>{
        Swal.close();
        Swal.fire(r.success ? 'success' : 'error', r.message)
            .then(()=> r.success && location.reload());
    })
    .catch(e=>{
        Swal.close();
        Swal.fire('ERROR', e.message, 'error');
    });

}


function submitIzin(data){

    Swal.fire({title:'Memproses...',allowOutsideClick:false,didOpen:()=>Swal.showLoading()});

    const form = new FormData();
    form.append('jenis','izin');
    form.append('tanggal_mulai', data.mulai);
    form.append('tanggal_selesai', data.selesai);
    form.append('alasan', data.alasan);
    if(data.file) form.append('dokumen',data.file);

    fetchJson('{{ route("karyawan.izin.store") }}',{
        method:'POST',
        headers:{'X-CSRF-TOKEN':'{{ csrf_token() }}'},
        body:form
    })
    .then(r=>{
        Swal.close();
        Swal.fire(r.success?'success':'error', r.message)
            .then(()=> r.success && location.reload());
    })
    .catch(e=>{
        Swal.close();
        Swal.fire('ERROR', e.message,'error');
    });

}

// ======================================================
// GEOLOCATION
// ======================================================
function calculateDistance(lat1, lon1, lat2, lon2){
    const R = 6371000;
    const dLat=(lat2-lat1)*Math.PI/180;
    const dLon=(lon2-lon1)*Math.PI/180;

    const a = Math.sin(dLat/2)**2 +
        Math.cos(lat1*Math.PI/180) *
        Math.cos(lat2*Math.PI/180) *
        Math.sin(dLon/2)**2;

    return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
}

function getLocation(cb){

    if(isGettingLocation) return;

    if(!navigator.geolocation){
        Swal.fire('Browser tidak mendukung GPS','Gunakan Chrome/Edge','error')
        return cb(null);
    }

    isGettingLocation = true;
    Swal.fire({title:'Mendapatkan lokasi...',allowOutsideClick:false,didOpen:()=>Swal.showLoading()});

    navigator.geolocation.getCurrentPosition(
        pos=>{
            Swal.close();
            isGettingLocation=false;

            const loc={
                lat:pos.coords.latitude,
                lng:pos.coords.longitude
            };

            const dist=calculateDistance(
                loc.lat,
                loc.lng,
                lokasiKantor.lat,
                lokasiKantor.lng
            );

            loc.distance=dist;
            loc.inRadius= dist <= lokasiKantor.radius;

            cb(loc);
        },
        err=>{
            Swal.close();
            isGettingLocation=false;
            Swal.fire('GPS Error',err.message,'error');
            cb(null);
        },
        {enableHighAccuracy:true,timeout:30000}
    );
}


// ======================================================
// CHECKIN
// ======================================================
function doCheckIn(){

    if(!validasiRadiusAktif){

        Swal.fire({
            title:'Hadir WFH',
            text:'Tanpa validasi radius',
            showCancelButton:true
        })
        .then(r=>{
            r.isConfirmed && submitCheckIn({lat:0,lng:0});
        });
        return;
    }

    getLocation(loc=>{
        if(!loc) return;

        Swal.fire({
            title:'Konfirmasi Hadir',
            html:`
            Jarak: ${Math.round(loc.distance)} m<br>
            <b style="color:${loc.inRadius?'green':'red'}">
              ${loc.inRadius?'Dalam Radius':'Diluar Radius'}
            </b>
            `,
            showCancelButton:true
        })
        .then(r=>{
            r.isConfirmed && submitCheckIn(loc);
        });
    });

}

function submitCheckIn(loc){

    Swal.fire({title:'Memproses...',allowOutsideClick:false,didOpen:()=>Swal.showLoading()});

    fetchJson('{{ route("karyawan.absensi.checkin") }}',{
        method:'POST',
        headers:{
            'Content-Type':'application/json',
            'X-CSRF-TOKEN':'{{ csrf_token() }}'
        },
        body: JSON.stringify({
            latitude: loc.lat,
            longitude: loc.lng
        })
    })
    .then(r=>{
        Swal.close();
        Swal.fire(r.success?'success':'error', r.message)
            .then(()=> r.success && location.reload());
    })
    .catch(e=>{
        Swal.close();
        Swal.fire('ERROR',e.message,'error');
    });

}


// ======================================================
// CHECK OUT
// ======================================================
function doCheckOut(){

    if(!validasiRadiusAktif){
        Swal.fire({
            title:'Checkout WFH',
            text:'Tanpa GPS',
            showCancelButton:true
        })
        .then(r=>{
            r.isConfirmed && submitCheckOut({lat:0,lng:0});
        });
        return;
    }

    getLocation(loc=>{
        if(!loc)return;

        Swal.fire({
            title:'Konfirmasi Checkout',
            html:`Jarak ${Math.round(loc.distance)}m`,
            showCancelButton:true
        })
        .then(r=>{
            r.isConfirmed && submitCheckOut(loc);
        });
    });

}

function submitCheckOut(loc){

    Swal.fire({title:'Memproses...',allowOutsideClick:false,didOpen:()=>Swal.showLoading()});

    fetchJson('{{ route("karyawan.absensi.checkout") }}',{
        method:'POST',
        headers:{
            'Content-Type':'application/json',
            'X-CSRF-TOKEN':'{{ csrf_token() }}'
        },
        body: JSON.stringify({
            latitude: loc.lat,
            longitude: loc.lng
        })
    })
    .then(r=>{
        Swal.close();
        Swal.fire(r.success?'success':'error', r.message)
            .then(()=> r.success && location.reload());
    })
    .catch(e=>{
        Swal.close();
        Swal.fire('ERROR',e.message,'error');
    });

}
</script>

@endpush
