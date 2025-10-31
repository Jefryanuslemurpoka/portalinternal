@extends('layouts.app')

@section('title', 'Log Book Surat')
@section('page-title', 'Log Book Surat')

@section('content')
<div class="space-y-4 sm:space-y-6">

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 lg:gap-6">
        <div class="bg-gradient-to-br from-teal-500 to-cyan-600 rounded-lg sm:rounded-xl shadow-lg p-4 sm:p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-white/80 mb-1">Total Surat</p>
                    <h3 class="text-2xl sm:text-3xl font-bold">{{ $totalSurat }}</h3>
                    <p class="text-xs text-white/70 mt-1">Semua Surat</p>
                </div>
                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-envelope text-xl sm:text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg sm:rounded-xl shadow-lg p-4 sm:p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-white/80 mb-1">Surat Masuk</p>
                    <h3 class="text-2xl sm:text-3xl font-bold">{{ $suratMasuk }}</h3>
                    <p class="text-xs text-white/70 mt-1">Diterima</p>
                </div>
                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-inbox text-xl sm:text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-amber-600 rounded-lg sm:rounded-xl shadow-lg p-4 sm:p-6 text-white sm:col-span-2 lg:col-span-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-white/80 mb-1">Surat Keluar</p>
                    <h3 class="text-2xl sm:text-3xl font-bold">{{ $suratKeluar }}</h3>
                    <p class="text-xs text-white/70 mt-1">Dikirim</p>
                </div>
                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-paper-plane text-xl sm:text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg sm:rounded-xl shadow-lg p-4 sm:p-6">
        <form method="GET" action="{{ route('karyawan.surat.index') }}" class="space-y-4">
            
            <div class="flex items-center justify-between mb-3 sm:mb-4">
                <h3 class="text-base sm:text-lg font-bold text-gray-800">
                    <i class="fas fa-filter mr-2"></i>Filter Surat
                </h3>
                <a href="{{ route('karyawan.surat.index') }}" class="text-xs sm:text-sm text-gray-600 hover:text-gray-800">
                    <i class="fas fa-redo mr-1"></i>
                    <span class="hidden sm:inline">Reset Filter</span>
                    <span class="sm:hidden">Reset</span>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                
                <!-- Filter Jenis -->
                <div>
                    <label class="form-label text-xs sm:text-sm">Jenis Surat</label>
                    <select name="jenis" class="form-input text-sm w-full">
                        <option value="">Semua Jenis</option>
                        <option value="masuk" {{ request('jenis') == 'masuk' ? 'selected' : '' }}>Surat Masuk</option>
                        <option value="keluar" {{ request('jenis') == 'keluar' ? 'selected' : '' }}>Surat Keluar</option>
                    </select>
                </div>

                <!-- Filter Bulan -->
                <div>
                    <label class="form-label text-xs sm:text-sm">Bulan</label>
                    <select name="bulan" class="form-input text-sm w-full">
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
                    <label class="form-label text-xs sm:text-sm">Tahun</label>
                    <select name="tahun" class="form-input text-sm w-full">
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
                    <button type="submit" class="btn btn-primary w-full text-sm">
                        <i class="fas fa-search mr-2"></i>Tampilkan
                    </button>
                </div>

            </div>

        </form>
    </div>

    <!-- Table Surat -->
    <div class="bg-white rounded-lg sm:rounded-xl shadow-lg overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-gray-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-4">
            <h3 class="text-base sm:text-lg font-bold text-gray-800">
                <i class="fas fa-list mr-2"></i>Daftar Surat
            </h3>
            <a href="{{ route('karyawan.surat.create') }}" class="w-full sm:w-auto inline-flex items-center justify-center px-4 sm:px-5 py-2 sm:py-2.5 bg-gradient-to-r from-teal-500 to-cyan-600 text-white font-semibold rounded-lg hover:from-teal-600 hover:to-cyan-700 transition shadow-md text-sm whitespace-nowrap">
                <i class="fas fa-plus mr-2"></i>
                <span>Tambah Surat</span>
            </a>
        </div>

        <!-- Mobile Card View -->
        <div class="block lg:hidden">
            @forelse($surat as $key => $s)
            <div class="border-b border-gray-200 p-4 hover:bg-gray-50 transition">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-2 py-1 rounded-full text-xs font-semibold
                                {{ $s->jenis == 'masuk' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }}">
                                <i class="fas fa-{{ $s->jenis == 'masuk' ? 'inbox' : 'paper-plane' }} mr-1"></i>
                                {{ ucfirst($s->jenis) }}
                            </span>
                        </div>
                        <p class="text-sm font-bold text-gray-900 mb-1">{{ $s->nomor_surat }}</p>
                        <p class="text-xs text-gray-500">{{ $s->tanggal->format('d M Y') }} • {{ $s->tanggal->diffForHumans() }}</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('karyawan.surat.edit', $s->id) }}" 
                           class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                           title="Edit">
                            <i class="fas fa-edit text-sm"></i>
                        </a>
                        <button onclick="openDeleteModal({{ $s->id }}, '{{ $s->nomor_surat }}')" 
                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition"
                                title="Hapus">
                            <i class="fas fa-trash text-sm"></i>
                        </button>
                    </div>
                </div>
                
                <div class="space-y-2 text-sm">
                    <div class="flex">
                        <span class="text-gray-600 w-24 flex-shrink-0">Pengirim:</span>
                        <span class="text-gray-900 font-medium">{{ $s->pengirim }}</span>
                    </div>
                    <div class="flex">
                        <span class="text-gray-600 w-24 flex-shrink-0">Penerima:</span>
                        <span class="text-gray-900 font-medium">{{ $s->penerima }}</span>
                    </div>
                    <div class="flex">
                        <span class="text-gray-600 w-24 flex-shrink-0">Perihal:</span>
                        <span class="text-gray-900">{{ Str::limit($s->perihal, 50) }}</span>
                    </div>
                    @if($s->file_path)
                    <div class="pt-2">
                        <a href="{{ route('karyawan.surat.download', $s->id) }}" 
                           class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition text-xs font-medium">
                            <i class="fas fa-download mr-1"></i>
                            Download File
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @empty
            <div class="p-8 sm:p-12 text-center">
                <div class="empty-state">
                    <i class="fas fa-envelope-open text-4xl sm:text-5xl"></i>
                    <p class="text-sm sm:text-base">Belum ada data surat</p>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Desktop Table View -->
        <div class="hidden lg:block overflow-x-auto">
            <table id="suratTable" class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-4 xl:px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No</th>
                        <th class="px-4 xl:px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">No. Surat</th>
                        <th class="px-4 xl:px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Jenis</th>
                        <th class="px-4 xl:px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tanggal</th>
                        <th class="px-4 xl:px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Pengirim</th>
                        <th class="px-4 xl:px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Penerima</th>
                        <th class="px-4 xl:px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Perihal</th>
                        <th class="px-4 xl:px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">File</th>
                        <th class="px-4 xl:px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($surat as $key => $s)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 xl:px-6 py-4 text-sm text-gray-900">
                            {{ $surat->firstItem() + $key }}
                        </td>
                        <td class="px-4 xl:px-6 py-4">
                            <p class="text-sm font-semibold text-gray-900">{{ $s->nomor_surat }}</p>
                        </td>
                        <td class="px-4 xl:px-6 py-4">
                            <span class="px-2 xl:px-3 py-1 rounded-full text-xs font-semibold
                                {{ $s->jenis == 'masuk' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700' }}">
                                <i class="fas fa-{{ $s->jenis == 'masuk' ? 'inbox' : 'paper-plane' }} mr-1"></i>
                                {{ ucfirst($s->jenis) }}
                            </span>
                        </td>
                        <td class="px-4 xl:px-6 py-4">
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $s->tanggal->format('d M Y') }}</p>
                                <p class="text-xs text-gray-500">{{ $s->tanggal->diffForHumans() }}</p>
                            </div>
                        </td>
                        <td class="px-4 xl:px-6 py-4">
                            <p class="text-sm text-gray-900">{{ $s->pengirim }}</p>
                        </td>
                        <td class="px-4 xl:px-6 py-4">
                            <p class="text-sm text-gray-900">{{ $s->penerima }}</p>
                        </td>
                        <td class="px-4 xl:px-6 py-4">
                            <div class="max-w-xs">
                                <p class="text-sm text-gray-700 line-clamp-2" title="{{ $s->perihal }}">{{ $s->perihal }}</p>
                            </div>
                        </td>
                        <td class="px-4 xl:px-6 py-4">
                            @if($s->file_path)
                                <a href="{{ route('karyawan.surat.download', $s->id) }}" 
                                   class="inline-flex items-center px-3 py-1 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition text-xs">
                                    <i class="fas fa-download mr-1"></i>
                                    Download
                                </a>
                            @else
                                <span class="text-xs text-gray-400">Tidak ada file</span>
                            @endif
                        </td>
                        <td class="px-4 xl:px-6 py-4">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('karyawan.surat.edit', $s->id) }}" 
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
                </tbody>
            </table>
        </div>

        @if($surat->hasPages())
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-gray-200">
            {{ $surat->appends(request()->query())->links() }}
        </div>
        @endif
    </div>

</div>

<!-- Modal Delete -->
<div id="deleteModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full transform transition-all">
        <div class="p-4 sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg sm:text-xl font-bold text-gray-900">Konfirmasi Hapus</h3>
                <button onclick="closeModal('deleteModal')" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="text-center">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-exclamation-triangle text-red-600 text-xl sm:text-2xl"></i>
                    </div>
                    <p class="text-sm sm:text-base text-gray-700 mb-2">Hapus surat dengan nomor:</p>
                    <p class="text-base sm:text-lg font-bold text-gray-900 break-words px-2" id="deleteNomorSurat"></p>
                    <p class="text-xs sm:text-sm text-red-600 mt-3 sm:mt-4">Data dan file yang dihapus tidak dapat dikembalikan!</p>
                </div>
                
                <div class="flex gap-3 mt-5 sm:mt-6">
                    <button type="button" onclick="closeModal('deleteModal')" class="flex-1 px-4 py-2 sm:py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium transition text-sm">
                        Batal
                    </button>
                    <button type="submit" class="flex-1 px-4 py-2 sm:py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium transition text-sm">
                        Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Open Delete Modal
    function openDeleteModal(id, nomorSurat) {
        document.getElementById('deleteNomorSurat').textContent = nomorSurat;
        document.getElementById('deleteForm').action = `/karyawan/surat/${id}`;
        openModal('deleteModal');
    }

    // Open Modal
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    // Close Modal
    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = 'auto';
    }

    // Close modal when clicking outside
    document.getElementById('deleteModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal('deleteModal');
        }
    });

    // Close modal with ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal('deleteModal');
        }
    });
</script>
@endpush