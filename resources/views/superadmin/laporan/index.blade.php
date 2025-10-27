@extends('layouts.app')

@section('title', 'Laporan')
@section('page-title', 'Laporan')

@section('content')
<div class="space-y-6">

    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-blue-400 to-blue-500 rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-2">
                    <i class="fas fa-file-alt mr-2"></i>Laporan & Export Data
                </h2>
                <p class="text-blue-50">Generate dan export laporan absensi, cuti/izin, dan karyawan</p>
            </div>
            <div class="hidden md:block">
                <i class="fas fa-chart-bar text-6xl text-white/20"></i>
            </div>
        </div>
    </div>

    <!-- Laporan Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Laporan Absensi -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-blue-400 to-blue-500 px-6 py-4">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-clipboard-check text-2xl text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-white">Laporan Absensi</h3>
                        <p class="text-xs text-blue-50">Rekap kehadiran karyawan</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('superadmin.laporan.absensi') }}" method="GET" target="_blank" class="p-6">
                <div class="space-y-4">
                    
                    <div>
                        <label class="form-label">Tanggal Mulai <span class="text-orange-500">*</span></label>
                        <input type="date" name="tanggal_mulai" class="form-input" required>
                    </div>

                    <div>
                        <label class="form-label">Tanggal Selesai <span class="text-orange-500">*</span></label>
                        <input type="date" name="tanggal_selesai" class="form-input" required>
                    </div>

                    <div>
                        <label class="form-label">Filter Divisi</label>
                        <select name="divisi" class="form-input">
                            <option value="">Semua Divisi</option>
                            <option value="IT">IT</option>
                            <option value="HRD">HRD</option>
                            <option value="Finance">Finance</option>
                            <option value="Marketing">Marketing</option>
                            <option value="Operasional">Operasional</option>
                        </select>
                    </div>

                    <button type="submit" class="bg-gradient-to-r from-orange-400 to-orange-500 hover:from-orange-500 hover:to-orange-600 text-white font-semibold rounded-lg px-4 py-2 transition w-full shadow-md">
                        <i class="fas fa-eye mr-2"></i>Lihat Laporan
                    </button>

                </div>
            </form>
        </div>

        <!-- Laporan Cuti/Izin -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-blue-300 to-blue-400 px-6 py-4">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-calendar-check text-2xl text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-white">Laporan Cuti/Izin</h3>
                        <p class="text-xs text-blue-50">Rekap pengajuan cuti & izin</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('superadmin.laporan.cutiizin') }}" method="GET" target="_blank" class="p-6">
                <div class="space-y-4">
                    
                    <div>
                        <label class="form-label">Tanggal Mulai <span class="text-orange-500">*</span></label>
                        <input type="date" name="tanggal_mulai" class="form-input" required>
                    </div>

                    <div>
                        <label class="form-label">Tanggal Selesai <span class="text-orange-500">*</span></label>
                        <input type="date" name="tanggal_selesai" class="form-input" required>
                    </div>

                    <div>
                        <label class="form-label">Filter Status</label>
                        <select name="status" class="form-input">
                            <option value="">Semua Status</option>
                            <option value="pending">Pending</option>
                            <option value="disetujui">Disetujui</option>
                            <option value="ditolak">Ditolak</option>
                        </select>
                    </div>

                    <button type="submit" class="bg-gradient-to-r from-orange-400 to-orange-500 hover:from-orange-500 hover:to-orange-600 text-white font-semibold rounded-lg px-4 py-2 transition w-full shadow-md">
                        <i class="fas fa-eye mr-2"></i>Lihat Laporan
                    </button>

                </div>
            </form>
        </div>

        <!-- Laporan Karyawan -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="bg-gradient-to-r from-cyan-400 to-cyan-500 px-6 py-4">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                        <i class="fas fa-users text-2xl text-white"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-white">Laporan Karyawan</h3>
                        <p class="text-xs text-cyan-50">Data master karyawan</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('superadmin.laporan.karyawan') }}" method="GET" target="_blank" class="p-6">
                <div class="space-y-4">
                    
                    <div>
                        <label class="form-label">Filter Divisi</label>
                        <select name="divisi" class="form-input">
                            <option value="">Semua Divisi</option>
                            <option value="IT">IT</option>
                            <option value="HRD">HRD</option>
                            <option value="Finance">Finance</option>
                            <option value="Marketing">Marketing</option>
                            <option value="Operasional">Operasional</option>
                        </select>
                    </div>

                    <div>
                        <label class="form-label">Filter Status</label>
                        <select name="status" class="form-input">
                            <option value="">Semua Status</option>
                            <option value="aktif">Aktif</option>
                            <option value="nonaktif">Non-Aktif</option>
                        </select>
                    </div>

                    <div class="h-16"></div>

                    <button type="submit" class="bg-gradient-to-r from-orange-400 to-orange-500 hover:from-orange-500 hover:to-orange-600 text-white font-semibold rounded-lg px-4 py-2 transition w-full shadow-md">
                        <i class="fas fa-eye mr-2"></i>Lihat Laporan
                    </button>

                </div>
            </form>
        </div>

    </div>

    <!-- Info Box -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
        <div class="flex items-start">
            <i class="fas fa-info-circle text-blue-500 text-2xl mt-1 mr-4"></i>
            <div>
                <h4 class="text-lg font-bold text-blue-900 mb-2">Panduan Export Laporan</h4>
                <ul class="text-sm text-blue-800 space-y-2">
                    <li class="flex items-start">
                        <i class="fas fa-check text-orange-500 mr-2 mt-1"></i>
                        <span><strong>Lihat Laporan:</strong> Klik tombol "Lihat Laporan" untuk preview data di tab baru</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-orange-500 mr-2 mt-1"></i>
                        <span><strong>Export PDF:</strong> Di halaman laporan, klik tombol "Export PDF" untuk download</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-orange-500 mr-2 mt-1"></i>
                        <span><strong>Export Excel:</strong> Di halaman laporan, klik tombol "Export Excel" untuk download CSV</span>
                    </li>
                    <li class="flex items-start">
                        <i class="fas fa-check text-orange-500 mr-2 mt-1"></i>
                        <span><strong>Filter:</strong> Gunakan filter untuk menyaring data sesuai kebutuhan</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>

</div>
@endsection