@extends('layouts.app')

@section('title', 'Persetujuan Cuti/Izin')
@section('page-title', 'Persetujuan Cuti/Izin')

@section('content')
<div class="space-y-4 sm:space-y-6">

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 lg:gap-6">
        <div class="bg-gradient-to-br from-amber-500 to-orange-500 rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-white/80 mb-1">Pending</p>
                    <h3 class="text-2xl sm:text-3xl font-bold">{{ $cutiIzin->where('status', 'pending')->count() }}</h3>
                </div>
                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-clock text-2xl sm:text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-teal-500 to-cyan-600 rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-white/80 mb-1">Disetujui</p>
                    <h3 class="text-2xl sm:text-3xl font-bold">{{ $cutiIzin->where('status', 'disetujui')->count() }}</h3>
                </div>
                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-2xl sm:text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-red-500 to-pink-500 rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-6 text-white sm:col-span-2 lg:col-span-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-white/80 mb-1">Ditolak</p>
                    <h3 class="text-2xl sm:text-3xl font-bold">{{ $cutiIzin->where('status', 'ditolak')->count() }}</h3>
                </div>
                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-times-circle text-2xl sm:text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Pengajuan -->
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-4">
                <h2 class="text-lg sm:text-xl font-bold text-gray-900">
                    <i class="fas fa-calendar-check text-teal-600 mr-2"></i>
                    <span class="hidden sm:inline">Daftar Pengajuan Cuti/Izin</span>
                    <span class="sm:hidden">Pengajuan Cuti/Izin</span>
                </h2>
                <a href="{{ route('superadmin.cutiizin.create') }}" class="inline-flex items-center justify-center px-4 sm:px-5 py-2 sm:py-2.5 bg-gradient-to-r from-teal-500 to-cyan-600 text-white font-semibold rounded-lg hover:from-teal-600 hover:to-cyan-700 transition shadow-md text-sm whitespace-nowrap">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah
                </a>
            </div>
        </div>

        <!-- Filters & Search -->
        <div class="px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 border-b border-gray-200">
            <div class="flex flex-col gap-2 sm:gap-3">
                <div class="w-full">
                    <div class="relative">
                        <input type="text" id="searchInput" placeholder="Cari karyawan, divisi..." 
                               class="w-full pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-400 transition">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-2 sm:flex sm:gap-3">
                    <select id="filterStatus" class="px-3 sm:px-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-400 transition">
                        <option value="">Semua Status</option>
                        <option value="pending">Pending</option>
                        <option value="disetujui">Disetujui</option>
                        <option value="ditolak">Ditolak</option>
                    </select>
                    <select id="filterJenis" class="px-3 sm:px-4 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-400 transition">
                        <option value="">Semua Jenis</option>
                        <option value="cuti">Cuti</option>
                        <option value="izin">Izin</option>
                        <option value="sakit">Sakit</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Table Desktop -->
        <div class="hidden lg:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 xl:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No</th>
                        <th class="px-4 xl:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Karyawan</th>
                        <th class="px-4 xl:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jenis</th>
                        <th class="px-4 xl:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                        <th class="px-4 xl:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Durasi</th>
                        <th class="px-4 xl:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Alasan</th>
                        <th class="px-4 xl:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-4 xl:px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tableBody" class="bg-white divide-y divide-gray-200">
                    @forelse($cutiIzin as $key => $ci)
                    <tr class="hover:bg-teal-50 transition">
                        <td class="px-4 xl:px-6 py-4 text-sm text-gray-900">
                            {{ $cutiIzin->firstItem() + $key }}
                        </td>
                        <td class="px-4 xl:px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <img src="{{ $ci->user->foto ? asset('storage/' . $ci->user->foto) : 'https://ui-avatars.com/api/?name=' . urlencode($ci->user->name) . '&background=14b8a6&color=fff' }}" 
                                     alt="{{ $ci->user->name }}"
                                     class="w-10 h-10 rounded-full object-cover border-2 border-teal-200 flex-shrink-0">
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $ci->user->name }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ $ci->user->divisi }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 xl:px-6 py-4">
                            <span class="px-2 xl:px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap
                                {{ $ci->jenis == 'cuti' ? 'bg-teal-100 text-teal-700' : '' }}
                                {{ $ci->jenis == 'izin' ? 'bg-cyan-100 text-cyan-700' : '' }}
                                {{ $ci->jenis == 'sakit' ? 'bg-red-100 text-red-700' : '' }}">
                                {{ ucfirst($ci->jenis) }}
                            </span>
                        </td>
                        <td class="px-4 xl:px-6 py-4">
                            <div class="text-sm">
                                <p class="font-semibold text-gray-900 whitespace-nowrap">{{ $ci->tanggal_mulai->format('d M Y') }}</p>
                                <p class="text-xs text-gray-500 whitespace-nowrap">s/d {{ $ci->tanggal_selesai->format('d M Y') }}</p>
                            </div>
                        </td>
                        <td class="px-4 xl:px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                            {{ $ci->tanggal_mulai->diffInDays($ci->tanggal_selesai) + 1 }} hari
                        </td>
                        <td class="px-4 xl:px-6 py-4">
                            <div class="max-w-xs">
                                <p class="text-sm text-gray-700 line-clamp-2">{{ $ci->alasan }}</p>
                            </div>
                        </td>
                        <td class="px-4 xl:px-6 py-4">
                            <span class="px-2 xl:px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap
                                {{ $ci->status == 'pending' ? 'bg-amber-100 text-amber-700' : '' }}
                                {{ $ci->status == 'disetujui' ? 'bg-teal-100 text-teal-700' : '' }}
                                {{ $ci->status == 'ditolak' ? 'bg-red-100 text-red-700' : '' }}">
                                {{ ucfirst($ci->status) }}
                            </span>
                        </td>
                        <td class="px-4 xl:px-6 py-4">
                            @if($ci->status == 'pending')
                            <div class="flex items-center space-x-2">
                                <button onclick="openApproveModal({{ $ci->id }}, '{{ $ci->user->name }}', '{{ $ci->jenis }}')" 
                                        class="p-2 text-teal-600 hover:bg-teal-50 rounded-lg transition"
                                        title="Setujui">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button onclick="openRejectModal({{ $ci->id }}, '{{ $ci->user->name }}', '{{ $ci->jenis }}')" 
                                        class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition"
                                        title="Tolak">
                                    <i class="fas fa-times"></i>
                                </button>
                                <button onclick="openDetailModal({{ json_encode($ci) }})" 
                                        class="p-2 text-cyan-600 hover:bg-cyan-50 rounded-lg transition"
                                        title="Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <a href="{{ route('superadmin.cutiizin.edit', $ci->id) }}" 
                                   class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="confirmDelete({{ $ci->id }}, '{{ $ci->user->name }}', '{{ $ci->jenis }}')" 
                                        class="p-2 text-gray-600 hover:bg-gray-50 rounded-lg transition"
                                        title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            @else
                            <div class="flex items-center space-x-2">
                                <button onclick="openDetailModal({{ json_encode($ci) }})" 
                                        class="p-2 text-cyan-600 hover:bg-cyan-50 rounded-lg transition"
                                        title="Detail">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <a href="{{ route('superadmin.cutiizin.edit', $ci->id) }}" 
                                   class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                                   title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="confirmDelete({{ $ci->id }}, '{{ $ci->user->name }}', '{{ $ci->jenis }}')" 
                                        class="p-2 text-gray-600 hover:bg-gray-50 rounded-lg transition"
                                        title="Hapus">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <div class="text-xs text-gray-500 ml-2">
                                    <p class="truncate max-w-[100px]">oleh {{ $ci->approver->name ?? '-' }}</p>
                                    <p>{{ $ci->updated_at->format('d M Y') }}</p>
                                </div>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-400">
                                <i class="fas fa-calendar-alt text-5xl mb-4"></i>
                                <p class="text-lg font-semibold">Belum ada pengajuan cuti/izin</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="lg:hidden divide-y divide-gray-200" id="mobileTableBody">
            @forelse($cutiIzin as $key => $ci)
            <div class="p-4 hover:bg-teal-50 transition">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center space-x-3 flex-1 min-w-0">
                        <img src="{{ $ci->user->foto ? asset('storage/' . $ci->user->foto) : 'https://ui-avatars.com/api/?name=' . urlencode($ci->user->name) . '&background=14b8a6&color=fff' }}" 
                             alt="{{ $ci->user->name }}"
                             class="w-12 h-12 rounded-full object-cover border-2 border-teal-200 flex-shrink-0">
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $ci->user->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ $ci->user->divisi }}</p>
                        </div>
                    </div>
                    <span class="px-2 py-1 rounded-full text-xs font-semibold whitespace-nowrap ml-2 flex-shrink-0
                        {{ $ci->status == 'pending' ? 'bg-amber-100 text-amber-700' : '' }}
                        {{ $ci->status == 'disetujui' ? 'bg-teal-100 text-teal-700' : '' }}
                        {{ $ci->status == 'ditolak' ? 'bg-red-100 text-red-700' : '' }}">
                        {{ ucfirst($ci->status) }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-3 mb-3">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Jenis</p>
                        <span class="px-2 py-1 rounded-full text-xs font-semibold inline-block
                            {{ $ci->jenis == 'cuti' ? 'bg-teal-100 text-teal-700' : '' }}
                            {{ $ci->jenis == 'izin' ? 'bg-cyan-100 text-cyan-700' : '' }}
                            {{ $ci->jenis == 'sakit' ? 'bg-red-100 text-red-700' : '' }}">
                            {{ ucfirst($ci->jenis) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Durasi</p>
                        <p class="text-sm font-semibold text-gray-900">{{ $ci->tanggal_mulai->diffInDays($ci->tanggal_selesai) + 1 }} hari</p>
                    </div>
                </div>

                <div class="mb-3">
                    <p class="text-xs text-gray-500 mb-1">Tanggal</p>
                    <p class="text-sm font-semibold text-gray-900">{{ $ci->tanggal_mulai->format('d M Y') }} - {{ $ci->tanggal_selesai->format('d M Y') }}</p>
                </div>

                <div class="mb-3">
                    <p class="text-xs text-gray-500 mb-1">Alasan</p>
                    <p class="text-sm text-gray-700 line-clamp-2">{{ $ci->alasan }}</p>
                </div>

                @if($ci->status == 'pending')
                <div class="grid grid-cols-2 gap-2 mb-2">
                    <button onclick="openApproveModal({{ $ci->id }}, '{{ $ci->user->name }}', '{{ $ci->jenis }}')" 
                            class="py-2 px-3 bg-teal-100 text-teal-700 rounded-lg hover:bg-teal-200 transition text-sm font-semibold">
                        <i class="fas fa-check mr-1"></i> Setujui
                    </button>
                    <button onclick="openRejectModal({{ $ci->id }}, '{{ $ci->user->name }}', '{{ $ci->jenis }}')" 
                            class="py-2 px-3 bg-red-100 text-red-700 rounded-lg hover:bg-red-200 transition text-sm font-semibold">
                        <i class="fas fa-times mr-1"></i> Tolak
                    </button>
                </div>
                <div class="grid grid-cols-3 gap-2">
                    <button onclick="openDetailModal({{ json_encode($ci) }})" 
                            class="py-2 px-3 bg-cyan-100 text-cyan-700 rounded-lg hover:bg-cyan-200 transition text-sm font-semibold">
                        <i class="fas fa-eye"></i>
                    </button>
                    <a href="{{ route('superadmin.cutiizin.edit', $ci->id) }}" 
                       class="py-2 px-3 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition text-sm font-semibold text-center flex items-center justify-center">
                        <i class="fas fa-edit"></i>
                    </a>
                    <button onclick="confirmDelete({{ $ci->id }}, '{{ $ci->user->name }}', '{{ $ci->jenis }}')" 
                            class="py-2 px-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm font-semibold">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                @else
                <div class="flex items-center justify-between mb-2">
                    <div class="text-xs text-gray-500">
                        <p>Diproses oleh: <span class="font-semibold">{{ $ci->approver->name ?? '-' }}</span></p>
                        <p>{{ $ci->updated_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
                <div class="grid grid-cols-3 gap-2">
                    <button onclick="openDetailModal({{ json_encode($ci) }})" 
                            class="py-2 px-3 bg-cyan-100 text-cyan-700 rounded-lg hover:bg-cyan-200 transition text-sm font-semibold">
                        <i class="fas fa-eye"></i>
                    </button>
                    <a href="{{ route('superadmin.cutiizin.edit', $ci->id) }}" 
                       class="py-2 px-3 bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200 transition text-sm font-semibold text-center flex items-center justify-center">
                        <i class="fas fa-edit"></i>
                    </a>
                    <button onclick="confirmDelete({{ $ci->id }}, '{{ $ci->user->name }}', '{{ $ci->jenis }}')" 
                            class="py-2 px-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition text-sm font-semibold">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                @endif
            </div>
            @empty
            <div class="px-4 py-12 text-center">
                <div class="flex flex-col items-center justify-center text-gray-400">
                    <i class="fas fa-calendar-alt text-5xl mb-4"></i>
                    <p class="text-base font-semibold">Belum ada pengajuan cuti/izin</p>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-gray-200">
            {{ $cutiIzin->links() }}
        </div>
    </div>

</div>

<!-- Modal Approve -->
<div id="approveModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-2xl max-w-md w-full transform transition-all max-h-[90vh] overflow-y-auto">
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 sticky top-0 bg-white rounded-t-xl sm:rounded-t-2xl">
            <h3 class="text-lg sm:text-xl font-bold text-gray-900">
                <i class="fas fa-check-circle text-teal-600 mr-2"></i>
                Setujui Pengajuan
            </h3>
        </div>
        <form id="approveForm" method="POST">
            @csrf
            <div class="px-4 sm:px-6 py-4 sm:py-6">
                <div class="text-center mb-4 sm:mb-6">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 bg-teal-100 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                        <i class="fas fa-check-circle text-teal-600 text-xl sm:text-2xl"></i>
                    </div>
                    <p class="text-sm sm:text-base text-gray-700 mb-2">Setujui pengajuan <span id="approveJenis" class="font-semibold"></span> dari:</p>
                    <p class="text-base sm:text-lg font-bold text-gray-900" id="approveKaryawanName"></p>
                </div>

                <div>
                    <label for="keterangan_approve" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-comment mr-2 text-teal-600"></i>Keterangan (Opsional)
                    </label>
                    <textarea name="keterangan_approval" id="keterangan_approve" rows="3" 
                              class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-400 transition" 
                              placeholder="Tambahkan catatan jika diperlukan"></textarea>
                </div>
            </div>
            <div class="px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 rounded-b-xl sm:rounded-b-2xl flex flex-col sm:flex-row justify-end gap-2 sm:gap-3 sticky bottom-0">
                <button type="button" onclick="closeModal('approveModal')" class="w-full sm:w-auto px-4 sm:px-5 py-2 sm:py-2.5 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition text-sm sm:text-base">
                    Batal
                </button>
                <button type="submit" class="w-full sm:w-auto px-4 sm:px-5 py-2 sm:py-2.5 bg-gradient-to-r from-teal-500 to-cyan-600 text-white font-semibold rounded-lg hover:from-teal-600 hover:to-cyan-700 transition shadow-md text-sm sm:text-base">
                    <i class="fas fa-check mr-2"></i>Setujui
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Reject -->
<div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-2xl max-w-md w-full transform transition-all max-h-[90vh] overflow-y-auto">
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 sticky top-0 bg-white rounded-t-xl sm:rounded-t-2xl">
            <h3 class="text-lg sm:text-xl font-bold text-gray-900">
                <i class="fas fa-times-circle text-red-600 mr-2"></i>
                Tolak Pengajuan
            </h3>
        </div>
        <form id="rejectForm" method="POST">
            @csrf
            <div class="px-4 sm:px-6 py-4 sm:py-6">
                <div class="text-center mb-4 sm:mb-6">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                        <i class="fas fa-times-circle text-red-600 text-xl sm:text-2xl"></i>
                    </div>
                    <p class="text-sm sm:text-base text-gray-700 mb-2">Tolak pengajuan <span id="rejectJenis" class="font-semibold"></span> dari:</p>
                    <p class="text-base sm:text-lg font-bold text-gray-900" id="rejectKaryawanName"></p>
                </div>

                <div>
                    <label for="keterangan_reject" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-comment mr-2 text-red-600"></i>Alasan Penolakan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="keterangan_approval" id="keterangan_reject" rows="3" 
                              class="w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-400 transition" 
                              placeholder="Masukkan alasan penolakan" required></textarea>
                    <p class="text-xs text-gray-500 mt-1">Alasan penolakan akan dikirimkan ke karyawan</p>
                </div>
            </div>
            <div class="px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 rounded-b-xl sm:rounded-b-2xl flex flex-col sm:flex-row justify-end gap-2 sm:gap-3 sticky bottom-0">
                <button type="button" onclick="closeModal('rejectModal')" class="w-full sm:w-auto px-4 sm:px-5 py-2 sm:py-2.5 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition text-sm sm:text-base">
                    Batal
                </button>
                <button type="submit" class="w-full sm:w-auto px-4 sm:px-5 py-2 sm:py-2.5 bg-gradient-to-r from-red-600 to-pink-600 text-white font-semibold rounded-lg hover:from-red-700 hover:to-pink-700 transition shadow-md text-sm sm:text-base">
                    <i class="fas fa-times mr-2"></i>Tolak
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Detail -->
<div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-2xl max-w-2xl w-full transform transition-all max-h-[90vh] overflow-y-auto">
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 sticky top-0 bg-white rounded-t-xl sm:rounded-t-2xl">
            <h3 class="text-lg sm:text-xl font-bold text-gray-900">
                <i class="fas fa-info-circle text-cyan-600 mr-2"></i>
                Detail Pengajuan
            </h3>
        </div>
        <div class="px-4 sm:px-6 py-4 sm:py-6 space-y-4">
            <div class="flex items-center space-x-3 sm:space-x-4 pb-4 border-b">
                <img id="detailFoto" src="" alt="" class="w-14 h-14 sm:w-16 sm:h-16 rounded-full object-cover border-2 border-teal-200 flex-shrink-0">
                <div class="min-w-0 flex-1">
                    <h4 id="detailNama" class="text-base sm:text-lg font-bold text-gray-900 truncate"></h4>
                    <p id="detailDivisi" class="text-sm text-gray-500 truncate"></p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 sm:gap-4">
                <div>
                    <p class="text-xs text-gray-500 mb-1">Jenis</p>
                    <p id="detailJenis" class="text-sm sm:text-base font-semibold text-gray-900"></p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-1">Status</p>
                    <span id="detailStatus"></span>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-1">Tanggal Mulai</p>
                    <p id="detailTanggalMulai" class="text-sm sm:text-base font-semibold text-gray-900"></p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 mb-1">Tanggal Selesai</p>
                    <p id="detailTanggalSelesai" class="text-sm sm:text-base font-semibold text-gray-900"></p>
                </div>
                <div class="col-span-2">
                    <p class="text-xs text-gray-500 mb-1">Durasi</p>
                    <p id="detailDurasi" class="text-sm sm:text-base font-semibold text-gray-900"></p>
                </div>
            </div>

            <div>
                <p class="text-xs text-gray-500 mb-1">Alasan</p>
                <div id="detailAlasan" class="bg-gray-50 rounded-lg p-3 sm:p-4 text-sm text-gray-700"></div>
            </div>

            <div id="detailApprovalSection" class="hidden">
                <p class="text-xs text-gray-500 mb-1">Keterangan Approval</p>
                <div class="bg-teal-50 border border-teal-200 rounded-lg p-3 sm:p-4">
                    <p id="detailKeterangan" class="text-sm text-teal-800"></p>
                    <p id="detailApprover" class="text-xs text-teal-600 mt-2"></p>
                </div>
            </div>
        </div>
        <div class="px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 rounded-b-xl sm:rounded-b-2xl flex justify-end sticky bottom-0">
            <button type="button" onclick="closeModal('detailModal')" class="w-full sm:w-auto px-4 sm:px-5 py-2 sm:py-2.5 bg-gradient-to-r from-teal-500 to-cyan-600 text-white font-semibold rounded-lg hover:from-teal-600 hover:to-cyan-700 transition shadow-md text-sm sm:text-base">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-2xl max-w-md w-full transform transition-all">
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200">
            <h3 class="text-lg sm:text-xl font-bold text-gray-900">
                <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                Konfirmasi Hapus
            </h3>
        </div>
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="px-4 sm:px-6 py-4 sm:py-6">
                <div class="text-center mb-4 sm:mb-6">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3 sm:mb-4">
                        <i class="fas fa-exclamation-triangle text-red-600 text-xl sm:text-2xl"></i>
                    </div>
                    <p class="text-sm sm:text-base text-gray-700 mb-2">Apakah Anda yakin ingin menghapus pengajuan <span id="deleteJenis" class="font-semibold"></span> dari:</p>
                    <p class="text-base sm:text-lg font-bold text-gray-900" id="deleteKaryawanName"></p>
                    <p class="text-xs sm:text-sm text-red-600 mt-2">Data yang dihapus tidak dapat dikembalikan!</p>
                </div>
            </div>
            <div class="px-4 sm:px-6 py-3 sm:py-4 bg-gray-50 rounded-b-xl sm:rounded-b-2xl flex flex-col sm:flex-row justify-end gap-2 sm:gap-3">
                <button type="button" onclick="closeModal('deleteModal')" class="w-full sm:w-auto px-4 sm:px-5 py-2 sm:py-2.5 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition text-sm sm:text-base">
                    Batal
                </button>
                <button type="submit" class="w-full sm:w-auto px-4 sm:px-5 py-2 sm:py-2.5 bg-gradient-to-r from-red-600 to-pink-600 text-white font-semibold rounded-lg hover:from-red-700 hover:to-pink-700 transition shadow-md text-sm sm:text-base">
                    <i class="fas fa-trash mr-2"></i>Hapus
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Search functionality
    document.getElementById('searchInput').addEventListener('input', function() {
        filterTable();
    });

    // Filter Status
    document.getElementById('filterStatus').addEventListener('change', function() {
        filterTable();
    });

    // Filter Jenis
    document.getElementById('filterJenis').addEventListener('change', function() {
        filterTable();
    });

    // Filter table function
    function filterTable() {
        const statusFilter = document.getElementById('filterStatus').value.toLowerCase();
        const jenisFilter = document.getElementById('filterJenis').value.toLowerCase();
        const searchValue = document.getElementById('searchInput').value.toLowerCase();
        
        // Filter desktop table
        const tableRows = document.querySelectorAll('#tableBody tr');
        tableRows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const showStatus = statusFilter === '' || text.includes(statusFilter);
            const showJenis = jenisFilter === '' || text.includes(jenisFilter);
            const showSearch = text.includes(searchValue);
            
            row.style.display = (showStatus && showJenis && showSearch) ? '' : 'none';
        });
        
        // Filter mobile cards
        const mobileCards = document.querySelectorAll('#mobileTableBody > div');
        mobileCards.forEach(card => {
            const text = card.textContent.toLowerCase();
            const showStatus = statusFilter === '' || text.includes(statusFilter);
            const showJenis = jenisFilter === '' || text.includes(jenisFilter);
            const showSearch = text.includes(searchValue);
            
            card.style.display = (showStatus && showJenis && showSearch) ? '' : 'none';
        });
    }

    // Modal functions
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.style.overflow = '';
        
        // Reset form if exists
        const form = modal.querySelector('form');
        if (form) {
            form.reset();
        }
    }

    // Close modal when clicking outside
    document.querySelectorAll('[id$="Modal"]').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal(this.id);
            }
        });
    });

    // Close modal on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modals = ['approveModal', 'rejectModal', 'detailModal', 'deleteModal'];
            modals.forEach(modalId => {
                const modal = document.getElementById(modalId);
                if (modal && !modal.classList.contains('hidden')) {
                    closeModal(modalId);
                }
            });
        }
    });

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
            : `https://ui-avatars.com/api/?name=${encodeURIComponent(cutiIzin.user.name)}&background=14b8a6&color=fff`;
        document.getElementById('detailNama').textContent = cutiIzin.user.name;
        document.getElementById('detailDivisi').textContent = cutiIzin.user.divisi;
        document.getElementById('detailJenis').textContent = cutiIzin.jenis.charAt(0).toUpperCase() + cutiIzin.jenis.slice(1);
        
        // Status badge
        let statusClass = '';
        if (cutiIzin.status === 'pending') statusClass = 'bg-amber-100 text-amber-700';
        else if (cutiIzin.status === 'disetujui') statusClass = 'bg-teal-100 text-teal-700';
        else statusClass = 'bg-red-100 text-red-700';
        
        const statusBadge = `<span class="px-2 sm:px-3 py-1 rounded-full text-xs font-semibold ${statusClass}">${cutiIzin.status.charAt(0).toUpperCase() + cutiIzin.status.slice(1)}</span>`;
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

    // Confirm Delete
    function confirmDelete(id, name, jenis) {
        document.getElementById('deleteKaryawanName').textContent = name;
        document.getElementById('deleteJenis').textContent = jenis;
        document.getElementById('deleteForm').action = `/superadmin/cuti-izin/${id}`;
        openModal('deleteModal');
    }
</script>
@endpush