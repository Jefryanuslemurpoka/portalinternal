@extends('layouts.app')

@section('title', 'Gaji Karyawan')
@section('page-title', 'Manajemen Gaji Karyawan')

@section('content')
<div class="space-y-6">

    <!-- Header Actions -->
    <div class="flex flex-col lg:flex-row lg:justify-between lg:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Slip Gaji Karyawan</h2>
            <p class="text-gray-600 text-sm">Kelola slip gaji dan pembayaran karyawan</p>
        </div>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('finance.gaji.master.index') }}" class="bg-white hover:bg-gray-50 text-gray-700 px-4 py-2 rounded-lg border border-gray-300 transition">
                <i class="fas fa-cog mr-2"></i>Master Gaji
            </a>
            <a href="{{ route('finance.gaji.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-2 rounded-lg shadow-lg transition">
                <i class="fas fa-plus mr-2"></i>Generate Slip Gaji
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm">Total Slip Bulan Ini</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $slips->total() }}</h3>
                </div>
                <div class="bg-white/20 p-4 rounded-xl">
                    <i class="fas fa-file-invoice text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-100 text-sm">Pending Approval</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $slips->where('status', 'pending')->count() }}</h3>
                </div>
                <div class="bg-white/20 p-4 rounded-xl">
                    <i class="fas fa-clock text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm">Sudah Dibayar</p>
                    <h3 class="text-3xl font-bold mt-2">{{ $slips->where('status', 'paid')->count() }}</h3>
                </div>
                <div class="bg-white/20 p-4 rounded-xl">
                    <i class="fas fa-check-circle text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm">Total Gaji</p>
                    <h3 class="text-2xl font-bold mt-2">Rp {{ number_format($slips->sum('gaji_bersih'), 0, ',', '.') }}</h3>
                </div>
                <div class="bg-white/20 p-4 rounded-xl">
                    <i class="fas fa-wallet text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <form method="GET" action="{{ route('finance.gaji.index') }}" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <!-- Search -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Cari Karyawan</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Nama atau NIK..."
                               class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>
                </div>

                <!-- Tahun -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                    <select name="tahun" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                        <option value="">Semua Tahun</option>
                        @foreach($tahunList as $tahun)
                            <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>
                                {{ $tahun }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Bulan -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                    <select name="bulan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                        <option value="">Semua Bulan</option>
                        @foreach(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $index => $namaBulan)
                            <option value="{{ $index + 1 }}" {{ request('bulan') == ($index + 1) ? 'selected' : '' }}>
                                {{ $namaBulan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                        <option value="">Semua Status</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    </select>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-2 rounded-lg transition">
                    <i class="fas fa-filter mr-2"></i>Filter
                </button>
                <a href="{{ route('finance.gaji.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-6 py-2 rounded-lg transition">
                    <i class="fas fa-redo mr-2"></i>Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Slip Number</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Karyawan</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Periode</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Gaji Bersih</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($slips as $slip)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $slip->slip_number }}</div>
                                <div class="text-xs text-gray-500">{{ $slip->created_at->format('d M Y H:i') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="h-10 w-10 rounded-full bg-teal-100 flex items-center justify-center">
                                        <span class="text-teal-600 font-semibold">{{ substr($slip->user->name, 0, 2) }}</span>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $slip->user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $slip->user->nik }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900">{{ $slip->bulan_name }} {{ $slip->tahun }}</div>
                                <div class="text-xs text-gray-500">{{ $slip->periode_start->format('d/m') }} - {{ $slip->periode_end->format('d/m') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-semibold text-gray-900">Rp {{ number_format($slip->gaji_bersih, 0, ',', '.') }}</div>
                                <div class="text-xs text-gray-500">Kotor: Rp {{ number_format($slip->gaji_kotor, 0, ',', '.') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $slip->status_badge }}">
                                    {{ $slip->status_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('finance.gaji.show', $slip->id) }}" 
                                       class="text-blue-600 hover:text-blue-800 transition"
                                       title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($slip->canEdit())
                                        <a href="{{ route('finance.gaji.edit', $slip->id) }}" 
                                           class="text-yellow-600 hover:text-yellow-800 transition"
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    @endif
                                    <a href="{{ route('finance.gaji.pdf', $slip->id) }}" 
                                       class="text-red-600 hover:text-red-800 transition"
                                       title="Download PDF"
                                       target="_blank">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                    @if($slip->isDraft())
                                        <form action="{{ route('finance.gaji.destroy', $slip->id) }}" method="POST" class="inline"
                                              onsubmit="return confirm('Yakin ingin menghapus slip ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 transition" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-inbox text-6xl text-gray-300 mb-4"></i>
                                    <h3 class="text-lg font-semibold text-gray-700 mb-2">Belum Ada Data</h3>
                                    <p class="text-gray-500 mb-4">Belum ada slip gaji yang dibuat</p>
                                    <a href="{{ route('finance.gaji.create') }}" class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-2 rounded-lg transition">
                                        <i class="fas fa-plus mr-2"></i>Generate Slip Gaji
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($slips->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $slips->links() }}
            </div>
        @endif
    </div>

</div>
@endsection