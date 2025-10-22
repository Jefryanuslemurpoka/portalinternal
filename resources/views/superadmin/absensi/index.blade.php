@extends('layouts.app')

@section('title', 'Manajemen Absensi')
@section('page-title', 'Manajemen Absensi')

@section('content')
<div class="space-y-6">

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-white/80 mb-1">Total Hadir</p>
                    <h3 class="text-3xl font-bold">{{ $totalHadir }}</h3>
                    <p class="text-xs text-white/70 mt-1">Karyawan</p>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-white/80 mb-1">Tepat Waktu</p>
                    <h3 class="text-3xl font-bold">{{ $tepatWaktu }}</h3>
                    <p class="text-xs text-white/70 mt-1">≤ 08:00</p>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-white/80 mb-1">Terlambat</p>
                    <h3 class="text-3xl font-bold">{{ $terlambat }}</h3>
                    <p class="text-xs text-white/70 mt-1">> 08:00</p>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation-circle text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-xl shadow-lg p-6">
        <form method="GET" action="{{ route('superadmin.absensi.index') }}" class="space-y-4">
            
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-800">
                    <i class="fas fa-filter mr-2"></i>Filter Absensi
                </h3>
                <button type="button" onclick="resetFilter()" class="text-sm text-gray-600 hover:text-gray-800">
                    <i class="fas fa-redo mr-1"></i>Reset Filter
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                
                <!-- Filter Tanggal Tunggal -->
                <div>
                    <label class="form-label">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" 
                           value="{{ request('tanggal', date('Y-m-d')) }}" 
                           class="form-input">
                </div>

                <!-- Filter Karyawan -->
                <div>
                    <label class="form-label">Karyawan</label>
                    <select name="user_id" id="user_id" class="form-input">
                        <option value="">Semua Karyawan</option>
                        @foreach($karyawan as $k)
                            <option value="{{ $k->id }}" {{ request('user_id') == $k->id ? 'selected' : '' }}>
                                {{ $k->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Divisi -->
                <div>
                    <label class="form-label">Divisi</label>
                    <select name="divisi" id="divisi" class="form-input">
                        <option value="">Semua Divisi</option>
                        @foreach($divisiList as $div)
                            <option value="{{ $div }}" {{ request('divisi') == $div ? 'selected' : '' }}>
                                {{ $div }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Button Filter -->
                <div class="flex items-end">
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

    <!-- Table Absensi -->
    <x-table 
        id="absensiTable"
        title="Rekap Absensi"
        :headers="['No', 'Tanggal', 'Karyawan', 'Divisi', 'Jam Masuk', 'Jam Keluar', 'Lokasi', 'Status', 'Aksi']"
        :addButton="false"
    >
        @forelse($absensi as $key => $a)
        <tr class="hover:bg-gray-50 transition">
            <td class="px-6 py-4 text-sm text-gray-900">
                {{ $absensi->firstItem() + $key }}
            </td>
            <td class="px-6 py-4">
                <div>
                    <p class="text-sm font-semibold text-gray-900">{{ $a->tanggal->format('d M Y') }}</p>
                    <p class="text-xs text-gray-500">{{ $a->tanggal->format('l') }}</p>
                </div>
            </td>
            <td class="px-6 py-4">
                <div class="flex items-center space-x-3">
                    <img src="{{ $a->user->foto ? asset('storage/' . $a->user->foto) : 'https://ui-avatars.com/api/?name=' . urlencode($a->user->name) . '&background=4F46E5&color=fff' }}" 
                         alt="{{ $a->user->name }}"
                         class="w-10 h-10 rounded-full object-cover border-2 border-gray-200">
                    <div>
                        <p class="text-sm font-semibold text-gray-900">{{ $a->user->name }}</p>
                        <p class="text-xs text-gray-500">{{ $a->user->email }}</p>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4">
                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
                    {{ $a->user->divisi }}
                </span>
            </td>
            <td class="px-6 py-4">
                <div class="flex items-center space-x-2">
                    @if($a->jam_masuk)
                        <span class="text-sm font-semibold {{ strtotime($a->jam_masuk) <= strtotime('08:00:00') ? 'text-green-600' : 'text-red-600' }}">
                            {{ date('H:i', strtotime($a->jam_masuk)) }}
                        </span>
                        @if(strtotime($a->jam_masuk) > strtotime('08:00:00'))
                            <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs">
                                Terlambat
                            </span>
                        @endif
                    @else
                        <span class="text-xs text-gray-400">-</span>
                    @endif
                </div>
            </td>
            <td class="px-6 py-4">
                @if($a->jam_keluar)
                    <span class="text-sm font-semibold text-gray-900">
                        {{ date('H:i', strtotime($a->jam_keluar)) }}
                    </span>
                @else
                    <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs">
                        Belum Check-out
                    </span>
                @endif
            </td>
            <td class="px-6 py-4">
                @if($a->lokasi)
                    <button onclick="showLocation('{{ $a->lokasi }}')" class="text-blue-600 hover:text-blue-800 text-sm">
                        <i class="fas fa-map-marker-alt mr-1"></i>Lihat
                    </button>
                @else
                    <span class="text-xs text-gray-400">-</span>
                @endif
            </td>
            <td class="px-6 py-4">
                @if($a->jam_masuk && $a->jam_keluar)
                    @php
                        $jamMasuk = \Carbon\Carbon::parse($a->jam_masuk);
                        $jamKeluar = \Carbon\Carbon::parse($a->jam_keluar);
                        $durasi = $jamMasuk->diff($jamKeluar);
                    @endphp
                    <span class="badge badge-success">
                        Lengkap ({{ $durasi->h }}j {{ $durasi->i }}m)
                    </span>
                @elseif($a->jam_masuk)
                    <span class="badge badge-warning">
                        Check-in Saja
                    </span>
                @else
                    <span class="badge badge-secondary">
                        Tidak Lengkap
                    </span>
                @endif
            </td>
            <td class="px-6 py-4">
                <div class="flex items-center space-x-2">
                    @if($a->foto)
                    <button onclick="showPhoto('{{ asset('storage/' . $a->foto) }}')" 
                            class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                            title="Lihat Foto">
                        <i class="fas fa-camera"></i>
                    </button>
                    @endif
                    <button onclick="openDeleteModal({{ $a->id }}, '{{ $a->user->name }}', '{{ $a->tanggal->format('d M Y') }}')" 
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
                    <i class="fas fa-calendar-times"></i>
                    <p>Tidak ada data absensi</p>
                    <p class="text-sm text-gray-500 mt-2">Coba ubah filter tanggal atau divisi</p>
                </div>
            </td>
        </tr>
        @endforelse

        <x-slot name="pagination">
            {{ $absensi->appends(request()->query())->links() }}
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
            <p class="text-gray-700 mb-2">Hapus data absensi:</p>
            <p class="text-lg font-bold text-gray-900" id="deleteKaryawanName"></p>
            <p class="text-sm text-gray-600" id="deleteTanggal"></p>
            <p class="text-sm text-red-600 mt-4">Data yang dihapus tidak dapat dikembalikan!</p>
        </div>
    </form>
</x-modal>

<!-- Modal Location -->
<x-modal id="locationModal" title="Lokasi Absensi" size="md" type="info">
    <div id="locationContent" class="text-center">
        <p class="text-gray-700 mb-4" id="locationText"></p>
        <a id="locationLink" href="#" target="_blank" class="btn btn-primary">
            <i class="fas fa-map-marked-alt mr-2"></i>Buka di Google Maps
        </a>
    </div>
</x-modal>

<!-- Modal Photo -->
<x-modal id="photoModal" title="Foto Absensi" size="lg" type="info">
    <div class="text-center">
        <img id="photoImage" src="" alt="Foto Absensi" class="max-w-full h-auto rounded-lg shadow-lg mx-auto">
    </div>
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
        window.location.href = "{{ route('superadmin.absensi.index') }}";
    }

    // Open Delete Modal
    function openDeleteModal(id, name, tanggal) {
        document.getElementById('deleteKaryawanName').textContent = name;
        document.getElementById('deleteTanggal').textContent = tanggal;
        document.getElementById('deleteForm').action = `/superadmin/absensi/${id}`;
        openModal('deleteModal');
    }

    // Show Location
    function showLocation(location) {
        const coords = location.split(',');
        const lat = coords[0].trim();
        const lng = coords[1].trim();
        
        document.getElementById('locationText').textContent = `Latitude: ${lat}, Longitude: ${lng}`;
        document.getElementById('locationLink').href = `https://www.google.com/maps?q=${lat},${lng}`;
        openModal('locationModal');
    }

    // Show Photo
    function showPhoto(photoUrl) {
        document.getElementById('photoImage').src = photoUrl;
        openModal('photoModal');
    }
</script>
@endpush