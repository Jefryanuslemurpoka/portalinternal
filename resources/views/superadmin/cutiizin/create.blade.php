@extends('layouts.app')

@section('title', 'Tambah Pengajuan Cuti/Izin')
@section('page-title', 'Tambah Pengajuan Cuti/Izin')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 bg-gradient-to-r from-teal-500 to-cyan-600 border-b">
            <h2 class="text-xl font-bold text-white flex items-center">
                <i class="fas fa-calendar-plus mr-3"></i>
                Form Pengajuan Cuti/Izin
            </h2>
        </div>

        <!-- Form -->
        <form action="{{ route('superadmin.cutiizin.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf

            <!-- Pilih Karyawan -->
            <div>
                <label for="user_id" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-user text-teal-600 mr-2"></i>Karyawan <span class="text-red-500">*</span>
                </label>
                <select name="user_id" id="user_id" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-400 transition @error('user_id') border-red-500 @enderror">
                    <option value="">-- Pilih Karyawan --</option>
                    @foreach($karyawan as $k)
                        <option value="{{ $k->id }}" {{ old('user_id') == $k->id ? 'selected' : '' }}>
                            {{ $k->name }} - {{ $k->divisi }}
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <p class="text-red-500 text-xs mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                @enderror
            </div>

            <!-- Jenis -->
            <div>
                <label for="jenis" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-clipboard-list text-teal-600 mr-2"></i>Jenis <span class="text-red-500">*</span>
                </label>
                <select name="jenis" id="jenis" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-400 transition @error('jenis') border-red-500 @enderror">
                    <option value="">-- Pilih Jenis --</option>
                    <option value="cuti" {{ old('jenis') == 'cuti' ? 'selected' : '' }}>Cuti</option>
                    <option value="izin" {{ old('jenis') == 'izin' ? 'selected' : '' }}>Izin</option>
                    <option value="sakit" {{ old('jenis') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                </select>
                @error('jenis')
                    <p class="text-red-500 text-xs mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                @enderror
            </div>

            <!-- Tanggal -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="tanggal_mulai" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-calendar-day text-teal-600 mr-2"></i>Tanggal Mulai <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-400 transition @error('tanggal_mulai') border-red-500 @enderror">
                    @error('tanggal_mulai')
                        <p class="text-red-500 text-xs mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tanggal_selesai" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-calendar-check text-teal-600 mr-2"></i>Tanggal Selesai <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal_selesai" id="tanggal_selesai" value="{{ old('tanggal_selesai') }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-400 transition @error('tanggal_selesai') border-red-500 @enderror">
                    @error('tanggal_selesai')
                        <p class="text-red-500 text-xs mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Info Durasi -->
            <div id="durasiInfo" class="hidden bg-teal-50 border border-teal-200 rounded-lg p-4">
                <p class="text-sm text-teal-800">
                    <i class="fas fa-info-circle mr-2"></i>
                    Durasi: <span id="durasiText" class="font-semibold"></span>
                </p>
            </div>

            <!-- Alasan -->
            <div>
                <label for="alasan" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-comment-alt text-teal-600 mr-2"></i>Alasan <span class="text-red-500">*</span>
                </label>
                <textarea name="alasan" id="alasan" rows="4" required
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-400 transition @error('alasan') border-red-500 @enderror"
                          placeholder="Jelaskan alasan pengajuan cuti/izin...">{{ old('alasan') }}</textarea>
                @error('alasan')
                    <p class="text-red-500 text-xs mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                @enderror
            </div>

            <!-- Dokumen Pendukung -->
            <div>
                <label for="dokumen" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-file-upload text-teal-600 mr-2"></i>Dokumen Pendukung (Opsional)
                </label>
                <input type="file" name="dokumen" id="dokumen" accept=".pdf,.jpg,.jpeg,.png"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-400 transition @error('dokumen') border-red-500 @enderror">
                <p class="text-xs text-gray-500 mt-1">
                    <i class="fas fa-info-circle mr-1"></i>
                    Format: PDF, JPG, JPEG, PNG (Maks. 2MB)
                </p>
                @error('dokumen')
                    <p class="text-red-500 text-xs mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                @enderror
            </div>

            <!-- Preview Dokumen -->
            <div id="previewDokumen" class="hidden bg-gray-50 border border-gray-200 rounded-lg p-4">
                <p class="text-sm text-gray-700 mb-2">
                    <i class="fas fa-eye mr-2"></i>Preview Dokumen:
                </p>
                <div id="previewContent"></div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-3 pt-4 border-t">
                <a href="{{ route('superadmin.cutiizin.index') }}" 
                   class="px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-gradient-to-r from-teal-500 to-cyan-600 text-white font-semibold rounded-lg hover:from-teal-600 hover:to-cyan-700 transition shadow-md">
                    <i class="fas fa-save mr-2"></i>Simpan Pengajuan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Hitung durasi otomatis
    const tanggalMulai = document.getElementById('tanggal_mulai');
    const tanggalSelesai = document.getElementById('tanggal_selesai');
    const durasiInfo = document.getElementById('durasiInfo');
    const durasiText = document.getElementById('durasiText');

    function hitungDurasi() {
        if (tanggalMulai.value && tanggalSelesai.value) {
            const start = new Date(tanggalMulai.value);
            const end = new Date(tanggalSelesai.value);
            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
            
            durasiText.textContent = diffDays + ' hari';
            durasiInfo.classList.remove('hidden');
        } else {
            durasiInfo.classList.add('hidden');
        }
    }

    tanggalMulai.addEventListener('change', hitungDurasi);
    tanggalSelesai.addEventListener('change', hitungDurasi);

    // Preview dokumen
    document.getElementById('dokumen').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('previewDokumen');
        const previewContent = document.getElementById('previewContent');
        
        if (file) {
            preview.classList.remove('hidden');
            
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewContent.innerHTML = `<img src="${e.target.result}" class="max-w-xs rounded-lg border border-gray-300">`;
                };
                reader.readAsDataURL(file);
            } else if (file.type === 'application/pdf') {
                previewContent.innerHTML = `
                    <div class="flex items-center space-x-3 bg-white p-3 rounded-lg border border-gray-300">
                        <i class="fas fa-file-pdf text-red-500 text-2xl"></i>
                        <div>
                            <p class="font-semibold text-gray-900">${file.name}</p>
                            <p class="text-xs text-gray-500">${(file.size / 1024).toFixed(2)} KB</p>
                        </div>
                    </div>
                `;
            }
        } else {
            preview.classList.add('hidden');
        }
    });
</script>
@endpush