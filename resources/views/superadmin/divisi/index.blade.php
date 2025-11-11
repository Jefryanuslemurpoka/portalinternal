@extends('layouts.app')

@section('title', 'Manajemen Divisi')
@section('page-title', 'Manajemen Divisi')

@section('content')
<div class="space-y-4 sm:space-y-6">

    <!-- Flash Messages -->
    @if(session('success'))
    <div id="successAlert" class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm animate-fade-in">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-500 text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
            <button onclick="closeAlert('successAlert')" class="text-green-500 hover:text-green-700 transition">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div id="errorAlert" class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm animate-fade-in">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-500 text-xl"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                </div>
            </div>
            <button onclick="closeAlert('errorAlert')" class="text-red-500 hover:text-red-700 transition">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    @endif

    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
        <div>
            <h2 class="text-xl sm:text-2xl font-bold text-gray-900">Manajemen Divisi</h2>
            <p class="text-xs sm:text-sm text-gray-600 mt-1">Kelola divisi perusahaan</p>
        </div>
        <button onclick="openModal('addModal')" class="w-full sm:w-auto px-4 sm:px-5 py-2 sm:py-2.5 bg-gradient-to-r from-teal-500 to-cyan-600 text-white font-semibold rounded-lg hover:from-teal-600 hover:to-cyan-700 transition shadow-md text-xs sm:text-sm whitespace-nowrap">
            <i class="fas fa-plus mr-2"></i>Tambah Divisi
        </button>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg overflow-hidden">
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
            <h3 class="text-base sm:text-lg font-bold text-gray-800">
                <i class="fas fa-sitemap mr-2 text-teal-600"></i>Daftar Divisi
            </h3>
        </div>

        <!-- Mobile View -->
        <div class="block lg:hidden divide-y divide-gray-100">
            @forelse($divisi as $d)
            <div class="p-4 hover:bg-teal-50 transition">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <h4 class="text-sm font-semibold text-gray-900">{{ $d->nama_divisi }}</h4>
                        <p class="text-xs text-gray-500 mt-1">{{ $d->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                        <div class="flex items-center gap-2 mt-2">
                            <span class="px-2 py-0.5 {{ $d->status == 'aktif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} rounded-full text-xs font-semibold">
                                {{ ucfirst($d->status) }}
                            </span>
                            <span class="text-xs text-gray-500">
                                {{ $d->users()->count() }} Karyawan
                            </span>
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-2 pt-3 border-t border-gray-100">
                    <button onclick="openEditModal({{ json_encode($d) }})" 
                            class="px-3 py-1.5 text-xs text-amber-600 hover:bg-amber-50 rounded-lg transition">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button onclick="openDeleteModal({{ $d->id }}, '{{ $d->nama_divisi }}', {{ $d->users()->count() }})" 
                            class="px-3 py-1.5 text-xs text-red-600 hover:bg-red-50 rounded-lg transition">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            @empty
            <div class="p-8 sm:p-12 text-center">
                <i class="fas fa-inbox text-gray-300 text-4xl sm:text-5xl mb-3"></i>
                <p class="text-gray-500 text-sm sm:text-base">Belum ada divisi</p>
                <p class="text-xs sm:text-sm text-gray-400 mt-2">Klik tombol "Tambah Divisi" untuk menambah divisi baru</p>
            </div>
            @endforelse
        </div>

        <!-- Desktop Table -->
        <div class="hidden lg:block overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-teal-500 to-cyan-600 text-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold">No</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Nama Divisi</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Deskripsi</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Status</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Jumlah Karyawan</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($divisi as $key => $d)
                    <tr class="hover:bg-teal-50 transition">
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $divisi->firstItem() + $key }}</td>
                        <td class="px-6 py-4">
                            <p class="text-sm font-semibold text-gray-900">{{ $d->nama_divisi }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-600">{{ $d->deskripsi ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 {{ $d->status == 'aktif' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }} rounded-full text-xs font-semibold">
                                {{ ucfirst($d->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-sm text-gray-900">{{ $d->users()->count() }} Karyawan</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-2">
                                <button onclick="openEditModal({{ json_encode($d) }})" 
                                        class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition"
                                        title="Edit">
                                    <i class="fas fa-edit text-sm"></i>
                                </button>
                                <button onclick="openDeleteModal({{ $d->id }}, '{{ $d->nama_divisi }}', {{ $d->users()->count() }})" 
                                        class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition"
                                        title="Hapus">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <i class="fas fa-inbox text-gray-300 text-5xl mb-3"></i>
                            <p class="text-gray-500">Belum ada divisi</p>
                            <p class="text-sm text-gray-400 mt-2">Klik tombol "Tambah Divisi" untuk menambah divisi baru</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($divisi->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $divisi->links() }}
        </div>
        @endif
    </div>

</div>

<!-- Modal Add -->
<div id="addModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full" onclick="event.stopPropagation()">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-xl font-bold text-gray-900">Tambah Divisi Baru</h3>
        </div>
        <form action="{{ route('superadmin.divisi.store') }}" method="POST">
            @csrf
            <div class="p-6 space-y-4">
                <div>
                    <label class="form-label">Nama Divisi <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_divisi" class="form-input" placeholder="Contoh: IT, HR, Marketing" required>
                </div>
                <div>
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" rows="3" class="form-input" placeholder="Deskripsi singkat divisi"></textarea>
                </div>
                <div>
                    <label class="form-label">Status <span class="text-red-500">*</span></label>
                    <select name="status" class="form-input" required>
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>
            </div>
            <div class="p-6 bg-gray-50 rounded-b-xl flex items-center justify-end space-x-3">
                <button type="button" onclick="closeModal('addModal')" class="px-5 py-2.5 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-teal-500 to-cyan-600 text-white font-semibold rounded-lg hover:from-teal-600 hover:to-cyan-700 transition shadow-md">
                    <i class="fas fa-save mr-2"></i>Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full" onclick="event.stopPropagation()">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-xl font-bold text-gray-900">Edit Divisi</h3>
        </div>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="p-6 space-y-4">
                <div>
                    <label class="form-label">Nama Divisi <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_divisi" id="edit_nama_divisi" class="form-input" required>
                </div>
                <div>
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" id="edit_deskripsi" rows="3" class="form-input"></textarea>
                </div>
                <div>
                    <label class="form-label">Status <span class="text-red-500">*</span></label>
                    <select name="status" id="edit_status" class="form-input" required>
                        <option value="aktif">Aktif</option>
                        <option value="nonaktif">Nonaktif</option>
                    </select>
                </div>
            </div>
            <div class="p-6 bg-gray-50 rounded-b-xl flex items-center justify-end space-x-3">
                <button type="button" onclick="closeModal('editModal')" class="px-5 py-2.5 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-amber-500 to-orange-600 text-white font-semibold rounded-lg hover:from-amber-600 hover:to-orange-700 transition shadow-md">
                    <i class="fas fa-save mr-2"></i>Update
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Delete -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full" onclick="event.stopPropagation()">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-xl font-bold text-gray-900">Konfirmasi Hapus</h3>
        </div>
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="p-6">
                <div class="text-center">
                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                    </div>
                    <p class="text-base text-gray-700 mb-2">Hapus divisi:</p>
                    <p class="text-lg font-bold text-gray-900" id="deleteDivisiName"></p>
                    <p class="text-sm text-gray-600 mt-2" id="deleteKaryawanCount"></p>
                    <p class="text-sm text-red-600 mt-4" id="deleteWarning"></p>
                </div>
            </div>
            <div class="p-6 bg-gray-50 rounded-b-xl flex items-center justify-end space-x-3">
                <button type="button" onclick="closeModal('deleteModal')" class="px-5 py-2.5 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
                    Batal
                </button>
                <button type="submit" id="deleteButton" class="px-5 py-2.5 bg-gradient-to-r from-red-500 to-red-600 text-white font-semibold rounded-lg hover:from-red-600 hover:to-red-700 transition shadow-md">
                    <i class="fas fa-trash mr-2"></i>Hapus
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Prevent flash message showing again on back button
    (function() {
        // Check if this is a page loaded from cache (back button)
        const isBackButton = (performance.navigation && performance.navigation.type === 2) || 
                            (performance.getEntriesByType('navigation')[0] && 
                             performance.getEntriesByType('navigation')[0].type === 'back_forward');
        
        if (isBackButton) {
            // Remove all flash messages immediately on back button
            const successAlert = document.getElementById('successAlert');
            const errorAlert = document.getElementById('errorAlert');
            
            if (successAlert) successAlert.remove();
            if (errorAlert) errorAlert.remove();
            
            // Also remove any SweetAlert modals
            const swalModals = document.querySelectorAll('.swal2-container');
            swalModals.forEach(modal => modal.remove());
        } else {
            // Normal page load - show flash messages
            // Add a flag to history state so back button can detect it
            if (document.getElementById('successAlert') || document.getElementById('errorAlert')) {
                history.replaceState({ flashShown: true }, '', window.location.href);
            }
        }
    })();

    // Auto-hide flash messages
    function closeAlert(alertId) {
        const alert = document.getElementById(alertId);
        if (alert) {
            alert.style.transition = 'opacity 0.3s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }
    }

    // Auto-hide after 5 seconds
    setTimeout(() => {
        closeAlert('successAlert');
        closeAlert('errorAlert');
    }, 5000);

    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    function openEditModal(divisi) {
        document.getElementById('edit_nama_divisi').value = divisi.nama_divisi;
        document.getElementById('edit_deskripsi').value = divisi.deskripsi || '';
        document.getElementById('edit_status').value = divisi.status;
        document.getElementById('editForm').action = `/superadmin/divisi/${divisi.id}`;
        openModal('editModal');
    }

    function openDeleteModal(id, nama, jumlahKaryawan) {
        document.getElementById('deleteDivisiName').textContent = nama;
        document.getElementById('deleteKaryawanCount').textContent = `Jumlah karyawan: ${jumlahKaryawan}`;
        
        const deleteButton = document.getElementById('deleteButton');
        const warningText = document.getElementById('deleteWarning');
        
        if (jumlahKaryawan > 0) {
            deleteButton.disabled = true;
            deleteButton.classList.add('opacity-50', 'cursor-not-allowed');
            warningText.textContent = 'Divisi tidak dapat dihapus karena masih digunakan oleh karyawan!';
        } else {
            deleteButton.disabled = false;
            deleteButton.classList.remove('opacity-50', 'cursor-not-allowed');
            warningText.textContent = 'Data yang dihapus tidak dapat dikembalikan!';
        }
        
        document.getElementById('deleteForm').action = `/superadmin/divisi/${id}`;
        openModal('deleteModal');
    }

    // Close modal with ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            ['addModal', 'editModal', 'deleteModal'].forEach(modalId => {
                const modal = document.getElementById(modalId);
                if (!modal.classList.contains('hidden')) {
                    closeModal(modalId);
                }
            });
        }
    });

    // Close modal when clicking outside
    ['addModal', 'editModal', 'deleteModal'].forEach(modalId => {
        document.getElementById(modalId).addEventListener('click', function(e) {
            if (e.target === this) closeModal(modalId);
        });
    });
</script>
@endpush