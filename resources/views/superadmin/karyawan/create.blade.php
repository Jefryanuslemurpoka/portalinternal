@extends('layouts.app')

@section('title', 'Tambah Karyawan')
@section('page-title', 'Tambah Karyawan')

@section('content')
<div class="max-w-3xl mx-auto">

    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('superadmin.karyawan.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-800">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar Karyawan
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-4">
            <h3 class="text-xl font-bold text-white">Form Tambah Karyawan</h3>
        </div>

        <!-- Form -->
        <form action="{{ route('superadmin.karyawan.store') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf

            <div class="space-y-6">

                <!-- Foto Profile -->
                <div>
                    <label class="form-label">
                        <i class="fas fa-camera mr-2"></i>Foto Profil (Opsional)
                    </label>
                    <div class="file-upload">
                        <input type="file" name="foto" id="foto" accept="image/*" onchange="previewImage(event)">
                        <div class="text-center">
                            <img id="imagePreview" src="https://via.placeholder.com/150?text=Upload+Foto" class="mx-auto mb-3 w-32 h-32 rounded-full object-cover border-4 border-gray-200">
                            <i class="fas fa-cloud-upload-alt text-3xl text-gray-400 mb-2"></i>
                            <p class="text-sm text-gray-600">Klik untuk upload foto</p>
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
                        <i class="fas fa-user mr-2"></i>Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" 
                           class="form-input @error('name') border-red-500 @enderror" 
                           placeholder="Masukkan nama lengkap" required>
                    @error('name')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="form-label">
                        <i class="fas fa-envelope mr-2"></i>Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" 
                           class="form-input @error('email') border-red-500 @enderror" 
                           placeholder="nama@email.com" required>
                    @error('email')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="form-label">
                        <i class="fas fa-lock mr-2"></i>Password <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="password" name="password" id="password" 
                               class="form-input @error('password') border-red-500 @enderror" 
                               placeholder="Minimal 6 karakter" required>
                        <button type="button" onclick="togglePassword('password')" class="absolute right-3 top-3 text-gray-500">
                            <i class="fas fa-eye" id="togglePasswordIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Konfirmasi Password -->
                <div>
                    <label for="password_confirmation" class="form-label">
                        <i class="fas fa-lock mr-2"></i>Konfirmasi Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation" 
                           class="form-input" 
                           placeholder="Ulangi password" required>
                </div>

                <!-- Divisi -->
                <div>
                    <label for="divisi" class="form-label">
                        <i class="fas fa-building mr-2"></i>Divisi <span class="text-red-500">*</span>
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
                        <i class="fas fa-toggle-on mr-2"></i>Status <span class="text-red-500">*</span>
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
@endpush class="form-input @error('divisi') border-red-500 @enderror" required>
                        <option value="">-- Pilih Divisi --</option>
                        <option value="IT" {{ old('divisi') == 'IT' ? 'selected' : '' }}>IT</option>
                        <option value="HRD" {{ old('divisi') == 'HRD' ? 'selected' : '' }}>HRD</option>
                        <option value="Finance" {{ old('divisi') == 'Finance' ? 'selected' : '' }}>Finance</option>
                        <option value="Marketing" {{ old('divisi') == 'Marketing' ? 'selected' : '' }}>Marketing</option>
                        <option value="Operasional" {{ old('divisi') == 'Operasional' ? 'selected' : '' }}>Operasional</option>
                    </select>
                    @error('divisi')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="form-label">
                        <i class="fas fa-toggle-on mr-2"></i>Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status" id="status" class="form-input @error('status') border-red-500 @enderror" required>
                        <option value="">-- Pilih Status --</option>
                        <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                        <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
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
                    <i class="fas fa-save mr-2"></i>Simpan Karyawan
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

    // Toggle Password Visibility
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = document.getElementById('togglePasswordIcon');
        
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>
@endpush