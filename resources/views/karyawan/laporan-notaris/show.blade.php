@extends('layouts.app')

@section('title', 'Detail Laporan Notaris')
@section('page-title', 'Detail Laporan Notaris')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Header -->
    <div class="bg-white p-6 rounded-xl shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">📊 {{ $laporan->nama_bulan }}</h1>
                <p class="text-gray-600 text-sm mt-1">Detail laporan order notaris</p>
            </div>
            <a href="{{ route('karyawan.laporan-notaris.index') }}" 
               class="text-gray-600 hover:text-gray-800">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('karyawan.laporan-notaris.edit', $laporan->id) }}" 
               class="px-4 py-2 bg-amber-600 hover:bg-amber-700 text-white rounded-lg transition-colors text-sm">
                <i class="fas fa-edit mr-2"></i>Edit Laporan
            </a>
            
            @php
                $notarisList = App\Models\LaporanNotaris::getNotarisList();
            @endphp
            
            @foreach($notarisList as $key => $nama)
                <a href="{{ route('karyawan.laporan-notaris.notaris-detail', [$laporan->id, $key]) }}" 
                   class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors text-sm">
                    <i class="fas fa-user mr-2"></i>{{ $nama }}
                </a>
            @endforeach

            <a href="{{ route('karyawan.laporan-notaris.grafik', $laporan->id) }}" 
               class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg transition-colors text-sm">
                <i class="fas fa-chart-bar mr-2"></i>Lihat Grafik
            </a>
        </div>
    </div>

    <!-- Tabel Detail -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-teal-600 to-cyan-700">
            <h3 class="text-lg font-semibold text-white">PT. PURI DIGITAL OUTPUT</h3>
            <p class="text-teal-100 text-sm">LAPORAN ORDER NOTARIS</p>
            <p class="text-teal-100 text-sm font-semibold">{{ strtoupper($laporan->nama_bulan) }}</p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 border">No</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 border">Tanggal</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 border">Gamal</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 border">Eny</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 border">Nyoman</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 border">Otty</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 border">Kartika</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 border">Retno</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 border">Neltje</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700 border">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($laporan->details as $index => $detail)
                        <tr class="{{ $detail->is_libur ? 'bg-red-50' : 'hover:bg-gray-50' }}">
                            <td class="px-4 py-2 text-center border">{{ $index + 1 }}</td>
                            <td class="px-4 py-2 text-center border">{{ $detail->tanggal->format('d.m.Y') }}</td>
                            <td class="px-4 py-2 text-center border">{{ $detail->gamal }}</td>
                            <td class="px-4 py-2 text-center border">{{ $detail->eny }}</td>
                            <td class="px-4 py-2 text-center border">{{ $detail->nyoman }}</td>
                            <td class="px-4 py-2 text-center border">{{ $detail->otty }}</td>
                            <td class="px-4 py-2 text-center border">{{ $detail->kartika }}</td>
                            <td class="px-4 py-2 text-center border">{{ $detail->retno }}</td>
                            <td class="px-4 py-2 text-center border">{{ $detail->neltje }}</td>
                            <td class="px-4 py-2 text-center border font-semibold">{{ $detail->total }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50 font-semibold">
                    <tr>
                        <td colspan="2" class="px-4 py-3 text-center border text-gray-800">Total</td>
                        <td class="px-4 py-3 text-center border">{{ number_format($laporan->total_gamal) }}</td>
                        <td class="px-4 py-3 text-center border">{{ number_format($laporan->total_eny) }}</td>
                        <td class="px-4 py-3 text-center border">{{ number_format($laporan->total_nyoman) }}</td>
                        <td class="px-4 py-3 text-center border">{{ number_format($laporan->total_otty) }}</td>
                        <td class="px-4 py-3 text-center border">{{ number_format($laporan->total_kartika) }}</td>
                        <td class="px-4 py-3 text-center border">{{ number_format($laporan->total_retno) }}</td>
                        <td class="px-4 py-3 text-center border">{{ number_format($laporan->total_neltje) }}</td>
                        <td class="px-4 py-3 text-center border bg-teal-100 text-teal-800 text-lg">
                            {{ number_format($laporan->grand_total) }}
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Footer Info -->
        <div class="p-6 border-t border-gray-200 bg-gray-50 text-center">
            <p class="text-sm text-gray-600">
                <span class="inline-block w-4 h-4 bg-red-100 border border-red-300 mr-1"></span>
                Hari Libur/Tanggal Merah
            </p>
            <p class="text-xs text-gray-500 mt-2">
                Dibuat oleh: {{ $laporan->creator->name ?? 'System' }} | 
                Tanggal: {{ $laporan->created_at->format('d F Y, H:i') }}
            </p>
        </div>
    </div>

</div>
@endsection