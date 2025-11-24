@extends('layouts.app')

@section('title', 'Master Gaji Karyawan')
@section('page-title', 'Master Gaji Karyawan')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Master Gaji Karyawan</h2>
            <p class="text-gray-600 text-sm">Setup gaji pokok dan tunjangan tetap untuk setiap karyawan</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('finance.gaji.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Slip Gaji
            </a>
            <a href="{{ route('finance.gaji.master.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-2 rounded-lg shadow-lg transition">
                <i class="fas fa-plus mr-2"></i>Tambah Data Gaji
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">Total Karyawan</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $salaries->total() }}</h3>
                </div>
                <div class="bg-white/20 p-4 rounded-xl">
                    <i class="fas fa-users text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">Gaji Terendah</p>
                    <h3 class="text-2xl font-bold mt-2">Rp {{ number_format($salaries->min('gaji_pokok') ?? 0, 0, ',', '.') }}</h3>
                </div>
                <div class="bg-white/20 p-4 rounded-xl">
                    <i class="fas fa-arrow-down text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm">Gaji Tertinggi</p>
                    <h3 class="text-2xl font-bold mt-2">Rp {{ number_format($salaries->max('gaji_pokok') ?? 0, 0, ',', '.') }}</h3>
                </div>
                <div class="bg-white/20 p-4 rounded-xl">
                    <i class="fas fa-arrow-up text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm">Rata-rata Gaji</p>
                    <h3 class="text-2xl font-bold mt-2">Rp {{ number_format($salaries->avg('gaji_pokok') ?? 0, 0, ',', '.') }}</h3>
                </div>
                <div class="bg-white/20 p-4 rounded-xl">
                    <i class="fas fa-chart-line text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Karyawan</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Gaji Pokok</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tunjangan Tetap</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Gaji</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Berlaku Dari</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($salaries as $salary)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-teal-100 flex items-center justify-center">
                                        <span class="text-teal-600 font-semibold">{{ substr($salary->user->name, 0, 2) }}</span>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $salary->user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $salary->user->nik }}</div>
                                        <div class="text-xs text-gray-500">{{ $salary->user->jabatan }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-gray-900">Rp {{ number_format($salary->gaji_pokok, 0, ',', '.') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-700">
                                    @if($salary->total_tunjangan > 0)
                                        <div class="space-y-1">
                                            @if($salary->tunjangan_jabatan > 0)
                                                <div class="text-xs">Jabatan: Rp {{ number_format($salary->tunjangan_jabatan, 0, ',', '.') }}</div>
                                            @endif
                                            @if($salary->tunjangan_transport > 0)
                                                <div class="text-xs">Transport: Rp {{ number_format($salary->tunjangan_transport, 0, ',', '.') }}</div>
                                            @endif
                                            @if($salary->tunjangan_makan > 0)
                                                <div class="text-xs">Makan: Rp {{ number_format($salary->tunjangan_makan, 0, ',', '.') }}</div>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-teal-600">Rp {{ number_format($salary->total_gaji, 0, ',', '.') }}</div>
                                <div class="text-xs text-gray-500">Total: Pokok + Tunjangan</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-700">{{ $salary->effective_date->format('d M Y') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($salary->status === 'aktif')
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                        Aktif
                                    </span>
                                @else
                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('finance.gaji.master.edit', $salary->id) }}" 
                                       class="text-blue-600 hover:text-blue-800 transition"
                                       title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('finance.gaji.master.destroy', $salary->id) }}" 
                                          method="POST" 
                                          class="inline"
                                          onsubmit="return confirm('Yakin ingin menghapus data gaji untuk {{ $salary->user->name }}?\n\nData slip gaji yang sudah dibuat tidak akan terhapus.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-800 transition" 
                                                title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Belum Ada Data</h3>
                                    <p class="text-gray-500 mb-4">Belum ada master gaji yang dibuat</p>
                                    <a href="{{ route('finance.gaji.master.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-2 rounded-lg transition">
                                        <i class="fas fa-plus mr-2"></i>Tambah Data Gaji
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($salaries->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $salaries->links() }}
            </div>
        @endif
    </div>

</div>
@endsection