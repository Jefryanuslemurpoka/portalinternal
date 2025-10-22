@extends('layouts.app')

@section('title', 'Persetujuan Cuti/Izin')
@section('page-title', 'Persetujuan Cuti/Izin')

@section('content')
<div class="space-y-6">

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-white/80 mb-1">Pending</p>
                    <h3 class="text-3xl font-bold">{{ $cutiIzin->where('status', 'pending')->count() }}</h3>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-clock text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-teal-500 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-white/80 mb-1">Disetujui</p>
                    <h3 class="text-3xl font-bold">{{ $cutiIzin->where('status', 'disetujui')->count() }}</h3>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-red-500 to-pink-500 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-white/80 mb-1">Ditolak</p>
                    <h3 class="text-3xl font-bold">{{ $cutiIzin->where('status', 'ditolak')->count() }}</h3>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-times-circle text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Pengajuan -->
    <x-table 
        id="cutiIzinTable"
        title="Daftar Pengajuan Cuti/Izin"
        :headers="['No', 'Karyawan', 'Jenis', 'Tanggal', 'Durasi', 'Alasan', 'Status', 'Aksi']"
        :addButton="false"
    >
        <x-slot name="filters">
            <select id="filterStatus" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Status</option>
                <option value="pending">Pending</option>
                <option value="disetujui">Disetujui</option>
                <option value="ditolak">Ditolak</option>
            </select>
            <select id="filterJenis" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Jenis</option>
                <option value="cuti">Cuti</option>
                <option value="izin">Izin</option>
                <option value="sakit">Sakit</option>
            </select>
        </x-slot>

        @forelse($cutiIzin as $key => $ci)
        <tr class="hover:bg-gray-50 transition">
            <td class="px-6 py-4 text-sm text-gray-900">
                {{ $cutiIzin->firstItem() + $key }}
            </td>
            <td class="px-6 py-4">
                <div class="flex items-center space-x-3">
                    <img src="{{ $ci->user->foto ? asset('storage/' . $ci->user->foto) : 'https://ui-avatars.com/api/?name=' . urlencode($ci->user->name) . '&background=4F46E5&color=fff' }}" 
                         alt="{{ $ci->user->name }}"
                         class="w-10 h-10 rounded-full object-cover border-2 border-gray-200">
                    <div>
                        <p class="text-sm font-semibold text-gray-900">{{ $ci->user->name }}</p>
                        <p class="text-xs text-gray-500">{{ $ci->user->divisi }}</p>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4">
                <span class="px-3 py-1 rounded-full text-xs font-semibold
                    {{ $ci->jenis == 'cuti' ? 'bg-blue-100 text-blue-700' : '' }}
                    {{ $ci->jenis == 'izin' ? 'bg-purple-100 text-purple-700' : '' }}
                    {{ $ci->jenis == 'sakit' ? 'bg-red-100 text-red-700' : '' }}">
                    {{ ucfirst($ci->jenis) }}
                </span>
            </td>
            <td class="px-6 py-4">
                <div class="text-sm">
                    <p class="font-semibold text-gray-900">{{ $ci->tanggal_mulai->format('d M Y') }}</p>
                    <p class="text-xs text-gray-500">s/d {{ $ci->tanggal_selesai->format('d M Y') }}</p>
                </div>
            </td>
            <td class="px-6 py-4 text-sm text-gray-900">
                {{ $ci->tanggal_mulai->diffInDays($ci->tanggal_selesai) + 1 }} hari
            </td>
            <td class="px-6 py-4">
                <div class="max-w-xs">
                    <p class="text-sm text-gray-700 line-clamp-2">{{ $ci->alasan }}</p>
                </div>
            </td>
            <td class="px-6 py-4">
                <span class="badge badge-{{ $ci->status == 'pending' ? 'warning' : ($ci->status == 'disetujui' ? 'success' : 'danger') }}">
                    {{ ucfirst($ci->status) }}
                </span>
            </td>
            <td class="px-6 py-4">
                @if($ci->status == 'pending')
                <div class="flex items-center space-x-2">
                    <button onclick="openApproveModal({{ $ci->id }}, '{{ $ci->user->name }}', '{{ $ci->jenis }}')" 
                            class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition"
                            title="Setujui">
                        <i class="fas fa-check"></i>
                    </button>
                    <button onclick="openRejectModal({{ $ci->id }}, '{{ $ci->user->name }}', '{{ $ci->jenis }}')" 
                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition"
                            title="Tolak">
                        <i class="fas fa-times"></i>
                    </button>
                    <button onclick="openDetailModal({{ json_encode($ci) }})" 
                            class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                            title="Detail">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                @else
                <div class="flex items-center space-x-2">
                    <button onclick="openDetailModal({{ json_encode($ci) }})" 
                            class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                            title="Detail">
                        <i class="fas fa-eye"></i>
                    </button>
                    <div class="text-xs text-gray-500">
                        <p>oleh {{ $ci->approver->name ?? '-' }}</p>
                        <p>{{ $ci->updated_at->format('d M Y') }}</p>
                    </div>
                </div>
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8" class="px-6 py-12 text-center">
                <div class="empty-state">
                    <i class="fas fa-calendar-alt"></i>
                    <p>Belum ada pengajuan cuti/izin</p>
                </div>
            </td>
        </tr>
        @endforelse

        <x-slot name="pagination">
            {{ $cutiIzin->links() }}
        </x-slot>
    </x-table>

</div>

<!-- Modal Approve -->
<x-modal id="approveModal" title="Setujui Pengajuan" size="md" type="info">
    <form id="approveForm" method="POST">
        @csrf
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-check-circle text-green-600 text-2xl"></i>
            </div>
            <p class="text-gray-700 mb-2">Setujui pengajuan <span id="approveJenis" class="font-semibold"></span> dari:</p>
            <p class="text-lg font-bold text-gray-900" id="approveKaryawanName"></p>
        </div>

        <div>
            <label for="keterangan_approve" class="form-label">
                <i class="fas fa-comment mr-2"></i>Keterangan (Opsional)
            </label>
            <textarea name="keterangan_approval" id="keterangan_approve" rows="3" 
                      class="form-input" 
                      placeholder="Tambahkan catatan jika diperlukan"></textarea>
        </div>
    </form>
    <x-slot name="footerButtons">
        <button type="button" onclick="closeModal('approveModal')" class="px-5 py-2.5 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
            Batal
        </button>
        <button type="submit" form="approveForm" class="px-5 py-2.5 bg-gradient-to-r from-green-600 to-teal-600 text-white font-semibold rounded-lg hover:from-green-700 hover:to-teal-700 transition shadow-md">
            <i class="fas fa-check mr-2"></i>Setujui
        </button>
    </x-slot>
</x-modal>

<!-- Modal Reject -->
<x-modal id="rejectModal" title="Tolak Pengajuan" size="md" type="confirm">
    <form id="rejectForm" method="POST">
        @csrf
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-times-circle text-red-600 text-2xl"></i>
            </div>
            <p class="text-gray-700 mb-2">Tolak pengajuan <span id="rejectJenis" class="font-semibold"></span> dari:</p>
            <p class="text-lg font-bold text-gray-900" id="rejectKaryawanName"></p>
        </div>

        <div>
            <label for="keterangan_reject" class="form-label">
                <i class="fas fa-comment mr-2"></i>Alasan Penolakan <span class="text-red-500">*</span>
            </label>
            <textarea name="keterangan_approval" id="keterangan_reject" rows="3" 
                      class="form-input" 
                      placeholder="Masukkan alasan penolakan" required></textarea>
            <p class="text-xs text-gray-500 mt-1">Alasan penolakan akan dikirimkan ke karyawan</p>
        </div>
    </form>
    <x-slot name="footerButtons">
        <button type="button" onclick="closeModal('rejectModal')" class="px-5 py-2.5 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
            Batal
        </button>
        <button type="submit" form="rejectForm" class="px-5 py-2.5 bg-gradient-to-r from-red-600 to-pink-600 text-white font-semibold rounded-lg hover:from-red-700 hover:to-pink-700 transition shadow-md">
            <i class="fas fa-times mr-2"></i>Tolak
        </button>
    </x-slot>
</x-modal>

<!-- Modal Detail -->
<x-modal id="detailModal" title="Detail Pengajuan" size="lg" type="info">
    <div class="space-y-4">
        <div class="flex items-center space-x-4 pb-4 border-b">
            <img id="detailFoto" src="" alt="" class="w-16 h-16 rounded-full object-cover border-2 border-gray-200">
            <div>
                <h4 id="detailNama" class="text-lg font-bold text-gray-900"></h4>
                <p id="detailDivisi" class="text-sm text-gray-500"></p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-xs text-gray-500 mb-1">Jenis</p>
                <p id="detailJenis" class="font-semibold text-gray-900"></p>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">Status</p>
                <span id="detailStatus"></span>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">Tanggal Mulai</p>
                <p id="detailTanggalMulai" class="font-semibold text-gray-900"></p>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">Tanggal Selesai</p>
                <p id="detailTanggalSelesai" class="font-semibold text-gray-900"></p>
            </div>
            <div class="col-span-2">
                <p class="text-xs text-gray-500 mb-1">Durasi</p>
                <p id="detailDurasi" class="font-semibold text-gray-900"></p>
            </div>
        </div>

        <div>
            <p class="text-xs text-gray-500 mb-1">Alasan</p>
            <div id="detailAlasan" class="bg-gray-50 rounded-lg p-4 text-sm text-gray-700"></div>
        </div>

        <div id="detailApprovalSection" class="hidden">
            <p class="text-xs text-gray-500 mb-1">Keterangan Approval</p>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p id="detailKeterangan" class="text-sm text-blue-800"></p>
                <p id="detailApprover" class="text-xs text-blue-600 mt-2"></p>
            </div>
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
    // Filter Status
    document.getElementById('filterStatus').addEventListener('change', function() {
        filterTable();
    });

    document.getElementById('filterJenis').addEventListener('change', function() {
        filterTable();
    });

    function filterTable() {
        const statusFilter = document.getElementById('filterStatus').value.toLowerCase();
        const jenisFilter = document.getElementById('filterJenis').value.toLowerCase();
        const searchValue = document.getElementById('searchInputcutiIzinTable').value.toLowerCase();
        const tableRows = document.querySelectorAll('#tableBodycutiIzinTable tr');
        
        tableRows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const showStatus = statusFilter === '' || text.includes(statusFilter);
            const showJenis = jenisFilter === '' || text.includes(jenisFilter);
            const showSearch = text.includes(searchValue);
            
            row.style.display = (showStatus && showJenis && showSearch) ? '' : 'none';
        });
    }

    // Open Approve Modal
    function openApproveModal(id, name, jenis) {
        document.getElementById('approveKaryawanName').textContent = name;
        document.getElementById('approveJenis').textContent = jenis;
        document.getElementById('approveForm').action = `/superadmin/cuti-izin/${id}/approve`;
        openModal('approveModal');
    }

    // Open Reject Modal
    function openRejectModal(id, name, jenis) {
        document.getElementById('rejectKaryawanName').textContent = name;
        document.getElementById('rejectJenis').textContent = jenis;
        document.getElementById('rejectForm').action = `/superadmin/cuti-izin/${id}/reject`;
        openModal('rejectModal');
    }

    // Open Detail Modal
    function openDetailModal(data) {
        const cutiIzin = typeof data === 'string' ? JSON.parse(data) : data;
        
        document.getElementById('detailFoto').src = cutiIzin.user.foto 
            ? `/storage/${cutiIzin.user.foto}` 
            : `https://ui-avatars.com/api/?name=${encodeURIComponent(cutiIzin.user.name)}&background=4F46E5&color=fff`;
        document.getElementById('detailNama').textContent = cutiIzin.user.name;
        document.getElementById('detailDivisi').textContent = cutiIzin.user.divisi;
        document.getElementById('detailJenis').textContent = cutiIzin.jenis.charAt(0).toUpperCase() + cutiIzin.jenis.slice(1);
        
        const statusBadge = `<span class="badge badge-${cutiIzin.status == 'pending' ? 'warning' : (cutiIzin.status == 'disetujui' ? 'success' : 'danger')}">${cutiIzin.status.charAt(0).toUpperCase() + cutiIzin.status.slice(1)}</span>`;
        document.getElementById('detailStatus').innerHTML = statusBadge;
        
        document.getElementById('detailTanggalMulai').textContent = new Date(cutiIzin.tanggal_mulai).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        document.getElementById('detailTanggalSelesai').textContent = new Date(cutiIzin.tanggal_selesai).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        
        const days = Math.ceil((new Date(cutiIzin.tanggal_selesai) - new Date(cutiIzin.tanggal_mulai)) / (1000 * 60 * 60 * 24)) + 1;
        document.getElementById('detailDurasi').textContent = `${days} hari`;
        
        document.getElementById('detailAlasan').textContent = cutiIzin.alasan;
        
        if (cutiIzin.keterangan_approval && cutiIzin.status !== 'pending') {
            document.getElementById('detailApprovalSection').classList.remove('hidden');
            document.getElementById('detailKeterangan').textContent = cutiIzin.keterangan_approval;
            document.getElementById('detailApprover').textContent = `Oleh: ${cutiIzin.approver ? cutiIzin.approver.name : '-'} pada ${new Date(cutiIzin.updated_at).toLocaleDateString('id-ID')}`;
        } else {
            document.getElementById('detailApprovalSection').classList.add('hidden');
        }
        
        openModal('detailModal');
    }
</script>
@endpush