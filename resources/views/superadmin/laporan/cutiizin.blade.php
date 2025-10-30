@extends('layouts.app')

@section('title', 'Laporan Cuti/Izin')
@section('page-title', 'Laporan Cuti/Izin')

@section('content')
<div class="space-y-4 sm:space-y-6">

    <!-- Header & Action Buttons -->
    <div class="bg-white rounded-lg sm:rounded-xl shadow-lg p-4 sm:p-6 no-print">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-800 mb-1 sm:mb-2">
                    <i class="fas fa-calendar-check text-teal-600 mr-2"></i>
                    Laporan Cuti/Izin Karyawan
                </h1>
                <p class="text-xs sm:text-sm text-gray-600">
                    Periode: <span class="font-semibold">{{ \Carbon\Carbon::parse($tanggalMulai)->format('d M Y') }}</span> 
                    s/d <span class="font-semibold">{{ \Carbon\Carbon::parse($tanggalSelesai)->format('d M Y') }}</span>
                    @if($status)
                        | Status: <span class="font-semibold">{{ ucfirst($status) }}</span>
                    @endif
                </p>
            </div>
            <div class="flex flex-wrap gap-2 w-full sm:w-auto">
                <form action="{{ route('superadmin.laporan.cutiizin.pdf') }}" method="GET" class="inline">
                    <input type="hidden" name="tanggal_mulai" value="{{ $tanggalMulai }}">
                    <input type="hidden" name="tanggal_selesai" value="{{ $tanggalSelesai }}">
                    @if($status)
                    <input type="hidden" name="status" value="{{ $status }}">
                    @endif
                    <button type="submit" class="flex-1 sm:flex-none bg-gradient-to-r from-teal-500 to-cyan-600 hover:from-teal-600 hover:to-cyan-700 text-white px-3 sm:px-4 py-2 rounded-lg font-semibold transition text-xs sm:text-sm shadow-md">
                        <i class="fas fa-file-pdf mr-1 sm:mr-2"></i>Export PDF
                    </button>
                </form>
                <form action="{{ route('superadmin.laporan.cutiizin.excel') }}" method="GET" class="inline">
                    <input type="hidden" name="tanggal_mulai" value="{{ $tanggalMulai }}">
                    <input type="hidden" name="tanggal_selesai" value="{{ $tanggalSelesai }}">
                    @if($status)
                    <input type="hidden" name="status" value="{{ $status }}">
                    @endif
                    <button type="submit" class="flex-1 sm:flex-none bg-gradient-to-r from-teal-500 to-cyan-600 hover:from-teal-600 hover:to-cyan-700 text-white px-3 sm:px-4 py-2 rounded-lg font-semibold transition text-xs sm:text-sm shadow-md">
                        <i class="fas fa-file-excel mr-1 sm:mr-2"></i>Export Excel
                    </button>
                </form>
                <a href="{{ route('superadmin.laporan.index') }}" class="flex-1 sm:flex-none bg-gray-500 hover:bg-gray-600 text-white px-3 sm:px-4 py-2 rounded-lg font-semibold transition text-xs sm:text-sm text-center">
                    <i class="fas fa-arrow-left mr-1 sm:mr-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Print Header -->
    <div class="hidden print:block mb-6">
        <div class="text-center border-b-2 border-gray-800 pb-4 mb-4">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">LAPORAN CUTI/IZIN KARYAWAN</h1>
            <p class="text-sm text-gray-600">
                Periode: {{ \Carbon\Carbon::parse($tanggalMulai)->format('d M Y') }} s/d {{ \Carbon\Carbon::parse($tanggalSelesai)->format('d M Y') }}
                @if($status) | Status: {{ ucfirst($status) }} @endif
            </p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <div class="bg-gradient-to-br from-teal-500 to-cyan-600 rounded-lg sm:rounded-xl p-3 sm:p-4 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs opacity-90 mb-1">Total Pengajuan</p>
                    <h3 class="text-xl sm:text-2xl font-bold">{{ $cutiIzin->count() }}</h3>
                </div>
                <i class="fas fa-calendar-alt text-2xl sm:text-3xl opacity-20"></i>
            </div>
        </div>
        <div class="bg-gradient-to-br from-yellow-500 to-orange-500 rounded-lg sm:rounded-xl p-3 sm:p-4 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs opacity-90 mb-1">Pending</p>
                    <h3 class="text-xl sm:text-2xl font-bold">{{ $cutiIzin->where('status', 'pending')->count() }}</h3>
                </div>
                <i class="fas fa-clock text-2xl sm:text-3xl opacity-20"></i>
            </div>
        </div>
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg sm:rounded-xl p-3 sm:p-4 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs opacity-90 mb-1">Disetujui</p>
                    <h3 class="text-xl sm:text-2xl font-bold">{{ $cutiIzin->where('status', 'disetujui')->count() }}</h3>
                </div>
                <i class="fas fa-check-circle text-2xl sm:text-3xl opacity-20"></i>
            </div>
        </div>
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-lg sm:rounded-xl p-3 sm:p-4 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs opacity-90 mb-1">Ditolak</p>
                    <h3 class="text-xl sm:text-2xl font-bold">{{ $cutiIzin->where('status', 'ditolak')->count() }}</h3>
                </div>
                <i class="fas fa-times-circle text-2xl sm:text-3xl opacity-20"></i>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg sm:rounded-xl shadow-lg overflow-hidden">
        
        <!-- Mobile Card View -->
        <div class="block lg:hidden">
            @forelse($cutiIzin as $key => $item)
            <div class="border-b border-gray-200 p-4 hover:bg-gray-50">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <p class="font-bold text-gray-900 text-sm mb-1">{{ $item->user->name }}</p>
                        <p class="text-xs text-gray-600">{{ $item->user->divisi }} - {{ $item->user->jabatan }}</p>
                    </div>
                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                        @if($item->status == 'disetujui') bg-green-100 text-green-700
                        @elseif($item->status == 'pending') bg-yellow-100 text-yellow-700
                        @else bg-red-100 text-red-700
                        @endif">
                        {{ ucfirst($item->status) }}
                    </span>
                </div>
                <div class="space-y-1 text-xs">
                    <p><span class="text-gray-600">Jenis:</span> <span class="font-semibold">{{ ucfirst($item->jenis) }}</span></p>
                    <p><span class="text-gray-600">Tanggal:</span> <span class="font-semibold">{{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') }} - {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d M Y') }}</span></p>
                    <p><span class="text-gray-600">Durasi:</span> <span class="font-semibold">{{ $item->durasi }} hari</span></p>
                    @if($item->keterangan)
                    <p><span class="text-gray-600">Keterangan:</span> <span class="text-gray-800">{{ Str::limit($item->keterangan, 50) }}</span></p>
                    @endif
                </div>
            </div>
            @empty
            <div class="p-8 text-center">
                <i class="fas fa-inbox text-4xl text-gray-400 mb-3"></i>
                <p class="text-gray-600">Tidak ada data cuti/izin</p>
            </div>
            @endforelse
        </div>

        <!-- Desktop Table View -->
        <div class="hidden lg:block overflow-x-auto">
            <table class="w-full" id="laporanTable">
                <thead class="bg-gradient-to-r from-teal-500 to-cyan-600 text-white">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">No</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Nama Karyawan</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Divisi</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Jenis</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Tanggal Mulai</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Tanggal Selesai</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Durasi</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($cutiIzin as $key => $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm">{{ $key + 1 }}</td>
                        <td class="px-4 py-3 text-sm font-semibold text-gray-900">{{ $item->user->name }}</td>
                        <td class="px-4 py-3 text-sm">{{ $item->user->divisi }}</td>
                        <td class="px-4 py-3 text-sm">
                            <span class="px-2 py-1 bg-cyan-100 text-cyan-700 rounded text-xs font-semibold">
                                {{ ucfirst($item->jenis) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">{{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-sm">{{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-sm font-semibold">{{ $item->durasi }} hari</td>
                        <td class="px-4 py-3">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                @if($item->status == 'disetujui') bg-green-100 text-green-700
                                @elseif($item->status == 'pending') bg-yellow-100 text-yellow-700
                                @else bg-red-100 text-red-700
                                @endif">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-sm">{{ Str::limit($item->keterangan, 40) ?? '-' }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-4 py-8 text-center text-gray-600">
                            <i class="fas fa-inbox text-4xl mb-3 block"></i>
                            Tidak ada data cuti/izin
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Print Footer -->
    <div class="hidden print:block mt-8 text-sm">
        <div class="flex justify-between">
            <div></div>
            <div class="text-center">
                <p class="mb-16">Mengetahui,</p>
                <p class="font-bold border-t-2 border-gray-800 pt-1 inline-block px-8">HRD Manager</p>
            </div>
        </div>
    </div>

</div>
@endsection

@push('styles')
<style>
    @media print {
        .no-print, .sidebar, .navbar, nav, header, footer { 
            display: none !important; 
        }
        body { 
            print-color-adjust: exact; 
            -webkit-print-color-adjust: exact; 
        }
        .container, .main-content {
            margin: 0 !important;
            padding: 0 !important;
            max-width: 100% !important;
        }
    }
</style>
@endpush