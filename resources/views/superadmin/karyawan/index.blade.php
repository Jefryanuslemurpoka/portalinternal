@extends('layouts.app')

@section('title', 'Manajemen Karyawan')
@section('page-title', 'Manajemen Karyawan')

@section('content')
<div class="space-y-6">

    <!-- Table Karyawan -->
    <x-table 
        id="karyawanTable"
        title="Data Karyawan"
        :headers="['No', 'Foto', 'Nama', 'Email', 'Divisi', 'Status', 'Aksi']"
        addRoute="{{ route('superadmin.karyawan.create') }}"
        addText="Tambah Karyawan"
    >
        <x-slot name="filters">
            <select id="filterStatus" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-400 transition">
                <option value="">Semua Status</option>
                <option value="aktif">Aktif</option>
                <option value="nonaktif">Non-Aktif</option>
            </select>
        </x-slot>

        @forelse($karyawan as $key => $k)
        <tr class="hover:bg-teal-50 transition">
            <td class="px-6 py-4 text-sm text-gray-900">
                {{ $karyawan->firstItem() + $key }}
            </td>
            <td class="px-6 py-4">
                <img src="{{ $k->foto ? asset('storage/' . $k->foto) : 'https://ui-avatars.com/api/?name=' . urlencode($k->name) . '&background=14b8a6&color=fff' }}" 
                     alt="{{ $k->name }}"
                     class="w-12 h-12 rounded-full object-cover border-2 border-teal-200 shadow-sm">
            </td>
            <td class="px-6 py-4">
                <div>
                    <p class="text-sm font-semibold text-gray-900">{{ $k->name }}</p>
                    <p class="text-xs text-gray-500">Terdaftar {{ $k->created_at->diffForHumans() }}</p>
                </div>
            </td>
            <td class="px-6 py-4 text-sm text-gray-900">
                {{ $k->email }}
            </td>
            <td class="px-6 py-4">
                <span class="px-3 py-1 bg-teal-100 text-teal-700 rounded-full text-xs font-semibold">
                    {{ $k->divisi }}
                </span>
            </td>
            <td class="px-6 py-4">
                <span class="badge badge-{{ $k->status == 'aktif' ? 'success' : 'danger' }}">
                    {{ ucfirst($k->status) }}
                </span>
            </td>
            <td class="px-6 py-4">
                <div class="flex items-center space-x-2">
                    <a href="{{ route('superadmin.karyawan.edit', $k->id) }}" 
                       class="p-2 text-teal-600 hover:bg-teal-50 rounded-lg transition"
                       title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    <button onclick="openResetModal({{ $k->id }}, '{{ $k->name }}')" 
                            class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition"
                            title="Reset Password">
                        <i class="fas fa-key"></i>
                    </button>
                    <button onclick="openDeleteModal({{ $k->id }}, '{{ $k->name }}')" 
                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition"
                            title="Hapus">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" class="px-6 py-12 text-center">
                <div class="empty-state">
                    <i class="fas fa-users"></i>
                    <p>Belum ada data karyawan</p>
                </div>
            </td>
        </tr>
        @endforelse

        <x-slot name="pagination">
            {{ $karyawan->links() }}
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
            <p class="text-gray-700 mb-2">Apakah Anda yakin ingin menghapus karyawan:</p>
            <p class="text-lg font-bold text-gray-900" id="deleteKaryawanName"></p>
            <p class="text-sm text-red-600 mt-4">Data yang dihapus tidak dapat dikembalikan!</p>
        </div>
    </form>
</x-modal>

<!-- Modal Reset Password -->
<x-modal id="resetModal" title="Reset Password" size="sm" type="confirm">
    <form id="resetForm" method="POST">
        @csrf
        <div class="text-center">
            <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-key text-amber-600 text-2xl"></i>
            </div>
            <p class="text-gray-700 mb-2">Reset password untuk karyawan:</p>
            <p class="text-lg font-bold text-gray-900 mb-4" id="resetKaryawanName"></p>
            <div class="bg-teal-50 border border-teal-200 rounded-xl p-4">
                <p class="text-sm text-gray-600 mb-1">Password akan direset menjadi:</p>
                <p class="text-lg font-mono font-bold text-teal-600">karyawan123</p>
            </div>
        </div>
    </form>
    <x-slot name="footerButtons">
        <button type="button" onclick="closeModal('resetModal')" class="px-5 py-2.5 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
            Batal
        </button>
        <button type="submit" form="resetForm" class="px-5 py-2.5 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-semibold rounded-lg hover:from-amber-600 hover:to-orange-600 transition shadow-md">
            <i class="fas fa-key mr-2"></i>Reset Password
        </button>
    </x-slot>
</x-modal>

@endsection

@push('scripts')
<script>
    // Filter Status
    document.getElementById('filterStatus').addEventListener('change', function() {
        const filterValue = this.value.toLowerCase();
        const tableRows = document.querySelectorAll('#tableBodykaryawanTable tr');
        
        tableRows.forEach(row => {
            if (filterValue === '') {
                row.style.display = '';
            } else {
                const statusText = row.textContent.toLowerCase();
                row.style.display = statusText.includes(filterValue) ? '' : 'none';
            }
        });
    });

    // Open Delete Modal
    function openDeleteModal(id, name) {
        document.getElementById('deleteKaryawanName').textContent = name;
        document.getElementById('deleteForm').action = `/superadmin/karyawan/${id}`;
        openModal('deleteModal');
    }

    // Open Reset Password Modal
    function openResetModal(id, name) {
        document.getElementById('resetKaryawanName').textContent = name;
        document.getElementById('resetForm').action = `/superadmin/karyawan/${id}/reset-password`;
        openModal('resetModal');
    }
</script>
@endpush