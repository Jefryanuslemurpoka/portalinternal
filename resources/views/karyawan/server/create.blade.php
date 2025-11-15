@extends('layouts.app')

@section('title', 'Tambah Log Server')
@section('page-title', 'Tambah Log Server')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('karyawan.serverlog.index') }}" class="inline-flex items-center text-gray-600 hover:text-teal-600 transition font-medium">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Log Book Server
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-teal-600 to-cyan-600 px-8 py-6">
            <h3 class="text-xl font-bold text-white flex items-center">
                <i class="fas fa-server mr-3"></i>
                Form Tambah Log Server
            </h3>
            <p class="text-sm text-white/90 mt-2">Catat aktivitas dan status server dengan detail</p>
        </div>

        <!-- Form -->
        <form action="{{ route('karyawan.serverlog.store') }}" method="POST" class="p-8">
            @csrf

            <!-- Grid Layout: Kiri Form, Kanan Tombol -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                <!-- KIRI: Form Input (6 kolom dari 10) -->
                <div class="space-y-6">

                    <!-- Tanggal -->
                    <div>
                        <label for="tanggal" class="form-label text-sm font-semibold text-gray-700 mb-2 block">
                            <i class="fas fa-calendar mr-2"></i>Tanggal <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" 
                               class="form-input w-full @error('tanggal') border-red-500 @enderror" required>
                        @error('tanggal')
                            <p class="form-error mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Aktivitas -->
                    <div>
                        <label for="aktivitas" class="form-label text-sm font-semibold text-gray-700 mb-2 block">
                            <i class="fas fa-tasks mr-2"></i>Aktivitas <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="aktivitas" id="aktivitas" value="{{ old('aktivitas') }}" 
                               class="form-input w-full @error('aktivitas') border-red-500 @enderror" 
                               placeholder="Contoh: Backup Database, Restart Server, Update System" required>
                        @error('aktivitas')
                            <p class="form-error mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="form-label text-sm font-semibold text-gray-700 mb-2 block">
                            <i class="fas fa-info-circle mr-2"></i>Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" id="status" class="form-input w-full @error('status') border-red-500 @enderror" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="normal" {{ old('status') == 'normal' ? 'selected' : '' }}>
                                ✅ Normal - Berjalan Lancar
                            </option>
                            <option value="warning" {{ old('status') == 'warning' ? 'selected' : '' }}>
                                ⚠️ Warning - Perlu Perhatian
                            </option>
                            <option value="error" {{ old('status') == 'error' ? 'selected' : '' }}>
                                ❌ Error - Butuh Tindakan
                            </option>
                        </select>
                        @error('status')
                            <p class="form-error mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label for="deskripsi" class="form-label text-sm font-semibold text-gray-700 mb-2 block">
                            <i class="fas fa-align-left mr-2"></i>Deskripsi (Opsional)
                        </label>
                        <textarea name="deskripsi" id="deskripsi" rows="6" 
                                  class="form-input w-full @error('deskripsi') border-red-500 @enderror" 
                                  placeholder="Detail aktivitas, error message, atau catatan penting lainnya...">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <p class="form-error mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                </div>

                <!-- KANAN: Tombol Aksi -->
                <div class="space-y-4">

                    <!-- Tombol Aksi -->
                    <div class="space-y-4 pt-2 mt-6">
                        <button type="submit" class="w-full px-6 py-4 bg-gradient-to-r from-teal-600 to-cyan-600 hover:from-teal-700 hover:to-cyan-700 text-white font-semibold rounded-xl transition shadow-lg hover:shadow-xl">
                            <i class="fas fa-save mr-2"></i>Simpan Log
                        </button>
                        <a href="{{ route('karyawan.serverlog.index') }}" class="block w-full px-6 py-4 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-xl transition text-center">
                            <i class="fas fa-times mr-2"></i>Batal
                        </a>
                    </div>

                </div>

            </div>

        </form>

    </div>

</div>
@endsection