@extends('layouts.app')

@section('title', 'Pengumuman')
@section('page-title', 'Pengumuman')

@section('content')
<div class="space-y-4 sm:space-y-6">

    <!-- Statistics Card -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
        <!-- Card 1: Total Pengumuman - Teal Gradient (Original) -->
        <div class="bg-gradient-to-br from-teal-700 to-teal-600 rounded-lg sm:rounded-xl shadow-lg p-4 sm:p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-white/80 mb-1">Total Pengumuman</p>
                    <h3 class="text-2xl sm:text-3xl font-bold">{{ $pengumuman->total() }}</h3>
                    <p class="text-xs text-white/70 mt-1">Semua Pengumuman</p>
                </div>
                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-bullhorn text-xl sm:text-3xl"></i>
                </div>
            </div>
        </div>

        <!-- Card 2: Bulan Ini - Orange Gradient -->
        <div class="bg-gradient-to-br from-orange-500 to-red-600 rounded-lg sm:rounded-xl shadow-lg p-4 sm:p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-white/80 mb-1">Bulan Ini</p>
                    <h3 class="text-2xl sm:text-3xl font-bold">{{ $pengumuman->where('tanggal', '>=', now()->startOfMonth())->count() }}</h3>
                    <p class="text-xs text-white/70 mt-1">Pengumuman Baru</p>
                </div>
                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-calendar-check text-xl sm:text-3xl"></i>
                </div>
            </div>
        </div>

        <!-- Card 3: Minggu Ini - Blue Gradient -->
        <div class="bg-gradient-to-br from-blue-500 to-cyan-600 rounded-lg sm:rounded-xl shadow-lg p-4 sm:p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-white/80 mb-1">Minggu Ini</p>
                    <h3 class="text-2xl sm:text-3xl font-bold">{{ $pengumuman->where('tanggal', '>=', now()->startOfWeek())->count() }}</h3>
                    <p class="text-xs text-white/70 mt-1">Pengumuman Terbaru</p>
                </div>
                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-bell text-xl sm:text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- List Pengumuman -->
    <div class="bg-white rounded-lg sm:rounded-xl shadow-lg overflow-hidden">
        
        <!-- Header -->
        <div class="px-4 sm:px-6 py-4 border-b border-gray-200 bg-gray-50">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <h3 class="text-base sm:text-lg font-bold text-gray-800">
                    <i class="fas fa-list mr-2"></i>Daftar Pengumuman
                </h3>
                <a href="{{ route('superadmin.pengumuman.create') }}" 
                   class="inline-flex items-center justify-center px-4 sm:px-5 py-2 sm:py-2.5 bg-gradient-to-r from-teal-500 to-cyan-600 text-white font-semibold rounded-lg hover:from-teal-600 hover:to-cyan-700 transition shadow-md text-sm whitespace-nowrap">
                    <i class="fas fa-plus mr-2"></i>Tambah
                </a>
            </div>
        </div>

        <!-- List Items - Desktop/Tablet View -->
        <div class="hidden sm:block divide-y divide-gray-200">
            @forelse($pengumuman as $p)
            <div class="p-4 sm:p-6 hover:bg-gray-50 transition">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <!-- Header -->
                        <div class="flex items-center space-x-3 mb-3">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-teal-600 to-cyan-600 rounded-full flex items-center justify-center text-white flex-shrink-0">
                                <i class="fas fa-bullhorn text-base sm:text-xl"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-base sm:text-lg font-bold text-gray-900 mb-1 truncate">{{ $p->judul }}</h4>
                                <div class="flex flex-wrap items-center gap-2 sm:gap-4 text-xs sm:text-sm text-gray-500">
                                    <span class="inline-flex items-center">
                                        <i class="fas fa-calendar mr-1"></i>
                                        {{ $p->tanggal->format('d M Y') }}
                                    </span>
                                    <span class="inline-flex items-center">
                                        <i class="fas fa-user mr-1"></i>
                                        <span class="truncate max-w-[100px] sm:max-w-none">{{ $p->creator->name }}</span>
                                    </span>
                                    <span class="inline-flex items-center">
                                        <i class="fas fa-clock mr-1"></i>
                                        {{ $p->tanggal->diffForHumans() }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="ml-0 sm:ml-15">
                            <p class="text-sm sm:text-base text-gray-700 leading-relaxed">
                                {{ Str::limit($p->konten, 200) }}
                            </p>
                            @if(strlen($p->konten) > 200)
                                <button onclick="showDetail({{ json_encode($p) }})" 
                                        class="text-teal-600 hover:text-teal-800 text-xs sm:text-sm mt-2 font-medium">
                                    Baca selengkapnya <i class="fas fa-arrow-right ml-1"></i>
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center space-x-2 ml-4 flex-shrink-0">
                        <a href="{{ route('superadmin.pengumuman.edit', $p->id) }}" 
                           class="p-2 text-teal-600 hover:bg-teal-50 rounded-lg transition"
                           title="Edit">
                            <i class="fas fa-edit"></i>
                        </a>
                        <button onclick="openDeleteModal({{ $p->id }}, '{{ addslashes($p->judul) }}')" 
                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition"
                                title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-8 sm:p-12 text-center">
                <div class="empty-state">
                    <i class="fas fa-bullhorn text-4xl sm:text-5xl"></i>
                    <p class="text-sm sm:text-base">Belum ada pengumuman</p>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Card View - Mobile Only -->
        <div class="sm:hidden divide-y divide-gray-200">
            @forelse($pengumuman as $p)
            <div class="p-4 hover:bg-gray-50 transition">
                <div class="flex items-start gap-3 mb-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-teal-600 to-cyan-600 rounded-full flex items-center justify-center text-white flex-shrink-0">
                        <i class="fas fa-bullhorn text-base"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-sm font-bold text-gray-900 mb-1">{{ $p->judul }}</h4>
                        <div class="flex flex-wrap items-center gap-2 text-xs text-gray-500">
                            <span><i class="fas fa-calendar mr-1"></i>{{ $p->tanggal->format('d M Y') }}</span>
                            <span>•</span>
                            <span class="truncate max-w-[120px]">{{ $p->creator->name }}</span>
                        </div>
                    </div>
                </div>

                <p class="text-xs text-gray-700 leading-relaxed mb-2">
                    {{ Str::limit($p->konten, 120) }}
                </p>

                @if(strlen($p->konten) > 120)
                    <button onclick="showDetail({{ json_encode($p) }})" 
                            class="text-teal-600 hover:text-teal-800 text-xs font-medium mb-3">
                        Baca selengkapnya <i class="fas fa-arrow-right ml-1"></i>
                    </button>
                @endif

                <div class="flex gap-2 mt-3 pt-3 border-t border-gray-100">
                    <a href="{{ route('superadmin.pengumuman.edit', $p->id) }}" 
                       class="flex-1 text-center py-2 px-3 bg-teal-50 text-teal-600 rounded-lg hover:bg-teal-100 transition text-xs font-medium">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </a>
                    <button onclick="openDeleteModal({{ $p->id }}, '{{ addslashes($p->judul) }}')" 
                            class="flex-1 text-center py-2 px-3 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition text-xs font-medium">
                        <i class="fas fa-trash mr-1"></i>Hapus
                    </button>
                </div>
            </div>
            @empty
            <div class="p-8 text-center">
                <div class="empty-state">
                    <i class="fas fa-bullhorn text-4xl"></i>
                    <p class="text-sm">Belum ada pengumuman</p>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($pengumuman->hasPages())
        <div class="px-4 sm:px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $pengumuman->links() }}
        </div>
        @endif

    </div>

</div>

<!-- Modal Delete -->
<div id="deleteModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg sm:rounded-xl shadow-xl max-w-md w-full mx-4">
        <div class="p-4 sm:p-6">
            <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-4">Konfirmasi Hapus</h3>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="text-center">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                    </div>
                    <p class="text-sm sm:text-base text-gray-700 mb-2">Hapus pengumuman:</p>
                    <p class="text-base sm:text-lg font-bold text-gray-900 break-words px-2" id="deleteJudul"></p>
                    <p class="text-xs sm:text-sm text-red-600 mt-4">Data yang dihapus tidak dapat dikembalikan!</p>
                </div>
                <div class="flex flex-col sm:flex-row gap-3 mt-6">
                    <button type="button" onclick="closeModal('deleteModal')" 
                            class="flex-1 px-4 py-2.5 bg-gray-200 text-gray-800 font-semibold rounded-lg hover:bg-gray-300 transition text-sm">
                        Batal
                    </button>
                    <button type="submit" 
                            class="flex-1 px-4 py-2.5 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition text-sm">
                        Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Detail -->
<div id="detailModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg sm:rounded-xl shadow-xl max-w-3xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-4 sm:p-6">
            <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-4">Detail Pengumuman</h3>
            <div class="space-y-4">
                <div class="flex items-center space-x-3 pb-4 border-b">
                    <div class="w-12 h-12 bg-gradient-to-br from-teal-600 to-cyan-600 rounded-full flex items-center justify-center text-white flex-shrink-0">
                        <i class="fas fa-bullhorn text-xl"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 id="detailJudul" class="text-base sm:text-xl font-bold text-gray-900 break-words"></h4>
                        <div class="flex flex-wrap items-center gap-2 sm:gap-3 text-xs sm:text-sm text-gray-500 mt-1">
                            <span id="detailTanggal"></span>
                            <span class="hidden sm:inline">•</span>
                            <span id="detailCreator" class="truncate max-w-[150px] sm:max-w-none"></span>
                        </div>
                    </div>
                </div>

                <div>
                    <p class="text-sm sm:text-base text-gray-700 leading-relaxed whitespace-pre-wrap" id="detailKonten"></p>
                </div>
            </div>
            <div class="mt-6">
                <button type="button" onclick="closeModal('detailModal')" 
                        class="w-full px-5 py-2.5 bg-gradient-to-r from-teal-500 to-cyan-600 text-white font-semibold rounded-lg hover:from-teal-600 hover:to-cyan-700 transition shadow-md text-sm">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Open Modal
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    // Close Modal
    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

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

    // Close modal when clicking outside
    document.addEventListener('click', function(event) {
        const deleteModal = document.getElementById('deleteModal');
        const detailModal = document.getElementById('detailModal');
        
        if (event.target === deleteModal) {
            closeModal('deleteModal');
        }
        if (event.target === detailModal) {
            closeModal('detailModal');
        }
    });
</script>
@endpush