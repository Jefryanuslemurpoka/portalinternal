@extends('layouts.app')

@section('title', 'Pengajuan Cuti & Izin')
@section('page-title', 'Pengajuan Cuti & Izin')

@section('content')
<div class="space-y-4 md:space-y-6">

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-teal-50 border-l-4 border-teal-500 p-3 md:p-4 rounded-lg shadow-sm">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-teal-500 text-lg md:text-xl mr-2 md:mr-3"></i>
                <p class="text-teal-800 font-medium text-sm md:text-base">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-3 md:p-4 rounded-lg shadow-sm">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-red-500 text-lg md:text-xl mr-2 md:mr-3"></i>
                <p class="text-red-800 font-medium text-sm md:text-base">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-4 lg:gap-6">
        <div class="bg-gradient-to-br from-teal-500 to-cyan-500 rounded-lg md:rounded-xl shadow-lg p-4 md:p-6 text-white">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs md:text-sm font-medium text-white/80 mb-1">Total Pengajuan</p>
                    <h3 class="text-2xl md:text-3xl font-bold">{{ $statistik['total'] }}</h3>
                </div>
                <div class="w-10 h-10 md:w-12 md:h-12 lg:w-16 lg:h-16 bg-white/20 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-file-alt text-lg md:text-xl lg:text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-yellow-500 to-orange-500 rounded-lg md:rounded-xl shadow-lg p-4 md:p-6 text-white">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs md:text-sm font-medium text-white/80 mb-1">Pending</p>
                    <h3 class="text-2xl md:text-3xl font-bold">{{ $statistik['pending'] }}</h3>
                </div>
                <div class="w-10 h-10 md:w-12 md:h-12 lg:w-16 lg:h-16 bg-white/20 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-clock text-lg md:text-xl lg:text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-emerald-500 to-teal-500 rounded-lg md:rounded-xl shadow-lg p-4 md:p-6 text-white">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs md:text-sm font-medium text-white/80 mb-1">Disetujui</p>
                    <h3 class="text-2xl md:text-3xl font-bold">{{ $statistik['disetujui'] }}</h3>
                </div>
                <div class="w-10 h-10 md:w-12 md:h-12 lg:w-16 lg:h-16 bg-white/20 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-check-circle text-lg md:text-xl lg:text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-cyan-500 to-blue-500 rounded-lg md:rounded-xl shadow-lg p-4 md:p-6 text-white">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-xs md:text-sm font-medium text-white/80 mb-1">Sisa Cuti</p>
                    <h3 class="text-2xl md:text-3xl font-bold">{{ $statistik['sisa_cuti'] }}</h3>
                    <p class="text-xs md:text-sm text-white/70">hari</p>
                </div>
                <div class="w-10 h-10 md:w-12 md:h-12 lg:w-16 lg:h-16 bg-white/20 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-calendar-check text-lg md:text-xl lg:text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters - Mobile -->
    <div class="lg:hidden bg-white rounded-lg shadow-lg p-4">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-bold text-gray-800">
                <i class="fas fa-filter mr-2"></i>Filter
            </h3>
            <button onclick="openModal('modalPengajuan')" class="px-3 py-1.5 bg-gradient-to-r from-teal-600 to-cyan-600 text-white text-sm font-semibold rounded-lg hover:from-teal-700 hover:to-cyan-700 transition shadow-md whitespace-nowrap">
                <i class="fas fa-plus mr-1"></i>Ajukan
            </button>
        </div>
        <div class="grid grid-cols-2 gap-2">
            <select id="filterStatusMobile" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                <option value="">Semua Status</option>
                <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="disetujui" {{ $status == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                <option value="ditolak" {{ $status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
            <select id="filterJenisMobile" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                <option value="">Semua Jenis</option>
                <option value="cuti" {{ $jenis == 'cuti' ? 'selected' : '' }}>Cuti</option>
                <option value="izin" {{ $jenis == 'izin' ? 'selected' : '' }}>Izin</option>
                <option value="sakit" {{ $jenis == 'sakit' ? 'selected' : '' }}>Sakit</option>
            </select>
        </div>
    </div>

    <!-- Table Desktop -->
    <div class="hidden lg:block">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Table Header -->
            <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <h3 class="text-lg font-bold text-gray-800">
                    <i class="fas fa-list mr-2"></i>Riwayat Pengajuan
                </h3>
                <div class="flex items-center space-x-3">
                    <!-- Filters -->
                    <select id="filterStatus" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="disetujui" {{ $status == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                        <option value="ditolak" {{ $status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                    <select id="filterJenis" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                        <option value="">Semua Jenis</option>
                        <option value="cuti" {{ $jenis == 'cuti' ? 'selected' : '' }}>Cuti</option>
                        <option value="izin" {{ $jenis == 'izin' ? 'selected' : '' }}>Izin</option>
                        <option value="sakit" {{ $jenis == 'sakit' ? 'selected' : '' }}>Sakit</option>
                    </select>
                    <!-- Add Button -->
                    <button onclick="openModal('modalPengajuan')" class="px-5 py-2.5 bg-gradient-to-r from-teal-600 to-cyan-600 text-white font-semibold rounded-lg hover:from-teal-700 hover:to-cyan-700 transition shadow-md">
                        <i class="fas fa-plus mr-2"></i>Ajukan Baru
                    </button>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Pengajuan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durasi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alasan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($pengajuanList as $index => $pengajuan)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $pengajuanList->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->format('d/m/Y H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $pengajuan->jenis == 'cuti' ? 'bg-teal-100 text-teal-700' : '' }}
                                    {{ $pengajuan->jenis == 'izin' ? 'bg-cyan-100 text-cyan-700' : '' }}
                                    {{ $pengajuan->jenis == 'sakit' ? 'bg-red-100 text-red-700' : '' }}">
                                    {{ ucfirst($pengajuan->jenis) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm">
                                    <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->format('d M Y') }}</p>
                                    <p class="text-xs text-gray-500">s/d {{ \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->format('d M Y') }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                {{ $pengajuan->jumlah_hari }} hari
                            </td>
                            <td class="px-6 py-4">
                                <div class="max-w-xs">
                                    <p class="text-sm text-gray-700 line-clamp-2">{{ Str::limit($pengajuan->alasan, 50) }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($pengajuan->status == 'pending')
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                                        <i class="fas fa-clock"></i> Pending
                                    </span>
                                @elseif($pengajuan->status == 'disetujui')
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-teal-100 text-teal-700">
                                        <i class="fas fa-check"></i> Disetujui
                                    </span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                        <i class="fas fa-times"></i> Ditolak
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center space-x-2">
                                    <button onclick="openDetailModal({{ json_encode($pengajuan) }})" 
                                            class="p-2 text-teal-600 hover:bg-teal-50 rounded-lg transition"
                                            title="Detail">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    
                                    @if($pengajuan->status == 'pending')
                                        <button onclick="confirmDelete({{ $pengajuan->id }})" 
                                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition"
                                                title="Batalkan">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-inbox text-4xl text-gray-400"></i>
                                    </div>
                                    <p class="text-gray-500 mb-4">Belum ada pengajuan</p>
                                    <button onclick="openModal('modalPengajuan')" class="px-5 py-2.5 bg-gradient-to-r from-teal-600 to-cyan-600 text-white font-semibold rounded-lg hover:from-teal-700 hover:to-cyan-700 transition shadow-md">
                                        <i class="fas fa-plus mr-2"></i>Buat Pengajuan Pertama
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($pengajuanList->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $pengajuanList->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Card View Mobile & Tablet -->
    <div class="lg:hidden space-y-3">
        @forelse($pengajuanList as $pengajuan)
        <div class="bg-white rounded-lg shadow-lg p-4 hover:shadow-xl transition">
            <!-- Header -->
            <div class="flex items-start justify-between mb-3 pb-3 border-b border-gray-100">
                <div class="flex-1">
                    <div class="flex items-center space-x-2 mb-2">
                        <span class="px-2 py-1 rounded-full text-xs font-semibold
                            {{ $pengajuan->jenis == 'cuti' ? 'bg-teal-100 text-teal-700' : '' }}
                            {{ $pengajuan->jenis == 'izin' ? 'bg-cyan-100 text-cyan-700' : '' }}
                            {{ $pengajuan->jenis == 'sakit' ? 'bg-red-100 text-red-700' : '' }}">
                            {{ ucfirst($pengajuan->jenis) }}
                        </span>
                        @if($pengajuan->status == 'pending')
                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                                <i class="fas fa-clock"></i> Pending
                            </span>
                        @elseif($pengajuan->status == 'disetujui')
                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-teal-100 text-teal-700">
                                <i class="fas fa-check"></i> Disetujui
                            </span>
                        @else
                            <span class="px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                <i class="fas fa-times"></i> Ditolak
                            </span>
                        @endif
                    </div>
                    <p class="text-xs text-gray-500">
                        <i class="fas fa-calendar-alt mr-1"></i>
                        {{ \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->format('d/m/Y H:i') }}
                    </p>
                </div>
            </div>

            <!-- Content -->
            <div class="space-y-2 mb-3">
                <div class="flex items-center text-sm">
                    <i class="fas fa-calendar-check text-teal-600 w-5"></i>
                    <span class="text-gray-600 ml-2">
                        {{ \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->format('d M Y') }} - 
                        {{ \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->format('d M Y') }}
                    </span>
                </div>
                <div class="flex items-center text-sm">
                    <i class="fas fa-clock text-cyan-600 w-5"></i>
                    <span class="text-gray-600 ml-2">{{ $pengajuan->jumlah_hari }} hari</span>
                </div>
                <div class="flex items-start text-sm">
                    <i class="fas fa-comment text-gray-400 w-5 mt-1"></i>
                    <p class="text-gray-600 ml-2 line-clamp-2">{{ $pengajuan->alasan }}</p>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center space-x-2 pt-3 border-t border-gray-100">
                <button onclick="openDetailModal({{ json_encode($pengajuan) }})" 
                        class="flex-1 px-3 py-2 text-sm text-teal-600 bg-teal-50 hover:bg-teal-100 rounded-lg transition font-medium">
                    <i class="fas fa-eye mr-1"></i>Detail
                </button>
                
                @if($pengajuan->status == 'pending')
                    <button onclick="confirmDelete({{ $pengajuan->id }})" 
                            class="px-3 py-2 text-sm text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition font-medium">
                        <i class="fas fa-trash"></i>
                    </button>
                @endif
            </div>
        </div>
        @empty
        <div class="bg-white rounded-lg shadow-lg p-8 text-center">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-inbox text-4xl text-gray-400"></i>
            </div>
            <p class="text-gray-500 mb-4">Belum ada pengajuan</p>
            <button onclick="openModal('modalPengajuan')" class="px-5 py-2.5 bg-gradient-to-r from-teal-600 to-cyan-600 text-white font-semibold rounded-lg hover:from-teal-700 hover:to-cyan-700 transition shadow-md text-sm">
                <i class="fas fa-plus mr-2"></i>Buat Pengajuan Pertama
            </button>
        </div>
        @endforelse

        <!-- Pagination -->
        @if($pengajuanList->hasPages())
            <div class="mt-4 flex justify-center">
                {{ $pengajuanList->links() }}
            </div>
        @endif
    </div>

</div>

<!-- Modal Form Pengajuan -->
<div id="modalPengajuan" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay with animation -->
        <div class="fixed inset-0 transition-opacity bg-gray-900 bg-opacity-75 backdrop-blur-sm" onclick="closeModal('modalPengajuan')"></div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
            
            <!-- Header with gradient -->
            <div class="bg-gradient-to-r from-teal-600 via-cyan-600 to-blue-600 px-6 py-5 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full -mr-32 -mt-32"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-white opacity-5 rounded-full -ml-24 -mb-24"></div>
                <div class="relative flex items-center justify-between">
                    <div>
                        <h3 class="text-xl font-bold text-white flex items-center">
                            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-file-alt text-xl"></i>
                            </div>
                            Form Pengajuan Cuti/Izin
                        </h3>
                        <p class="text-white/80 text-sm mt-1 ml-13">Lengkapi formulir di bawah dengan teliti</p>
                    </div>
                    <button onclick="closeModal('modalPengajuan')" class="text-white/80 hover:text-white transition">
                        <i class="fas fa-times text-2xl"></i>
                    </button>
                </div>
            </div>

            <!-- Body -->
            <div class="px-6 py-6 max-h-[65vh] overflow-y-auto scrollbar-thin scrollbar-thumb-teal-500 scrollbar-track-gray-100">
                <form action="{{ route('karyawan.cutiizin.store') }}" method="POST" enctype="multipart/form-data" id="formPengajuan">
                    @csrf
                    
                    <!-- Info Sisa Cuti dengan design card -->
                    <div class="bg-gradient-to-br from-teal-50 to-cyan-50 border-2 border-teal-200 rounded-xl p-4 mb-6 shadow-sm">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-teal-500 rounded-full flex items-center justify-center mr-4 shadow-md">
                                <i class="fas fa-calendar-check text-white text-xl"></i>
                            </div>
                            <div>
                                <p class="text-teal-600 text-xs font-semibold uppercase tracking-wide">Sisa Kuota Cuti Anda</p>
                                <p class="text-teal-900 text-2xl font-bold">{{ $statistik['sisa_cuti'] }} <span class="text-base font-normal">hari</span></p>
                            </div>
                        </div>
                    </div>

                    <!-- Jenis Pengajuan -->
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            <i class="fas fa-list text-teal-600 mr-2"></i>Jenis Pengajuan 
                            <span class="text-red-500">*</span>
                        </label>
                        <select name="jenis" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition @error('jenis') border-red-500 @enderror" required id="jenisPengajuan">
                            <option value="">-- Pilih Jenis --</option>
                            <option value="cuti" {{ old('jenis') == 'cuti' ? 'selected' : '' }}>Cuti</option>
                            <option value="izin" {{ old('jenis') == 'izin' ? 'selected' : '' }}>Izin</option>
                            <option value="sakit" {{ old('jenis') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                        </select>
                        @error('jenis')
                            <p class="text-red-500 text-xs mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                        <div class="mt-2 text-xs text-gray-600 bg-gray-50 rounded-lg p-3 space-y-1">
                            <p><i class="fas fa-umbrella-beach text-teal-500 mr-1"></i><strong>Cuti:</strong> Untuk keperluan pribadi/liburan</p>
                            <p><i class="fas fa-door-open text-cyan-500 mr-1"></i><strong>Izin:</strong> Untuk keperluan mendadak</p>
                            <p><i class="fas fa-briefcase-medical text-red-500 mr-1"></i><strong>Sakit:</strong> Ketika sedang sakit (disarankan melampirkan surat dokter)</p>
                        </div>
                    </div>

                    <!-- Periode Tanggal -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                        <!-- Tanggal Mulai -->
                        <div class="relative">
                            <label class="block text-sm font-bold text-gray-700 mb-2">
                                <i class="fas fa-calendar-day text-teal-600 mr-2"></i>Tanggal Mulai
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="date" 
                                       name="tanggal_mulai" 
                                       class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition cursor-pointer @error('tanggal_mulai') border-red-500 @enderror" 
                                       value="{{ old('tanggal_mulai') }}"
                                       min="{{ date('Y-m-d') }}"
                                       required
                                       id="tanggalMulai">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-teal-500 pointer-events-none">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                            </div>
                            @error('tanggal_mulai')
                                <p class="text-red-500 text-xs mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Tanggal Selesai -->
                        <div class="relative">
                            <label class="block text-sm font-bold text-gray-700 mb-2">
                                <i class="fas fa-calendar-check text-cyan-600 mr-2"></i>Tanggal Selesai
                                <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="date" 
                                       name="tanggal_selesai" 
                                       class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-cyan-500 focus:ring-2 focus:ring-cyan-200 transition cursor-pointer @error('tanggal_selesai') border-red-500 @enderror" 
                                       value="{{ old('tanggal_selesai') }}"
                                       required
                                       id="tanggalSelesai">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-cyan-500 pointer-events-none">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                            </div>
                            @error('tanggal_selesai')
                                <p class="text-red-500 text-xs mt-1 flex items-center">
                                    <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-6">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-500 mt-0.5 mr-2"></i>
                            <p class="text-xs text-blue-700">Gunakan date picker (icon kalender) untuk memilih tanggal. Jika mengetik manual, pastikan format sudah lengkap sebelum pindah ke field lain.</p>
                        </div>
                    </div>

                    <!-- Info Jumlah Hari dengan animasi -->
                    <div class="mb-6" id="infoJumlahHari" style="display: none;">
                        <div class="bg-gradient-to-r from-teal-50 to-cyan-50 border-2 border-teal-200 rounded-xl p-4 shadow-sm animate-fadeIn">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-teal-500 rounded-full flex items-center justify-center mr-3 shadow-md">
                                    <i class="fas fa-calculator text-white"></i>
                                </div>
                                <div>
                                    <p class="text-teal-600 text-xs font-semibold uppercase tracking-wide">Total Durasi</p>
                                    <p class="text-teal-900 text-xl font-bold"><span id="totalHari">0</span> hari</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Alasan -->
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            <i class="fas fa-comment-dots text-teal-600 mr-2"></i>Alasan Pengajuan
                            <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <textarea name="alasan" 
                                      rows="4" 
                                      class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-200 transition resize-none @error('alasan') border-red-500 @enderror" 
                                      placeholder="Jelaskan alasan pengajuan Anda dengan detail..."
                                      required
                                      maxlength="500"
                                      id="alasanTextarea">{{ old('alasan') }}</textarea>
                            <div class="absolute bottom-3 right-3 text-xs text-gray-400">
                                <span id="charCount">0</span>/500
                            </div>
                        </div>
                        @error('alasan')
                            <p class="text-red-500 text-xs mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- File Pendukung dengan drag & drop style -->
                    <div class="mb-6">
                        <label class="block text-sm font-bold text-gray-700 mb-2">
                            <i class="fas fa-paperclip text-teal-600 mr-2"></i>File Pendukung
                            <span class="text-gray-400 text-xs font-normal">(Opsional)</span>
                        </label>
                        <div class="relative">
                            <input type="file" 
                                   name="file_pendukung" 
                                   class="hidden"
                                   accept=".jpg,.jpeg,.png,.pdf"
                                   id="filePendukung">
                            <label for="filePendukung" class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition group">
                                <div class="flex flex-col items-center justify-center text-center px-4">
                                    <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 group-hover:text-teal-500 transition mb-2"></i>
                                    <p class="text-sm text-gray-600 font-semibold mb-1">Klik untuk upload file</p>
                                    <p class="text-xs text-gray-500">JPG, PNG, PDF (Max. 2MB)</p>
                                    <p class="text-xs text-teal-600 mt-1 font-medium" id="fileName"></p>
                                </div>
                            </label>
                        </div>
                        @error('file_pendukung')
                            <p class="text-red-500 text-xs mt-1 flex items-center">
                                <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-2 flex items-center">
                            <i class="fas fa-info-circle text-blue-500 mr-1"></i>
                            Disarankan untuk melampirkan surat dokter jika mengajukan sakit
                        </p>
                    </div>
                </form>
            </div>

            <!-- Footer with improved buttons -->
            <div class="bg-gray-50 px-6 py-4 flex flex-col sm:flex-row justify-end space-y-2 sm:space-y-0 sm:space-x-3 border-t border-gray-200">
                <button type="button" 
                        onclick="closeModal('modalPengajuan')" 
                        class="px-6 py-3 bg-white border-2 border-gray-300 text-gray-700 font-semibold rounded-xl hover:bg-gray-50 hover:border-gray-400 transition shadow-sm">
                    <i class="fas fa-times mr-2"></i>Batal
                </button>
                <button type="submit" 
                        form="formPengajuan" 
                        class="px-6 py-3 bg-gradient-to-r from-teal-600 via-cyan-600 to-blue-600 text-white font-semibold rounded-xl hover:from-teal-700 hover:via-cyan-700 hover:to-blue-700 transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-paper-plane mr-2"></i>Kirim Pengajuan
                </button>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(-10px); }
    to { opacity: 1; transform: translateY(0); }
}

.animate-fadeIn {
    animation: fadeIn 0.3s ease-out;
}

/* Custom scrollbar */
.scrollbar-thin::-webkit-scrollbar {
    width: 6px;
}

.scrollbar-thin::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

.scrollbar-thin::-webkit-scrollbar-thumb {
    background: #14b8a6;
    border-radius: 10px;
}

.scrollbar-thin::-webkit-scrollbar-thumb:hover {
    background: #0d9488;
}

/* Smooth transition for modal */
#modalPengajuan > div > div {
    animation: slideUp 0.3s ease-out;
}

@keyframes slideUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
</style>

<script>
// Character counter untuk textarea
document.getElementById('alasanTextarea')?.addEventListener('input', function() {
    document.getElementById('charCount').textContent = this.value.length;
});

// Show file name when selected
document.getElementById('filePendukung')?.addEventListener('change', function() {
    const file = this.files[0];
    const fileNameDisplay = document.getElementById('fileName');
    
    if (file) {
        // Validasi ukuran file
        if (file.size > 2048000) {
            alert('❌ Ukuran file maksimal 2MB!');
            this.value = '';
            fileNameDisplay.textContent = '';
            return;
        }
        
        // Tampilkan nama file
        fileNameDisplay.textContent = `✓ ${file.name}`;
        fileNameDisplay.classList.add('text-teal-600');
    } else {
        fileNameDisplay.textContent = '';
    }
});

// Hitung jumlah hari otomatis dengan validasi
function hitungJumlahHari() {
    const tanggalMulai = document.getElementById('tanggalMulai').value;
    const tanggalSelesai = document.getElementById('tanggalSelesai').value;
    
    // Update min tanggal selesai
    if (tanggalMulai) {
        document.getElementById('tanggalSelesai').setAttribute('min', tanggalMulai);
    }
    
    if (tanggalMulai && tanggalSelesai) {
        const mulai = new Date(tanggalMulai);
        const selesai = new Date(tanggalSelesai);
        
        // Cek apakah tanggal valid
        if (isNaN(mulai.getTime()) || isNaN(selesai.getTime())) {
            document.getElementById('infoJumlahHari').style.display = 'none';
            return;
        }
        
        if (selesai < mulai) {
            document.getElementById('tanggalSelesai').value = '';
            document.getElementById('infoJumlahHari').style.display = 'none';
            
            // Tampilkan pesan error (hanya sekali)
            const existingError = document.querySelector('.date-error-notification');
            if (!existingError) {
                const errorDiv = document.createElement('div');
                errorDiv.className = 'date-error-notification fixed top-4 right-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-lg z-50 animate-fadeIn';
                errorDiv.innerHTML = `
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-xl mr-3"></i>
                        <p class="font-semibold">Tanggal selesai tidak boleh lebih awal dari tanggal mulai!</p>
                    </div>
                `;
                document.body.appendChild(errorDiv);
                
                setTimeout(() => {
                    errorDiv.style.opacity = '0';
                    errorDiv.style.transition = 'opacity 0.3s';
                    setTimeout(() => errorDiv.remove(), 300);
                }, 3000);
            }
            return;
        }
        
        const diffTime = Math.abs(selesai - mulai);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
        
        document.getElementById('totalHari').textContent = diffDays;
        document.getElementById('infoJumlahHari').style.display = 'block';
    } else {
        document.getElementById('infoJumlahHari').style.display = 'none';
    }
}

// Event listener untuk kedua input tanggal
document.getElementById('tanggalMulai')?.addEventListener('change', hitungJumlahHari);
document.getElementById('tanggalSelesai')?.addEventListener('change', hitungJumlahHari);

// Auto-open date picker saat click di readonly input (fallback untuk browser lama)
document.getElementById('tanggalMulai')?.addEventListener('click', function() {
    if (this.showPicker) {
        try {
            this.showPicker();
        } catch (e) {
            // Fallback jika showPicker tidak tersedia
            this.focus();
        }
    }
});

document.getElementById('tanggalSelesai')?.addEventListener('click', function() {
    if (this.showPicker) {
        try {
            this.showPicker();
        } catch (e) {
            // Fallback jika showPicker tidak tersedia
            this.focus();
        }
    }
});

// Reset form ketika modal ditutup
function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    document.body.style.overflow = 'auto';
    
    if (modalId === 'modalPengajuan') {
        // Reset form
        document.getElementById('formPengajuan')?.reset();
        document.getElementById('infoJumlahHari').style.display = 'none';
        document.getElementById('fileName').textContent = '';
        document.getElementById('charCount').textContent = '0';
    }
}

function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}
</script>

<!-- Modal Detail -->
<div id="detailModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeModal('detailModal')"></div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <!-- Header -->
            <div class="bg-gradient-to-r from-teal-600 to-cyan-600 px-6 py-4">
                <h3 class="text-lg font-bold text-white">
                    <i class="fas fa-info-circle mr-2"></i>Detail Pengajuan
                </h3>
            </div>

            <!-- Body -->
            <div class="px-6 py-4 max-h-[70vh] overflow-y-auto">
                <div class="space-y-3 md:space-y-4">
                    <div class="flex items-center space-x-3 md:space-x-4 pb-3 md:pb-4 border-b">
                        <img id="detailFoto" src="" alt="" class="w-12 h-12 md:w-16 md:h-16 rounded-full object-cover border-2 border-gray-200">
                        <div>
                            <h4 id="detailNama" class="text-base md:text-lg font-bold text-gray-900"></h4>
                            <p id="detailDivisi" class="text-xs md:text-sm text-gray-500"></p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 md:gap-4">
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Jenis</p>
                            <p id="detailJenis" class="text-sm md:text-base font-semibold text-gray-900"></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Status</p>
                            <span id="detailStatus"></span>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Tanggal Pengajuan</p>
                            <p id="detailTanggalPengajuan" class="text-sm md:text-base font-semibold text-gray-900"></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Durasi</p>
                            <p id="detailDurasi" class="text-sm md:text-base font-semibold text-gray-900"></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Tanggal Mulai</p>
                            <p id="detailTanggalMulai" class="text-sm md:text-base font-semibold text-gray-900"></p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 mb-1">Tanggal Selesai</p>
                            <p id="detailTanggalSelesai" class="text-sm md:text-base font-semibold text-gray-900"></p>
                        </div>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500 mb-1">Alasan</p>
                        <div id="detailAlasan" class="bg-gray-50 rounded-lg p-3 md:p-4 text-xs md:text-sm text-gray-700"></div>
                    </div>

                    <div id="detailFileSection" class="hidden">
                        <p class="text-xs text-gray-500 mb-1">File Pendukung</p>
                        <a id="detailFileLink" href="" target="_blank" class="inline-flex items-center px-3 md:px-4 py-2 bg-teal-50 text-teal-700 text-xs md:text-sm rounded-lg hover:bg-teal-100 transition">
                            <i class="fas fa-download mr-2"></i>Lihat File
                        </a>
                    </div>

                    <div id="detailApprovalSection" class="hidden">
                        <p class="text-xs text-gray-500 mb-1">Catatan Admin</p>
                        <div class="bg-teal-50 border border-teal-200 rounded-lg p-3 md:p-4">
                            <p id="detailCatatan" class="text-xs md:text-sm text-teal-800"></p>
                            <p id="detailApprover" class="text-xs text-teal-600 mt-2"></p>
                        </div>
                    </div>
                </div>
            </div>

                <!-- Footer -->
                <div class="bg-gray-50 px-6 py-4 flex justify-end">
                    <button type="button" onclick="closeModal('detailModal')" class="px-4 md:px-5 py-2 md:py-2.5 bg-gradient-to-r from-teal-600 to-cyan-600 text-white text-sm md:text-base font-semibold rounded-lg hover:from-teal-700 hover:to-cyan-700 transition shadow-md">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    @endsection

    @push('scripts')
    <script>
    // Modal Functions
    function openModal(modalId) {
        document.getElementById(modalId).classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(modalId) {
        document.getElementById(modalId).classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Hitung jumlah hari otomatis
    document.getElementById('tanggalMulai').addEventListener('change', hitungJumlahHari);
    document.getElementById('tanggalSelesai').addEventListener('change', hitungJumlahHari);

    function hitungJumlahHari() {
        const tanggalMulai = document.getElementById('tanggalMulai').value;
        const tanggalSelesai = document.getElementById('tanggalSelesai').value;
        
        if (tanggalMulai && tanggalSelesai) {
            const mulai = new Date(tanggalMulai);
            const selesai = new Date(tanggalSelesai);
            
            const diffTime = Math.abs(selesai - mulai);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
            
            document.getElementById('totalHari').textContent = diffDays;
            document.getElementById('infoJumlahHari').style.display = 'block';
            
            // Update min tanggal selesai
            document.getElementById('tanggalSelesai').min = tanggalMulai;
        }
    }

    // Validasi file size
    document.getElementById('filePendukung').addEventListener('change', function() {
        const file = this.files[0];
        if (file && file.size > 2048000) { // 2MB
            alert('Ukuran file maksimal 2MB!');
            this.value = '';
        }
    });

    // ✅ PERBAIKAN FILTER - Desktop
    document.getElementById('filterStatus')?.addEventListener('change', function() {
        applyFilter();
    });

    document.getElementById('filterJenis')?.addEventListener('change', function() {
        applyFilter();
    });

    // ✅ PERBAIKAN FILTER - Mobile
    document.getElementById('filterStatusMobile')?.addEventListener('change', function() {
        applyFilterMobile();
    });

    document.getElementById('filterJenisMobile')?.addEventListener('change', function() {
        applyFilterMobile();
    });

    // ✅ Function untuk apply filter Desktop
    function applyFilter() {
        const status = document.getElementById('filterStatus').value;
        const jenis = document.getElementById('filterJenis').value;
        
        // Build URL dengan parameter
        let url = '{{ route('karyawan.cutiizin.index') }}?';
        let params = [];
        
        if (status !== '') {
            params.push('status=' + status);
        }
        if (jenis !== '') {
            params.push('jenis=' + jenis);
        }
        
        url += params.join('&');
        window.location.href = url;
    }

    // ✅ Function untuk apply filter Mobile
    function applyFilterMobile() {
        const status = document.getElementById('filterStatusMobile').value;
        const jenis = document.getElementById('filterJenisMobile').value;
        
        // Build URL dengan parameter
        let url = '{{ route('karyawan.cutiizin.index') }}?';
        let params = [];
        
        if (status !== '') {
            params.push('status=' + status);
        }
        if (jenis !== '') {
            params.push('jenis=' + jenis);
        }
        
        url += params.join('&');
        window.location.href = url;
    }

    // ✅ PERBAIKAN: Open Detail Modal dengan pengecekan null
    function openDetailModal(data) {
        const pengajuan = typeof data === 'string' ? JSON.parse(data) : data;
        
        // Debug log
        console.log('Data Pengajuan:', pengajuan);
        
        document.getElementById('detailFoto').src = pengajuan.user.foto 
            ? `/storage/${pengajuan.user.foto}` 
            : `https://ui-avatars.com/api/?name=${encodeURIComponent(pengajuan.user.name)}&background=0d9488&color=fff`;
        document.getElementById('detailNama').textContent = pengajuan.user.name;
        document.getElementById('detailDivisi').textContent = pengajuan.user.divisi || '-';
        document.getElementById('detailJenis').textContent = pengajuan.jenis.charAt(0).toUpperCase() + pengajuan.jenis.slice(1);
        
        let statusClass = 'bg-yellow-100 text-yellow-700';
        if (pengajuan.status === 'disetujui') statusClass = 'bg-teal-100 text-teal-700';
        if (pengajuan.status === 'ditolak') statusClass = 'bg-red-100 text-red-700';
        
        const statusBadge = `<span class="px-2 md:px-3 py-1 rounded-full text-xs font-semibold ${statusClass}">${pengajuan.status.charAt(0).toUpperCase() + pengajuan.status.slice(1)}</span>`;
        document.getElementById('detailStatus').innerHTML = statusBadge;
        
        // ✅ FIX: Cek tanggal_pengajuan dengan benar
        if (pengajuan.tanggal_pengajuan && pengajuan.tanggal_pengajuan !== '1970-01-01 00:00:00') {
            const tanggalPengajuan = new Date(pengajuan.tanggal_pengajuan);
            if (!isNaN(tanggalPengajuan.getTime()) && tanggalPengajuan.getFullYear() > 2000) {
                document.getElementById('detailTanggalPengajuan').textContent = tanggalPengajuan.toLocaleDateString('id-ID', { 
                    day: 'numeric', 
                    month: 'long', 
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            } else {
                document.getElementById('detailTanggalPengajuan').textContent = '-';
            }
        } else {
            document.getElementById('detailTanggalPengajuan').textContent = '-';
        }
        
        document.getElementById('detailTanggalMulai').textContent = new Date(pengajuan.tanggal_mulai).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        document.getElementById('detailTanggalSelesai').textContent = new Date(pengajuan.tanggal_selesai).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
        
        // ✅ FIX: Cek jumlah_hari dengan benar
        document.getElementById('detailDurasi').textContent = (pengajuan.jumlah_hari && pengajuan.jumlah_hari > 0) 
            ? `${pengajuan.jumlah_hari} hari` 
            : '-';
        
        document.getElementById('detailAlasan').textContent = pengajuan.alasan;
        
        // File Pendukung
        if (pengajuan.file_pendukung) {
            document.getElementById('detailFileSection').classList.remove('hidden');
            document.getElementById('detailFileLink').href = `/storage/${pengajuan.file_pendukung}`;
        } else {
            document.getElementById('detailFileSection').classList.add('hidden');
        }
        
        // Catatan Admin
        if (pengajuan.catatan_admin && pengajuan.status !== 'pending') {
            document.getElementById('detailApprovalSection').classList.remove('hidden');
            document.getElementById('detailCatatan').textContent = pengajuan.catatan_admin;
            if (pengajuan.tanggal_approval) {
                const tanggalApproval = new Date(pengajuan.tanggal_approval);
                if (!isNaN(tanggalApproval.getTime())) {
                    document.getElementById('detailApprover').textContent = `Diproses pada ${tanggalApproval.toLocaleDateString('id-ID')}`;
                } else {
                    document.getElementById('detailApprover').textContent = '';
                }
            } else {
                document.getElementById('detailApprover').textContent = '';
            }
        } else {
            document.getElementById('detailApprovalSection').classList.add('hidden');
        }
        
        openModal('detailModal');
    }

    // Confirm Delete
    function confirmDelete(id) {
        if (confirm('Yakin ingin membatalkan pengajuan ini?')) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/karyawan/cuti-izin/${id}`;
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            
            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        }
    }

    // Auto dismiss alerts
    setTimeout(function() {
        const alerts = document.querySelectorAll('.bg-teal-50, .bg-red-50');
        alerts.forEach(function(alert) {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
    </script>
    @endpush