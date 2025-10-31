@extends('layouts.app')

@section('title', 'Dashboard Super Admin')
@section('page-title', 'Dashboard')

@section('content')
<div class="space-y-6">

    <!-- Welcome Section -->
    <div class="bg-gradient-to-br from-teal-500 to-cyan-500 rounded-2xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-2">Selamat Datang, {{ Auth::user()->name }}! 👋</h2>
                <p class="text-white/80">Kelola sistem absensi dan karyawan dengan mudah</p>
            </div>
            <div class="hidden md:block">
                <i class="fas fa-chart-line text-6xl text-white/20"></i>
            </div>
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

        <!-- Total Karyawan -->
        <div class="bg-gradient-to-br from-teal-500 to-cyan-500 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-white/80 mb-1">Total Karyawan</p>
                    <h3 class="text-3xl font-bold">{{ $totalKaryawan }}</h3>
                    <p class="text-xs text-white/70 mt-1">{{ $karyawanAktif }} Aktif</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-white/20">
                <a href="{{ route('superadmin.karyawan.index') }}" class="text-sm text-white hover:text-gray-100 font-medium flex items-center transition">
                    Lihat Detail <i class="fas fa-arrow-right ml-2 text-xs"></i>
                </a>
            </div>
        </div>

        <!-- Absensi Hari Ini -->
        <div class="bg-gradient-to-br from-cyan-500 to-blue-500 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-white/80 mb-1">Absensi Hari Ini</p>
                    <h3 class="text-3xl font-bold">{{ $absensiHariIni }}</h3>
                    <p class="text-xs text-white/70 mt-1">{{ $belumAbsen }} Belum Absen</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-clipboard-check text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-white/20">
                <a href="{{ route('superadmin.absensi.index') }}" class="text-sm text-white hover:text-gray-100 font-medium flex items-center transition">
                    Lihat Detail <i class="fas fa-arrow-right ml-2 text-xs"></i>
                </a>
            </div>
        </div>

        <!-- Pengajuan Pending -->
        <div class="bg-gradient-to-br from-amber-500 to-orange-500 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-white/80 mb-1">Pengajuan Pending</p>
                    <h3 class="text-3xl font-bold">{{ $cutiPending }}</h3>
                    <p class="text-xs text-white/70 mt-1">Cuti/Izin Menunggu</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-clock text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-white/20">
                <a href="{{ route('superadmin.cutiizin.index') }}" class="text-sm text-white hover:text-gray-100 font-medium flex items-center transition">
                    Lihat Detail <i class="fas fa-arrow-right ml-2 text-xs"></i>
                </a>
            </div>
        </div>

        <!-- Pengumuman -->
        <div class="bg-gradient-to-br from-teal-500 to-emerald-500 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-white/80 mb-1">Total Pengumuman</p>
                    <h3 class="text-3xl font-bold">{{ $totalPengumuman }}</h3>
                    <p class="text-xs text-white/70 mt-1">Pengumuman Aktif</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-bullhorn text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-white/20">
                <a href="{{ route('superadmin.pengumuman.index') }}" class="text-sm text-white hover:text-gray-100 font-medium flex items-center transition">
                    Lihat Detail <i class="fas fa-arrow-right ml-2 text-xs"></i>
                </a>
            </div>
        </div>

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
                <div class="w-12 h-12 bg-teal-50 rounded-full flex items-center justify-center">
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
                <div class="w-12 h-12 bg-teal-50 rounded-full flex items-center justify-center">
                    <i class="fas fa-chart-pie text-teal-600 text-xl"></i>
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
            <div class="bg-gradient-to-r from-teal-500 to-cyan-500 px-6 py-4">
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
                    <div class="empty-state py-8 text-center text-gray-500">
                        <i class="fas fa-inbox text-2xl mb-2"></i>
                        <p>Tidak ada pengajuan pending</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Karyawan Terbaru -->
        <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-teal-500 to-cyan-500 px-6 py-4">
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
                            <span class="px-3 py-1 text-xs rounded-full font-semibold {{ $karyawan->status == 'aktif' ? 'bg-teal-100 text-teal-700' : 'bg-red-100 text-red-600' }}">
                                {{ ucfirst($karyawan->status) }}
                            </span>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-state py-8 text-center text-gray-500">
                        <i class="fas fa-users text-2xl mb-2"></i>
                        <p>Belum ada karyawan</p>
                    </div>
                @endif
            </div>
        </div>

    </div>

    <!-- Pengumuman Terbaru -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="bg-gradient-to-r from-teal-500 to-cyan-500 px-6 py-4">
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
                            <i class="fas fa-calendar mr-2 text-teal-600"></i>
                            <span>{{ $pengumuman->tanggal->format('d M Y') }}</span>
                            <span class="mx-2">•</span>
                            <i class="fas fa-user mr-2 text-teal-600"></i>
                            <span>{{ $pengumuman->creator->name }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state py-8 text-center text-gray-500">
                    <i class="fas fa-bullhorn text-2xl mb-2"></i>
                    <p>Belum ada pengumuman</p>
                </div>
            @endif
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    const ctxKehadiran = document.getElementById('kehadiranChart').getContext('2d');
    const kehadiranChart = new Chart(ctxKehadiran, {
        type: 'line',
        data: {
            labels: @json($labels),
            datasets: [
                {
                    label: 'Hadir',
                    data: @json($dataHadir),
                    borderColor: '#14b8a6',
                    backgroundColor: 'rgba(20, 184, 166, 0.15)',
                    tension: 0.4,
                    fill: true
                },
                {
                    label: 'Tidak Hadir',
                    data: @json($dataTidakHadir),
                    borderColor: '#ef4444',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    tension: 0.4,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom' } },
            scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
        }
    });

    const ctxDivisi = document.getElementById('divisiChart').getContext('2d');
    const divisiChart = new Chart(ctxDivisi, {
        type: 'doughnut',
        data: {
            labels: @json($divisiLabels),
            datasets: [{
                data: @json($divisiValues),
                backgroundColor: [
                    '#14b8a6',  // Teal
                    '#06b6d4',  // Cyan
                    '#3b82f6',  // Blue
                    '#f59e0b',  // Amber
                    '#10b981',  // Emerald
                    '#8b5cf6'   // Violet
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom' } } }
    });
</script>
@endpush