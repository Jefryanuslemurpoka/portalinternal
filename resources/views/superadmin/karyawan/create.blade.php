@extends('layouts.app')

@section('title', 'Tambah Karyawan')
@section('page-title', 'Tambah Karyawan')

@section('content')
<div class="max-w-6xl mx-auto">

    <!-- Debug Error Messages -->
    @if ($errors->any())
        <div class="mb-8 bg-red-50 border border-red-200 rounded-xl p-5">
            <h3 class="text-red-800 font-bold mb-3">Error Validation:</h3>
            <ul class="list-disc list-inside text-red-700 text-sm space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="mb-8 bg-green-50 border border-green-200 rounded-xl p-5">
            <p class="text-green-800 font-semibold">✅ {{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-8 bg-red-50 border border-red-200 rounded-xl p-5">
            <p class="text-red-800 font-semibold">❌ {{ session('error') }}</p>
        </div>
    @endif

    <!-- Back Button -->
    <div class="mb-8">
        <a href="{{ route('superadmin.karyawan.index') }}" class="inline-flex items-center text-gray-600 hover:text-teal-600 transition font-medium">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Daftar Karyawan
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        
        <!-- Header -->
        <div class="bg-gradient-to-r from-teal-500 to-cyan-600 px-8 py-6">
            <h3 class="text-2xl font-bold text-white">Form Tambah Karyawan</h3>
            <p class="text-teal-50 text-sm mt-2">Lengkapi semua data karyawan dengan benar</p>
        </div>

        <!-- Form -->
        <form action="{{ route('superadmin.karyawan.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf

            <div class="space-y-14">

                <!-- SECTION: Foto Profile -->
                <div class="pb-10 border-b-2 border-gray-200">
                    <h4 class="text-xl font-bold text-gray-800 mb-8 flex items-center gap-3">
                            <i class="fas fa-camera text-teal-600 text-lg"></i>
                        <span>Foto Profil</span>
                    </h4>
                    <div class="bg-gray-50 rounded-xl p-8 border-2 border-dashed border-gray-300 hover:border-teal-400 transition">
                        <div class="file-upload cursor-pointer" onclick="document.getElementById('foto').click()">
                            <input type="file" name="foto" id="foto" accept="image/*" onchange="previewImage(event, 'imagePreview')" class="hidden">
                            <div class="text-center">
                                <img id="imagePreview" src="https://ui-avatars.com/api/?name=Upload+Foto&background=e0e0e0&color=9ca3af&size=200" class="mx-auto mb-5 w-36 h-36 rounded-full object-cover border-4 border-white shadow-lg">
                                <i class="fas fa-cloud-upload-alt text-4xl text-teal-400 mb-4"></i>
                                <p class="text-base font-semibold text-gray-700">Klik untuk upload foto profil</p>
                                <p class="text-sm text-gray-500 mt-3">Format: JPG, PNG (Maksimal: 2MB)</p>
                            </div>
                        </div>
                    </div>
                    @error('foto')
                        <p class="form-error mt-3">{{ $message }}</p>
                    @enderror
                </div>

                <!-- SECTION: Data Pribadi -->
                <div class="pb-10 border-b-2 border-gray-200">
                    <h4 class="text-xl font-bold text-gray-800 mb-8 flex items-center gap-3">
                            <i class="fas fa-user text-teal-600 text-lg"></i>
                        <span>Data Pribadi</span>
                    </h4>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-7">
                        <!-- Nama Lengkap -->
                        <div class="lg:col-span-2">
                            <label for="name" class="form-label text-sm font-semibold text-gray-700 mb-2 block">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" 
                                   class="form-input @error('name') border-red-500 @enderror" 
                                   placeholder="Masukkan nama lengkap" required>
                            @error('name')
                                <p class="form-error mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- NIK -->
                        <div>
                            <label for="nik" class="form-label text-sm font-semibold text-gray-700 mb-2 block">
                                NIK (16 Digit) <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nik" id="nik" value="{{ old('nik') }}" 
                                   class="form-input @error('nik') border-red-500 @enderror" 
                                   placeholder="Contoh: 3201010101010001" maxlength="16" required>
                            @error('nik')
                                <p class="form-error mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Gelar -->
                        <div>
                            <label for="gelar" class="form-label text-sm font-semibold text-gray-700 mb-2 block">
                                Gelar <span class="text-gray-400 text-xs">(Opsional)</span>
                            </label>
                            <input type="text" name="gelar" id="gelar" value="{{ old('gelar') }}" 
                                   class="form-input @error('gelar') border-red-500 @enderror" 
                                   placeholder="Contoh: S.Kom, S.T, M.M">
                            @error('gelar')
                                <p class="form-error mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="lg:col-span-2">
                            <label for="email" class="form-label text-sm font-semibold text-gray-700 mb-2 block">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" 
                                   class="form-input @error('email') border-red-500 @enderror" 
                                   placeholder="nama@email.com" required>
                            @error('email')
                                <p class="form-error mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Alamat -->
                        <div class="lg:col-span-2">
                            <label for="alamat" class="form-label text-sm font-semibold text-gray-700 mb-2 block">
                                Alamat Lengkap <span class="text-red-500">*</span>
                            </label>
                            <textarea name="alamat" id="alamat" rows="3" 
                                      class="form-input @error('alamat') border-red-500 @enderror" 
                                      placeholder="Jl. Contoh No. 123, RT/RW, Kelurahan, Kecamatan, Kota" required>{{ old('alamat') }}</textarea>
                            @error('alamat')
                                <p class="form-error mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tempat Lahir -->
                        <div>
                            <label for="tempat_lahir" class="form-label text-sm font-semibold text-gray-700 mb-2 block">
                                Tempat Lahir <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="tempat_lahir" id="tempat_lahir" value="{{ old('tempat_lahir') }}" 
                                   class="form-input @error('tempat_lahir') border-red-500 @enderror" 
                                   placeholder="Contoh: Jakarta" required>
                            @error('tempat_lahir')
                                <p class="form-error mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Lahir -->
                        <div>
                            <label for="tanggal_lahir" class="form-label text-sm font-semibold text-gray-700 mb-2 block">
                                Tanggal Lahir <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir') }}" 
                                   class="form-input @error('tanggal_lahir') border-red-500 @enderror" required>
                            @error('tanggal_lahir')
                                <p class="form-error mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jenis Kelamin -->
                        <div>
                            <label for="jenis_kelamin" class="form-label text-sm font-semibold text-gray-700 mb-2 block">
                                Jenis Kelamin <span class="text-red-500">*</span>
                            </label>
                            <select name="jenis_kelamin" id="jenis_kelamin" class="form-input @error('jenis_kelamin') border-red-500 @enderror" required>
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <p class="form-error mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nomor Rekening -->
                        <div>
                            <label for="nomor_rekening" class="form-label text-sm font-semibold text-gray-700 mb-2 block">
                                Nomor Rekening <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nomor_rekening" id="nomor_rekening" value="{{ old('nomor_rekening') }}" 
                                   class="form-input @error('nomor_rekening') border-red-500 @enderror" 
                                   placeholder="Contoh: 1234567890" required>
                            @error('nomor_rekening')
                                <p class="form-error mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- SECTION: Data Akun -->
                <div class="pb-10 border-b-2 border-gray-200">
                    <h4 class="text-xl font-bold text-gray-800 mb-8 flex items-center gap-3">
                            <i class="fas fa-lock text-teal-600 text-lg"></i>
                        <span>Data Akun & Password</span>
                    </h4>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-7">
                        <!-- Password -->
                        <div>
                            <label for="password" class="form-label text-sm font-semibold text-gray-700 mb-2 block">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" name="password" id="password" 
                                       class="form-input @error('password') border-red-500 @enderror pr-12" 
                                       placeholder="Minimal 6 karakter" required>
                                <button type="button" onclick="togglePassword('password', 'togglePasswordIcon')" 
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-teal-600 transition">
                                    <i class="fas fa-eye" id="togglePasswordIcon"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="form-error mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Konfirmasi Password -->
                        <div>
                            <label for="password_confirmation" class="form-label text-sm font-semibold text-gray-700 mb-2 block">
                                Konfirmasi Password <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="password_confirmation" 
                                       class="form-input pr-12" 
                                       placeholder="Ulangi password" required>
                                <button type="button" onclick="togglePassword('password_confirmation', 'togglePasswordConfIcon')" 
                                        class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-teal-600 transition">
                                    <i class="fas fa-eye" id="togglePasswordConfIcon"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION: Data Kepegawaian -->
                <div class="pb-10 border-b-2 border-gray-200">
                    <h4 class="text-xl font-bold text-gray-800 mb-8 flex items-center gap-3">
                            <i class="fas fa-briefcase text-teal-600 text-lg"></i>
                        <span>Data Kepegawaian</span>
                    </h4>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-7">
                        <!-- Divisi dengan Quick Add -->
                        <div>
                            <label for="divisi" class="form-label text-sm font-semibold text-gray-700 mb-2 block">
                                Divisi <span class="text-red-500">*</span>
                            </label>
                            <div class="flex gap-3">
                                <select name="divisi" id="divisi" class="form-input @error('divisi') border-red-500 @enderror flex-1" required>
                                    <option value="">-- Pilih Divisi --</option>
                                    @foreach($divisiList as $d)
                                        <option value="{{ $d->nama_divisi }}" {{ old('divisi') == $d->nama_divisi ? 'selected' : '' }}>
                                            {{ $d->nama_divisi }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('divisi')
                                <p class="form-error mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jabatan -->
                        <div>
                            <label for="jabatan" class="form-label text-sm font-semibold text-gray-700 mb-2 block">
                                Jabatan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="jabatan" id="jabatan" value="{{ old('jabatan') }}" 
                                   class="form-input @error('jabatan') border-red-500 @enderror" 
                                   placeholder="Contoh: Staff, Manager, Supervisor" required>
                            @error('jabatan')
                                <p class="form-error mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="form-label text-sm font-semibold text-gray-700 mb-2 block">
                                Status Karyawan <span class="text-red-500">*</span>
                            </label>
                            <select name="status" id="status" class="form-input @error('status') border-red-500 @enderror" required>
                                <option value="">-- Pilih Status --</option>
                                <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Non-Aktif</option>
                            </select>
                            @error('status')
                                <p class="form-error mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Aktif Dari -->
                        <div>
                            <label for="aktif_dari" class="form-label text-sm font-semibold text-gray-700 mb-2 block">
                                Aktif Dari <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="aktif_dari" id="aktif_dari" value="{{ old('aktif_dari') }}" 
                                   class="form-input @error('aktif_dari') border-red-500 @enderror" required>
                            @error('aktif_dari')
                                <p class="form-error mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Aktif Sampai -->
                        <div class="lg:col-span-2">
                            <label for="aktif_sampai" class="form-label text-sm font-semibold text-gray-700 mb-2 block">
                                Aktif Sampai <span class="text-gray-400 text-xs">(Opsional, kosongkan jika permanen)</span>
                            </label>
                            <input type="date" name="aktif_sampai" id="aktif_sampai" value="{{ old('aktif_sampai') }}" 
                                   class="form-input @error('aktif_sampai') border-red-500 @enderror">
                            <p class="text-xs text-gray-500 mt-3">💡 Kosongkan jika karyawan tidak memiliki masa kontrak berakhir</p>
                            @error('aktif_sampai')
                                <p class="form-error mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- SECTION: Dokumen Karyawan -->
                <div>
                    <h4 class="text-xl font-bold text-gray-800 mb-8 flex items-center gap-3">
                            <i class="fas fa-file-alt text-teal-600 text-lg"></i>
                        <span>Dokumen Karyawan</span>
                        <span class="text-sm font-normal text-gray-500">(Opsional)</span>
                    </h4>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-7">
                        
                        <!-- Foto KTP -->
                        <div>
                            <label class="form-label text-sm font-semibold text-gray-700 mb-3 block">
                                <i class="fas fa-id-card mr-2 text-teal-600"></i>Foto KTP
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 hover:border-teal-400 hover:bg-gray-50 transition cursor-pointer" onclick="document.getElementById('foto_ktp').click()">
                                <input type="file" name="foto_ktp" id="foto_ktp" accept="image/*" onchange="previewDokumen(event, 'previewKTP', 'filenameKTP')" class="hidden">
                                <div class="text-center">
                                    <img id="previewKTP" src="" class="hidden mx-auto mb-3 w-full h-40 object-contain rounded-lg">
                                    <i class="fas fa-id-card text-5xl text-gray-300 mb-3"></i>
                                    <p class="text-sm text-gray-600 font-medium">Upload Foto KTP</p>
                                    <p class="text-xs text-gray-400 mt-2" id="filenameKTP">JPG, PNG (Max: 2MB)</p>
                                </div>
                            </div>
                            @error('foto_ktp')
                                <p class="form-error mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Foto NPWP -->
                        <div>
                            <label class="form-label text-sm font-semibold text-gray-700 mb-3 block">
                                <i class="fas fa-file-invoice mr-2 text-teal-600"></i>Foto NPWP
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 hover:border-teal-400 hover:bg-gray-50 transition cursor-pointer" onclick="document.getElementById('foto_npwp').click()">
                                <input type="file" name="foto_npwp" id="foto_npwp" accept="image/*" onchange="previewDokumen(event, 'previewNPWP', 'filenameNPWP')" class="hidden">
                                <div class="text-center">
                                    <img id="previewNPWP" src="" class="hidden mx-auto mb-3 w-full h-40 object-contain rounded-lg">
                                    <i class="fas fa-file-invoice text-5xl text-gray-300 mb-3"></i>
                                    <p class="text-sm text-gray-600 font-medium">Upload Foto NPWP</p>
                                    <p class="text-xs text-gray-400 mt-2" id="filenameNPWP">JPG, PNG (Max: 2MB)</p>
                                </div>
                            </div>
                            @error('foto_npwp')
                                <p class="form-error mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Foto BPJS -->
                        <div>
                            <label class="form-label text-sm font-semibold text-gray-700 mb-3 block">
                                <i class="fas fa-hospital mr-2 text-teal-600"></i>Foto BPJS
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 hover:border-teal-400 hover:bg-gray-50 transition cursor-pointer" onclick="document.getElementById('foto_bpjs').click()">
                                <input type="file" name="foto_bpjs" id="foto_bpjs" accept="image/*" onchange="previewDokumen(event, 'previewBPJS', 'filenameBPJS')" class="hidden">
                                <div class="text-center">
                                    <img id="previewBPJS" src="" class="hidden mx-auto mb-3 w-full h-40 object-contain rounded-lg">
                                    <i class="fas fa-hospital text-5xl text-gray-300 mb-3"></i>
                                    <p class="text-sm text-gray-600 font-medium">Upload Foto BPJS</p>
                                    <p class="text-xs text-gray-400 mt-2" id="filenameBPJS">JPG, PNG (Max: 2MB)</p>
                                </div>
                            </div>
                            @error('foto_bpjs')
                                <p class="form-error mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Dokumen Kontrak -->
                        <div>
                            <label class="form-label text-sm font-semibold text-gray-700 mb-3 block">
                                <i class="fas fa-file-contract mr-2 text-teal-600"></i>Dokumen Kontrak Kerja
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 hover:border-teal-400 hover:bg-gray-50 transition cursor-pointer" onclick="document.getElementById('dokumen_kontrak').click()">
                                <input type="file" name="dokumen_kontrak" id="dokumen_kontrak" accept=".pdf,.doc,.docx,image/*" onchange="previewDokumen(event, 'previewKontrak', 'filenameKontrak', true)" class="hidden">
                                <div class="text-center">
                                    <img id="previewKontrak" src="" class="hidden mx-auto mb-3 w-full h-40 object-contain rounded-lg">
                                    <i class="fas fa-file-contract text-5xl text-gray-300 mb-3"></i>
                                    <p class="text-sm text-gray-600 font-medium">Upload Kontrak Kerja</p>
                                    <p class="text-xs text-gray-400 mt-2" id="filenameKontrak">PDF, DOC, DOCX, JPG, PNG (Max: 5MB)</p>
                                </div>
                            </div>
                            @error('dokumen_kontrak')
                                <p class="form-error mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                </div>

            </div>

            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row justify-end gap-4 mt-12 pt-10 border-t-2 border-gray-200">
                <a href="{{ route('superadmin.karyawan.index') }}" class="btn btn-secondary text-center">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-2"></i>Simpan Karyawan
                </button>
            </div>

        </form>

    </div>

</div>

<!-- Modal Quick Add Divisi - LAYOUT BARU: Kiri Input, Kanan Tombol -->
<div id="quickAddDivisiModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-2xl w-full" onclick="event.stopPropagation()">
        <div class="bg-gradient-to-r from-teal-500 to-cyan-600 px-6 py-5 rounded-t-2xl">
            <h3 class="text-xl font-bold text-white flex items-center">
                <i class="fas fa-plus-circle mr-3"></i>
                Tambah Divisi Baru
            </h3>
        </div>
        <form id="quickAddDivisiForm">
            <div class="p-6">
                <!-- Grid 2 Kolom: Kiri Form, Kanan Tombol -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- KIRI: Form Input (2 kolom) -->
                    <div class="md:col-span-2 space-y-5">
                        <div>
                            <label class="form-label text-sm font-semibold text-gray-700 mb-2 block">
                                Nama Divisi <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="quick_divisi_name" class="form-input" placeholder="Contoh: IT, HR, Marketing" required>
                        </div>
                        <div>
                            <label class="form-label text-sm font-semibold text-gray-700 mb-2 block">
                                Deskripsi <span class="text-gray-400 text-xs">(Opsional)</span>
                            </label>
                            <textarea id="quick_divisi_desc" rows="3" class="form-input" placeholder="Deskripsi singkat tentang divisi"></textarea>
                        </div>
                        <div>
                            <label class="form-label text-sm font-semibold text-gray-700 mb-2 block">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select id="quick_divisi_status" class="form-input" required>
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- KANAN: Tombol Aksi (1 kolom) -->
                    <div class="md:col-span-1 flex flex-col justify-center space-y-3">
                        <button type="submit" 
                                class="w-full px-5 py-3 bg-gradient-to-r from-teal-500 to-cyan-600 text-white font-semibold rounded-lg hover:from-teal-600 hover:to-cyan-700 transition shadow-md">
                            <i class="fas fa-save mr-2"></i>Simpan & Gunakan
                        </button>
                        <button type="button" onclick="closeQuickAddDivisi()" 
                                class="w-full px-5 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
                            <i class="fas fa-times mr-2"></i>Batal
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Preview Image Profile
    function previewImage(event, previewId) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById(previewId).src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    }

    // Preview Dokumen
    function previewDokumen(event, previewId, filenameId, isDocument = false) {
        const file = event.target.files[0];
        const preview = document.getElementById(previewId);
        const filenameEl = document.getElementById(filenameId);
        
        if (file) {
            filenameEl.textContent = file.name;
            filenameEl.classList.add('text-teal-600', 'font-semibold');
            
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    preview.parentElement.querySelector('i').classList.add('hidden');
                }
                reader.readAsDataURL(file);
            } else if (isDocument) {
                preview.classList.add('hidden');
                const icon = preview.parentElement.querySelector('i');
                icon.classList.remove('hidden');
                
                if (file.type.includes('pdf')) {
                    icon.className = 'fas fa-file-pdf text-5xl text-red-500 mb-3';
                } else if (file.type.includes('word') || file.name.endsWith('.doc') || file.name.endsWith('.docx')) {
                    icon.className = 'fas fa-file-word text-5xl text-blue-500 mb-3';
                }
            }
        }
    }

    // Toggle Password Visibility
    function togglePassword(fieldId, iconId) {
        const field = document.getElementById(fieldId);
        const icon = document.getElementById(iconId);
        
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

    // Quick Add Divisi Functions
    function openQuickAddDivisi() {
        document.getElementById('quickAddDivisiModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeQuickAddDivisi() {
        document.getElementById('quickAddDivisiModal').classList.add('hidden');
        document.getElementById('quickAddDivisiForm').reset();
        document.body.style.overflow = 'auto';
    }

    document.getElementById('quickAddDivisiForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const namaDivisi = document.getElementById('quick_divisi_name').value;
        const deskripsi = document.getElementById('quick_divisi_desc').value;
        const status = document.getElementById('quick_divisi_status').value;
        
        try {
            const response = await fetch('{{ route("superadmin.divisi.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    nama_divisi: namaDivisi,
                    deskripsi: deskripsi,
                    status: status
                })
            });
            
            if (response.ok) {
                const select = document.getElementById('divisi');
                const option = new Option(namaDivisi, namaDivisi, true, true);
                select.add(option);
                
                closeQuickAddDivisi();
                
                // Show success notification
                const notification = document.createElement('div');
                notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 animate-slide-in';
                notification.innerHTML = '<i class="fas fa-check-circle mr-2"></i>Divisi "' + namaDivisi + '" berhasil ditambahkan!';
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    notification.remove();
                }, 3000);
            } else {
                const data = await response.json();
                alert('❌ Gagal menambahkan divisi: ' + (data.message || 'Terjadi kesalahan'));
            }
        } catch (error) {
            alert('❌ Terjadi kesalahan: ' + error.message);
        }
    });

    // Close modal dengan ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('quickAddDivisiModal');
            if (!modal.classList.contains('hidden')) {
                closeQuickAddDivisi();
            }
        }
    });

    // Close modal saat klik di luar
    document.getElementById('quickAddDivisiModal').addEventListener('click', function(e) {
        if (e.target === this) closeQuickAddDivisi();
    });
</script>
@endpush