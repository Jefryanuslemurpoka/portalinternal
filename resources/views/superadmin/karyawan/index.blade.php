@extends('layouts.app')

@section('title', 'Manajemen Karyawan')
@section('page-title', 'Manajemen Karyawan')

@section('content')
<div class="space-y-4 sm:space-y-6">

    <!-- Header Section with Filters - Mobile Optimized -->
    <div class="bg-white rounded-xl shadow-sm border border-teal-100 p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4 mb-4">
            <div>
                <h2 class="text-lg sm:text-xl font-bold text-gray-800">Data Karyawan</h2>
                <p class="text-xs sm:text-sm text-gray-500 mt-1">Kelola data karyawan perusahaan</p>
            </div>
            <a href="{{ route('superadmin.karyawan.create') }}" class="inline-flex items-center justify-center px-4 sm:px-5 py-2 sm:py-2.5 bg-gradient-to-r from-teal-500 to-cyan-600 text-white font-semibold rounded-lg hover:from-teal-600 hover:to-cyan-700 transition shadow-md text-sm whitespace-nowrap flex-shrink-0">
                <i class="fas fa-plus mr-2"></i>
                <span class="hidden sm:inline">Tambah Karyawan</span>
                <span class="sm:hidden">Tambah</span>
            </a>
        </div>
        
        <!-- Search & Filter Row -->
        <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
            <div class="relative flex-1">
                <input type="text" 
                       id="searchInput" 
                       placeholder="Cari nama, email, atau divisi..." 
                       class="w-full pl-10 pr-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-400 transition text-sm">
                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
            </div>
            <select id="filterStatus" class="w-full sm:w-48 px-3 sm:px-4 py-2 sm:py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-400 transition text-sm flex-shrink-0">
                <option value="">Semua Status</option>
                <option value="aktif">Aktif</option>
                <option value="nonaktif">Non-Aktif</option>
            </select>
        </div>
    </div>

    <!-- Desktop Table View (Hidden on Mobile) -->
    <div class="hidden lg:block bg-white rounded-xl shadow-sm border border-teal-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-teal-500 to-cyan-600 text-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold">No</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Foto</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Nama</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Email</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Divisi</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Status</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBodykaryawanTable" class="divide-y divide-gray-100">
                    @forelse($karyawan as $key => $k)
                    <tr class="hover:bg-teal-50 transition" data-status="{{ strtolower($k->status) }}">
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
                                <a href="{{ route('superadmin.karyawan.edit', $k->uuid) }}" 
                                   class="p-2 text-teal-600 hover:bg-teal-50 rounded-lg transition"
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="openResetModal('{{ $k->uuid }}', '{{ $k->name }}')" 
                                        class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition"
                                        title="Reset Password">
                                    <i class="fas fa-key"></i>
                                </button>
                                <button onclick="openDeleteModal('{{ $k->uuid }}', '{{ $k->name }}')" 
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
                                <i class="fas fa-users text-gray-300 text-4xl mb-3"></i>
                                <p class="text-gray-500">Belum ada data karyawan</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Mobile Card View (Hidden on Desktop) -->
    <div class="lg:hidden space-y-3 sm:space-y-4" id="mobileCardView">
        @forelse($karyawan as $key => $k)
        <div class="bg-white rounded-xl shadow-sm border border-teal-100 p-4 hover:shadow-md transition" data-status="{{ strtolower($k->status) }}">
            <!-- Header dengan Foto dan Info -->
            <div class="flex items-start gap-3 mb-3">
                <img src="{{ $k->foto ? asset('storage/' . $k->foto) : 'https://ui-avatars.com/api/?name=' . urlencode($k->name) . '&background=14b8a6&color=fff' }}" 
                     alt="{{ $k->name }}"
                     class="w-14 h-14 sm:w-16 sm:h-16 rounded-full object-cover border-2 border-teal-200 shadow-sm flex-shrink-0">
                <div class="flex-1 min-w-0">
                    <h3 class="text-sm sm:text-base font-bold text-gray-900 truncate">{{ $k->name }}</h3>
                    <p class="text-xs sm:text-sm text-gray-600 truncate">{{ $k->email }}</p>
                    <div class="flex items-center gap-2 mt-1.5">
                        <span class="px-2 py-0.5 bg-teal-100 text-teal-700 rounded-full text-xs font-semibold">
                            {{ $k->divisi }}
                        </span>
                        <span class="badge badge-{{ $k->status == 'aktif' ? 'success' : 'danger' }} text-xs">
                            {{ ucfirst($k->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Info Tambahan -->
            <div class="bg-gray-50 rounded-lg p-2.5 sm:p-3 mb-3">
                <p class="text-xs text-gray-500">
                    <i class="fas fa-calendar-alt mr-1 text-teal-500"></i>
                    Terdaftar {{ $k->created_at->diffForHumans() }}
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-2">
                <a href="{{ route('superadmin.karyawan.edit', $k->uuid) }}" 
                   class="flex-1 inline-flex items-center justify-center px-3 py-2 text-teal-600 bg-teal-50 hover:bg-teal-100 rounded-lg transition text-sm font-medium">
                    <i class="fas fa-edit mr-1.5"></i>
                    <span class="hidden xs:inline">Edit</span>
                </a>
                <button onclick="openResetModal('{{ $k->uuid }}', '{{ $k->name }}')" 
                        class="flex-1 inline-flex items-center justify-center px-3 py-2 text-amber-600 bg-amber-50 hover:bg-amber-100 rounded-lg transition text-sm font-medium">
                    <i class="fas fa-key mr-1.5"></i>
                    <span class="hidden xs:inline">Reset</span>
                </button>
                <button onclick="openDeleteModal('{{ $k->uuid }}', '{{ $k->name }}')" 
                        class="flex-1 inline-flex items-center justify-center px-3 py-2 text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition text-sm font-medium">
                    <i class="fas fa-trash mr-1.5"></i>
                    <span class="hidden xs:inline">Hapus</span>
                </button>
            </div>
        </div>
        @empty
        <div class="bg-white rounded-xl shadow-sm border border-teal-100 p-8 sm:p-12 text-center">
            <i class="fas fa-users text-gray-300 text-4xl sm:text-5xl mb-3"></i>
            <p class="text-sm sm:text-base text-gray-500">Belum ada data karyawan</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($karyawan->hasPages())
    <div class="bg-white rounded-xl shadow-sm border border-teal-100 p-3 sm:p-4">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="text-xs sm:text-sm text-gray-600 text-center sm:text-left">
                Menampilkan {{ $karyawan->firstItem() }} - {{ $karyawan->lastItem() }} dari {{ $karyawan->total() }} data
            </div>
            <div class="pagination-wrapper">
                {{ $karyawan->links() }}
            </div>
        </div>
    </div>
    @endif

</div>

<!-- Modal Delete -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-auto" onclick="event.stopPropagation()">
        <div class="p-4 sm:p-6 border-b border-gray-200">
            <h3 class="text-lg sm:text-xl font-bold text-gray-900">Konfirmasi Hapus</h3>
        </div>
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="p-4 sm:p-6">
                <div class="text-center">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                    </div>
                    <p class="text-sm sm:text-base text-gray-700 mb-2">Apakah Anda yakin ingin menghapus karyawan:</p>
                    <p class="text-base sm:text-lg font-bold text-gray-900" id="deleteKaryawanName"></p>
                    <p class="text-xs sm:text-sm text-red-600 mt-4">Data yang dihapus tidak dapat dikembalikan!</p>
                </div>
            </div>
            <div class="p-4 sm:p-6 bg-gray-50 rounded-b-xl flex flex-col-reverse sm:flex-row items-center justify-end gap-2 sm:gap-3">
                <button type="button" onclick="closeModal('deleteModal')" class="w-full sm:w-auto px-4 sm:px-5 py-2 sm:py-2.5 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition text-sm">
                    Batal
                </button>
                <button type="submit" class="w-full sm:w-auto px-4 sm:px-5 py-2 sm:py-2.5 bg-gradient-to-r from-red-500 to-red-600 text-white font-semibold rounded-lg hover:from-red-600 hover:to-red-700 transition shadow-md text-sm">
                    <i class="fas fa-trash mr-2"></i>Hapus
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Reset Password -->
<div id="resetModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full mx-auto" onclick="event.stopPropagation()">
        <div class="p-4 sm:p-6 border-b border-gray-200">
            <h3 class="text-lg sm:text-xl font-bold text-gray-900">Reset Password</h3>
        </div>
        <form id="resetForm" method="POST">
            @csrf
            <div class="p-4 sm:p-6">
                <div class="text-center">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-key text-amber-600 text-2xl"></i>
                    </div>
                    <p class="text-sm sm:text-base text-gray-700 mb-2">Reset password untuk karyawan:</p>
                    <p class="text-base sm:text-lg font-bold text-gray-900 mb-4" id="resetKaryawanName"></p>
                    <div class="bg-teal-50 border border-teal-200 rounded-xl p-3 sm:p-4">
                        <p class="text-xs sm:text-sm text-gray-600 mb-1">Password akan direset menjadi:</p>
                        <p class="text-base sm:text-lg font-mono font-bold text-teal-600">karyawan123</p>
                    </div>
                </div>
            </div>
            <div class="p-4 sm:p-6 bg-gray-50 rounded-b-xl flex flex-col-reverse sm:flex-row items-center justify-end gap-2 sm:gap-3">
                <button type="button" onclick="closeModal('resetModal')" class="w-full sm:w-auto px-4 sm:px-5 py-2 sm:py-2.5 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition text-sm">
                    Batal
                </button>
                <button type="submit" class="w-full sm:w-auto px-4 sm:px-5 py-2 sm:py-2.5 bg-gradient-to-r from-amber-500 to-orange-500 text-white font-semibold rounded-lg hover:from-amber-600 hover:to-orange-600 transition shadow-md text-sm">
                    <i class="fas fa-key mr-2"></i>Reset Password
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Live Search Function - FIXED
    function performSearch() {
        const searchValue = document.getElementById('searchInput').value.toLowerCase();
        const filterValue = document.getElementById('filterStatus').value.toLowerCase();
        
        // Search in desktop table
        const tableRows = document.querySelectorAll('#tableBodykaryawanTable tr[data-status]');
        let visibleCount = 0;
        
        tableRows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const rowStatus = row.getAttribute('data-status');
            
            const matchesSearch = searchValue === '' || text.includes(searchValue);
            const matchesFilter = filterValue === '' || rowStatus === filterValue;
            const isVisible = matchesSearch && matchesFilter;
            
            row.style.display = isVisible ? '' : 'none';
            if (isVisible) visibleCount++;
        });
        
        // Search in mobile cards
        const mobileCards = document.querySelectorAll('#mobileCardView > div[data-status]');
        let visibleCardCount = 0;
        
        mobileCards.forEach(card => {
            const text = card.textContent.toLowerCase();
            const cardStatus = card.getAttribute('data-status');
            
            const matchesSearch = searchValue === '' || text.includes(searchValue);
            const matchesFilter = filterValue === '' || cardStatus === filterValue;
            const isVisible = matchesSearch && matchesFilter;
            
            card.style.display = isVisible ? '' : 'none';
            if (isVisible) visibleCardCount++;
        });
        
        // Show "no results" message if nothing found
        showNoResultsMessage(visibleCount === 0 && visibleCardCount === 0);
    }

    // Live Search - Works for both desktop and mobile
    document.getElementById('searchInput').addEventListener('keyup', performSearch);

    // Filter Status - Works for both desktop and mobile
    document.getElementById('filterStatus').addEventListener('change', performSearch);

    // Show/hide no results message
    function showNoResultsMessage(show) {
        // Remove existing no results messages
        document.querySelectorAll('.no-results-message').forEach(el => el.remove());
        
        if (show) {
            // Add to desktop table
            const tableBody = document.getElementById('tableBodykaryawanTable');
            if (tableBody && window.innerWidth >= 1024) {
                const noResultRow = document.createElement('tr');
                noResultRow.className = 'no-results-message';
                noResultRow.innerHTML = `
                    <td colspan="7" class="px-6 py-12 text-center">
                        <i class="fas fa-search text-gray-300 text-4xl mb-3"></i>
                        <p class="text-gray-500">Tidak ada data yang cocok dengan pencarian</p>
                    </td>
                `;
                tableBody.appendChild(noResultRow);
            }
            
            // Add to mobile view
            const mobileView = document.getElementById('mobileCardView');
            if (mobileView && window.innerWidth < 1024) {
                const noResultCard = document.createElement('div');
                noResultCard.className = 'no-results-message bg-white rounded-xl shadow-sm border border-teal-100 p-8 sm:p-12 text-center';
                noResultCard.innerHTML = `
                    <i class="fas fa-search text-gray-300 text-4xl sm:text-5xl mb-3"></i>
                    <p class="text-sm sm:text-base text-gray-500">Tidak ada data yang cocok dengan pencarian</p>
                `;
                mobileView.appendChild(noResultCard);
            }
        }
    }

    // Open Delete Modal - FIXED with UUID
    function openDeleteModal(uuid, name) {
        document.getElementById('deleteKaryawanName').textContent = name;
        document.getElementById('deleteForm').action = `/superadmin/karyawan/${uuid}`;
        openModal('deleteModal');
    }

    // Open Reset Password Modal - FIXED with UUID
    function openResetModal(uuid, name) {
        document.getElementById('resetKaryawanName').textContent = name;
        document.getElementById('resetForm').action = `/superadmin/karyawan/${uuid}/reset-password`;
        openModal('resetModal');
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

    // Close modal when clicking outside
    document.getElementById('deleteModal').addEventListener('click', function(e) {
        if (e.target === this) closeModal('deleteModal');
    });
    
    document.getElementById('resetModal').addEventListener('click', function(e) {
        if (e.target === this) closeModal('resetModal');
    });
</script>
@endpush