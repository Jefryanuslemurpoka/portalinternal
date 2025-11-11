@extends('layouts.app')

@section('title', 'Edit Karyawan')
@section('page-title', 'Edit Karyawan')

@section('content')
<div class="max-w-5xl mx-auto">

    <!-- Debug Error Messages -->
    @if ($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
            <h3 class="text-red-800 font-bold mb-2">Error Validation:</h3>
            <ul class="list-disc list-inside text-red-700 text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-4">
            <p class="text-green-800 font-semibold">✅ {{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
            <p class="text-red-800 font-semibold">❌ {{ session('error') }}</p>
        </div>
    @endif

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
        <form action="{{ route('superadmin.karyawan.update', $karyawan->uuid) }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            @method('PUT')

            <div class="space-y-8">

                <!-- SECTION: Foto Profile -->
                <div>
                    <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center border-b pb-2">
                        <i class="fas fa-camera mr-2 text-teal-600"></i>
                        Foto Profil
                    </h4>
                    <div class="file-upload cursor-pointer" onclick="document.getElementById('foto').click()">
                        <input type="file" name="foto" id="foto" accept="image/*" onchange="previewImage(event, 'imagePreview')" class="hidden">
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

                <!-- SECTION: Data Pribadi -->
                <div>
                    <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center border-b pb-2">
                        <i class="fas fa-user mr-2 text-teal-600"></i>
                        Data Pribadi
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Nama Lengkap -->
                        <div class="md:col-span-2">
                            <label for="name" class="form-label">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name', $karyawan->name) }}" 
                                   class="form-input @error('name') border-red-500 @enderror" 
                                   placeholder="Masukkan nama lengkap" required>
                            @error('name')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- NIK -->
                        <div>
                            <label for="nik" class="form-label">
                                NIK (16 Digit) <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nik" id="nik" value="{{ old('nik', $karyawan->nik) }}" 
                                   class="form-input @error('nik') border-red-500 @enderror" 
                                   placeholder="Contoh: 3201010101010001" maxlength="16" required>
                            @error('nik')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Gelar -->
                        <div>
                            <label for="gelar" class="form-label">
                                Gelar (Opsional)
                            </label>
                            <input type="text" name="gelar" id="gelar" value="{{ old('gelar', $karyawan->gelar) }}" 
                                   class="form-input @error('gelar') border-red-500 @enderror" 
                                   placeholder="Contoh: S.Kom, S.T, M.M">
                            @error('gelar')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="md:col-span-2">
                            <label for="email" class="form-label">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email', $karyawan->email) }}" 
                                   class="form-input @error('email') border-red-500 @enderror" 
                                   placeholder="nama@email.com" required>
                            @error('email')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Alamat -->
                        <div class="md:col-span-2">
                            <label for="alamat" class="form-label">
                                Alamat Lengkap <span class="text-red-500">*</span>
                            </label>
                            <textarea name="alamat" id="alamat" rows="3" 
                                      class="form-input @error('alamat') border-red-500 @enderror" 
                                      placeholder="Jl. Contoh No. 123, RT/RW, Kelurahan, Kecamatan, Kota" required>{{ old('alamat', $karyawan->alamat) }}</textarea>
                            @error('alamat')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tempat Lahir -->
                        <div>
                            <label for="tempat_lahir" class="form-label">
                                Tempat Lahir <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="tempat_lahir" id="tempat_lahir" value="{{ old('tempat_lahir', $karyawan->tempat_lahir) }}" 
                                   class="form-input @error('tempat_lahir') border-red-500 @enderror" 
                                   placeholder="Contoh: Jakarta" required>
                            @error('tempat_lahir')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Lahir -->
                        <div>
                            <label for="tanggal_lahir" class="form-label">
                                Tanggal Lahir <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="tanggal_lahir" id="tanggal_lahir" value="{{ old('tanggal_lahir', $karyawan->tanggal_lahir ? $karyawan->tanggal_lahir->format('Y-m-d') : '') }}" 
                                   class="form-input @error('tanggal_lahir') border-red-500 @enderror" required>
                            @error('tanggal_lahir')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jenis Kelamin -->
                        <div>
                            <label for="jenis_kelamin" class="form-label">
                                Jenis Kelamin <span class="text-red-500">*</span>
                            </label>
                            <select name="jenis_kelamin" id="jenis_kelamin" class="form-input @error('jenis_kelamin') border-red-500 @enderror" required>
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="Laki-laki" {{ old('jenis_kelamin', $karyawan->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="Perempuan" {{ old('jenis_kelamin', $karyawan->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                            @error('jenis_kelamin')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nomor Rekening -->
                        <div>
                            <label for="nomor_rekening" class="form-label">
                                Nomor Rekening <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nomor_rekening" id="nomor_rekening" value="{{ old('nomor_rekening', $karyawan->nomor_rekening) }}" 
                                   class="form-input @error('nomor_rekening') border-red-500 @enderror" 
                                   placeholder="Contoh: 1234567890" required>
                            @error('nomor_rekening')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- SECTION: Info Password -->
                <div>
                    <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center border-b pb-2">
                        <i class="fas fa-lock mr-2 text-teal-600"></i>
                        Informasi Password
                    </h4>
                    <div class="bg-teal-50 border border-teal-200 rounded-xl p-4">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-teal-600 mt-1 mr-3"></i>
                            <div>
                                <p class="text-sm text-teal-800 font-semibold mb-1">Password tidak akan diubah</p>
                                <p class="text-xs text-teal-700">Gunakan tombol "Reset Password" di daftar karyawan jika ingin mereset password.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION: Data Kepegawaian -->
                <div>
                    <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center border-b pb-2">
                        <i class="fas fa-briefcase mr-2 text-teal-600"></i>
                        Data Kepegawaian
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Divisi dengan Quick Add -->
                        <div>
                            <label for="divisi" class="form-label">
                                Divisi <span class="text-red-500">*</span>
                            </label>
                            <div class="flex gap-2">
                                <select name="divisi" id="divisi" class="form-input @error('divisi') border-red-500 @enderror flex-1" required>
                                    <option value="">-- Pilih Divisi --</option>
                                    @foreach($divisiList as $d)
                                        <option value="{{ $d->nama_divisi }}" {{ old('divisi', $karyawan->divisi) == $d->nama_divisi ? 'selected' : '' }}>
                                            {{ $d->nama_divisi }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="button" onclick="openQuickAddDivisi()" 
                                        class="px-3 py-2 bg-gradient-to-r from-teal-500 to-cyan-600 text-white rounded-lg hover:from-teal-600 hover:to-cyan-700 transition shadow-md whitespace-nowrap flex-shrink-0"
                                        title="Tambah Divisi Cepat">
                                    <i class="fas fa-plus"></i>
                                    <span class="hidden sm:inline ml-1">Baru</span>
                                </button>
                            </div>
                            @error('divisi')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jabatan -->
                        <div>
                            <label for="jabatan" class="form-label">
                                Jabatan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="jabatan" id="jabatan" value="{{ old('jabatan', $karyawan->jabatan) }}" 
                                   class="form-input @error('jabatan') border-red-500 @enderror" 
                                   placeholder="Contoh: Staff, Manager, Supervisor" required>
                            @error('jabatan')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="form-label">
                                Status Karyawan <span class="text-red-500">*</span>
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

                        <!-- Aktif Dari -->
                        <div>
                            <label for="aktif_dari" class="form-label">
                                Aktif Dari <span class="text-red-500">*</span>
                            </label>
                            <input type="date" name="aktif_dari" id="aktif_dari" value="{{ old('aktif_dari', $karyawan->aktif_dari ? $karyawan->aktif_dari->format('Y-m-d') : '') }}" 
                                   class="form-input @error('aktif_dari') border-red-500 @enderror" required>
                            @error('aktif_dari')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Aktif Sampai -->
                        <div class="md:col-span-2">
                            <label for="aktif_sampai" class="form-label">
                                Aktif Sampai (Opsional, kosongkan jika permanen)
                            </label>
                            <input type="date" name="aktif_sampai" id="aktif_sampai" value="{{ old('aktif_sampai', $karyawan->aktif_sampai ? $karyawan->aktif_sampai->format('Y-m-d') : '') }}" 
                                   class="form-input @error('aktif_sampai') border-red-500 @enderror">
                            <p class="text-xs text-gray-500 mt-1">Kosongkan jika karyawan tidak memiliki masa kontrak berakhir</p>
                            @error('aktif_sampai')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- SECTION: Dokumen Karyawan -->
                <div>
                    <h4 class="text-lg font-bold text-gray-800 mb-4 flex items-center border-b pb-2">
                        <i class="fas fa-file-alt mr-2 text-teal-600"></i>
                        Dokumen Karyawan (Opsional)
                    </h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- Foto KTP -->
                        <div>
                            <label class="form-label">
                                <i class="fas fa-id-card mr-2 text-teal-600"></i>Foto KTP
                            </label>
                            
                            @if($karyawan->foto_ktp)
                            <div class="mb-3 relative">
                                <img src="{{ asset('storage/' . $karyawan->foto_ktp) }}" class="w-full h-40 object-cover rounded-lg border-2 border-teal-200">
                                <div class="absolute top-2 right-2">
                                    <a href="{{ asset('storage/' . $karyawan->foto_ktp) }}" target="_blank" class="bg-white px-2 py-1 rounded-lg shadow text-xs text-teal-600 hover:text-teal-700">
                                        <i class="fas fa-eye"></i> Lihat
                                    </a>
                                </div>
                            </div>
                            @endif
                            
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-4 hover:border-teal-400 transition cursor-pointer" onclick="document.getElementById('foto_ktp').click()">
                                <input type="file" name="foto_ktp" id="foto_ktp" accept="image/*" onchange="previewDokumen(event, 'previewKTP', 'filenameKTP')" class="hidden">
                                <div class="text-center">
                                    <img id="previewKTP" src="" class="hidden mx-auto mb-3 w-full h-32 object-contain rounded-lg">
                                    <i class="fas fa-id-card text-4xl text-gray-400 mb-2"></i>
                                    <p class="text-sm text-gray-600 font-medium">{{ $karyawan->foto_ktp ? 'Ganti' : 'Upload' }} Foto KTP</p>
                                    <p class="text-xs text-gray-400 mt-1" id="filenameKTP">JPG, PNG (Max: 2MB)</p>
                                </div>
                            </div>
                            @error('foto_ktp')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Foto NPWP -->
                        <div>
                            <label class="form-label">
                                <i class="fas fa-file-invoice mr-2 text-teal-600"></i>Foto NPWP
                            </label>
                            
                            @if($karyawan->foto_npwp)
                            <div class="mb-3 relative">
                                <img src="{{ asset('storage/' . $karyawan->foto_npwp) }}" class="w-full h-40 object-cover rounded-lg border-2 border-teal-200">
                                <div class="absolute top-2 right-2">
                                    <a href="{{ asset('storage/' . $karyawan->foto_npwp) }}" target="_blank" class="bg-white px-2 py-1 rounded-lg shadow text-xs text-teal-600 hover:text-teal-700">
                                        <i class="fas fa-eye"></i> Lihat
                                    </a>
                                </div>
                            </div>
                            @endif
                            
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-4 hover:border-teal-400 transition cursor-pointer" onclick="document.getElementById('foto_npwp').click()">
                                <input type="file" name="foto_npwp" id="foto_npwp" accept="image/*" onchange="previewDokumen(event, 'previewNPWP', 'filenameNPWP')" class="hidden">
                                <div class="text-center">
                                    <img id="previewNPWP" src="" class="hidden mx-auto mb-3 w-full h-32 object-contain rounded-lg">
                                    <i class="fas fa-file-invoice text-4xl text-gray-400 mb-2"></i>
                                    <p class="text-sm text-gray-600 font-medium">{{ $karyawan->foto_npwp ? 'Ganti' : 'Upload' }} Foto NPWP</p>
                                    <p class="text-xs text-gray-400 mt-1" id="filenameNPWP">JPG, PNG (Max: 2MB)</p>
                                </div>
                            </div>
                            @error('foto_npwp')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Foto BPJS -->
                        <div>
                            <label class="form-label">
                                <i class="fas fa-hospital mr-2 text-teal-600"></i>Foto BPJS
                            </label>
                            
                            @if($karyawan->foto_bpjs)
                            <div class="mb-3 relative">
                                <img src="{{ asset('storage/' . $karyawan->foto_bpjs) }}" class="w-full h-40 object-cover rounded-lg border-2 border-teal-200">
                                <div class="absolute top-2 right-2">
                                    <a href="{{ asset('storage/' . $karyawan->foto_bpjs) }}" target="_blank" class="bg-white px-2 py-1 rounded-lg shadow text-xs text-teal-600 hover:text-teal-700">
                                        <i class="fas fa-eye"></i> Lihat
                                    </a>
                                </div>
                            </div>
                            @endif
                            
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-4 hover:border-teal-400 transition cursor-pointer" onclick="document.getElementById('foto_bpjs').click()">
                                <input type="file" name="foto_bpjs" id="foto_bpjs" accept="image/*" onchange="previewDokumen(event, 'previewBPJS', 'filenameBPJS')" class="hidden">
                                <div class="text-center">
                                    <img id="previewBPJS" src="" class="hidden mx-auto mb-3 w-full h-32 object-contain rounded-lg">
                                    <i class="fas fa-hospital text-4xl text-gray-400 mb-2"></i>
                                    <p class="text-sm text-gray-600 font-medium">{{ $karyawan->foto_bpjs ? 'Ganti' : 'Upload' }} Foto BPJS</p>
                                    <p class="text-xs text-gray-400 mt-1" id="filenameBPJS">JPG, PNG (Max: 2MB)</p>
                                </div>
                            </div>
                            @error('foto_bpjs')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Dokumen Kontrak -->
                        <div>
                            <label class="form-label">
                                <i class="fas fa-file-contract mr-2 text-teal-600"></i>Dokumen Kontrak Kerja
                            </label>
                            
                            @if($karyawan->dokumen_kontrak)
                            <div class="mb-3 p-3 bg-teal-50 border border-teal-200 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        @php
                                            $extension = pathinfo($karyawan->dokumen_kontrak, PATHINFO_EXTENSION);
                                        @endphp
                                        @if(in_array($extension, ['jpg', 'jpeg', 'png']))
                                            <i class="fas fa-image text-teal-600 mr-2"></i>
                                        @elseif($extension == 'pdf')
                                            <i class="fas fa-file-pdf text-red-600 mr-2"></i>
                                        @else
                                            <i class="fas fa-file-word text-blue-600 mr-2"></i>
                                        @endif
                                        <span class="text-sm text-gray-700">Dokumen tersimpan</span>
                                    </div>
                                    <a href="{{ asset('storage/' . $karyawan->dokumen_kontrak) }}" target="_blank" class="text-xs text-teal-600 hover:text-teal-700 font-semibold">
                                        <i class="fas fa-download"></i> Unduh
                                    </a>
                                </div>
                            </div>
                            @endif
                            
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-4 hover:border-teal-400 transition cursor-pointer" onclick="document.getElementById('dokumen_kontrak').click()">
                                <input type="file" name="dokumen_kontrak" id="dokumen_kontrak" accept=".pdf,.doc,.docx,image/*" onchange="previewDokumen(event, 'previewKontrak', 'filenameKontrak', true)" class="hidden">
                                <div class="text-center">
                                    <img id="previewKontrak" src="" class="hidden mx-auto mb-3 w-full h-32 object-contain rounded-lg">
                                    <i class="fas fa-file-contract text-4xl text-gray-400 mb-2"></i>
                                    <p class="text-sm text-gray-600 font-medium">{{ $karyawan->dokumen_kontrak ? 'Ganti' : 'Upload' }} Kontrak Kerja</p>
                                    <p class="text-xs text-gray-400 mt-1" id="filenameKontrak">PDF, DOC, DOCX, JPG, PNG (Max: 5MB)</p>
                                </div>
                            </div>
                            @error('dokumen_kontrak')
                                <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
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

<!-- Modal Quick Add Divisi -->
<div id="quickAddDivisiModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-xl max-w-md w-full" onclick="event.stopPropagation()">
        <div class="p-4 sm:p-6 border-b border-gray-200">
            <h3 class="text-lg sm:text-xl font-bold text-gray-900">Tambah Divisi Cepat</h3>
        </div>
        <form id="quickAddDivisiForm">
            <div class="p-4 sm:p-6 space-y-4">
                <div>
                    <label class="form-label text-sm">Nama Divisi <span class="text-red-500">*</span></label>
                    <input type="text" id="quick_divisi_name" class="form-input text-sm" placeholder="Contoh: IT, HR, Marketing" required>
                </div>
                <div>
                    <label class="form-label text-sm">Deskripsi</label>
                    <textarea id="quick_divisi_desc" rows="2" class="form-input text-sm" placeholder="Deskripsi singkat"></textarea>
                </div>
            </div<div class="p-4 sm:p-6 bg-gray-50 rounded-b-xl flex items-center justify-end space-x-3">
                <button type="button" onclick="closeQuickAddDivisi()" class="px-4 py-2 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition text-sm">
                    Batal
                </button>
                <button type="submit" class="px-4 py-2 bg-gradient-to-r from-teal-500 to-cyan-600 text-white font-semibold rounded-lg hover:from-teal-600 hover:to-cyan-700 transition shadow-md text-sm">
                    <i class="fas fa-save mr-2"></i>Simpan & Gunakan
                </button>
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
                    icon.className = 'fas fa-file-pdf text-4xl text-red-500 mb-2';
                } else if (file.type.includes('word') || file.name.endsWith('.doc') || file.name.endsWith('.docx')) {
                    icon.className = 'fas fa-file-word text-4xl text-blue-500 mb-2';
                }
            }
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
                    status: 'aktif'
                })
            });
            
            if (response.ok) {
                const select = document.getElementById('divisi');
                const option = new Option(namaDivisi, namaDivisi, true, true);
                select.add(option);
                
                closeQuickAddDivisi();
                alert('✅ Divisi "' + namaDivisi + '" berhasil ditambahkan dan sudah dipilih!');
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