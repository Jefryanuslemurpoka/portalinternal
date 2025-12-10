@extends('layouts.app')

@section('title', 'Laporan Order Notaris')
@section('page-title', 'Laporan Order Notaris')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-white p-6 rounded-xl shadow-sm">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">📊 Laporan Order Notaris</h1>
            <p class="text-gray-600 text-sm mt-1">Kelola dan pantau laporan bulanan order notaris</p>
        </div>
        <a href="{{ route('karyawan.laporan-notaris.create') }}" 
           class="inline-flex items-center px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white rounded-lg transition-colors shadow-sm">
            <i class="fas fa-plus mr-2"></i>
            Buat Laporan Baru
        </a>
    </div>

    <!-- Filter Tahun -->
    <div class="bg-white p-4 rounded-xl shadow-sm">
        <form method="GET" class="flex items-center gap-3">
            <label class="text-sm font-medium text-gray-700">Filter Tahun:</label>
            <select name="tahun" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                @foreach($tahunList as $t)
                    <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg transition-colors">
                <i class="fas fa-filter mr-2"></i>Filter
            </button>
        </form>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
            <div class="flex">
                <i class="fas fa-check-circle text-green-500 mr-3 mt-0.5"></i>
                <p class="text-green-800">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
            <div class="flex">
                <i class="fas fa-exclamation-circle text-red-500 mr-3 mt-0.5"></i>
                <p class="text-red-800">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Tabel Laporan -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        @if($laporans->isEmpty())
            <div class="text-center py-16">
                <i class="fas fa-folder-open text-gray-300 text-6xl mb-4"></i>
                <p class="text-gray-500 text-lg">Belum ada laporan untuk tahun {{ $tahun }}</p>
                <a href="{{ route('karyawan.laporan-notaris.create') }}" class="inline-block mt-4 text-teal-600 hover:text-teal-700">
                    Buat laporan pertama →
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Bulan</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Gamal</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Eny</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Nyoman</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Otty</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Kartika</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Retno</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Neltje</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-4 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($laporans as $laporan)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-medium text-gray-900">{{ $laporan->nama_bulan }}</div>
                                    <div class="text-xs text-gray-500">Dibuat: {{ $laporan->created_at->format('d/m/Y') }}</div>
                                </td>
                                <td class="px-6 py-4 text-center text-gray-900">{{ number_format($laporan->total_gamal) }}</td>
                                <td class="px-6 py-4 text-center text-gray-900">{{ number_format($laporan->total_eny) }}</td>
                                <td class="px-6 py-4 text-center text-gray-900">{{ number_format($laporan->total_nyoman) }}</td>
                                <td class="px-6 py-4 text-center text-gray-900">{{ number_format($laporan->total_otty) }}</td>
                                <td class="px-6 py-4 text-center text-gray-900">{{ number_format($laporan->total_kartika) }}</td>
                                <td class="px-6 py-4 text-center text-gray-900">{{ number_format($laporan->total_retno) }}</td>
                                <td class="px-6 py-4 text-center text-gray-900">{{ number_format($laporan->total_neltje) }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-teal-100 text-teal-800">
                                        {{ number_format($laporan->grand_total) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <!-- View Detail -->
                                        <a href="{{ route('karyawan.laporan-notaris.show', $laporan->id) }}" 
                                           class="text-blue-600 hover:text-blue-800 text-lg" 
                                           title="Detail Gabungan">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <!-- Download ZIP (8 Excel Files) -->
                                        <a href="{{ route('karyawan.laporan-notaris.download-zip', $laporan->id) }}" 
                                           class="text-teal-600 hover:text-teal-800 text-lg" 
                                           title="Download Laporan (ZIP - 8 File Excel)">
                                            <i class="fas fa-download"></i>
                                        </a>

                                        <!-- Grafik -->
                                        <a href="{{ route('karyawan.laporan-notaris.grafik', $laporan->id) }}" 
                                           class="text-purple-600 hover:text-purple-800 text-lg" 
                                           title="Lihat Grafik">
                                            <i class="fas fa-chart-bar"></i>
                                        </a>

                                        <!-- Dropdown Notaris -->
                                        <div class="relative inline-block text-left dropdown-notaris">
                                            <button type="button" 
                                                    class="text-green-600 hover:text-green-800 text-lg dropdown-btn"
                                                    title="Laporan Per Notaris"
                                                    onclick="toggleDropdown({{ $laporan->id }})">
                                                <i class="fas fa-users"></i>
                                            </button>
                                            <div id="dropdown-{{ $laporan->id }}" 
                                                 class="hidden absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5">
                                                <div class="py-1">
                                                    @php
                                                        $notarisList = App\Models\LaporanNotaris::getNotarisList();
                                                    @endphp
                                                    @foreach($notarisList as $key => $nama)
                                                        <a href="{{ route('karyawan.laporan-notaris.notaris-detail', [$laporan->id, $key]) }}" 
                                                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-teal-50 hover:text-teal-700">
                                                            <i class="fas fa-user mr-2"></i>{{ $nama }}
                                                        </a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Edit -->
                                        <a href="{{ route('karyawan.laporan-notaris.edit', $laporan->id) }}" 
                                           class="text-amber-600 hover:text-amber-800 text-lg" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <!-- Delete -->
                                        <form action="{{ route('karyawan.laporan-notaris.destroy', $laporan->id) }}" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="return confirm('Yakin ingin menghapus laporan ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-800 text-lg" 
                                                    title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $laporans->links() }}
            </div>
        @endif
    </div>

</div>

@push('scripts')
<script>
// Toggle dropdown notaris
function toggleDropdown(id) {
    const dropdown = document.getElementById(`dropdown-${id}`);
    const allDropdowns = document.querySelectorAll('[id^="dropdown-"]');
    
    // Close all other dropdowns
    allDropdowns.forEach(d => {
        if (d.id !== `dropdown-${id}`) {
            d.classList.add('hidden');
        }
    });
    
    // Toggle current dropdown
    dropdown.classList.toggle('hidden');
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const isDropdownButton = event.target.closest('.dropdown-btn');
    if (!isDropdownButton) {
        const allDropdowns = document.querySelectorAll('[id^="dropdown-"]');
        allDropdowns.forEach(d => d.classList.add('hidden'));
    }
});
</script>
@endpush
@endsection