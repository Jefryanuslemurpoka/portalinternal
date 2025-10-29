@extends('layouts.app')

@section('title', 'Laporan Karyawan')
@section('page-title', 'Laporan Karyawan')

@section('content')
<div class="space-y-4 sm:space-y-6">

    <!-- Header & Action Buttons -->
    <div class="bg-white rounded-lg sm:rounded-xl shadow-lg p-4 sm:p-6 no-print">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl font-bold text-gray-800 mb-1 sm:mb-2">
                    <i class="fas fa-users text-teal-600 mr-2"></i>
                    Laporan Data Karyawan
                </h1>
                <p class="text-xs sm:text-sm text-gray-600">
                    @if($divisi)
                        Divisi: <span class="font-semibold">{{ $divisi }}</span>
                    @else
                        Semua Divisi
                    @endif
                    @if($status)
                        | Status: <span class="font-semibold">{{ ucfirst($status) }}</span>
                    @endif
                </p>
            </div>
            <div class="flex flex-wrap gap-2 w-full sm:w-auto">
                <button onclick="window.print()" class="flex-1 sm:flex-none bg-gradient-to-r from-teal-500 to-cyan-600 hover:from-teal-600 hover:to-cyan-700 text-white px-3 sm:px-4 py-2 rounded-lg font-semibold transition text-xs sm:text-sm shadow-md">
                    <i class="fas fa-file-pdf mr-1 sm:mr-2"></i>Export PDF
                </button>
                <button onclick="exportExcel()" class="flex-1 sm:flex-none bg-gradient-to-r from-teal-500 to-cyan-600 hover:from-teal-600 hover:to-cyan-700 text-white px-3 sm:px-4 py-2 rounded-lg font-semibold transition text-xs sm:text-sm shadow-md">
                    <i class="fas fa-file-excel mr-1 sm:mr-2"></i>Export Excel
                </button>
                <a href="{{ route('superadmin.laporan.index') }}" class="flex-1 sm:flex-none bg-gray-500 hover:bg-gray-600 text-white px-3 sm:px-4 py-2 rounded-lg font-semibold transition text-xs sm:text-sm text-center">
                    <i class="fas fa-arrow-left mr-1 sm:mr-2"></i>Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Print Header -->
    <div class="hidden print:block mb-6">
        <div class="text-center border-b-2 border-gray-800 pb-4 mb-4">
            <h1 class="text-2xl font-bold text-gray-800 mb-2">LAPORAN DATA KARYAWAN</h1>
            <p class="text-sm text-gray-600">
                @if($divisi) Divisi: {{ $divisi }} @else Semua Divisi @endif
                @if($status) | Status: {{ ucfirst($status) }} @endif
            </p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <div class="bg-gradient-to-br from-teal-500 to-cyan-600 rounded-lg sm:rounded-xl p-3 sm:p-4 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs opacity-90 mb-1">Total Karyawan</p>
                    <h3 class="text-xl sm:text-2xl font-bold">{{ $karyawan->count() }}</h3>
                </div>
                <i class="fas fa-users text-2xl sm:text-3xl opacity-20"></i>
            </div>
        </div>
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg sm:rounded-xl p-3 sm:p-4 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs opacity-90 mb-1">Aktif</p>
                    <h3 class="text-xl sm:text-2xl font-bold">{{ $karyawan->where('status', 'aktif')->count() }}</h3>
                </div>
                <i class="fas fa-user-check text-2xl sm:text-3xl opacity-20"></i>
            </div>
        </div>
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-lg sm:rounded-xl p-3 sm:p-4 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs opacity-90 mb-1">Non-Aktif</p>
                    <h3 class="text-xl sm:text-2xl font-bold">{{ $karyawan->where('status', 'nonaktif')->count() }}</h3>
                </div>
                <i class="fas fa-user-times text-2xl sm:text-3xl opacity-20"></i>
            </div>
        </div>
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg sm:rounded-xl p-3 sm:p-4 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs opacity-90 mb-1">Divisi</p>
                    <h3 class="text-xl sm:text-2xl font-bold">{{ $karyawan->pluck('divisi')->unique()->count() }}</h3>
                </div>
                <i class="fas fa-building text-2xl sm:text-3xl opacity-20"></i>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg sm:rounded-xl shadow-lg overflow-hidden">
        
        <!-- Mobile Card View -->
        <div class="block lg:hidden">
            @forelse($karyawan as $key => $item)
            <div class="border-b border-gray-200 p-4 hover:bg-gray-50">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <p class="font-bold text-gray-900 text-sm mb-1">{{ $item->name }}</p>
                        <p class="text-xs text-gray-600">{{ $item->email }}</p>
                    </div>
                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                        {{ $item->status == 'aktif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ ucfirst($item->status) }}
                    </span>
                </div>
                <div class="space-y-1 text-xs">
                    <p><span class="text-gray-600">NIK:</span> <span class="font-semibold">{{ $item->nik }}</span></p>
                    <p><span class="text-gray-600">Divisi:</span> <span class="font-semibold">{{ $item->divisi }}</span></p>
                    <p><span class="text-gray-600">Jabatan:</span> <span class="font-semibold">{{ $item->jabatan }}</span></p>
                    <p><span class="text-gray-600">No. HP:</span> <span class="font-semibold">{{ $item->no_hp ?? '-' }}</span></p>
                </div>
            </div>
            @empty
            <div class="p-8 text-center">
                <i class="fas fa-inbox text-4xl text-gray-400 mb-3"></i>
                <p class="text-gray-600">Tidak ada data karyawan</p>
            </div>
            @endforelse
        </div>

        <!-- Desktop Table View -->
        <div class="hidden lg:block overflow-x-auto">
            <table class="w-full" id="laporanTable">
                <thead class="bg-gradient-to-r from-teal-500 to-cyan-600 text-white">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">No</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">NIK</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Nama</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Email</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Divisi</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Jabatan</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">No. HP</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($karyawan as $key => $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-sm">{{ $key + 1 }}</td>
                        <td class="px-4 py-3 text-sm font-semibold">{{ $item->nik }}</td>
                        <td class="px-4 py-3 text-sm font-semibold text-gray-900">{{ $item->name }}</td>
                        <td class="px-4 py-3 text-sm">{{ $item->email }}</td>
                        <td class="px-4 py-3 text-sm">{{ $item->divisi }}</td>
                        <td class="px-4 py-3 text-sm">{{ $item->jabatan }}</td>
                        <td class="px-4 py-3 text-sm">{{ $item->no_hp ?? '-' }}</td>
                        <td class="px-4 py-3">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                {{ $item->status == 'aktif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ ucfirst($item->status) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-8 text-center text-gray-600">
                            <i class="fas fa-inbox text-4xl mb-3 block"></i>
                            Tidak ada data karyawan
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

@push('scripts')
<script>
    function exportExcel() {
        const table = document.getElementById('laporanTable');
        if (!table) {
            alert('Tidak ada data untuk di-export');
            return;
        }

        let csv = [];
        const rows = table.querySelectorAll('tr');
        
        for (let i = 0; i < rows.length; i++) {
            const row = [];
            const cols = rows[i].querySelectorAll('td, th');
            
            for (let j = 0; j < cols.length; j++) {
                let text = cols[j].innerText.replace(/"/g, '""');
                row.push('"' + text + '"');
            }
            
            csv.push(row.join(','));
        }
        
        const csvFile = new Blob([csv.join('\n')], { type: 'text/csv' });
        const downloadLink = document.createElement('a');
        downloadLink.download = 'Laporan_Karyawan_{{ date("Y-m-d") }}.csv';
        downloadLink.href = window.URL.createObjectURL(csvFile);
        downloadLink.style.display = 'none';
        document.body.appendChild(downloadLink);
        downloadLink.click();
        document.body.removeChild(downloadLink);
    }
</script>
@endpush