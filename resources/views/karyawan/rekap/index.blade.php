@extends('layouts.app')

@section('title', 'Rekap Absensi')
@section('page-title', 'Rekap Absensi')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="bg-gradient-to-r from-teal-500 to-cyan-500 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-2">
                    <i class="fas fa-chart-bar mr-2"></i>Rekap Absensi
                </h2>
                <p class="text-white/80">Riwayat kehadiran Anda</p>
            </div>
            <div class="hidden md:block">
                <i class="fas fa-calendar-alt text-6xl text-white/20"></i>
            </div>
        </div>
    </div>

    <!-- Filter Periode -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
            <h3 class="text-lg font-bold text-gray-800">
                <i class="fas fa-filter mr-2"></i>Filter Periode
            </h3>
            <div class="flex space-x-2">
                <a href="{{ route('karyawan.rekap.index', ['periode' => 'harian']) }}" 
                   class="px-4 py-2 rounded-lg font-medium transition {{ $periode == 'harian' ? 'bg-teal-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    <i class="fas fa-calendar-day mr-2"></i>Harian
                </a>
                <a href="{{ route('karyawan.rekap.index', ['periode' => 'mingguan']) }}" 
                   class="px-4 py-2 rounded-lg font-medium transition {{ $periode == 'mingguan' ? 'bg-teal-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    <i class="fas fa-calendar-week mr-2"></i>Mingguan
                </a>
                <a href="{{ route('karyawan.rekap.index', ['periode' => 'bulanan']) }}" 
                   class="px-4 py-2 rounded-lg font-medium transition {{ $periode == 'bulanan' ? 'bg-teal-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300' }}">
                    <i class="fas fa-calendar-alt mr-2"></i>Bulanan
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <div class="bg-gradient-to-br from-teal-500 to-cyan-500 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-white/80 mb-1">Total Hadir</p>
                    <h3 class="text-3xl font-bold">{{ $totalHadir }}</h3>
                    <p class="text-xs text-white/70 mt-1">
                        @if($periode == 'harian')
                            Bulan Ini
                        @elseif($periode == 'mingguan')
                            4 Minggu Terakhir
                        @else
                            6 Bulan Terakhir
                        @endif
                    </p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-calendar-check text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-white/80 mb-1">Tepat Waktu</p>
                    <h3 class="text-3xl font-bold">{{ $tepatWaktu }}</h3>
                    <p class="text-xs text-white/70 mt-1">≤ {{ $jamMasuk }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-white/80 mb-1">Terlambat</p>
                    <h3 class="text-3xl font-bold">{{ $terlambat }}</h3>
                    <p class="text-xs text-white/70 mt-1">> {{ $jamMasuk }}</p>
                </div>
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation-circle text-2xl"></i>
                </div>
            </div>
        </div>

    </div>

    <!-- Tabel Rekap -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-800">
                    <i class="fas fa-list mr-2"></i>Detail Rekap
                    @if($periode == 'harian')
                        Harian
                    @elseif($periode == 'mingguan')
                        Mingguan
                    @else
                        Bulanan
                    @endif
                </h3>
                <p class="text-sm text-gray-600">
                    {{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}
                </p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">No</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Tanggal</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Hari</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Jam Masuk</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Jam Keluar</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Durasi</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-600 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($absensi as $key => $item)
                    @php
                        // ✅ Handle data dari collection (bisa Absensi model atau array)
                        $a = is_array($item) ? (object) $item : $item;
                        $tanggal = $a->tanggal instanceof \Carbon\Carbon ? $a->tanggal : \Carbon\Carbon::parse($a->tanggal);
                        $status = $a->status ?? 'hadir';
                        $jamMasukKaryawan = $a->jam_masuk ?? null;
                        $jamKeluarKaryawan = $a->jam_keluar ?? null;
                    @endphp
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $key + 1 }}
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-semibold text-gray-900">{{ $tanggal->format('d M Y') }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-600">{{ $tanggal->format('l') }}</p>
                        </td>
                        <td class="px-6 py-4">
                            @if($jamMasukKaryawan)
                                @php
                                    $batasJam = \Carbon\Carbon::createFromFormat('H:i', $jamMasuk)->format('H:i:s');
                                @endphp
                                <p class="text-sm font-semibold {{ strtotime($jamMasukKaryawan) <= strtotime($batasJam) ? 'text-green-600' : 'text-red-600' }}">
                                    {{ date('H:i', strtotime($jamMasukKaryawan)) }}
                                </p>
                            @else
                                <span class="text-sm text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($jamKeluarKaryawan)
                                <p class="text-sm font-semibold text-teal-600">
                                    {{ date('H:i', strtotime($jamKeluarKaryawan)) }}
                                </p>
                            @else
                                <span class="text-sm text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($jamMasukKaryawan && $jamKeluarKaryawan)
                                @php
                                    $jamMasukTime = \Carbon\Carbon::parse($jamMasukKaryawan);
                                    $jamKeluarTime = \Carbon\Carbon::parse($jamKeluarKaryawan);
                                    $durasi = $jamMasukTime->diff($jamKeluarTime);
                                @endphp
                                <p class="text-sm text-gray-700">
                                    {{ $durasi->h }}j {{ $durasi->i }}m
                                </p>
                            @else
                                <span class="text-sm text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($tanggal->dayOfWeek == 0 || $status == 'libur')
                                <!-- Minggu / Libur -->
                                <span class="badge badge-danger">
                                    Libur
                                </span>
                            @elseif($status == 'cuti')
                                <!-- Cuti -->
                                <span class="badge badge-secondary">
                                    Cuti
                                </span>
                            @elseif($status == 'izin')
                                <!-- Izin -->
                                <span class="badge badge-warning">
                                    Izin
                                </span>
                            @else
                                <!-- Hadir Normal -->
                                @if($jamMasukKaryawan)
                                    @php
                                        $batasJamMasuk = \Carbon\Carbon::createFromFormat('H:i', $jamMasuk)->format('H:i:s');
                                    @endphp
                                    @if(strtotime($jamMasukKaryawan) <= strtotime($batasJamMasuk))
                                        <span class="badge badge-success">
                                            <i class="fas fa-check mr-1"></i>Tepat Waktu
                                        </span>
                                    @else
                                        <span class="badge badge-danger">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>Terlambat
                                        </span>
                                    @endif
                                @else
                                    <span class="text-sm text-gray-400">-</span>
                                @endif
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="empty-state">
                                <i class="fas fa-calendar-times"></i>
                                <p>Tidak ada data absensi</p>
                                <p class="text-sm text-gray-500 mt-2">Untuk periode yang dipilih</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>

    <!-- Summary Info -->
    <div class="bg-teal-50 border border-teal-200 rounded-lg p-6">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-teal-600 text-xl mt-1 mr-3"></i>
            <div>
                <h4 class="text-sm font-bold text-teal-900 mb-2">Keterangan</h4>
                <ul class="text-sm text-teal-800 space-y-1">
                    <li class="flex items-center">
                        <span class="badge badge-success mr-2">Tepat Waktu</span>
                        Check-in sebelum atau pada jam {{ $jamMasuk }}
                    </li>
                    <li class="flex items-center">
                        <span class="badge badge-danger mr-2">Terlambat</span>
                        Check-in setelah jam {{ $jamMasuk }}
                    </li>
                    <li class="flex items-center">
                        <span class="badge badge-secondary mr-2">Cuti</span>
                        Sedang mengambil cuti
                    </li>
                    <li class="flex items-center">
                        <span class="badge badge-warning mr-2">Izin</span>
                        Sedang izin tidak masuk
                    </li>
                    <li class="flex items-center">
                        <span class="badge badge-danger mr-2">Libur</span>
                        Hari Minggu / Hari Libur
                    </li>
                </ul>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    // Add any custom JavaScript if needed
</script>
@endpush