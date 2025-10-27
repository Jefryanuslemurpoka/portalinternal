@extends('layouts.app')

@section('title', 'Edit Karyawan')
@section('page-title', 'Edit Karyawan')

@section('content')
<div class="max-w-3xl mx-auto">

    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('superadmin.karyawan.index') }}" class="inline-flex items-center text-gray-600 hover:text-teal-600 transition font-medium">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar Karyawan
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-teal-500 to-cyan-600 px-6 py-4">
            <h3 class="text-xl font-bold text-white">Form Edit Karyawan</h3>
        </div>

        <!-- Form -->
        <form action="{{ route('superadmin.karyawan.update', $karyawan->id) }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')

            <div class="space-y-6">

                <!-- Foto Profile -->
                <div>
                    <label class="form-label">
                        <i class="fas fa-camera mr-2 text-teal-600"></i>Foto Profil
                    </label>
                    <div class="file-upload">
                        <input type="file" name="foto" id="foto" accept="image/*" onchange="previewImage(event)">
                        <div class="text-center">
                            <img id="imagePreview" 
                                 src="{{ $karyawan->foto ? asset('storage/' . $karyawan->foto) : 'https://ui-avatars.com/api/?name=' . urlencode($karyawan->name) . '&background=14b8a6&color=fff&size=200' }}" 
                                 class="mx-auto mb-3 w-32 h-32 rounded-full object-cover border-4 border-teal-200 shadow-md">
                            <i class="fas fa-cloud-upload-alt text-3xl text-teal-400 mb-2"></i>
                            <p class="text-sm text-gray-600">Klik untuk upload foto baru</p>
                            <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG (Max: 2MB)</p>
                        </div>
                    </div>
                    @error('foto')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Nama -->
                <div>
                    <label for="name" class="form-label">
                        <i class="fas fa-user mr-2 text-teal-600"></i>Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', $karyawan->name) }}" 
                           class="form-input @error('name') border-red-500 @enderror" 
                           placeholder="Masukkan nama lengkap" required>
                    @error('name')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope mr-2 text-teal-600"></i>Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email', $karyawan->email) }}" 
                           class="form-input @error('email') border-red-500 @enderror" 
                           placeholder="nama@email.com" required>
                    @error('email')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Info Password -->
                <div class="bg-teal-50 border border-teal-200 rounded-xl p-4">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-teal-600 mt-1 mr-3"></i>
                        <div>
                            <p class="text-sm text-teal-800 font-semibold mb-1">Informasi Password</p>
                            <p class="text-xs text-teal-700">Password tidak akan diubah. Gunakan tombol "Reset Password" di daftar karyawan jika ingin mereset password.</p>
                        </div>
                    </div>
                </div>

                <!-- Divisi -->
                <div>
                    <label for="divisi" class="form-label">
                        <i class="fas fa-building mr-2 text-teal-600"></i>Divisi <span class="text-red-500">*</span>
                    </label>
                    <select name="divisi" id="divisi" class="form-input @error('divisi') border-red-500 @enderror" required>
                        <option value="">-- Pilih Divisi --</option>
                        <option value="IT" {{ old('divisi', $karyawan->divisi) == 'IT' ? 'selected' : '' }}>IT</option>
                        <option value="HRD" {{ old('divisi', $karyawan->divisi) == 'HRD' ? 'selected' : '' }}>HRD</option>
                        <option value="Finance" {{ old('divisi', $karyawan->divisi) == 'Finance' ? 'selected' : '' }}>Finance</option>
                        <option value="Marketing" {{ old('divisi', $karyawan->divisi) == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                        <option value="Operasional" {{ old('divisi', $karyawan->divisi) == 'Operasional' ? 'selected' : '' }}>Operasional</option>
                    </select>
                    @error('divisi')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="form-label">
                        <i class="fas fa-toggle-on mr-2 text-teal-600"></i>Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status" id="status" class="form-input @error('status') border-red-500 @enderror" required>
                        <option value="">-- Pilih Status --</option>
                        <option value="aktif" {{ old('status', $karyawan->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status', $karyawan->status) == 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                    </select>
                    @error('status')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('superadmin.karyawan.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-2"></i>Update Karyawan
                </button>
            </div>

        </form>

    </div>

</div>
@endsection

@push('scripts')
<script>
    // Preview Image
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            document.getElementById('imagePreview').src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endpush