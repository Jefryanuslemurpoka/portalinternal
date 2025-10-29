@extends('layouts.app')

@section('title', 'Log Book Server')
@section('page-title', 'Log Book Server')

@section('content')
<div class="space-y-4 sm:space-y-6">

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 md:gap-6">
        <div class="bg-gradient-to-br from-teal-500 to-cyan-600 rounded-lg sm:rounded-xl shadow-lg p-4 sm:p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-white/80 mb-1">Total Log</p>
                    <h3 class="text-xl sm:text-2xl md:text-3xl font-bold">{{ $totalLog }}</h3>
                    <p class="text-xs text-white/70 mt-1 hidden sm:block">Semua Aktivitas</p>
                </div>
                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-server text-xl sm:text-2xl md:text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg sm:rounded-xl shadow-lg p-4 sm:p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-white/80 mb-1">Normal</p>
                    <h3 class="text-xl sm:text-2xl md:text-3xl font-bold">{{ $statusNormal }}</h3>
                    <p class="text-xs text-white/70 mt-1 hidden sm:block">Berjalan Lancar</p>
                </div>
                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-xl sm:text-2xl md:text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-yellow-500 to-orange-500 rounded-lg sm:rounded-xl shadow-lg p-4 sm:p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-white/80 mb-1">Warning</p>
                    <h3 class="text-xl sm:text-2xl md:text-3xl font-bold">{{ $statusWarning }}</h3>
                    <p class="text-xs text-white/70 mt-1 hidden sm:block">Perlu Perhatian</p>
                </div>
                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-xl sm:text-2xl md:text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-lg sm:rounded-xl shadow-lg p-4 sm:p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-white/80 mb-1">Error</p>
                    <h3 class="text-xl sm:text-2xl md:text-3xl font-bold">{{ $statusError }}</h3>
                    <p class="text-xs text-white/70 mt-1 hidden sm:block">Butuh Tindakan</p>
                </div>
                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-times-circle text-xl sm:text-2xl md:text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg sm:rounded-xl shadow-lg p-4 sm:p-6">
        <form method="GET" action="{{ route('superadmin.serverlog.index') }}" class="space-y-4">
            
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                <h3 class="text-base sm:text-lg font-bold text-gray-800">
                    <i class="fas fa-filter mr-2"></i>Filter Log Server
                </h3>
                <button type="button" onclick="resetFilter()" class="text-sm text-gray-600 hover:text-gray-800 text-left sm:text-right">
                    <i class="fas fa-redo mr-1"></i>Reset Filter
                </button>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                
                <!-- Filter Tanggal -->
                <div>
                    <label class="form-label text-sm">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" 
                           value="{{ request('tanggal') }}" 
                           class="form-input w-full text-sm">
                </div>

                <!-- Filter Status -->
                <div>
                    <label class="form-label text-sm">Status</label>
                    <select name="status" class="form-input w-full text-sm">
                        <option value="">Semua Status</option>
                        <option value="normal" {{ request('status') == 'normal' ? 'selected' : '' }}>Normal</option>
                        <option value="warning" {{ request('status') == 'warning' ? 'selected' : '' }}>Warning</option>
                        <option value="error" {{ request('status') == 'error' ? 'selected' : '' }}>Error</option>
                    </select>
                </div>

                <!-- Button Filter -->
                <div class="flex items-end sm:col-span-2">
                    <button type="submit" class="btn btn-primary w-full text-sm">
                        <i class="fas fa-search mr-2"></i>Tampilkan
                    </button>
                </div>

            </div>

            <!-- Filter Range Tanggal (Collapsible) -->
            <div id="rangeFilter" class="hidden">
                <div class="border-t border-gray-200 pt-4 mt-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                        <div>
                            <label class="form-label text-sm">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" 
                                   value="{{ request('tanggal_mulai') }}" 
                                   class="form-input w-full text-sm">
                        </div>
                        <div>
                            <label class="form-label text-sm">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" 
                                   value="{{ request('tanggal_selesai') }}" 
                                   class="form-input w-full text-sm">
                        </div>
                    </div>
                </div>
            </div>

            <button type="button" onclick="toggleRangeFilter()" class="text-xs sm:text-sm text-teal-600 hover:text-teal-800">
                <i class="fas fa-calendar-week mr-1"></i>
                <span id="rangeToggleText">Gunakan Range Tanggal</span>
            </button>

        </form>
    </div>

    <!-- Table Server Log -->
    <div class="bg-white rounded-lg sm:rounded-xl shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4 p-4 sm:p-6 border-b border-gray-200">
            <h2 class="text-lg sm:text-xl font-bold text-gray-800">
                <i class="fas fa-server mr-2"></i>Aktivitas Server
            </h2>
            <a href="{{ route('superadmin.serverlog.create') }}" 
               class="inline-flex items-center justify-center px-4 sm:px-5 py-2 sm:py-2.5 bg-gradient-to-r from-teal-500 to-cyan-600 text-white font-semibold rounded-lg hover:from-teal-600 hover:to-cyan-700 transition shadow-md text-sm whitespace-nowrap">
                <i class="fas fa-plus mr-2"></i>
                Tambah
            </a>
        </div>

        <!-- Table for Desktop/Tablet -->
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aktivitas</th>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                        <th class="px-4 lg:px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($serverLog as $key => $log)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 lg:px-6 py-4 text-sm text-gray-900">
                            {{ $serverLog->firstItem() + $key }}
                        </td>
                        <td class="px-4 lg:px-6 py-4">
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $log->tanggal->format('d M Y') }}</p>
                                <p class="text-xs text-gray-500">{{ $log->tanggal->format('l') }}</p>
                                <p class="text-xs text-gray-400">{{ $log->created_at->format('H:i') }}</p>
                            </div>
                        </td>
                        <td class="px-4 lg:px-6 py-4">
                            <p class="text-sm font-semibold text-gray-900">{{ $log->aktivitas }}</p>
                        </td>
                        <td class="px-4 lg:px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold inline-flex items-center
                                {{ $log->status == 'normal' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $log->status == 'warning' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $log->status == 'error' ? 'bg-red-100 text-red-700' : '' }}">
                                <i class="fas fa-{{ $log->status == 'normal' ? 'check-circle' : ($log->status == 'warning' ? 'exclamation-triangle' : 'times-circle') }} mr-1"></i>
                                {{ ucfirst($log->status) }}
                            </span>
                        </td>
                        <td class="px-4 lg:px-6 py-4">
                            @if($log->deskripsi)
                                <div class="max-w-md">
                                    <p class="text-sm text-gray-700 line-clamp-2" title="{{ $log->deskripsi }}">
                                        {{ $log->deskripsi }}
                                    </p>
                                    @if(strlen($log->deskripsi) > 100)
                                        <button onclick="showDetail({{ json_encode($log) }})" 
                                                class="text-xs text-teal-600 hover:text-teal-800 mt-1">
                                            Lihat lengkap
                                        </button>
                                    @endif
                                </div>
                            @else
                                <span class="text-xs text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 lg:px-6 py-4">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('superadmin.serverlog.edit', $log->id) }}" 
                                   class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="openDeleteModal({{ $log->id }}, '{{ addslashes($log->aktivitas) }}')" 
                                        class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition"
                                        title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 lg:px-6 py-12 text-center">
                            <div class="empty-state">
                                <i class="fas fa-server"></i>
                                <p>Belum ada log server</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Card View for Mobile -->
        <div class="md:hidden divide-y divide-gray-200">
            @forelse($serverLog as $key => $log)
            <div class="p-4 hover:bg-gray-50 transition">
                <div class="flex justify-between items-start mb-3">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-xs font-semibold text-gray-500">#{{ $serverLog->firstItem() + $key }}</span>
                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold
                                {{ $log->status == 'normal' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $log->status == 'warning' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $log->status == 'error' ? 'bg-red-100 text-red-700' : '' }}">
                                <i class="fas fa-{{ $log->status == 'normal' ? 'check-circle' : ($log->status == 'warning' ? 'exclamation-triangle' : 'times-circle') }} mr-1"></i>
                                {{ ucfirst($log->status) }}
                            </span>
                        </div>
                        <p class="text-sm font-bold text-gray-900 mb-1">{{ $log->aktivitas }}</p>
                        <p class="text-xs text-gray-500">{{ $log->tanggal->format('d M Y, H:i') }}</p>
                    </div>
                </div>
                
                @if($log->deskripsi)
                <div class="mb-3">
                    <p class="text-xs text-gray-700 line-clamp-2">{{ $log->deskripsi }}</p>
                    @if(strlen($log->deskripsi) > 80)
                        <button onclick="showDetail({{ json_encode($log) }})" 
                                class="text-xs text-teal-600 hover:text-teal-800 mt-1">
                            Lihat lengkap
                        </button>
                    @endif
                </div>
                @endif

                <div class="flex gap-2 mt-3">
                    <a href="{{ route('superadmin.serverlog.edit', $log->id) }}" 
                       class="flex-1 text-center py-2 px-3 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition text-sm font-medium">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </a>
                    <button onclick="openDeleteModal({{ $log->id }}, '{{ addslashes($log->aktivitas) }}')" 
                            class="flex-1 text-center py-2 px-3 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition text-sm font-medium">
                        <i class="fas fa-trash mr-1"></i>Hapus
                    </button>
                </div>
            </div>
            @empty
            <div class="p-8 text-center">
                <div class="empty-state">
                    <i class="fas fa-server text-4xl"></i>
                    <p class="text-sm">Belum ada log server</p>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="px-4 py-3 sm:px-6 border-t border-gray-200">
            {{ $serverLog->appends(request()->query())->links() }}
        </div>
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
                    <p class="text-sm sm:text-base text-gray-700 mb-2">Hapus log server:</p>
                    <p class="text-base sm:text-lg font-bold text-gray-900 break-words" id="deleteAktivitas"></p>
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
    <div class="bg-white rounded-lg sm:rounded-xl shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-4 sm:p-6">
            <h3 class="text-lg sm:text-xl font-bold text-gray-900 mb-4">Detail Log Server</h3>
            <div class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Tanggal</p>
                        <p id="detailTanggal" class="text-sm font-semibold text-gray-900"></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Status</p>
                        <span id="detailStatus"></span>
                    </div>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-1">Aktivitas</p>
                    <p id="detailAktivitas" class="text-sm font-semibold text-gray-900"></p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-1">Deskripsi Lengkap</p>
                    <div id="detailDeskripsi" class="bg-gray-50 rounded-lg p-4 text-sm text-gray-700"></div>
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
    // Toggle Range Filter
    function toggleRangeFilter() {
        const rangeFilter = document.getElementById('rangeFilter');
        const toggleText = document.getElementById('rangeToggleText');
        const tanggalInput = document.getElementById('tanggal');
        
        if (rangeFilter.classList.contains('hidden')) {
            rangeFilter.classList.remove('hidden');
            toggleText.textContent = 'Gunakan Tanggal Tunggal';
            tanggalInput.disabled = true;
        } else {
            rangeFilter.classList.add('hidden');
            toggleText.textContent = 'Gunakan Range Tanggal';
            tanggalInput.disabled = false;
        }
    }

    // Reset Filter
    function resetFilter() {
        window.location.href = "{{ route('superadmin.serverlog.index') }}";
    }

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
    function openDeleteModal(id, aktivitas) {
        document.getElementById('deleteAktivitas').textContent = aktivitas;
        document.getElementById('deleteForm').action = `/superadmin/server-log/${id}`;
        openModal('deleteModal');
    }

    // Show Detail
    function showDetail(data) {
        const log = typeof data === 'string' ? JSON.parse(data) : data;
        
        document.getElementById('detailTanggal').textContent = new Date(log.tanggal).toLocaleDateString('id-ID', { 
            day: 'numeric', 
            month: 'long', 
            year: 'numeric' 
        });
        
        const statusColors = {
            'normal': 'bg-green-100 text-green-700',
            'warning': 'bg-yellow-100 text-yellow-700',
            'error': 'bg-red-100 text-red-700'
        };
        const statusIcons = {
            'normal': 'check-circle',
            'warning': 'exclamation-triangle',
            'error': 'times-circle'
        };
        
        document.getElementById('detailStatus').innerHTML = `<span class="px-3 py-1 rounded-full text-xs font-semibold inline-flex items-center ${statusColors[log.status]}"><i class="fas fa-${statusIcons[log.status]} mr-1"></i>${log.status.charAt(0).toUpperCase() + log.status.slice(1)}</span>`;
        
        document.getElementById('detailAktivitas').textContent = log.aktivitas;
        document.getElementById('detailDeskripsi').textContent = log.deskripsi || '-';
        
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