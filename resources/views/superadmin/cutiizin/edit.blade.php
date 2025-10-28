@extends('layouts.app')

@section('title', 'Edit Pengajuan Cuti/Izin')
@section('page-title', 'Edit Pengajuan Cuti/Izin')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 bg-gradient-to-r from-teal-500 to-cyan-600 border-b">
            <h2 class="text-xl font-bold text-white flex items-center">
                <i class="fas fa-edit mr-3"></i>
                Edit Pengajuan Cuti/Izin
            </h2>
        </div>

        <!-- Form -->
        <form action="{{ route('superadmin.cutiizin.update', $cutiIzin->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Status Badge -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Status Pengajuan</p>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            {{ $cutiIzin->status == 'pending' ? 'bg-amber-100 text-amber-700' : '' }}
                            {{ $cutiIzin->status == 'disetujui' ? 'bg-teal-100 text-teal-700' : '' }}
                            {{ $cutiIzin->status == 'ditolak' ? 'bg-red-100 text-red-700' : '' }}">
                            {{ ucfirst($cutiIzin->status) }}
                        </span>
                    </div>
                    @if($cutiIzin->status != 'pending')
                    <div class="text-sm text-gray-600">
                        <p>Diproses oleh: <span class="font-semibold">{{ $cutiIzin->approver->name ?? '-' }}</span></p>
                        <p class="text-xs">{{ $cutiIzin->approved_at ? $cutiIzin->approved_at->format('d M Y H:i') : '-' }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Pilih Karyawan -->
            <div>
                <label for="user_id" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-user text-teal-600 mr-2"></i>Karyawan <span class="text-red-500">*</span>
                </label>
                <select name="user_id" id="user_id" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-400 transition @error('user_id') border-red-500 @enderror">
                    <option value="">-- Pilih Karyawan --</option>
                    @foreach($karyawan as $k)
                        <option value="{{ $k->id }}" {{ old('user_id', $cutiIzin->user_id) == $k->id ? 'selected' : '' }}>
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
                    <option value="cuti" {{ old('jenis', $cutiIzin->jenis) == 'cuti' ? 'selected' : '' }}>Cuti</option>
                    <option value="izin" {{ old('jenis', $cutiIzin->jenis) == 'izin' ? 'selected' : '' }}>Izin</option>
                    <option value="sakit" {{ old('jenis', $cutiIzin->jenis) == 'sakit' ? 'selected' : '' }}>Sakit</option>
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
                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" 
                           value="{{ old('tanggal_mulai', $cutiIzin->tanggal_mulai->format('Y-m-d')) }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-400 transition @error('tanggal_mulai') border-red-500 @enderror">
                    @error('tanggal_mulai')
                        <p class="text-red-500 text-xs mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tanggal_selesai" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-calendar-check text-teal-600 mr-2"></i>Tanggal Selesai <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal_selesai" id="tanggal_selesai" 
                           value="{{ old('tanggal_selesai', $cutiIzin->tanggal_selesai->format('Y-m-d')) }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-400 transition @error('tanggal_selesai') border-red-500 @enderror">
                    @error('tanggal_selesai')
                        <p class="text-red-500 text-xs mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Info Durasi -->
            <div id="durasiInfo" class="bg-teal-50 border border-teal-200 rounded-lg p-4">
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
                          placeholder="Jelaskan alasan pengajuan cuti/izin...">{{ old('alasan', $cutiIzin->alasan) }}</textarea>
                @error('alasan')
                    <p class="text-red-500 text-xs mt-1"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                @enderror
            </div>

            <!-- Dokumen Lama -->
            @if($cutiIzin->dokumen)
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <p class="text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-file text-teal-600 mr-2"></i>Dokumen Saat Ini
                </p>
                <div class="flex items-center justify-between">
                    <a href="{{ asset('storage/' . $cutiIzin->dokumen) }}" target="_blank" 
                       class="text-teal-600 hover:text-teal-700 text-sm flex items-center">
                        <i class="fas fa-download mr-2"></i>
                        Lihat Dokumen
                    </a>
                    <label class="flex items-center space-x-2 text-sm text-gray-600">
                        <input type="checkbox" name="hapus_dokumen" value="1" class="rounded">
                        <span>Hapus dokumen ini</span>
                    </label>
                </div>
            </div>
            @endif

            <!-- Dokumen Pendukung -->
            <div>
                <label for="dokumen" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-file-upload text-teal-600 mr-2"></i>
                    {{ $cutiIzin->dokumen ? 'Ganti Dokumen (Opsional)' : 'Dokumen Pendukung (Opsional)' }}
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

            <!-- Preview Dokumen Baru -->
            <div id="previewDokumen" class="hidden bg-gray-50 border border-gray-200 rounded-lg p-4">
                <p class="text-sm text-gray-700 mb-2">
                    <i class="fas fa-eye mr-2"></i>Preview Dokumen Baru:
                </p>
                <div id="previewContent"></div>
            </div>

            <!-- Keterangan Approval (jika ada) -->
            @if($cutiIzin->keterangan_approval)
            <div class="bg-{{ $cutiIzin->status == 'disetujui' ? 'teal' : 'red' }}-50 border border-{{ $cutiIzin->status == 'disetujui' ? 'teal' : 'red' }}-200 rounded-lg p-4">
                <p class="text-sm font-semibold text-{{ $cutiIzin->status == 'disetujui' ? 'teal' : 'red' }}-800 mb-2">
                    <i class="fas fa-comment-dots mr-2"></i>Keterangan {{ ucfirst($cutiIzin->status) }}
                </p>
                <p class="text-sm text-{{ $cutiIzin->status == 'disetujui' ? 'teal' : 'red' }}-700">
                    {{ $cutiIzin->keterangan_approval }}
                </p>
            </div>
            @endif

            <!-- Buttons -->
            <div class="flex justify-end space-x-3 pt-4 border-t">
                <a href="{{ route('superadmin.cutiizin.index') }}" 
                   class="px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-gradient-to-r from-teal-500 to-cyan-600 text-white font-semibold rounded-lg hover:from-teal-600 hover:to-cyan-700 transition shadow-md">
                    <i class="fas fa-save mr-2"></i>Update Pengajuan
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
        }
    }

    // Hitung durasi saat pertama load
    hitungDurasi();

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