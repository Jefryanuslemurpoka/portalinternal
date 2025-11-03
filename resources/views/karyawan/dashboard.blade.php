@extends('layouts.app')

@section('title', 'Dashboard Karyawan')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">

    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-teal-500 to-cyan-600 rounded-2xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-2">Selamat Datang, {{ Auth::user()->name }}! 👋</h2>
                <p class="text-teal-50">{{ Auth::user()->divisi }} - {{ now()->format('l, d F Y') }}</p>
            </div>
        </div>
    </div>

    <!-- Status Kehadiran Hari Ini -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">
            <i class="fas fa-clock mr-2 text-teal-600"></i>Status Kehadiran Hari Ini
        </h3>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            
            <!-- Status Check-in -->
            <div class="bg-gradient-to-br {{ $sudahCheckIn ? 'from-teal-50 to-teal-100 border-teal-200' : 'from-gray-50 to-gray-100 border-gray-200' }} border rounded-xl p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-semibold text-gray-700">Check-in</span>
                    @if($sudahCheckIn)
                        <i class="fas fa-check-circle text-teal-600 text-xl"></i>
                    @else
                        <i class="fas fa-times-circle text-gray-400 text-xl"></i>
                    @endif
                </div>
                @if($sudahCheckIn)
                    <p class="text-2xl font-bold text-teal-600">{{ date('H:i', strtotime($absensiHariIni->jam_masuk)) }}</p>
                    <p class="text-xs text-gray-600 mt-1">
                        @php
                            // ✅ Hitung batas keterlambatan dengan toleransi
                            $batasKeterlambatan = \Carbon\Carbon::createFromFormat('H:i', $jamMasuk)
                                ->addMinutes($toleransi)
                                ->format('H:i:s');
                        @endphp
                        @if(strtotime($absensiHariIni->jam_masuk) <= strtotime($batasKeterlambatan))
                            <span class="text-teal-600">✓ Tepat Waktu</span>
                        @else
                            <span class="text-red-600">⚠ Terlambat</span>
                        @endif
                    </p>
                @else
                    <p class="text-lg font-semibold text-gray-400">Belum Check-in</p>
                    <p class="text-xs text-gray-500 mt-1">Jam Masuk: {{ $jamMasuk }} (Toleransi: {{ $toleransi }} mnt)</p>
                @endif
            </div>

            <!-- Status Check-out -->
            <div class="bg-gradient-to-br {{ $sudahCheckOut ? 'from-cyan-50 to-cyan-100 border-cyan-200' : 'from-gray-50 to-gray-100 border-gray-200' }} border rounded-xl p-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-semibold text-gray-700">Check-out</span>
                    @if($sudahCheckOut)
                        <i class="fas fa-check-circle text-cyan-600 text-xl"></i>
                    @else
                        <i class="fas fa-times-circle text-gray-400 text-xl"></i>
                    @endif
                </div>
                @if($sudahCheckOut)
                    <p class="text-2xl font-bold text-cyan-600">{{ date('H:i', strtotime($absensiHariIni->jam_keluar)) }}</p>
                    <p class="text-xs text-gray-600 mt-1">✓ Sudah Check-out</p>
                @else
                    <p class="text-lg font-semibold text-gray-400">Belum Check-out</p>
                    <p class="text-xs text-gray-500 mt-1">Jam Keluar: {{ $jamKeluar }}</p>
                @endif
            </div>

            <!-- Quick Action -->
            <div class="bg-gradient-to-br from-teal-50 to-cyan-100 border border-teal-200 rounded-xl p-4 flex items-center justify-center hover:shadow-md transition">
                <a href="{{ route('karyawan.absensi.index') }}" class="text-center">
                    <div class="w-12 h-12 bg-gradient-to-r from-teal-500 to-cyan-600 rounded-full flex items-center justify-center mx-auto mb-2 shadow-md">
                        <i class="fas fa-clipboard-check text-white text-xl"></i>
                    </div>
                    <p class="text-sm font-semibold text-teal-900">Absensi Sekarang</p>
                </a>
            </div>

        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        
        <!-- Total Hadir Bulan Ini -->
        <div class="bg-gradient-to-br from-teal-500 to-teal-600 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-white/80 mb-1">Hadir Bulan Ini</p>
                    <h3 class="text-3xl font-bold">{{ $absensiCount }}</h3>
                    <p class="text-xs text-white/70 mt-1">Hari</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-calendar-check text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Tepat Waktu -->
        <div class="bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-white/80 mb-1">Tepat Waktu</p>
                    <h3 class="text-3xl font-bold">{{ $tepatWaktu }}</h3>
                    <p class="text-xs text-white/70 mt-1">Hari</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Terlambat -->
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-white/80 mb-1">Terlambat</p>
                    <h3 class="text-3xl font-bold">{{ $terlambat }}</h3>
                    <p class="text-xs text-white/70 mt-1">Hari</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation-circle text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Cuti Pending -->
        <div class="bg-gradient-to-br from-amber-500 to-orange-500 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-white/80 mb-1">Cuti Pending</p>
                    <h3 class="text-3xl font-bold">{{ $cutiPending }}</h3>
                    <p class="text-xs text-white/70 mt-1">Pengajuan</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-hourglass-half text-2xl"></i>
                </div>
            </div>
        </div>

    </div>

    <!-- Grafik & Riwayat -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Mini Grafik Absensi 7 Hari -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Grafik Kehadiran</h3>
                    <p class="text-sm text-gray-500">7 Hari Terakhir</p>
                </div>
                <div class="w-10 h-10 bg-teal-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-chart-line text-teal-600"></i>
                </div>
            </div>
            <div style="height: 200px;">
                <canvas id="miniAbsensiChart"></canvas>
            </div>
        </div>

        <!-- Riwayat Absensi Terbaru -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Riwayat Absensi</h3>
                    <p class="text-sm text-gray-500">5 Terakhir</p>
                </div>
                <a href="{{ route('karyawan.rekap.index') }}" class="text-sm text-teal-600 hover:text-teal-700 font-medium transition">
                    Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
            <div class="space-y-3">
                @forelse($riwayatAbsensi as $r)
                @php
                    // ✅ Hitung batas keterlambatan untuk setiap riwayat
                    $batasKeterlambatanRiwayat = \Carbon\Carbon::createFromFormat('H:i', $jamMasuk)
                        ->addMinutes($toleransi)
                        ->format('H:i:s');
                @endphp
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl hover:bg-teal-50 transition">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-teal-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-calendar text-teal-600"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-900">{{ $r->tanggal->format('d M Y') }}</p>
                            <p class="text-xs text-gray-500">{{ $r->tanggal->format('l') }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold {{ strtotime($r->jam_masuk) <= strtotime($batasKeterlambatanRiwayat) ? 'text-teal-600' : 'text-red-600' }}">
                            {{ date('H:i', strtotime($r->jam_masuk)) }}
                        </p>
                        <p class="text-xs text-gray-500">
                            {{ $r->jam_keluar ? date('H:i', strtotime($r->jam_keluar)) : '-' }}
                        </p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-400">
                    <i class="fas fa-inbox text-4xl mb-2"></i>
                    <p class="text-sm">Belum ada riwayat absensi</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- Pengumuman Terbaru -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-teal-500 to-cyan-600 px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-bullhorn text-white text-lg"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-white">Pengumuman Terbaru</h3>
                        <p class="text-xs text-teal-50">Update & Informasi Penting</p>
                    </div>
                </div>
                <a href="{{ route('karyawan.pengumuman.index') }}" class="text-sm text-white hover:text-gray-100 font-medium transition">
                    Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>

        <div class="divide-y divide-gray-100">
            @forelse($pengumuman as $p)
            <div class="p-6 hover:bg-teal-50 transition">
                <div class="flex items-start space-x-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-teal-500 to-cyan-600 rounded-full flex items-center justify-center text-white flex-shrink-0 shadow-md">
                        <i class="fas fa-bullhorn"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-gray-900 mb-1">{{ $p->judul }}</h4>
                        <p class="text-sm text-gray-600 mb-2">{{ Str::limit($p->konten, 120) }}</p>
                        <div class="flex items-center text-xs text-gray-500">
                            <i class="fas fa-calendar mr-1 text-teal-500"></i>
                            <span>{{ $p->tanggal->format('d M Y') }}</span>
                            <span class="mx-2">•</span>
                            <i class="fas fa-user mr-1 text-teal-500"></i>
                            <span>{{ $p->creator->name }}</span>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-12 text-center text-gray-400">
                <i class="fas fa-bullhorn text-4xl mb-2"></i>
                <p class="text-sm">Belum ada pengumuman</p>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        
        <a href="{{ route('karyawan.absensi.index') }}" class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition text-center">
            <div class="w-12 h-12 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-clipboard-check text-teal-600 text-xl"></i>
            </div>
            <p class="font-semibold text-gray-900">Absensi</p>
        </a>

        <a href="{{ route('karyawan.cutiizin.index') }}" class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition text-center">
            <div class="w-12 h-12 bg-cyan-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-calendar-alt text-cyan-600 text-xl"></i>
            </div>
            <p class="font-semibold text-gray-900">Cuti/Izin</p>
        </a>

        <a href="{{ route('karyawan.rekap.index') }}" class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition text-center">
            <div class="w-12 h-12 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-chart-bar text-teal-600 text-xl"></i>
            </div>
            <p class="font-semibold text-gray-900">Rekap</p>
        </a>

        <a href="{{ route('karyawan.profil.index') }}" class="bg-white rounded-2xl shadow-lg p-6 hover:shadow-xl transition text-center">
            <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-user text-amber-600 text-xl"></i>
            </div>
            <p class="font-semibold text-gray-900">Profil</p>
        </a>

    </div>

</div>
@endsection

@push('scripts')
<script>
    // Mini Grafik Absensi 7 Hari
    const ctx = document.getElementById('miniAbsensiChart').getContext('2d');
    const miniAbsensiChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($labels),
            datasets: [{
                label: 'Kehadiran',
                data: @json($dataAbsensi),
                backgroundColor: 'rgba(20, 184, 166, 0.8)',
                borderColor: 'rgb(20, 184, 166)',
                borderWidth: 2,
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 1,
                    ticks: {
                        stepSize: 1,
                        callback: function(value) {
                            return value === 1 ? 'Hadir' : 'Tidak';
                        }
                    }
                }
            }
        }
    });
</script>
@endpush