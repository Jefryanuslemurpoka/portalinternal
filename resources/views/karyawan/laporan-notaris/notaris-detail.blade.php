@extends('layouts.app')

@section('title', 'Laporan ' . $namaNotaris)
@section('page-title', 'Laporan ' . $namaNotaris)

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header -->
    <div class="bg-white p-6 rounded-xl shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">📋 Laporan Order Per Notaris</h1>
                <p class="text-gray-600 text-sm mt-1">{{ $laporan->nama_bulan }}</p>
            </div>
            <a href="{{ route('karyawan.laporan-notaris.show', $laporan->id) }}" 
               class="text-gray-600 hover:text-gray-800">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <!-- Tabel Laporan Per Notaris -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">Laporan Order Per Notaris</h3>
            <p class="text-sm text-gray-600">{{ $laporan->nama_bulan }}</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-teal-600 text-white">
                    <tr>
                        <th class="px-6 py-3 text-center text-sm font-semibold border border-teal-700" rowspan="2">Nama Notaris</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold border border-teal-700" colspan="2">Tanggal</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold border border-teal-700" rowspan="2">Total Order</th>
                    </tr>
                    <tr>
                        <th class="px-6 py-3 text-center text-sm font-semibold border border-teal-700">No</th>
                        <th class="px-6 py-3 text-center text-sm font-semibold border border-teal-700">Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $no = 1;
                        $totalOrders = 0;
                        $details = $laporan->details->sortBy('tanggal');
                    @endphp

                    @foreach($details as $index => $detail)
                        @php
                            $orderValue = $detail->{$notaris};
                            $totalOrders += $orderValue;
                        @endphp
                        <tr class="{{ $detail->is_libur ? 'bg-red-50' : ($index % 2 == 0 ? 'bg-gray-50' : 'bg-white') }}">
                            @if($index == 0)
                                <td class="px-6 py-3 text-center border font-semibold bg-teal-50" rowspan="{{ $details->count() + 1 }}">
                                    {{ $namaNotaris }}
                                </td>
                            @endif
                            <td class="px-4 py-2 text-center border">{{ $no++ }}.</td>
                            <td class="px-4 py-2 text-center border">{{ $detail->tanggal->format('d/m/Y') }}</td>
                            <td class="px-4 py-2 text-center border font-medium">{{ $orderValue }}</td>
                        </tr>
                    @endforeach

                    <!-- Total Row -->
                    <tr class="bg-teal-100 font-bold">
                        <td colspan="2" class="px-6 py-3 text-center border border-teal-300">Total</td>
                        <td class="px-6 py-3 text-center border border-teal-300 text-teal-800 text-lg">
                            {{ number_format($totalOrders) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="p-6 border-t border-gray-200 bg-gray-50">
            <div class="text-center">
                <p class="text-sm font-semibold text-gray-800 mb-4">Annisa NurQalbi</p>
                <p class="text-xs text-gray-600">Document Controller & Update</p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="p-4 border-t border-gray-200 flex justify-center gap-3">
            <button onclick="window.print()" 
                    class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                <i class="fas fa-print mr-2"></i>Print
            </button>
            <button onclick="exportToExcel()" 
                    class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                <i class="fas fa-file-excel mr-2"></i>Export Excel
            </button>
        </div>
    </div>

</div>

@push('scripts')
<script>
function exportToExcel() {
    // Simple CSV export
    const table = document.querySelector('table');
    let csv = [];
    
    // Headers
    csv.push(['Laporan Order Per Notaris']);
    csv.push(['{{ $laporan->nama_bulan }}']);
    csv.push([]);
    csv.push(['Nama Notaris', 'No', 'Tanggal', 'Total Order']);
    
    // Data rows
    const rows = table.querySelectorAll('tbody tr');
    let namaNotaris = '{{ $namaNotaris }}';
    
    rows.forEach((row, index) => {
        const cells = row.querySelectorAll('td');
        if (cells.length >= 3 && !row.classList.contains('bg-teal-100')) {
            const no = cells[0].textContent.trim();
            const tanggal = cells[1].textContent.trim();
            const total = cells[2].textContent.trim();
            
            if (index === 0) {
                csv.push([namaNotaris, no, tanggal, total]);
            } else {
                csv.push(['', no, tanggal, total]);
            }
        }
    });
    
    // Total
    const totalRow = Array.from(rows).find(r => r.classList.contains('bg-teal-100'));
    if (totalRow) {
        const totalCells = totalRow.querySelectorAll('td');
        csv.push(['', '', 'Total', totalCells[1].textContent.trim()]);
    }
    
    // Convert to CSV string
    const csvContent = csv.map(row => row.join(',')).join('\n');
    
    // Download
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'Laporan_{{ $namaNotaris }}_{{ $laporan->bulan }}.csv';
    link.click();
}
</script>

<style>
@media print {
    .no-print {
        display: none !important;
    }
    
    body {
        print-color-adjust: exact;
        -webkit-print-color-adjust: exact;
    }
}
</style>
@endpush
@endsection