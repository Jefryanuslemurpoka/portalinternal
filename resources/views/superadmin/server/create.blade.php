@extends('layouts.app')

@section('title', 'Tambah Log Server')
@section('page-title', 'Tambah Log Server')

@section('content')
<div class="max-w-3xl mx-auto">

    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('superadmin.serverlog.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-800">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Log Book Server
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-4">
            <h3 class="text-xl font-bold text-white">Form Tambah Log Server</h3>
        </div>

        <!-- Form -->
        <form action="{{ route('superadmin.serverlog.store') }}" method="POST" class="p-6">
            @csrf

            <div class="space-y-6">

                <!-- Tanggal -->
                <div>
                    <label for="tanggal" class="form-label">
                        <i class="fas fa-calendar mr-2"></i>Tanggal <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal" id="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" 
                           class="form-input @error('tanggal') border-red-500 @enderror" required>
                    @error('tanggal')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Aktivitas -->
                <div>
                    <label for="aktivitas" class="form-label">
                        <i class="fas fa-tasks mr-2"></i>Aktivitas <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="aktivitas" id="aktivitas" value="{{ old('aktivitas') }}" 
                           class="form-input @error('aktivitas') border-red-500 @enderror" 
                           placeholder="Contoh: Backup Database, Restart Server, Update System" required>
                    @error('aktivitas')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="form-label">
                        <i class="fas fa-info-circle mr-2"></i>Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status" id="status" class="form-input @error('status') border-red-500 @enderror" required>
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
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Deskripsi -->
                <div>
                    <label for="deskripsi" class="form-label">
                        <i class="fas fa-align-left mr-2"></i>Deskripsi (Opsional)
                    </label>
                    <textarea name="deskripsi" id="deskripsi" rows="5" 
                              class="form-input @error('deskripsi') border-red-500 @enderror" 
                              placeholder="Detail aktivitas, error message, atau catatan penting lainnya...">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('superadmin.serverlog.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-2"></i>Simpan Log
                </button>
            </div>

        </form>

    </div>

</div>
@endsection