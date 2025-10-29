@extends('layouts.app')

@section('title', 'Laporan')
@section('page-title', 'Laporan')

@section('content')
<div class="space-y-4 sm:space-y-6 px-4 sm:px-0">

    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-teal-500 to-cyan-600 rounded-lg sm:rounded-xl shadow-lg p-4 sm:p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl sm:text-2xl font-bold mb-2">
                    <i class="fas fa-file-alt mr-2"></i>Laporan & Export Data
                </h2>
                <p class="text-xs sm:text-sm text-teal-50">Generate dan export laporan absensi, cuti/izin, dan karyawan</p>
            </div>
            <div class="hidden md:block">
                <i class="fas fa-chart-bar text-4xl sm:text-6xl text-white/20"></i>
            </div>
        </div>
    </div>

    <!-- Laporan Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
        
        <!-- Laporan Absensi -->
        <div class="bg-white rounded-lg sm:rounded-xl shadow-lg overflow-hidden flex flex-col">
            <div class="bg-gradient-to-r from-teal-500 to-cyan-600 px-4 sm:px-6 py-3 sm:py-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white/20 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-clipboard-check text-lg sm:text-2xl text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-base sm:text-lg font-bold text-white">Laporan Absensi</h3>
                        <p class="text-xs text-teal-50">Rekap kehadiran karyawan</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('superadmin.laporan.absensi') }}" method="GET" class="p-4 sm:p-6 flex-1 flex flex-col">
                <div class="space-y-3 sm:space-y-4 flex-1">
                    
                    <div>
                        <label class="form-label text-xs sm:text-sm">Tanggal Mulai <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_mulai" class="form-input text-sm" required value="{{ date('Y-m-01') }}">
                    </div>

                    <div>
                        <label class="form-label text-xs sm:text-sm">Tanggal Selesai <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_selesai" class="form-input text-sm" required value="{{ date('Y-m-d') }}">
                    </div>

                    <div>
                        <label class="form-label text-xs sm:text-sm">Filter Divisi</label>
                        <select name="divisi" class="form-input text-sm">
                            <option value="">Semua Divisi</option>
                            <option value="IT">IT</option>
                            <option value="HRD">HRD</option>
                            <option value="Finance">Finance</option>
                            <option value="Marketing">Marketing</option>
                            <option value="Operasional">Operasional</option>
                        </select>
                    </div>

                </div>

                <button type="submit" class="bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-semibold rounded-lg px-4 py-2 sm:py-2.5 transition w-full shadow-md text-sm mt-4">
                    <i class="fas fa-eye mr-2"></i>Lihat Laporan
                </button>
            </form>
        </div>

        <!-- Laporan Cuti/Izin -->
        <div class="bg-white rounded-lg sm:rounded-xl shadow-lg overflow-hidden flex flex-col">
            <div class="bg-gradient-to-r from-green-500 to-green-600 px-4 sm:px-6 py-3 sm:py-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white/20 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-calendar-check text-lg sm:text-2xl text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-base sm:text-lg font-bold text-white">Laporan Cuti/Izin</h3>
                        <p class="text-xs text-green-50">Rekap pengajuan cuti & izin</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('superadmin.laporan.cutiizin') }}" method="GET" class="p-4 sm:p-6 flex-1 flex flex-col">
                <div class="space-y-3 sm:space-y-4 flex-1">
                    
                    <div>
                        <label class="form-label text-xs sm:text-sm">Tanggal Mulai <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_mulai" class="form-input text-sm" required value="{{ date('Y-m-01') }}">
                    </div>

                    <div>
                        <label class="form-label text-xs sm:text-sm">Tanggal Selesai <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_selesai" class="form-input text-sm" required value="{{ date('Y-m-d') }}">
                    </div>

                    <div>
                        <label class="form-label text-xs sm:text-sm">Filter Status</label>
                        <select name="status" class="form-input text-sm">
                            <option value="">Semua Status</option>
                            <option value="pending">Pending</option>
                            <option value="disetujui">Disetujui</option>
                            <option value="ditolak">Ditolak</option>
                        </select>
                    </div>

                </div>

                <button type="submit" class="bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-semibold rounded-lg px-4 py-2 sm:py-2.5 transition w-full shadow-md text-sm mt-4">
                    <i class="fas fa-eye mr-2"></i>Lihat Laporan
                </button>
            </form>
        </div>

        <!-- Laporan Karyawan -->
        <div class="bg-white rounded-lg sm:rounded-xl shadow-lg overflow-hidden flex flex-col">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-4 sm:px-6 py-3 sm:py-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white/20 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-users text-lg sm:text-2xl text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-base sm:text-lg font-bold text-white">Laporan Karyawan</h3>
                        <p class="text-xs text-blue-50">Data master karyawan</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('superadmin.laporan.karyawan') }}" method="GET" class="p-4 sm:p-6 flex-1 flex flex-col">
                <div class="space-y-3 sm:space-y-4 flex-1">
                    
                    <div>
                        <label class="form-label text-xs sm:text-sm">Filter Divisi</label>
                        <select name="divisi" class="form-input text-sm">
                            <option value="">Semua Divisi</option>
                            <option value="IT">IT</option>
                            <option value="HRD">HRD</option>
                            <option value="Finance">Finance</option>
                            <option value="Marketing">Marketing</option>
                            <option value="Operasional">Operasional</option>
                        </select>
                    </div>

                    <div>
                        <label class="form-label text-xs sm:text-sm">Filter Status</label>
                        <select name="status" class="form-input text-sm">
                            <option value="">Semua Status</option>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Non-Aktif</option>
                        </select>
                    </div>

                    <div class="hidden lg:block" style="height: 52px;"></div>

                </div>

                <button type="submit" class="bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-semibold rounded-lg px-4 py-2 sm:py-2.5 transition w-full shadow-md text-sm mt-4">
                    <i class="fas fa-eye mr-2"></i>Lihat Laporan
                </button>
            </form>
        </div>

    </div>

    <!-- Info Box -->
    <div class="bg-teal-50 border border-teal-200 rounded-lg sm:rounded-xl p-4 sm:p-6">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-teal-600 text-xl sm:text-2xl mt-1 mr-3 sm:mr-4 flex-shrink-0"></i>
            <div>
                <h4 class="text-base sm:text-lg font-bold text-teal-900 mb-2">Panduan Export Laporan</h4>
                <ul class="text-xs sm:text-sm text-teal-800 space-y-2">
                    <li class="flex items-start">
                        <i class="fas fa-check text-orange-500 mr-2 mt-0.5 sm:mt-1 flex-shrink-0"></i>
                        <span><strong>Lihat Laporan:</strong> Klik tombol "Lihat Laporan" untuk preview data</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-orange-500 mr-2 mt-0.5 sm:mt-1 flex-shrink-0"></i>
                        <span><strong>Export PDF:</strong> Di halaman laporan, klik tombol "Export PDF" untuk download</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-orange-500 mr-2 mt-0.5 sm:mt-1 flex-shrink-0"></i>
                        <span><strong>Export Excel:</strong> Di halaman laporan, klik tombol "Export Excel" untuk download CSV</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-orange-500 mr-2 mt-0.5 sm:mt-1 flex-shrink-0"></i>
                        <span><strong>Filter:</strong> Gunakan filter untuk menyaring data sesuai kebutuhan</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

</div>
@endsection