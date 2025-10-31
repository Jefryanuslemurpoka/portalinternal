@extends('layouts.app')

@section('title', 'Manajemen Absensi')
@section('page-title', 'Manajemen Absensi')

@section('content')
<div class="space-y-4 sm:space-y-6">

    @php
    // Hitung batas keterlambatan (jam masuk + toleransi)
    $batasKeterlambatan = \Carbon\Carbon::createFromFormat('H:i', $jamMasuk)
        ->addMinutes($toleransi)
        ->format('H:i:s');
    
    // Format untuk tampilan di card
    $jamMasukFormatted = \Carbon\Carbon::createFromFormat('H:i', $jamMasuk)->format('H:i');
    $batasKetelambatanFormatted = \Carbon\Carbon::createFromFormat('H:i:s', $batasKeterlambatan)->format('H:i');
    @endphp

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
        <div class="bg-gradient-to-br from-teal-500 to-teal-600 rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-white/80 mb-1">Total Hadir</p>
                    <h3 class="text-2xl sm:text-3xl font-bold">{{ $totalHadir }}</h3>
                    <p class="text-xs text-white/70 mt-1">Karyawan</p>
                </div>
                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-2xl sm:text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-white/80 mb-1">Tepat Waktu</p>
                    <h3 class="text-2xl sm:text-3xl font-bold">{{ $tepatWaktu }}</h3>
                    <p class="text-xs text-white/70 mt-1">≤ {{ $batasKetelambatanFormatted }}</p>
                </div>
                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-2xl sm:text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-6 text-white sm:col-span-2 lg:col-span-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs sm:text-sm font-medium text-white/80 mb-1">Terlambat</p>
                    <h3 class="text-2xl sm:text-3xl font-bold">{{ $terlambat }}</h3>
                    <p class="text-xs text-white/70 mt-1">> {{ $batasKetelambatanFormatted }}</p>
                </div>
                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation-circle text-2xl sm:text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg p-4 sm:p-6">
        <form method="GET" action="{{ route('superadmin.absensi.index') }}" class="space-y-4">
            
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 sm:gap-0 mb-4">
                <h3 class="text-base sm:text-lg font-bold text-gray-800">
                    <i class="fas fa-filter mr-2 text-teal-600"></i>Filter Absensi
                </h3>
                <div class="flex items-center gap-2 sm:gap-3">
                    <button type="button" onclick="resetFilter()" class="text-xs sm:text-sm text-gray-600 hover:text-teal-600 transition whitespace-nowrap">
                        <i class="fas fa-redo mr-1"></i><span class="hidden sm:inline">Reset Filter</span><span class="sm:hidden">Reset</span>
                    </button>
                    <a href="{{ route('superadmin.absensi.create') }}" class="inline-flex items-center justify-center px-3 sm:px-5 py-2 sm:py-2.5 bg-gradient-to-r from-teal-500 to-cyan-600 text-white font-semibold rounded-lg hover:from-teal-600 hover:to-cyan-700 transition shadow-md text-xs sm:text-sm whitespace-nowrap flex-shrink-0">
                        <i class="fas fa-plus mr-1 sm:mr-2"></i>
                        <span class="hidden sm:inline">Tambah Absensi</span>
                        <span class="sm:hidden">Tambah</span>
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                
                <!-- Filter Tanggal Tunggal -->
                <div>
                    <label class="form-label text-xs sm:text-sm">Tanggal</label>
                    <input type="date" name="tanggal" id="tanggal" 
                           value="{{ request('tanggal', date('Y-m-d')) }}" 
                           class="form-input text-xs sm:text-sm">
                </div>

                <!-- Filter Karyawan -->
                <div>
                    <label class="form-label text-xs sm:text-sm">Karyawan</label>
                    <select name="user_id" id="user_id" class="form-input text-xs sm:text-sm">
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
                    <label class="form-label text-xs sm:text-sm">Divisi</label>
                    <select name="divisi" id="divisi" class="form-input text-xs sm:text-sm">
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
                    <button type="submit" class="btn btn-primary w-full text-xs sm:text-sm">
                        <i class="fas fa-search mr-1 sm:mr-2"></i>Tampilkan
                    </button>
                </div>

            </div>

            <!-- Filter Range Tanggal (Collapsible) -->
            <div id="rangeFilter" class="hidden">
                <div class="border-t border-gray-200 pt-4 mt-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                        <div>
                            <label class="form-label text-xs sm:text-sm">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" 
                                   value="{{ request('tanggal_mulai') }}" 
                                   class="form-input text-xs sm:text-sm">
                        </div>
                        <div>
                            <label class="form-label text-xs sm:text-sm">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" 
                                   value="{{ request('tanggal_selesai') }}" 
                                   class="form-input text-xs sm:text-sm">
                        </div>
                    </div>
                </div>
            </div>

            <button type="button" onclick="toggleRangeFilter()" class="text-xs sm:text-sm text-teal-600 hover:text-teal-700 font-medium transition">
                <i class="fas fa-calendar-week mr-1"></i>
                <span id="rangeToggleText">Gunakan Range Tanggal</span>
            </button>

        </form>
    </div>

    <!-- Table Absensi -->
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-lg overflow-hidden">
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <h3 class="text-base sm:text-lg font-bold text-gray-800">
                <i class="fas fa-clipboard-list mr-2 text-teal-600"></i>Rekap Absensi
            </h3>
            
            <!-- Export Button -->
            @if($absensi->total() > 0)
            <form action="{{ route('superadmin.absensi.export') }}" method="POST" class="inline-block">
                @csrf
                <input type="hidden" name="tanggal" value="{{ request('tanggal') }}">
                <input type="hidden" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}">
                <input type="hidden" name="tanggal_selesai" value="{{ request('tanggal_selesai') }}">
                <input type="hidden" name="user_id" value="{{ request('user_id') }}">
                <input type="hidden" name="divisi" value="{{ request('divisi') }}">
                
                <button type="submit" class="inline-flex items-center justify-center px-3 sm:px-4 py-2 sm:py-2.5 bg-gradient-to-r from-green-500 to-emerald-600 text-white font-semibold rounded-lg hover:from-green-600 hover:to-emerald-700 transition shadow-md text-xs sm:text-sm whitespace-nowrap">
                    <i class="fas fa-file-excel mr-1 sm:mr-2"></i>
                    <span class="hidden sm:inline">Export Excel</span>
                    <span class="sm:hidden">Export</span>
                </button>
            </form>
            @endif
        </div>
            
        <!-- Mobile Card View -->
        <div class="block lg:hidden divide-y divide-gray-100">
            @forelse($absensi as $key => $a)
            <div class="p-4 hover:bg-teal-50 transition">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex items-center space-x-3">
                        <img src="{{ $a->user->foto ? asset('storage/' . $a->user->foto) : 'https://ui-avatars.com/api/?name=' . urlencode($a->user->name) . '&background=14b8a6&color=fff' }}" 
                             alt="{{ $a->user->name }}"
                             class="w-12 h-12 rounded-full object-cover border-2 border-teal-200">
                        <div>
                            <p class="text-sm font-semibold text-gray-900">{{ $a->user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $a->user->email }}</p>
                            <span class="inline-block mt-1 px-2 py-0.5 bg-teal-100 text-teal-700 rounded-full text-xs font-semibold">
                                {{ $a->user->divisi }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tanggal:</span>
                        <span class="font-semibold text-gray-900">{{ $a->tanggal->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Jam Masuk:</span>
                        @if($a->jam_masuk)
                            <div class="flex items-center gap-2">
                                <span class="font-semibold {{ strtotime($a->jam_masuk) <= strtotime($batasKeterlambatan) ? 'text-teal-600' : 'text-red-600' }}">
                                    {{ date('H:i', strtotime($a->jam_masuk)) }}
                                </span>
                                @if(strtotime($a->jam_masuk) > strtotime($batasKeterlambatan))
                                    <span class="px-2 py-0.5 bg-red-100 text-red-700 rounded text-xs">
                                        Terlambat
                                    </span>
                                @endif
                            </div>
                        @else
                            <span class="text-xs text-gray-400">-</span>
                        @endif
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Jam Keluar:</span>
                        @if($a->jam_keluar)
                            <span class="font-semibold text-gray-900">
                                {{ date('H:i', strtotime($a->jam_keluar)) }}
                            </span>
                        @else
                            <span class="px-2 py-0.5 bg-amber-100 text-amber-700 rounded text-xs">
                                Belum Check-out
                            </span>
                        @endif
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Status:</span>
                        @if($a->jam_masuk && $a->jam_keluar)
                            @php
                                $jamMasukParse = \Carbon\Carbon::parse($a->jam_masuk);
                                $jamKeluarParse = \Carbon\Carbon::parse($a->jam_keluar);
                                $durasi = $jamMasukParse->diff($jamKeluarParse);
                            @endphp
                            <span class="badge badge-success text-xs">
                                Lengkap ({{ $durasi->h }}j {{ $durasi->i }}m)
                            </span>
                        @elseif($a->jam_masuk)
                            <span class="badge badge-warning text-xs">
                                Check-in Saja
                            </span>
                        @else
                            <span class="badge badge-secondary text-xs">
                                Tidak Lengkap
                            </span>
                        @endif
                    </div>
                </div>

                <div class="flex items-center justify-end gap-2 mt-3 pt-3 border-t border-gray-100">
                    @if($a->lokasi)
                    <button onclick="showLocation('{{ $a->lokasi }}')" 
                            class="px-3 py-1.5 text-xs text-teal-600 hover:bg-teal-50 rounded-lg transition"
                            title="Lokasi">
                        <i class="fas fa-map-marker-alt"></i>
                    </button>
                    @endif
                    <a href="{{ route('superadmin.absensi.edit', $a->id) }}" 
                       class="px-3 py-1.5 text-xs text-amber-600 hover:bg-amber-50 rounded-lg transition"
                       title="Edit">
                        <i class="fas fa-edit"></i>
                    </a>
                    @if($a->foto)
                    <button onclick="showPhoto('{{ asset('storage/' . $a->foto) }}')" 
                            class="px-3 py-1.5 text-xs text-purple-600 hover:bg-purple-50 rounded-lg transition"
                            title="Foto">
                        <i class="fas fa-camera"></i>
                    </button>
                    @endif
                    <button onclick="openDeleteModal({{ $a->id }}, '{{ $a->user->name }}', '{{ $a->tanggal->format('d M Y') }}')" 
                            class="px-3 py-1.5 text-xs text-red-600 hover:bg-red-50 rounded-lg transition"
                            title="Hapus">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            @empty
            <div class="p-8 sm:p-12 text-center">
                <i class="fas fa-calendar-times text-gray-300 text-4xl sm:text-5xl mb-3"></i>
                <p class="text-gray-500 text-sm sm:text-base">Tidak ada data absensi</p>
                <p class="text-xs sm:text-sm text-gray-400 mt-2">Coba ubah filter tanggal atau divisi</p>
            </div>
            @endforelse
        </div>

        <!-- Desktop Table View -->
        <div class="hidden lg:block overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gradient-to-r from-teal-500 to-cyan-600 text-white">
                    <tr>
                        <th class="px-4 xl:px-6 py-3 xl:py-4 text-left text-xs xl:text-sm font-semibold">No</th>
                        <th class="px-4 xl:px-6 py-3 xl:py-4 text-left text-xs xl:text-sm font-semibold">Tanggal</th>
                        <th class="px-4 xl:px-6 py-3 xl:py-4 text-left text-xs xl:text-sm font-semibold">Karyawan</th>
                        <th class="px-4 xl:px-6 py-3 xl:py-4 text-left text-xs xl:text-sm font-semibold">Divisi</th>
                        <th class="px-4 xl:px-6 py-3 xl:py-4 text-left text-xs xl:text-sm font-semibold">Jam Masuk</th>
                        <th class="px-4 xl:px-6 py-3 xl:py-4 text-left text-xs xl:text-sm font-semibold">Jam Keluar</th>
                        <th class="px-4 xl:px-6 py-3 xl:py-4 text-left text-xs xl:text-sm font-semibold">Lokasi</th>
                        <th class="px-4 xl:px-6 py-3 xl:py-4 text-left text-xs xl:text-sm font-semibold">Status</th>
                        <th class="px-4 xl:px-6 py-3 xl:py-4 text-left text-xs xl:text-sm font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($absensi as $key => $a)
                    <tr class="hover:bg-teal-50 transition">
                        <td class="px-4 xl:px-6 py-3 xl:py-4 text-xs xl:text-sm text-gray-900">
                            {{ $absensi->firstItem() + $key }}
                        </td>
                        <td class="px-4 xl:px-6 py-3 xl:py-4">
                            <div>
                                <p class="text-xs xl:text-sm font-semibold text-gray-900">{{ $a->tanggal->format('d M Y') }}</p>
                                <p class="text-xs text-gray-500">{{ $a->tanggal->format('l') }}</p>
                            </div>
                        </td>
                        <td class="px-4 xl:px-6 py-3 xl:py-4">
                            <div class="flex items-center space-x-3">
                                <img src="{{ $a->user->foto ? asset('storage/' . $a->user->foto) : 'https://ui-avatars.com/api/?name=' . urlencode($a->user->name) . '&background=14b8a6&color=fff' }}" 
                                     alt="{{ $a->user->name }}"
                                     class="w-8 xl:w-10 h-8 xl:h-10 rounded-full object-cover border-2 border-teal-200">
                                <div>
                                    <p class="text-xs xl:text-sm font-semibold text-gray-900">{{ $a->user->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $a->user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 xl:px-6 py-3 xl:py-4">
                            <span class="px-2 xl:px-3 py-1 bg-teal-100 text-teal-700 rounded-full text-xs font-semibold">
                                {{ $a->user->divisi }}
                            </span>
                        </td>
                        <td class="px-4 xl:px-6 py-3 xl:py-4">
                            <div class="flex items-center space-x-2">
                                @if($a->jam_masuk)
                                    <span class="text-xs xl:text-sm font-semibold {{ strtotime($a->jam_masuk) <= strtotime($batasKeterlambatan) ? 'text-teal-600' : 'text-red-600' }}">
                                        {{ date('H:i', strtotime($a->jam_masuk)) }}
                                    </span>
                                    @if(strtotime($a->jam_masuk) > strtotime($batasKeterlambatan))
                                        <span class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs">
                                            Terlambat
                                        </span>
                                    @endif
                                @else
                                    <span class="text-xs text-gray-400">-</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-4 xl:px-6 py-3 xl:py-4">
                            @if($a->jam_keluar)
                                <span class="text-xs xl:text-sm font-semibold text-gray-900">
                                    {{ date('H:i', strtotime($a->jam_keluar)) }}
                                </span>
                            @else
                                <span class="px-2 py-1 bg-amber-100 text-amber-700 rounded text-xs">
                                    Belum Check-out
                                </span>
                            @endif
                        </td>
                        <td class="px-4 xl:px-6 py-3 xl:py-4">
                            @if($a->lokasi)
                                <button onclick="showLocation('{{ $a->lokasi }}')" class="text-teal-600 hover:text-teal-700 text-xs xl:text-sm font-medium transition">
                                    <i class="fas fa-map-marker-alt mr-1"></i>Lihat
                                </button>
                            @else
                                <span class="text-xs text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 xl:px-6 py-3 xl:py-4">
                            @if($a->jam_masuk && $a->jam_keluar)
                                @php
                                    $jamMasukParse = \Carbon\Carbon::parse($a->jam_masuk);
                                    $jamKeluarParse = \Carbon\Carbon::parse($a->jam_keluar);
                                    $durasi = $jamMasukParse->diff($jamKeluarParse);
                                @endphp
                                <span class="badge badge-success text-xs">
                                    Lengkap ({{ $durasi->h }}j {{ $durasi->i }}m)
                                </span>
                            @elseif($a->jam_masuk)
                                <span class="badge badge-warning text-xs">
                                    Check-in Saja
                                </span>
                            @else
                                <span class="badge badge-secondary text-xs">
                                    Tidak Lengkap
                                </span>
                            @endif
                        </td>
                        <td class="px-4 xl:px-6 py-3 xl:py-4">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('superadmin.absensi.edit', $a->id) }}" 
                                   class="p-1.5 xl:p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition"
                                   title="Edit">
                                    <i class="fas fa-edit text-xs xl:text-sm"></i>
                                </a>
                                @if($a->foto)
                                <button onclick="showPhoto('{{ asset('storage/' . $a->foto) }}')" 
                                        class="p-1.5 xl:p-2 text-purple-600 hover:bg-purple-50 rounded-lg transition"
                                        title="Lihat Foto">
                                    <i class="fas fa-camera text-xs xl:text-sm"></i>
                                </button>
                                @endif
                                <button onclick="openDeleteModal({{ $a->id }}, '{{ $a->user->name }}', '{{ $a->tanggal->format('d M Y') }}')" 
                                        class="p-1.5 xl:p-2 text-red-600 hover:bg-red-50 rounded-lg transition"
                                        title="Hapus">
                                    <i class="fas fa-trash text-xs xl:text-sm"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center">
                            <div class="empty-state">
                                <i class="fas fa-calendar-times text-gray-300 text-5xl mb-3"></i>
                                <p class="text-gray-500">Tidak ada data absensi</p>
                                <p class="text-sm text-gray-400 mt-2">Coba ubah filter tanggal atau divisi</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($absensi->hasPages())
        <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-gray-200">
            {{ $absensi->appends(request()->query())->links() }}
        </div>
        @endif
    </div>

</div>

<!-- Modal Delete -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full" onclick="event.stopPropagation()">
        <div class="p-4 sm:p-6 border-b border-gray-200">
            <h3 class="text-lg sm:text-xl font-bold text-gray-900">Konfirmasi Hapus</h3>
        </div>
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="p-4 sm:p-6">
                <div class="text-center">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-exclamation-triangle text-red-600 text-xl sm:text-2xl"></i>
                    </div>
                    <p class="text-sm sm:text-base text-gray-700 mb-2">Hapus data absensi:</p>
                    <p class="text-base sm:text-lg font-bold text-gray-900" id="deleteKaryawanName"></p>
                    <p class="text-xs sm:text-sm text-gray-600" id="deleteTanggal"></p>
                    <p class="text-xs sm:text-sm text-red-600 mt-4">Data yang dihapus tidak dapat dikembalikan!</p>
                </div>
            </div>
            <div class="p-4 sm:p-6 bg-gray-50 rounded-b-xl flex items-center justify-end space-x-2 sm:space-x-3">
                <button type="button" onclick="closeModal('deleteModal')" class="px-4 sm:px-5 py-2 sm:py-2.5 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition text-xs sm:text-sm">
                    Batal
                </button>
                <button type="submit" class="px-4 sm:px-5 py-2 sm:py-2.5 bg-gradient-to-r from-red-500 to-red-600 text-white font-semibold rounded-lg hover:from-red-600 hover:to-red-700 transition shadow-md text-xs sm:text-sm">
                    <i class="fas fa-trash mr-1 sm:mr-2"></i>Hapus
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Location -->
<div id="locationModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full" onclick="event.stopPropagation()">
        <div class="p-4 sm:p-6 border-b border-gray-200">
            <h3 class="text-lg sm:text-xl font-bold text-gray-900">Lokasi Absensi</h3>
        </div>
        <div class="p-4 sm:p-6">
            <div id="locationContent" class="text-center">
                <p class="text-sm sm:text-base text-gray-700 mb-4" id="locationText"></p>
                <a id="locationLink" href="#" target="_blank" class="btn btn-primary text-xs sm:text-sm">
                    <i class="fas fa-map-marked-alt mr-2"></i>Buka di Google Maps
                </a>
            </div>
        </div>
        <div class="p-4 sm:p-6 bg-gray-50 rounded-b-xl flex justify-end">
            <button type="button" onclick="closeModal('locationModal')" class="px-4 sm:px-5 py-2 sm:py-2.5 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition text-xs sm:text-sm">
                Tutup
            </button>
        </div>
    </div>
</div>

<!-- Modal Photo -->
<div id="photoModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-full sm:max-w-3xl w-full mx-4" onclick="event.stopPropagation()">
        <div class="p-4 sm:p-6 border-b border-gray-200">
            <h3 class="text-lg sm:text-xl font-bold text-gray-900">Foto Absensi</h3>
        </div>
        <div class="p-4 sm:p-6">
            <div class="text-center">
                <img id="photoImage" src="" alt="Foto Absensi" class="max-w-full h-auto rounded-lg shadow-lg mx-auto">
            </div>
        </div>
        <div class="p-4 sm:p-6 bg-gray-50 rounded-b-xl flex justify-end">
            <button type="button" onclick="closeModal('photoModal')" class="px-4 sm:px-5 py-2 sm:py-2.5 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition text-xs sm:text-sm">
                Tutup
            </button>
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
    ['deleteModal', 'locationModal', 'photoModal'].forEach(modalId => {
        document.getElementById(modalId).addEventListener('click', function(e) {
            if (e.target === this) closeModal(modalId);
        });
    });

    // Close modal with ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            ['deleteModal', 'locationModal', 'photoModal'].forEach(modalId => {
                const modal = document.getElementById(modalId);
                if (!modal.classList.contains('hidden')) {
                    closeModal(modalId);
                }
            });
        }
    });
</script>
@endpush