@extends('layouts.app')

@section('title', 'Log Book Server')
@section('page-title', 'Log Book Server')

@section('content')
<div class="space-y-6">

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-white/80 mb-1">Total Log</p>
                    <h3 class="text-3xl font-bold">{{ $totalLog }}</h3>
                    <p class="text-xs text-white/70 mt-1">Semua Aktivitas</p>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-server text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-white/80 mb-1">Normal</p>
                    <h3 class="text-3xl font-bold">{{ $statusNormal }}</h3>
                    <p class="text-xs text-white/70 mt-1">Berjalan Lancar</p>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-white/80 mb-1">Warning</p>
                    <h3 class="text-3xl font-bold">{{ $statusWarning }}</h3>
                    <p class="text-xs text-white/70 mt-1">Perlu Perhatian</p>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-white/80 mb-1">Error</p>
                    <h3 class="text-3xl font-bold">{{ $statusError }}</h3>
                    <p class="text-xs text-white/70 mt-1">Butuh Tindakan</p>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-times-circle text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <form method="GET" action="{{ route('superadmin.serverlog.index') }}" class="space-y-4">
            
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-800">
                    <i class="fas fa-filter mr-2"></i>Filter Log Server
                </h3>
                <button type="button" onclick="resetFilter()" class="text-sm text-gray-600 hover:text-gray-800">
                    <i class="fas fa-redo mr-1"></i>Reset Filter
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                
                <!-- Filter Tanggal -->
                <div>
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" 
                           value="{{ request('tanggal') }}" 
                           class="form-input">
                </div>

                <!-- Filter Status -->
                <div>
                    <label class="form-label">Status</label>
                    <select name="status" class="form-input">
                        <option value="">Semua Status</option>
                        <option value="normal" {{ request('status') == 'normal' ? 'selected' : '' }}>Normal</option>
                        <option value="warning" {{ request('status') == 'warning' ? 'selected' : '' }}>Warning</option>
                        <option value="error" {{ request('status') == 'error' ? 'selected' : '' }}>Error</option>
                    </select>
                </div>

                <!-- Button Filter -->
                <div class="flex items-end md:col-span-2">
                    <button type="submit" class="btn btn-primary w-full">
                        <i class="fas fa-search mr-2"></i>Tampilkan
                    </button>
                </div>

            </div>

            <!-- Filter Range Tanggal (Collapsible) -->
            <div id="rangeFilter" class="hidden">
                <div class="border-t border-gray-200 pt-4 mt-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" 
                                   value="{{ request('tanggal_mulai') }}" 
                                   class="form-input">
                        </div>
                        <div>
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" 
                                   value="{{ request('tanggal_selesai') }}" 
                                   class="form-input">
                        </div>
                    </div>
                </div>
            </div>

            <button type="button" onclick="toggleRangeFilter()" class="text-sm text-blue-600 hover:text-blue-800">
                <i class="fas fa-calendar-week mr-1"></i>
                <span id="rangeToggleText">Gunakan Range Tanggal</span>
            </button>

        </form>
    </div>

    <!-- Table Server Log -->
    <x-table 
        id="serverLogTable"
        title="Aktivitas Server"
        :headers="['No', 'Tanggal', 'Aktivitas', 'Status', 'Deskripsi', 'Aksi']"
        addRoute="{{ route('superadmin.serverlog.create') }}"
        addText="Tambah Log"
    >
        @forelse($serverLog as $key => $log)
        <tr class="hover:bg-gray-50 transition">
            <td class="px-6 py-4 text-sm text-gray-900">
                {{ $serverLog->firstItem() + $key }}
            </td>
            <td class="px-6 py-4">
                <div>
                    <p class="text-sm font-semibold text-gray-900">{{ $log->tanggal->format('d M Y') }}</p>
                    <p class="text-xs text-gray-500">{{ $log->tanggal->format('l') }}</p>
                    <p class="text-xs text-gray-400">{{ $log->created_at->format('H:i') }}</p>
                </div>
            </td>
            <td class="px-6 py-4">
                <p class="text-sm font-semibold text-gray-900">{{ $log->aktivitas }}</p>
            </td>
            <td class="px-6 py-4">
                <span class="px-3 py-1 rounded-full text-xs font-semibold inline-flex items-center
                    {{ $log->status == 'normal' ? 'bg-green-100 text-green-700' : '' }}
                    {{ $log->status == 'warning' ? 'bg-yellow-100 text-yellow-700' : '' }}
                    {{ $log->status == 'error' ? 'bg-red-100 text-red-700' : '' }}">
                    <i class="fas fa-{{ $log->status == 'normal' ? 'check-circle' : ($log->status == 'warning' ? 'exclamation-triangle' : 'times-circle') }} mr-1"></i>
                    {{ ucfirst($log->status) }}
                </span>
            </td>
            <td class="px-6 py-4">
                @if($log->deskripsi)
                    <div class="max-w-md">
                        <p class="text-sm text-gray-700 line-clamp-2" title="{{ $log->deskripsi }}">
                            {{ $log->deskripsi }}
                        </p>
                        @if(strlen($log->deskripsi) > 100)
                            <button onclick="showDetail({{ json_encode($log) }})" 
                                    class="text-xs text-blue-600 hover:text-blue-800 mt-1">
                                Lihat lengkap
                            </button>
                        @endif
                    </div>
                @else
                    <span class="text-xs text-gray-400">-</span>
                @endif
            </td>
            <td class="px-6 py-4">
                <div class="flex items-center space-x-2">
                    <a href="{{ route('superadmin.serverlog.edit', $log->id) }}" 
                       class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                       title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    <button onclick="openDeleteModal({{ $log->id }}, '{{ $log->aktivitas }}')" 
                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition"
                            title="Hapus">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" class="px-6 py-12 text-center">
                <div class="empty-state">
                    <i class="fas fa-server"></i>
                    <p>Belum ada log server</p>
                </div>
            </td>
        </tr>
        @endforelse

        <x-slot name="pagination">
            {{ $serverLog->appends(request()->query())->links() }}
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
            <p class="text-gray-700 mb-2">Hapus log server:</p>
            <p class="text-lg font-bold text-gray-900" id="deleteAktivitas"></p>
            <p class="text-sm text-red-600 mt-4">Data yang dihapus tidak dapat dikembalikan!</p>
        </div>
    </form>
</x-modal>

<!-- Modal Detail -->
<x-modal id="detailModal" title="Detail Log Server" size="md" type="info">
    <div class="space-y-4">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-xs text-gray-500 mb-1">Tanggal</p>
                <p id="detailTanggal" class="font-semibold text-gray-900"></p>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">Status</p>
                <span id="detailStatus"></span>
            </div>
        </div>
        <div>
            <p class="text-xs text-gray-500 mb-1">Aktivitas</p>
            <p id="detailAktivitas" class="font-semibold text-gray-900"></p>
        </div>
        <div>
            <p class="text-xs text-gray-500 mb-1">Deskripsi Lengkap</p>
            <div id="detailDeskripsi" class="bg-gray-50 rounded-lg p-4 text-sm text-gray-700"></div>
        </div>
    </div>
    <x-slot name="footerButtons">
        <button type="button" onclick="closeModal('detailModal')" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-purple-700 transition shadow-md">
            Tutup
        </button>
    </x-slot>
</x-modal>

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
        
        const statusClass = log.status === 'normal' ? 'badge-success' : (log.status === 'warning' ? 'badge-warning' : 'badge-danger');
        const statusIcon = log.status === 'normal' ? 'check-circle' : (log.status === 'warning' ? 'exclamation-triangle' : 'times-circle');
        document.getElementById('detailStatus').innerHTML = `<span class="badge ${statusClass}"><i class="fas fa-${statusIcon} mr-1"></i>${log.status.charAt(0).toUpperCase() + log.status.slice(1)}</span>`;
        
        document.getElementById('detailAktivitas').textContent = log.aktivitas;
        document.getElementById('detailDeskripsi').textContent = log.deskripsi || '-';
        
        openModal('detailModal');
    }
</script>
@endpush