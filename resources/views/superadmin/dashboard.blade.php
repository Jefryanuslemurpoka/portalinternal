@extends('layouts.app')

@section('title', 'Dashboard Super Admin')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">

    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-teal-500 to-cyan-600 rounded-2xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-2">Selamat Datang, {{ Auth::user()->name }}! 👋</h2>
                <p class="text-teal-50">Kelola sistem absensi dan karyawan dengan mudah</p>
            </div>
            <div class="hidden md:block">
                <i class="fas fa-chart-line text-6xl text-white/20"></i>
            </div>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        
        <!-- Total Karyawan -->
        <x-card 
            title="Total Karyawan" 
            value="{{ $totalKaryawan }}" 
            icon="fas fa-users" 
            color="blue"
            subtitle="{{ $karyawanAktif }} Aktif"
        >
            <x-slot name="footer">
                <a href="{{ route('superadmin.karyawan.index') }}" class="text-sm text-teal-600 hover:text-teal-700 font-medium flex items-center transition">
                    Lihat Detail <i class="fas fa-arrow-right ml-2 text-xs"></i>
                </a>
            </x-slot>
        </x-card>

        <!-- Absensi Hari Ini -->
        <x-card 
            title="Absensi Hari Ini" 
            value="{{ $absensiHariIni }}" 
            icon="fas fa-clipboard-check" 
            color="green"
            subtitle="{{ $belumAbsen }} Belum Absen"
        >
            <x-slot name="footer">
                <a href="{{ route('superadmin.absensi.index') }}" class="text-sm text-cyan-600 hover:text-cyan-700 font-medium flex items-center transition">
                    Lihat Detail <i class="fas fa-arrow-right ml-2 text-xs"></i>
                </a>
            </x-slot>
        </x-card>

        <!-- Pengajuan Pending -->
        <x-card 
            title="Pengajuan Pending" 
            value="{{ $cutiPending }}" 
            icon="fas fa-clock" 
            color="yellow"
            subtitle="Cuti/Izin Menunggu"
        >
            <x-slot name="footer">
                <a href="{{ route('superadmin.cutiizin.index') }}" class="text-sm text-amber-600 hover:text-amber-700 font-medium flex items-center transition">
                    Lihat Detail <i class="fas fa-arrow-right ml-2 text-xs"></i>
                </a>
            </x-slot>
        </x-card>

        <!-- Pengumuman -->
        <x-card 
            title="Total Pengumuman" 
            value="{{ $totalPengumuman }}" 
            icon="fas fa-bullhorn" 
            color="purple"
            subtitle="Pengumuman Aktif"
        >
            <x-slot name="footer">
                <a href="{{ route('superadmin.pengumuman.index') }}" class="text-sm text-teal-600 hover:text-teal-700 font-medium flex items-center transition">
                    Lihat Detail <i class="fas fa-arrow-right ml-2 text-xs"></i>
                </a>
            </x-slot>
        </x-card>

    </div>

    <!-- Grafik Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Grafik Kehadiran 7 Hari Terakhir -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Statistik Kehadiran</h3>
                    <p class="text-sm text-gray-500">7 Hari Terakhir</p>
                </div>
                <div class="w-12 h-12 bg-teal-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-chart-bar text-teal-600 text-xl"></i>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="kehadiranChart"></canvas>
            </div>
        </div>

        <!-- Grafik Karyawan per Divisi -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Karyawan per Divisi</h3>
                    <p class="text-sm text-gray-500">Distribusi Karyawan</p>
                </div>
                <div class="w-12 h-12 bg-cyan-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-chart-pie text-cyan-600 text-xl"></i>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="divisiChart"></canvas>
            </div>
        </div>

    </div>

    <!-- Tabel Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Pengajuan Cuti/Izin Terbaru -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-amber-500 to-orange-500 px-6 py-4">
                <h3 class="text-lg font-bold text-white">Pengajuan Cuti/Izin Pending</h3>
            </div>
            <div class="p-6">
                @if($cutiTerbaru->count() > 0)
                    <div class="space-y-4">
                        @foreach($cutiTerbaru as $cuti)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-teal-50 transition">
                            <div class="flex items-center space-x-3">
                                <img src="{{ $cuti->user->foto ? asset('storage/' . $cuti->user->foto) : 'https://ui-avatars.com/api/?name=' . urlencode($cuti->user->name) . '&background=14b8a6&color=fff' }}" 
                                     class="w-10 h-10 rounded-full object-cover border-2 border-teal-200">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $cuti->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ ucfirst($cuti->jenis) }} - {{ $cuti->tanggal_mulai->format('d M Y') }}</p>
                                </div>
                            </div>
                            <a href="{{ route('superadmin.cutiizin.index') }}" class="text-teal-600 hover:text-teal-700 transition">
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state py-8">
                        <i class="fas fa-inbox"></i>
                        <p>Tidak ada pengajuan pending</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Karyawan Terbaru -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-teal-500 to-cyan-600 px-6 py-4">
                <h3 class="text-lg font-bold text-white">Karyawan Terbaru</h3>
            </div>
            <div class="p-6">
                @if($karyawanTerbaru->count() > 0)
                    <div class="space-y-4">
                        @foreach($karyawanTerbaru as $karyawan)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-teal-50 transition">
                            <div class="flex items-center space-x-3">
                                <img src="{{ $karyawan->foto ? asset('storage/' . $karyawan->foto) : 'https://ui-avatars.com/api/?name=' . urlencode($karyawan->name) . '&background=14b8a6&color=fff' }}" 
                                     class="w-10 h-10 rounded-full object-cover border-2 border-teal-200">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $karyawan->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $karyawan->divisi }} - {{ $karyawan->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <span class="badge badge-{{ $karyawan->status == 'aktif' ? 'success' : 'danger' }}">
                                {{ ucfirst($karyawan->status) }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state py-8">
                        <i class="fas fa-users"></i>
                        <p>Belum ada karyawan</p>
                    </div>
                @endif
            </div>
        </div>

    </div>

    <!-- Pengumuman Terbaru -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-teal-500 to-cyan-600 px-6 py-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-white">Pengumuman Terbaru</h3>
                <a href="{{ route('superadmin.pengumuman.index') }}" class="text-sm text-white hover:text-gray-100 transition">
                    Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
        <div class="p-6">
            @if($pengumumanTerbaru->count() > 0)
                <div class="space-y-4">
                    @foreach($pengumumanTerbaru as $pengumuman)
                    <div class="border-l-4 border-teal-500 pl-4 py-2 hover:bg-teal-50 transition rounded-r-lg">
                        <h4 class="font-semibold text-gray-800 mb-1">{{ $pengumuman->judul }}</h4>
                        <p class="text-sm text-gray-600 mb-2">{{ Str::limit($pengumuman->konten, 150) }}</p>
                        <div class="flex items-center text-xs text-gray-500">
                            <i class="fas fa-calendar mr-2 text-teal-500"></i>
                            <span>{{ $pengumuman->tanggal->format('d M Y') }}</span>
                            <span class="mx-2">•</span>
                            <i class="fas fa-user mr-2 text-teal-500"></i>
                            <span>{{ $pengumuman->creator->name }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state py-8">
                    <i class="fas fa-bullhorn"></i>
                    <p>Belum ada pengumuman</p>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    // Grafik Kehadiran 7 Hari Terakhir
    const ctxKehadiran = document.getElementById('kehadiranChart').getContext('2d');
    const kehadiranChart = new Chart(ctxKehadiran, {
        type: 'line',
        data: {
            labels: @json($labels),
            datasets: [
                {
                    label: 'Hadir',
                    data: @json($dataHadir),
                    borderColor: 'rgb(20, 184, 166)',
                    backgroundColor: 'rgba(20, 184, 166, 0.1)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Tidak Hadir',
                    data: @json($dataTidakHadir),
                    borderColor: 'rgb(239, 68, 68)',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Grafik Karyawan per Divisi
    const ctxDivisi = document.getElementById('divisiChart').getContext('2d');
    const divisiChart = new Chart(ctxDivisi, {
        type: 'doughnut',
        data: {
            labels: @json($divisiLabels),
            datasets: [{
                data: @json($divisiValues),
                backgroundColor: [
                    'rgb(20, 184, 166)',
                    'rgb(6, 182, 212)',
                    'rgb(34, 197, 94)',
                    'rgb(251, 146, 60)',
                    'rgb(239, 68, 68)',
                    'rgb(236, 72, 153)'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
</script>
@endpush