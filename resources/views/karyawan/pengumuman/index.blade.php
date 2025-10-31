@extends('layouts.app')

@section('title', 'Pengumuman')
@section('page-title', 'Pengumuman')

@section('content')
<div class="space-y-6">

    <!-- Statistics Card -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-teal-500 to-cyan-500 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-white/80 mb-1">Total Pengumuman</p>
                    <h3 class="text-3xl font-bold">{{ $statistik['total'] }}</h3>
                    <p class="text-xs text-white/70 mt-1">Semua Pengumuman</p>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-bullhorn text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-cyan-500 to-blue-500 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-white/80 mb-1">Pengumuman Penting</p>
                    <h3 class="text-3xl font-bold">{{ $statistik['penting'] }}</h3>
                    <p class="text-xs text-white/70 mt-1">Butuh Perhatian</p>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation-circle text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-teal-500 to-emerald-500 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-white/80 mb-1">Minggu Ini</p>
                    <h3 class="text-3xl font-bold">{{ $statistik['terbaru'] }}</h3>
                    <p class="text-xs text-white/70 mt-1">Pengumuman Baru</p>
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
            <h3 class="text-lg font-bold text-gray-800">
                <i class="fas fa-list mr-2"></i>Daftar Pengumuman
            </h3>
        </div>

        <!-- List Items -->
        <div class="divide-y divide-gray-200">
            @forelse($pengumumanList as $pengumuman)
            <div class="p-6 hover:bg-gray-50 transition">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <!-- Header -->
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-teal-500 to-cyan-500 rounded-full flex items-center justify-center text-white flex-shrink-0">
                                <i class="fas fa-bullhorn text-xl"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-lg font-bold text-gray-900 mb-1">{{ $pengumuman->judul }}</h4>
                                <div class="flex items-center space-x-4 text-sm text-gray-500">
                                    <span>
                                        <i class="fas fa-calendar mr-1"></i>
                                        {{ \Carbon\Carbon::parse($pengumuman->tanggal)->format('d M Y') }}
                                    </span>
                                    <span>
                                        <i class="fas fa-user mr-1"></i>
                                        {{ $pengumuman->creator->name ?? 'Admin' }}
                                    </span>
                                    <span>
                                        <i class="fas fa-clock mr-1"></i>
                                        {{ \Carbon\Carbon::parse($pengumuman->created_at)->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="ml-15">
                            <p class="text-gray-700 leading-relaxed">
                                {{ Str::limit($pengumuman->konten, 200) }}
                            </p>
                            @if(strlen($pengumuman->konten) > 200)
                                <button onclick="showDetail({{ json_encode($pengumuman) }})" 
                                        class="text-teal-600 hover:text-teal-800 text-sm mt-2 font-medium">
                                    Baca selengkapnya <i class="fas fa-arrow-right ml-1"></i>
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- View Button -->
                    <div class="ml-4">
                        <button onclick="showDetail({{ json_encode($pengumuman) }})" 
                                class="p-2 text-teal-600 hover:bg-teal-50 rounded-lg transition"
                                title="Lihat Detail">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-12 text-center">
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>
                        @if($search)
                            Tidak ditemukan pengumuman dengan kata kunci "{{ $search }}"
                        @else
                            Belum ada pengumuman
                        @endif
                    </p>
                    @if($search || $kategori != 'all')
                        <a href="{{ route('karyawan.pengumuman.index') }}" class="mt-4 px-5 py-2.5 bg-gradient-to-r from-teal-600 to-cyan-600 text-white font-semibold rounded-lg hover:from-teal-700 hover:to-cyan-700 transition shadow-md inline-block">
                            <i class="fas fa-redo mr-2"></i>Reset Filter
                        </a>
                    @endif
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($pengumumanList->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $pengumumanList->links() }}
        </div>
        @endif

    </div>

</div>

<!-- Modal Detail -->
<x-modal id="detailModal" title="Detail Pengumuman" size="lg" type="info">
    <div class="space-y-4">
        <div class="flex items-center space-x-3 pb-4 border-b">
            <div class="w-12 h-12 bg-gradient-to-br from-teal-500 to-cyan-500 rounded-full flex items-center justify-center text-white">
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
    // Show Detail
    function showDetail(data) {
        const pengumuman = typeof data === 'string' ? JSON.parse(data) : data;
        
        document.getElementById('detailJudul').textContent = pengumuman.judul;
        document.getElementById('detailTanggal').innerHTML = `<i class="fas fa-calendar mr-1"></i>${new Date(pengumuman.tanggal).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })}`;
        document.getElementById('detailCreator').innerHTML = `<i class="fas fa-user mr-1"></i>${pengumuman.creator ? pengumuman.creator.name : 'Admin'}`;
        document.getElementById('detailKonten').textContent = pengumuman.konten;
        
        openModal('detailModal');
    }
</script>
@endpush