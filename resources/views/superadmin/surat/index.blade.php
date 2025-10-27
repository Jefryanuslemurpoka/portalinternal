@extends('layouts.app')

@section('title', 'Log Book Surat')
@section('page-title', 'Log Book Surat')

@section('content')
<div class="space-y-6">

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-teal-500 to-cyan-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-white/80 mb-1">Total Surat</p>
                    <h3 class="text-3xl font-bold">{{ $totalSurat }}</h3>
                    <p class="text-xs text-white/70 mt-1">Semua Surat</p>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-envelope text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-white/80 mb-1">Surat Masuk</p>
                    <h3 class="text-3xl font-bold">{{ $suratMasuk }}</h3>
                    <p class="text-xs text-white/70 mt-1">Diterima</p>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-inbox text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-amber-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-white/80 mb-1">Surat Keluar</p>
                    <h3 class="text-3xl font-bold">{{ $suratKeluar }}</h3>
                    <p class="text-xs text-white/70 mt-1">Dikirim</p>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-paper-plane text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <form method="GET" action="{{ route('superadmin.surat.index') }}" class="space-y-4">
            
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-800">
                    <i class="fas fa-filter mr-2"></i>Filter Surat
                </h3>
                <a href="{{ route('superadmin.surat.index') }}" class="text-sm text-gray-600 hover:text-gray-800">
                    <i class="fas fa-redo mr-1"></i>Reset Filter
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                
                <!-- Filter Jenis -->
                <div>
                    <label class="form-label">Jenis Surat</label>
                    <select name="jenis" class="form-input">
                        <option value="">Semua Jenis</option>
                        <option value="masuk" {{ request('jenis') == 'masuk' ? 'selected' : '' }}>Surat Masuk</option>
                        <option value="keluar" {{ request('jenis') == 'keluar' ? 'selected' : '' }}>Surat Keluar</option>
                    </select>
                </div>

                <!-- Filter Bulan -->
                <div>
                    <label class="form-label">Bulan</label>
                    <select name="bulan" class="form-input">
                        <option value="">Semua Bulan</option>
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $i, 1)) }}
                            </option>
                        @endfor
                    </select>
                </div>

                <!-- Filter Tahun -->
                <div>
                    <label class="form-label">Tahun</label>
                    <select name="tahun" class="form-input">
                        <option value="">Semua Tahun</option>
                        @for($year = date('Y'); $year >= date('Y') - 5; $year--)
                            <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endfor
                    </select>
                </div>

                <!-- Button Filter -->
                <div class="flex items-end">
                    <button type="submit" class="btn btn-primary w-full">
                        <i class="fas fa-search mr-2"></i>Tampilkan
                    </button>
                </div>

            </div>

        </form>
    </div>

    <!-- Table Surat -->
    <x-table 
        id="suratTable"
        title="Daftar Surat"
        :headers="['No', 'No. Surat', 'Jenis', 'Tanggal', 'Pengirim', 'Penerima', 'Perihal', 'File', 'Aksi']"
        addRoute="{{ route('superadmin.surat.create') }}"
        addText="Tambah Surat"
    >
        @forelse($surat as $key => $s)
        <tr class="hover:bg-gray-50 transition">
            <td class="px-6 py-4 text-sm text-gray-900">
                {{ $surat->firstItem() + $key }}
            </td>
            <td class="px-6 py-4">
                <p class="text-sm font-semibold text-gray-900">{{ $s->nomor_surat }}</p>
            </td>
            <td class="px-6 py-4">
                <span class="px-3 py-1 rounded-full text-xs font-semibold
                    {{ $s->jenis == 'masuk' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }}">
                    <i class="fas fa-{{ $s->jenis == 'masuk' ? 'inbox' : 'paper-plane' }} mr-1"></i>
                    {{ ucfirst($s->jenis) }}
                </span>
            </td>
            <td class="px-6 py-4">
                <div>
                    <p class="text-sm font-semibold text-gray-900">{{ $s->tanggal->format('d M Y') }}</p>
                    <p class="text-xs text-gray-500">{{ $s->tanggal->diffForHumans() }}</p>
                </div>
            </td>
            <td class="px-6 py-4">
                <p class="text-sm text-gray-900">{{ $s->pengirim }}</p>
            </td>
            <td class="px-6 py-4">
                <p class="text-sm text-gray-900">{{ $s->penerima }}</p>
            </td>
            <td class="px-6 py-4">
                <div class="max-w-xs">
                    <p class="text-sm text-gray-700 line-clamp-2" title="{{ $s->perihal }}">{{ $s->perihal }}</p>
                </div>
            </td>
            <td class="px-6 py-4">
                @if($s->file_path)
                    <a href="{{ route('superadmin.surat.download', $s->id) }}" 
                       class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition text-xs">
                        <i class="fas fa-download mr-1"></i>
                        Download
                    </a>
                @else
                    <span class="text-xs text-gray-400">Tidak ada file</span>
                @endif
            </td>
            <td class="px-6 py-4">
                <div class="flex items-center space-x-2">
                    <a href="{{ route('superadmin.surat.edit', $s->id) }}" 
                       class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                       title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    <button onclick="openDeleteModal({{ $s->id }}, '{{ $s->nomor_surat }}')" 
                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition"
                            title="Hapus">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="9" class="px-6 py-12 text-center">
                <div class="empty-state">
                    <i class="fas fa-envelope-open"></i>
                    <p>Belum ada data surat</p>
                </div>
            </td>
        </tr>
        @endforelse

        <x-slot name="pagination">
            {{ $surat->appends(request()->query())->links() }}
        </x-slot>
    </x-table>

</div>

<!-- Modal Delete -->
<x-modal id="deleteModal" title="Konfirmasi Hapus" size="sm" type="confirm">
    <form id="deleteForm" method="POST">
        @csrf
        @method('DELETE')
        <div class="text-center">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
            </div>
            <p class="text-gray-700 mb-2">Hapus surat dengan nomor:</p>
            <p class="text-lg font-bold text-gray-900" id="deleteNomorSurat"></p>
            <p class="text-sm text-red-600 mt-4">Data dan file yang dihapus tidak dapat dikembalikan!</p>
        </div>
    </form>
</x-modal>

@endsection

@push('scripts')
<script>
    // Open Delete Modal
    function openDeleteModal(id, nomorSurat) {
        document.getElementById('deleteNomorSurat').textContent = nomorSurat;
        document.getElementById('deleteForm').action = `/superadmin/surat/${id}`;
        openModal('deleteModal');
    }
</script>
@endpush