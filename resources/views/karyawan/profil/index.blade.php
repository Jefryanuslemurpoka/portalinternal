@extends('layouts.app')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')

@section('content')
<div class="space-y-6">

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg shadow-sm">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                <p class="text-green-800 font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-red-500 text-xl mr-3"></i>
                <p class="text-red-800 font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column - Profile Photo & Info -->
        <div class="space-y-6">
            
            <!-- Profile Photo Card -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="p-6 text-center">
                    <div class="mb-4">
                        @if($user->foto)
                            <img src="{{ asset('storage/' . $user->foto) }}" 
                                 alt="Foto Profil" 
                                 class="w-40 h-40 rounded-full object-cover mx-auto border-4 border-gray-200 shadow-lg"
                                 id="previewFoto">
                        @else
                            <div class="w-40 h-40 rounded-full bg-gradient-to-br from-teal-500 to-cyan-500 text-white flex items-center justify-center mx-auto text-5xl font-bold shadow-lg"
                                 id="previewFoto">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    
                    <h3 class="text-xl font-bold text-gray-900 mb-1">
                        {{ $user->name }}{{ $user->gelar ? ', ' . $user->gelar : '' }}
                    </h3>
                    <p class="text-gray-500 mb-1">{{ $user->email }}</p>
                    @if($user->jabatan)
                        <p class="text-sm text-teal-600 font-semibold mb-4">{{ $user->jabatan }}</p>
                    @endif
                    
                    <div class="space-y-2">
                        <button onclick="openModal('modalUploadFoto')" class="w-full btn btn-primary">
                            <i class="fas fa-camera mr-2"></i> Ubah Foto
                        </button>
                        
                        @if($user->foto)
                            <form action="{{ route('karyawan.profil.foto') }}" 
                                  method="POST" 
                                  id="deletePhotoForm">
                                @csrf
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="w-full px-4 py-2 bg-red-50 text-red-600 font-semibold rounded-lg hover:bg-red-100 transition">
                                    <i class="fas fa-trash mr-2"></i> Hapus Foto
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Info Card -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-teal-600 to-cyan-600">
                    <h3 class="text-lg font-bold text-white">
                        <i class="fas fa-info-circle mr-2"></i>Informasi Akun
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-gray-200">
                            <span class="text-sm font-semibold text-gray-600">Role</span>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-teal-100 text-teal-700">
                                {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                            </span>
                        </div>
                        
                        @if($user->divisi)
                        <div class="flex items-center justify-between pb-3 border-b border-gray-200">
                            <span class="text-sm font-semibold text-gray-600">Divisi</span>
                            <span class="text-sm text-gray-900 font-medium">{{ $user->divisi }}</span>
                        </div>
                        @endif
                        
                        <div class="flex items-center justify-between pb-3 border-b border-gray-200">
                            <span class="text-sm font-semibold text-gray-600">Status</span>
                            @if($user->status == 'aktif')
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Aktif</span>
                            @else
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">Tidak Aktif</span>
                            @endif
                        </div>
                        
                        @if($user->aktif_dari)
                        <div class="flex items-center justify-between pb-3 border-b border-gray-200">
                            <span class="text-sm font-semibold text-gray-600">Aktif Sejak</span>
                            <span class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($user->aktif_dari)->format('d M Y') }}</span>
                        </div>
                        @endif
                        
                        @if($user->aktif_sampai)
                        <div class="flex items-center justify-between pb-3 border-b border-gray-200">
                            <span class="text-sm font-semibold text-gray-600">Kontrak Sampai</span>
                            <span class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($user->aktif_sampai)->format('d M Y') }}</span>
                        </div>
                        @endif
                        
                        <div class="flex items-center justify-between pb-3 border-b border-gray-200">
                            <span class="text-sm font-semibold text-gray-600">Terdaftar</span>
                            <span class="text-sm text-gray-900">{{ \Carbon\Carbon::parse($user->created_at)->format('d M Y') }}</span>
                        </div>
                        
                        @if($user->sisa_cuti)
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-semibold text-gray-600">Sisa Cuti</span>
                                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                                    {{ $user->sisa_cuti }} hari
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Forms -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Data Pribadi (Read Only) -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-teal-600 to-cyan-600">
                    <h3 class="text-lg font-bold text-white">
                        <i class="fas fa-id-card mr-2"></i>Data Pribadi
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- NIK -->
                        @if($user->nik)
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">NIK</label>
                            <p class="text-gray-900 font-medium mt-1">{{ $user->nik }}</p>
                        </div>
                        @endif
                        
                        <!-- Nama Lengkap dengan Gelar -->
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Nama Lengkap</label>
                            <p class="text-gray-900 font-medium mt-1">
                                {{ $user->name }}{{ $user->gelar ? ', ' . $user->gelar : '' }}
                            </p>
                        </div>
                        
                        <!-- Tempat Lahir -->
                        @if($user->tempat_lahir)
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Tempat Lahir</label>
                            <p class="text-gray-900 font-medium mt-1">{{ $user->tempat_lahir }}</p>
                        </div>
                        @endif
                        
                        <!-- Tanggal Lahir -->
                        @if($user->tanggal_lahir)
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Tanggal Lahir</label>
                            <p class="text-gray-900 font-medium mt-1">
                                {{ \Carbon\Carbon::parse($user->tanggal_lahir)->format('d F Y') }}
                                <span class="text-sm text-gray-500">({{ \Carbon\Carbon::parse($user->tanggal_lahir)->age }} tahun)</span>
                            </p>
                        </div>
                        @endif
                        
                        <!-- Jenis Kelamin -->
                        @if($user->jenis_kelamin)
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Jenis Kelamin</label>
                            <p class="text-gray-900 font-medium mt-1">{{ $user->jenis_kelamin }}</p>
                        </div>
                        @endif
                        
                        <!-- Nomor Rekening -->
                        @if($user->nomor_rekening)
                        <div>
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Nomor Rekening</label>
                            <p class="text-gray-900 font-medium mt-1">{{ $user->nomor_rekening }}</p>
                        </div>
                        @endif
                        
                        <!-- Alamat -->
                        @if($user->alamat)
                        <div class="md:col-span-2">
                            <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Alamat</label>
                            <p class="text-gray-900 font-medium mt-1">{{ $user->alamat }}</p>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Info Note -->
                    <div class="mt-6 bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
                        <div class="flex">
                            <i class="fas fa-info-circle text-blue-600 text-lg mr-3 mt-0.5"></i>
                            <div>
                                <p class="text-blue-800 font-semibold text-sm">Informasi</p>
                                <p class="text-blue-700 text-xs">Data pribadi ini hanya dapat diubah oleh Super Admin. Jika ada perubahan, silakan hubungi bagian HRD.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Dokumen Karyawan (Read Only) -->
            @if($user->foto_ktp || $user->foto_npwp || $user->foto_bpjs || $user->dokumen_kontrak)
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-purple-600 to-pink-600">
                    <h3 class="text-lg font-bold text-white">
                        <i class="fas fa-file-alt mr-2"></i>Dokumen Saya
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        
                        <!-- Foto KTP -->
                        @if($user->foto_ktp)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center">
                                    <i class="fas fa-id-card text-teal-600 text-xl mr-3"></i>
                                    <span class="font-semibold text-gray-900">Foto KTP</span>
                                </div>
                                <a href="{{ asset('storage/' . $user->foto_ktp) }}" 
                                   target="_blank" 
                                   class="text-teal-600 hover:text-teal-700 text-sm font-semibold">
                                    <i class="fas fa-eye mr-1"></i> Lihat
                                </a>
                            </div>
                            <img src="{{ asset('storage/' . $user->foto_ktp) }}" 
                                 alt="Foto KTP" 
                                 class="w-full h-32 object-cover rounded-lg">
                        </div>
                        @endif
                        
                        <!-- Foto NPWP -->
                        @if($user->foto_npwp)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center">
                                    <i class="fas fa-file-invoice text-teal-600 text-xl mr-3"></i>
                                    <span class="font-semibold text-gray-900">Foto NPWP</span>
                                </div>
                                <a href="{{ asset('storage/' . $user->foto_npwp) }}" 
                                   target="_blank" 
                                   class="text-teal-600 hover:text-teal-700 text-sm font-semibold">
                                    <i class="fas fa-eye mr-1"></i> Lihat
                                </a>
                            </div>
                            <img src="{{ asset('storage/' . $user->foto_npwp) }}" 
                                 alt="Foto NPWP" 
                                 class="w-full h-32 object-cover rounded-lg">
                        </div>
                        @endif
                        
                        <!-- Foto BPJS -->
                        @if($user->foto_bpjs)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center">
                                    <i class="fas fa-hospital text-teal-600 text-xl mr-3"></i>
                                    <span class="font-semibold text-gray-900">Foto BPJS</span>
                                </div>
                                <a href="{{ asset('storage/' . $user->foto_bpjs) }}" 
                                   target="_blank" 
                                   class="text-teal-600 hover:text-teal-700 text-sm font-semibold">
                                    <i class="fas fa-eye mr-1"></i> Lihat
                                </a>
                            </div>
                            <img src="{{ asset('storage/' . $user->foto_bpjs) }}" 
                                 alt="Foto BPJS" 
                                 class="w-full h-32 object-cover rounded-lg">
                        </div>
                        @endif
                        
                        <!-- Dokumen Kontrak -->
                        @if($user->dokumen_kontrak)
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                            <div class="flex items-center justify-between mb-2">
                                <div class="flex items-center">
                                    <i class="fas fa-file-contract text-teal-600 text-xl mr-3"></i>
                                    <span class="font-semibold text-gray-900">Dokumen Kontrak</span>
                                </div>
                                <a href="{{ asset('storage/' . $user->dokumen_kontrak) }}" 
                                   target="_blank" 
                                   class="text-teal-600 hover:text-teal-700 text-sm font-semibold">
                                    <i class="fas fa-download mr-1"></i> Unduh
                                </a>
                            </div>
                            <div class="bg-gray-100 rounded-lg p-6 text-center">
                                @php
                                    $extension = pathinfo($user->dokumen_kontrak, PATHINFO_EXTENSION);
                                @endphp
                                @if($extension == 'pdf')
                                    <i class="fas fa-file-pdf text-6xl text-red-500 mb-2"></i>
                                    <p class="text-sm text-gray-600">Dokumen PDF</p>
                                @elseif(in_array($extension, ['doc', 'docx']))
                                    <i class="fas fa-file-word text-6xl text-blue-500 mb-2"></i>
                                    <p class="text-sm text-gray-600">Dokumen Word</p>
                                @else
                                    <img src="{{ asset('storage/' . $user->dokumen_kontrak) }}" 
                                         alt="Dokumen Kontrak" 
                                         class="w-full h-32 object-cover rounded-lg">
                                @endif
                            </div>
                        </div>
                        @endif
                        
                    </div>
                </div>
            </div>
            @endif
            
            <!-- Edit Profile Form -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-teal-600 to-cyan-600">
                    <h3 class="text-lg font-bold text-white">
                        <i class="fas fa-user-edit mr-2"></i>Edit Profil
                    </h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('karyawan.profil.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nama Lengkap -->
                            <div>
                                <label class="form-label">
                                    <i class="fas fa-user mr-2"></i>Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       name="name" 
                                       class="form-input @error('name') border-red-500 @enderror" 
                                       value="{{ old('name', $user->name) }}"
                                       required>
                                @error('name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="form-label">
                                    <i class="fas fa-envelope mr-2"></i>Email <span class="text-red-500">*</span>
                                </label>
                                <input type="email" 
                                       name="email" 
                                       class="form-input @error('email') border-red-500 @enderror" 
                                       value="{{ old('email', $user->email) }}"
                                       required>
                                @error('email')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Nomor Telepon -->
                            <div>
                                <label class="form-label">
                                    <i class="fas fa-phone mr-2"></i>Nomor Telepon
                                </label>
                                <input type="text" 
                                       name="phone" 
                                       class="form-input @error('phone') border-red-500 @enderror" 
                                       value="{{ old('phone', $user->phone) }}"
                                       placeholder="08xxxxxxxxxx">
                                @error('phone')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tanggal Lahir -->
                            <div>
                                <label class="form-label">
                                    <i class="fas fa-calendar mr-2"></i>Tanggal Lahir
                                </label>
                                <input type="date" 
                                       name="tanggal_lahir" 
                                       class="form-input @error('tanggal_lahir') border-red-500 @enderror" 
                                       value="{{ old('tanggal_lahir', $user->tanggal_lahir ? $user->tanggal_lahir->format('Y-m-d') : '') }}">
                                @error('tanggal_lahir')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Jenis Kelamin -->
                            <div>
                                <label class="form-label">
                                    <i class="fas fa-venus-mars mr-2"></i>Jenis Kelamin
                                </label>
                                <select name="jenis_kelamin" class="form-input @error('jenis_kelamin') border-red-500 @enderror">
                                    <option value="">-- Pilih --</option>
                                    <option value="Laki-laki" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ old('jenis_kelamin', $user->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('jenis_kelamin')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Alamat -->
                            <div class="md:col-span-2">
                                <label class="form-label">
                                    <i class="fas fa-map-marker-alt mr-2"></i>Alamat
                                </label>
                                <textarea name="alamat" 
                                          rows="3" 
                                          class="form-input @error('alamat') border-red-500 @enderror"
                                          placeholder="Alamat lengkap">{{ old('alamat', $user->alamat) }}</textarea>
                                @error('alamat')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-2"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Change Password Form -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-yellow-500 to-orange-500">
                    <h3 class="text-lg font-bold text-white">
                        <i class="fas fa-lock mr-2"></i>Ubah Password
                    </h3>
                </div>
                <div class="p-6">
                    <form action="{{ route('karyawan.profil.password') }}" method="POST" id="formPassword">
                        @csrf
                        @method('PUT')

                        <div class="bg-teal-50 border-l-4 border-teal-500 p-4 rounded-lg mb-6">
                            <div class="flex">
                                <i class="fas fa-info-circle text-teal-600 text-xl mr-3 mt-0.5"></i>
                                <div>
                                    <p class="text-teal-800 font-semibold">Tips Keamanan:</p>
                                    <p class="text-teal-700 text-sm">Gunakan kombinasi huruf besar, huruf kecil, angka, dan simbol. Minimal 8 karakter.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Password Lama -->
                        <div class="mb-6">
                            <label class="form-label">
                                <i class="fas fa-key mr-2"></i>Password Lama <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" 
                                       name="current_password" 
                                       class="form-input pr-12 @error('current_password') border-red-500 @enderror"
                                       id="currentPassword"
                                       required>
                                <button type="button" 
                                        onclick="togglePassword('currentPassword')" 
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                    <i class="fas fa-eye" id="iconCurrentPassword"></i>
                                </button>
                            </div>
                            @error('current_password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Baru -->
                        <div class="mb-6">
                            <label class="form-label">
                                <i class="fas fa-lock mr-2"></i>Password Baru <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" 
                                       name="new_password" 
                                       class="form-input pr-12 @error('new_password') border-red-500 @enderror"
                                       id="newPassword"
                                       minlength="8"
                                       required>
                                <button type="button" 
                                        onclick="togglePassword('newPassword')" 
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                    <i class="fas fa-eye" id="iconNewPassword"></i>
                                </button>
                            </div>
                            @error('new_password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Minimal 8 karakter</p>
                        </div>

                        <!-- Konfirmasi Password -->
                        <div class="mb-6">
                            <label class="form-label">
                                <i class="fas fa-check-circle mr-2"></i>Konfirmasi Password Baru <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" 
                                       name="new_password_confirmation" 
                                       class="form-input pr-12 @error('new_password_confirmation') border-red-500 @enderror"
                                       id="confirmPassword"
                                       minlength="8"
                                       required>
                                <button type="button" 
                                        onclick="togglePassword('confirmPassword')" 
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700">
                                    <i class="fas fa-eye" id="iconConfirmPassword"></i>
                                </button>
                            </div>
                            @error('new_password_confirmation')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password Strength Indicator -->
                        <div class="mb-6">
                            <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                                <div class="h-full transition-all duration-300" id="passwordStrength" style="width: 0%"></div>
                            </div>
                            <p class="text-xs text-gray-600 mt-1" id="passwordStrengthText"></p>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-yellow-500 to-orange-500 text-white font-semibold rounded-lg hover:from-yellow-600 hover:to-orange-600 transition shadow-md">
                                <i class="fas fa-key mr-2"></i> Ubah Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Upload Foto -->
<x-modal id="modalUploadFoto" title="Upload Foto Profil" size="md" type="info">
    <form action="{{ route('karyawan.profil.foto') }}" method="POST" enctype="multipart/form-data" id="formUploadFoto">
        @csrf
        
        <div class="text-center mb-6">
            <!-- Preview -->
            <div class="mb-4">
                <img id="imagePreview" 
                     src="{{ $user->foto ? asset('storage/' . $user->foto) : '' }}" 
                     alt="Preview" 
                     class="max-h-80 rounded-lg mx-auto {{ $user->foto ? '' : 'hidden' }}">
                <div id="placeholderPreview" 
                     class="bg-gray-100 rounded-lg flex items-center justify-center mx-auto {{ $user->foto ? 'hidden' : '' }}"
                     style="height: 320px;">
                    <i class="fas fa-image text-6xl text-gray-400"></i>
                </div>
            </div>

            <!-- File Input -->
            <div>
                <label class="form-label text-left">
                    <i class="fas fa-image mr-2"></i>Pilih Foto <span class="text-red-500">*</span>
                </label>
                <input type="file" 
                       name="foto" 
                       class="form-input @error('foto') border-red-500 @enderror"
                       accept="image/*"
                       id="fotoInput"
                       required>
                @error('foto')
                    <p class="text-red-500 text-xs mt-1 text-left">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1 text-left">Format: JPG, JPEG, PNG. Maksimal 2MB</p>
            </div>
        </div>
    </form>
    
    <x-slot name="footerButtons">
        <button type="button" onclick="closeModal('modalUploadFoto')" class="px-5 py-2.5 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
            Batal
        </button>
        <button type="submit" form="formUploadFoto" class="px-5 py-2.5 bg-gradient-to-r from-teal-600 to-cyan-600 text-white font-semibold rounded-lg hover:from-teal-700 hover:to-cyan-700 transition shadow-md">
            <i class="fas fa-upload mr-2"></i> Upload
        </button>
    </x-slot>
</x-modal>

@endsection

@push('scripts')
<script>
(function() {
    'use strict';
    
    // Toggle Password Visibility
    window.togglePassword = function(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = document.getElementById('icon' + fieldId.charAt(0).toUpperCase() + fieldId.slice(1));
        
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    };

    // Password Strength Checker
    const newPasswordField = document.getElementById('newPassword');
    if (newPasswordField && !newPasswordField.dataset.listenerAdded) {
        newPasswordField.dataset.listenerAdded = 'true';
        newPasswordField.addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('passwordStrength');
            const strengthText = document.getElementById('passwordStrengthText');
            
            let strength = 0;
            
            if (password.length >= 8) strength += 25;
            if (password.match(/[a-z]+/)) strength += 25;
            if (password.match(/[A-Z]+/)) strength += 25;
            if (password.match(/[0-9]+/)) strength += 25;
            
            strengthBar.style.width = strength + '%';
            
            if (strength <= 25) {
                strengthBar.className = 'h-full transition-all duration-300 bg-red-500';
                strengthText.textContent = 'Lemah';
                strengthText.className = 'text-xs text-red-600 mt-1';
            } else if (strength <= 50) {
                strengthBar.className = 'h-full transition-all duration-300 bg-yellow-500';
                strengthText.textContent = 'Cukup';
                strengthText.className = 'text-xs text-yellow-600 mt-1';
            } else if (strength <= 75) {
                strengthBar.className = 'h-full transition-all duration-300 bg-teal-500';
                strengthText.textContent = 'Baik';
                strengthText.className = 'text-xs text-teal-600 mt-1';
            } else {
                strengthBar.className = 'h-full transition-all duration-300 bg-green-500';
                strengthText.textContent = 'Kuat';
                strengthText.className = 'text-xs text-green-600 mt-1';
            }
        });
    }

    // Image Preview
    const fotoInput = document.getElementById('fotoInput');
    if (fotoInput && !fotoInput.dataset.listenerAdded) {
        fotoInput.dataset.listenerAdded = 'true';
        fotoInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Check file size
                if (file.size > 2048000) { // 2MB
                    alert('Ukuran file maksimal 2MB!');
                    this.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.getElementById('imagePreview').src = event.target.result;
                    document.getElementById('imagePreview').classList.remove('hidden');
                    document.getElementById('placeholderPreview').classList.add('hidden');
                }
                reader.readAsDataURL(file);
            }
        });
    }

    // Handle delete photo form submission
    const deleteForm = document.getElementById('deletePhotoForm');
    if (deleteForm && !deleteForm.dataset.listenerAdded) {
        deleteForm.dataset.listenerAdded = 'true';
        deleteForm.addEventListener('submit', function(e) {
            if (!confirm('Yakin ingin menghapus foto profil?')) {
                e.preventDefault();
                return false;
            }
        });
    }

    // Auto dismiss alerts (hanya untuk alert messages, bukan tombol!)
    setTimeout(function() {
        const alerts = document.querySelectorAll('.bg-green-50.border-l-4, .bg-red-50.border-l-4');
        alerts.forEach(function(alert) {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);

    // Confirm password match validation
    const formPassword = document.getElementById('formPassword');
    if (formPassword && !formPassword.dataset.listenerAdded) {
        formPassword.dataset.listenerAdded = 'true';
        formPassword.addEventListener('submit', function(e) {
            const newPassword = document.getElementById('newPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;
            
            if (newPassword !== confirmPassword) {
                e.preventDefault();
                alert('Konfirmasi password tidak cocok!');
                return false;
            }
        });
    }
})();
</script>
@endpush
