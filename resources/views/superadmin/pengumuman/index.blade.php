@extends('layouts.app')

@section('title', 'Pengumuman')
@section('page-title', 'Pengumuman')

@section('content')
<div class="space-y-6">

    <!-- Statistics Card -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-teal-700 to-teal-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-white/80 mb-1">Total Pengumuman</p>
                    <h3 class="text-3xl font-bold">{{ $pengumuman->total() }}</h3>
                    <p class="text-xs text-white/70 mt-1">Semua Pengumuman</p>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-bullhorn text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-cyan-600 to-teal-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-white/80 mb-1">Bulan Ini</p>
                    <h3 class="text-3xl font-bold">{{ $pengumuman->where('tanggal', '>=', now()->startOfMonth())->count() }}</h3>
                    <p class="text-xs text-white/70 mt-1">Pengumuman Baru</p>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-calendar-check text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-teal-600 to-cyan-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-white/80 mb-1">Minggu Ini</p>
                    <h3 class="text-3xl font-bold">{{ $pengumuman->where('tanggal', '>=', now()->startOfWeek())->count() }}</h3>
                    <p class="text-xs text-white/70 mt-1">Pengumuman Terbaru</p>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-bell text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- List Pengumuman -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        
        <!-- Header -->
        <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-800">
                    <i class="fas fa-list mr-2"></i>Daftar Pengumuman
                </h3>
                <a href="{{ route('superadmin.pengumuman.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus mr-2"></i>Tambah Pengumuman
                </a>
            </div>
        </div>

        <!-- List Items -->
        <div class="divide-y divide-gray-200">
            @forelse($pengumuman as $p)
            <div class="p-6 hover:bg-gray-50 transition">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <!-- Header -->
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-teal-600 to-cyan-600 rounded-full flex items-center justify-center text-white">
                                <i class="fas fa-bullhorn text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-lg font-bold text-gray-900 mb-1">{{ $p->judul }}</h4>
                                <div class="flex items-center space-x-4 text-sm text-gray-500">
                                    <span>
                                        <i class="fas fa-calendar mr-1"></i>
                                        {{ $p->tanggal->format('d M Y') }}
                                    </span>
                                    <span>
                                        <i class="fas fa-user mr-1"></i>
                                        {{ $p->creator->name }}
                                    </span>
                                    <span>
                                        <i class="fas fa-clock mr-1"></i>
                                        {{ $p->tanggal->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="ml-15">
                            <p class="text-gray-700 leading-relaxed">
                                {{ Str::limit($p->konten, 200) }}
                            </p>
                            @if(strlen($p->konten) > 200)
                                <button onclick="showDetail({{ json_encode($p) }})" 
                                        class="text-teal-600 hover:text-teal-800 text-sm mt-2 font-medium">
                                    Baca selengkapnya <i class="fas fa-arrow-right ml-1"></i>
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center space-x-2 ml-4">
                        <a href="{{ route('superadmin.pengumuman.edit', $p->id) }}" 
                           class="p-2 text-teal-600 hover:bg-teal-50 rounded-lg transition"
                           title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button onclick="openDeleteModal({{ $p->id }}, '{{ $p->judul }}')" 
                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition"
                                title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-12 text-center">
                <div class="empty-state">
                    <i class="fas fa-bullhorn"></i>
                    <p>Belum ada pengumuman</p>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($pengumuman->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $pengumuman->links() }}
        </div>
        @endif

    </div>

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
            <p class="text-gray-700 mb-2">Hapus pengumuman:</p>
            <p class="text-lg font-bold text-gray-900" id="deleteJudul"></p>
            <p class="text-sm text-red-600 mt-4">Data yang dihapus tidak dapat dikembalikan!</p>
        </div>
    </form>
</x-modal>

<!-- Modal Detail -->
<x-modal id="detailModal" title="Detail Pengumuman" size="lg" type="info">
    <div class="space-y-4">
        <div class="flex items-center space-x-3 pb-4 border-b">
            <div class="w-12 h-12 bg-gradient-to-br from-teal-600 to-cyan-600 rounded-full flex items-center justify-center text-white">
                <i class="fas fa-bullhorn text-xl"></i>
            </div>
            <div>
                <h4 id="detailJudul" class="text-xl font-bold text-gray-900"></h4>
                <div class="flex items-center space-x-3 text-sm text-gray-500 mt-1">
                    <span id="detailTanggal"></span>
                    <span>•</span>
                    <span id="detailCreator"></span>
                </div>
            </div>
        </div>

        <div>
            <p class="text-gray-700 leading-relaxed whitespace-pre-wrap" id="detailKonten"></p>
        </div>
    </div>
    <x-slot name="footerButtons">
        <button type="button" onclick="closeModal('detailModal')" class="px-5 py-2.5 bg-gradient-to-r from-teal-600 to-cyan-600 text-white font-semibold rounded-lg hover:from-teal-700 hover:to-cyan-700 transition shadow-md">
            Tutup
        </button>
    </x-slot>
</x-modal>

@endsection

@push('scripts')
<script>
    // Open Delete Modal
    function openDeleteModal(id, judul) {
        document.getElementById('deleteJudul').textContent = judul;
        document.getElementById('deleteForm').action = `/superadmin/pengumuman/${id}`;
        openModal('deleteModal');
    }

    // Show Detail
    function showDetail(data) {
        const pengumuman = typeof data === 'string' ? JSON.parse(data) : data;
        
        document.getElementById('detailJudul').textContent = pengumuman.judul;
        document.getElementById('detailTanggal').innerHTML = `<i class="fas fa-calendar mr-1"></i>${new Date(pengumuman.tanggal).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}`;
        document.getElementById('detailCreator').innerHTML = `<i class="fas fa-user mr-1"></i>${pengumuman.creator.name}`;
        document.getElementById('detailKonten').textContent = pengumuman.konten;
        
        openModal('detailModal');
    }
</script>
@endpush