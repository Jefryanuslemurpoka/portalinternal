@extends('layouts.app')

@section('title', 'Edit Laporan Notaris')
@section('page-title', 'Edit Laporan Notaris')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Header -->
    <div class="bg-white p-6 rounded-xl shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">✏️ Edit Laporan Order Notaris</h1>
                <p class="text-gray-600 text-sm mt-1">{{ $laporan->nama_bulan }}</p>
            </div>
            <a href="{{ route('karyawan.laporan-notaris.show', $laporan->id) }}" 
               class="text-gray-600 hover:text-gray-800">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <form action="{{ route('karyawan.laporan-notaris.update', $laporan->id) }}" method="POST" id="formEdit">
        @csrf
        @method('PUT')

        <!-- Tabel Input Data -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-teal-600 to-cyan-700">
                <h3 class="text-lg font-semibold text-white">PT. PURI DIGITAL OUTPUT</h3>
                <p class="text-teal-100 text-sm">LAPORAN ORDER NOTARIS</p>
                <p class="text-teal-100 text-sm font-semibold">{{ strtoupper($laporan->nama_bulan) }}</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full" id="tabelData">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700 border">No</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 border">Tanggal</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 border">Gamal</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 border">Eny</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 border">Nyoman</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 border">Otty</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 border">Kartika</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 border">Retno</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 border">Neltje</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 border">Total</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 border">Libur</th>
                        </tr>
                    </thead>
                    <tbody id="bodyData">
                        @foreach($laporan->details as $index => $detail)
                            <tr class="{{ $detail->is_libur ? 'bg-red-50' : 'hover:bg-gray-50' }}">
                                <td class="px-4 py-2 text-center border">{{ $index + 1 }}</td>
                                <td class="px-4 py-2 text-center border">
                                    <input type="hidden" name="details[{{ $index }}][tanggal]" value="{{ $detail->tanggal->format('Y-m-d') }}">
                                    {{ $detail->tanggal->format('d.m.Y') }}
                                </td>
                                <td class="px-4 py-2 border">
                                    <input type="number" 
                                           name="details[{{ $index }}][gamal]" 
                                           value="{{ $detail->gamal }}" 
                                           min="0" 
                                           class="w-full px-2 py-1 border rounded text-center input-notaris" 
                                           data-notaris="gamal">
                                </td>
                                <td class="px-4 py-2 border">
                                    <input type="number" 
                                           name="details[{{ $index }}][eny]" 
                                           value="{{ $detail->eny }}" 
                                           min="0" 
                                           class="w-full px-2 py-1 border rounded text-center input-notaris" 
                                           data-notaris="eny">
                                </td>
                                <td class="px-4 py-2 border">
                                    <input type="number" 
                                           name="details[{{ $index }}][nyoman]" 
                                           value="{{ $detail->nyoman }}" 
                                           min="0" 
                                           class="w-full px-2 py-1 border rounded text-center input-notaris" 
                                           data-notaris="nyoman">
                                </td>
                                <td class="px-4 py-2 border">
                                    <input type="number" 
                                           name="details[{{ $index }}][otty]" 
                                           value="{{ $detail->otty }}" 
                                           min="0" 
                                           class="w-full px-2 py-1 border rounded text-center input-notaris" 
                                           data-notaris="otty">
                                </td>
                                <td class="px-4 py-2 border">
                                    <input type="number" 
                                           name="details[{{ $index }}][kartika]" 
                                           value="{{ $detail->kartika }}" 
                                           min="0" 
                                           class="w-full px-2 py-1 border rounded text-center input-notaris" 
                                           data-notaris="kartika">
                                </td>
                                <td class="px-4 py-2 border">
                                    <input type="number" 
                                           name="details[{{ $index }}][retno]" 
                                           value="{{ $detail->retno }}" 
                                           min="0" 
                                           class="w-full px-2 py-1 border rounded text-center input-notaris" 
                                           data-notaris="retno">
                                </td>
                                <td class="px-4 py-2 border">
                                    <input type="number" 
                                           name="details[{{ $index }}][neltje]" 
                                           value="{{ $detail->neltje }}" 
                                           min="0" 
                                           class="w-full px-2 py-1 border rounded text-center input-notaris" 
                                           data-notaris="neltje">
                                </td>
                                <td class="px-4 py-2 text-center border font-semibold row-total">{{ $detail->total }}</td>
                                <td class="px-4 py-2 text-center border">
                                    <input type="checkbox" 
                                           name="details[{{ $index }}][is_libur]" 
                                           class="w-4 h-4" 
                                           {{ $detail->is_libur ? 'checked' : '' }}>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50 font-semibold">
                        <tr>
                            <td colspan="2" class="px-4 py-3 text-center border">Total</td>
                            <td class="px-4 py-3 text-center border" id="totalGamal">{{ $laporan->total_gamal }}</td>
                            <td class="px-4 py-3 text-center border" id="totalEny">{{ $laporan->total_eny }}</td>
                            <td class="px-4 py-3 text-center border" id="totalNyoman">{{ $laporan->total_nyoman }}</td>
                            <td class="px-4 py-3 text-center border" id="totalOtty">{{ $laporan->total_otty }}</td>
                            <td class="px-4 py-3 text-center border" id="totalKartika">{{ $laporan->total_kartika }}</td>
                            <td class="px-4 py-3 text-center border" id="totalRetno">{{ $laporan->total_retno }}</td>
                            <td class="px-4 py-3 text-center border" id="totalNeltje">{{ $laporan->total_neltje }}</td>
                            <td class="px-4 py-3 text-center border bg-teal-100 text-teal-800" id="grandTotal">{{ $laporan->grand_total }}</td>
                            <td class="px-4 py-3 text-center border"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Tombol Submit -->
            <div class="p-6 border-t border-gray-200 bg-gray-50">
                <div class="flex justify-end gap-3">
                    <a href="{{ route('karyawan.laporan-notaris.show', $laporan->id) }}" 
                       class="px-6 py-2 border border-gray-300 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg transition-colors">
                        <i class="fas fa-save mr-2"></i>Update Laporan
                    </button>
                </div>
            </div>
        </div>

    </form>

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('.input-notaris');
    
    inputs.forEach(input => {
        input.addEventListener('input', calculateTotals);
    });

    function calculateTotals() {
        const notarisColumns = ['gamal', 'eny', 'nyoman', 'otty', 'kartika', 'retno', 'neltje'];
        const totals = {};
        
        notarisColumns.forEach(col => {
            totals[col] = 0;
        });

        const rows = document.querySelectorAll('#bodyData tr');
        rows.forEach(row => {
            let rowTotal = 0;
            notarisColumns.forEach(col => {
                const input = row.querySelector(`input[data-notaris="${col}"]`);
                const val = parseInt(input.value) || 0;
                totals[col] += val;
                rowTotal += val;
            });
            row.querySelector('.row-total').textContent = rowTotal;
        });

        // Update footer totals
        document.getElementById('totalGamal').textContent = totals.gamal;
        document.getElementById('totalEny').textContent = totals.eny;
        document.getElementById('totalNyoman').textContent = totals.nyoman;
        document.getElementById('totalOtty').textContent = totals.otty;
        document.getElementById('totalKartika').textContent = totals.kartika;
        document.getElementById('totalRetno').textContent = totals.retno;
        document.getElementById('totalNeltje').textContent = totals.neltje;

        const grandTotal = Object.values(totals).reduce((a, b) => a + b, 0);
        document.getElementById('grandTotal').textContent = grandTotal;
    }
});
</script>
@endpush
@endsection