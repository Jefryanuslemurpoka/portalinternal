@extends('layouts.app')

@section('title', 'Buat Laporan Notaris')
@section('page-title', 'Buat Laporan Notaris')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Header -->
    <div class="bg-white p-6 rounded-xl shadow-sm">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">📝 Buat Laporan Bulanan Order Notaris</h1>
                <p class="text-gray-600 text-sm mt-1">Input data order harian untuk semua notaris</p>
            </div>
            <a href="{{ route('karyawan.laporan-notaris.index') }}" 
               class="text-gray-600 hover:text-gray-800">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>
    </div>

    <form action="{{ route('karyawan.laporan-notaris.store') }}" method="POST" id="formLaporan">
        @csrf

        <!-- Pilih Bulan -->
        <div class="bg-white p-6 rounded-xl shadow-sm">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Pilih Periode Bulan</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bulan & Tahun</label>
                    <input type="month" 
                           name="bulan" 
                           id="inputBulan"
                           value="{{ old('bulan') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                           required>
                    @error('bulan')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex items-end">
                    <button type="button" 
                            id="btnGenerate" 
                            class="w-full px-6 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg transition-colors">
                        <i class="fas fa-calendar-alt mr-2"></i>Generate Tanggal
                    </button>
                </div>
            </div>
        </div>

        <!-- Tabel Input Data -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden" id="containerTabel" style="display: none;">
            <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-teal-600 to-cyan-700">
                <h3 class="text-lg font-semibold text-white">PT. PURI DIGITAL OUTPUT</h3>
                <p class="text-teal-100 text-sm">LAPORAN ORDER NOTARIS</p>
                <p class="text-teal-100 text-sm font-semibold" id="judulBulan"></p>
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
                        <!-- Data akan di-generate via JavaScript -->
                    </tbody>
                    <tfoot class="bg-gray-50 font-semibold">
                        <tr>
                            <td colspan="2" class="px-4 py-3 text-center border">Total</td>
                            <td class="px-4 py-3 text-center border" id="totalGamal">0</td>
                            <td class="px-4 py-3 text-center border" id="totalEny">0</td>
                            <td class="px-4 py-3 text-center border" id="totalNyoman">0</td>
                            <td class="px-4 py-3 text-center border" id="totalOtty">0</td>
                            <td class="px-4 py-3 text-center border" id="totalKartika">0</td>
                            <td class="px-4 py-3 text-center border" id="totalRetno">0</td>
                            <td class="px-4 py-3 text-center border" id="totalNeltje">0</td>
                            <td class="px-4 py-3 text-center border bg-teal-100 text-teal-800" id="grandTotal">0</td>
                            <td class="px-4 py-3 text-center border"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Tombol Submit -->
            <div class="p-6 border-t border-gray-200 bg-gray-50">
                <div class="flex justify-end gap-3">
                    <a href="{{ route('karyawan.laporan-notaris.index') }}" 
                       class="px-6 py-2 border border-gray-300 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors">
                        Batal
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg transition-colors">
                        <i class="fas fa-save mr-2"></i>Simpan Laporan
                    </button>
                </div>
            </div>
        </div>

    </form>

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const btnGenerate = document.getElementById('btnGenerate');
    const inputBulan = document.getElementById('inputBulan');
    const containerTabel = document.getElementById('containerTabel');
    const bodyData = document.getElementById('bodyData');
    const judulBulan = document.getElementById('judulBulan');

    btnGenerate.addEventListener('click', function() {
        const bulan = inputBulan.value;
        if (!bulan) {
            alert('Pilih bulan terlebih dahulu!');
            return;
        }

        generateTabel(bulan);
        containerTabel.style.display = 'block';
    });

    function generateTabel(bulan) {
        const [year, month] = bulan.split('-');
        const daysInMonth = new Date(year, month, 0).getDate();
        const namaBulan = new Date(year, month - 1).toLocaleDateString('id-ID', { month: 'long', year: 'numeric' });
        
        judulBulan.textContent = namaBulan.toUpperCase();
        bodyData.innerHTML = '';

        for (let i = 1; i <= daysInMonth; i++) {
            const date = `${year}-${month.padStart(2, '0')}-${i.toString().padStart(2, '0')}`;
            const dayOfWeek = new Date(date).getDay();
            const isWeekend = dayOfWeek === 0 || dayOfWeek === 6;

            const row = document.createElement('tr');
            row.className = isWeekend ? 'bg-red-50' : 'hover:bg-gray-50';
            row.innerHTML = `
                <td class="px-4 py-2 text-center border">${i}</td>
                <td class="px-4 py-2 text-center border">
                    <input type="hidden" name="details[${i-1}][tanggal]" value="${date}">
                    ${new Date(date).toLocaleDateString('id-ID', { day: '2-digit', month: '2-digit', year: 'numeric' })}
                </td>
                <td class="px-4 py-2 border"><input type="number" name="details[${i-1}][gamal]" value="0" min="0" class="w-full px-2 py-1 border rounded text-center input-notaris" data-notaris="gamal"></td>
                <td class="px-4 py-2 border"><input type="number" name="details[${i-1}][eny]" value="0" min="0" class="w-full px-2 py-1 border rounded text-center input-notaris" data-notaris="eny"></td>
                <td class="px-4 py-2 border"><input type="number" name="details[${i-1}][nyoman]" value="0" min="0" class="w-full px-2 py-1 border rounded text-center input-notaris" data-notaris="nyoman"></td>
                <td class="px-4 py-2 border"><input type="number" name="details[${i-1}][otty]" value="0" min="0" class="w-full px-2 py-1 border rounded text-center input-notaris" data-notaris="otty"></td>
                <td class="px-4 py-2 border"><input type="number" name="details[${i-1}][kartika]" value="0" min="0" class="w-full px-2 py-1 border rounded text-center input-notaris" data-notaris="kartika"></td>
                <td class="px-4 py-2 border"><input type="number" name="details[${i-1}][retno]" value="0" min="0" class="w-full px-2 py-1 border rounded text-center input-notaris" data-notaris="retno"></td>
                <td class="px-4 py-2 border"><input type="number" name="details[${i-1}][neltje]" value="0" min="0" class="w-full px-2 py-1 border rounded text-center input-notaris" data-notaris="neltje"></td>
                <td class="px-4 py-2 text-center border font-semibold row-total">0</td>
                <td class="px-4 py-2 text-center border">
                    <input type="checkbox" name="details[${i-1}][is_libur]" class="w-4 h-4" ${isWeekend ? 'checked' : ''}>
                </td>
            `;
            bodyData.appendChild(row);
        }

        attachCalculateListeners();
    }

    function attachCalculateListeners() {
        const inputs = document.querySelectorAll('.input-notaris');
        inputs.forEach(input => {
            input.addEventListener('input', calculateTotals);
        });
    }

    function calculateTotals() {
        const notarisColumns = ['gamal', 'eny', 'nyoman', 'otty', 'kartika', 'retno', 'neltje'];
        const totals = {};
        
        notarisColumns.forEach(col => {
            totals[col] = 0;
        });

        const rows = bodyData.querySelectorAll('tr');
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