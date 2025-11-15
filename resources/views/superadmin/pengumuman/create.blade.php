<!-- Redesigned Blade Template: Tambah Pengumuman (Teal-Cyan Theme) -->
@extends('layouts.app')

@section('title', 'Tambah Pengumuman')
@section('page-title', 'Tambah Pengumuman')

@section('content')
<div class="max-w-7xl mx-auto space-y-5">

    <!-- Back Button -->
    <div class="flex items-center mb-4">
        <a href="{{ route('superadmin.pengumuman.index') }}"
           class="inline-flex items-center gap-2 text-gray-700 hover:text-teal-600 font-semibold transition">
            <i class="fas fa-arrow-left text-lg"></i>
            <span>Kembali</span>
        </a>
    </div>

    <!-- Main Container -->
    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100">

        <!-- Header -->
        <div class="px-6 py-5 bg-gradient-to-r from-teal-600 to-cyan-600">
            <h2 class="text-xl font-extrabold text-white tracking-wide flex items-center gap-3">
                <i class="fas fa-bullhorn text-3xl"></i>
                Tambah Pengumuman Baru
            </h2>
            <p class="text-white/80 text-sm mt-2">Isi data pengumuman secara lengkap untuk dipublikasikan.</p>
        </div>

        <!-- Form Body -->
        <form action="{{ route('superadmin.pengumuman.store') }}" method="POST" class="p-4">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-10 gap-10">

                <!-- LEFT SECTION: INPUT FORM -->
                <div class="lg:col-span-6 space-y-5">

                    <!-- Judul -->
                    <div class="space-y-2">
                        <label class="block font-semibold text-gray-700">Judul Pengumuman <span class="text-red-500">*</span></label>
                        <input type="text" id="judul" name="judul"
                               placeholder="Contoh: Informasi Libur Nasional, Maintenance Sistem, dll"
                               value="{{ old('judul') }}"
                               class="w-full px-3 py-2 rounded-xl border-gray-300 shadow-sm focus:ring-2 focus:ring-teal-500 focus:outline-none @error('judul') border-red-500 @enderror" required>
                        @error('judul')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500"><span id="judulCount">0</span>/255 karakter</p>
                    </div>

                    <!-- Tanggal -->
                    <div class="space-y-2">
                        <label class="block font-semibold text-gray-700">Tanggal Pengumuman <span class="text-red-500">*</span></label>
                        <input type="date" id="tanggal" name="tanggal"
                               value="{{ old('tanggal', date('Y-m-d')) }}"
                               class="w-full px-3 py-2 rounded-xl border-gray-300 shadow-sm focus:ring-2 focus:ring-teal-500 @error('tanggal') border-red-500 @enderror" required>
                        @error('tanggal')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Konten -->
                    <div class="space-y-2">
                        <label class="block font-semibold text-gray-700">Konten Pengumuman <span class="text-red-500">*</span></label>
                        <textarea id="konten" name="konten" rows="8"
                                  placeholder="Tulis isi pengumuman..."
                                  class="w-full px-3 py-2 rounded-xl border-gray-300 shadow-sm focus:ring-2 focus:ring-teal-500 resize-none @error('konten') border-red-500 @enderror" required>{{ old('konten') }}</textarea>
                        @error('konten')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500"><span id="kontenCount">0</span> karakter</p>
                    </div>

                </div>

                <!-- RIGHT SECTION: PREVIEW + TIPS -->
                <div class="lg:col-span-4 space-y-5">

                    <!-- Tips Card -->
                    <div class="bg-gradient-to-br from-teal-50 to-cyan-50 border border-teal-200 rounded-2xl p-4 shadow-sm mb-4">
                        <h3 class="font-bold text-gray-800 text-lg mb-4 flex items-center gap-3">
                            <i class="fas fa-lightbulb text-teal-600 text-xl"></i>
                            Tips Membuat Pengumuman
                        </h3>
                        <ul class="space-y-3 text-gray-700 text-sm">
                            <li class="flex gap-3"><i class="fas fa-check-circle text-teal-600 mt-1"></i> Gunakan judul yang jelas & spesifik</li>
                            <li class="flex gap-3"><i class="fas fa-check-circle text-teal-600 mt-1"></i> Cantumkan informasi penting secara ringkas</li>
                            <li class="flex gap-3"><i class="fas fa-check-circle text-teal-600 mt-1"></i> Tulis tanggal, waktu, serta detail kegiatan</li>
                            <li class="flex gap-3"><i class="fas fa-check-circle text-teal-600 mt-1"></i> Gunakan bahasa sederhana dan mudah dipahami</li>
                        </ul>
                    </div>

                    <!-- Preview Card -->
                    <div class="bg-white border border-gray-200 rounded-2xl p-4 shadow-sm mt-4">
                        <h3 class="font-bold text-gray-800 text-lg mb-4 flex items-center gap-2">
                            <i class="fas fa-eye text-teal-600"></i> Live Preview
                        </h3>

                        <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4 space-y-4 shadow-inner">

                            <!-- Header Preview -->
                            <div class="flex items-start gap-3">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-teal-500 to-cyan-500 flex items-center justify-center text-white shadow-md">
                                    <i class="fas fa-bullhorn"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 id="previewJudul" class="text-gray-900 font-bold truncate">(Judul akan tampil di sini)</h4>
                                    <p id="previewTanggal" class="text-xs text-gray-500 mt-1">
                                        <i class="fas fa-calendar mr-1"></i>{{ date('d M Y') }}
                                    </p>
                                </div>
                            </div>

                            <!-- Content Preview -->
                            <div class="bg-white border border-gray-200 rounded-xl p-4 text-sm text-gray-700 leading-relaxed whitespace-pre-wrap shadow-sm">
                                <p id="previewKonten">(Konten akan tampil di sini)</p>
                            </div>

                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-4">
                        <button type="submit"
                            class="w-full py-3 rounded-xl bg-gradient-to-r from-teal-600 to-cyan-600 hover:from-teal-700 hover:to-cyan-700 text-white font-bold shadow-lg hover:shadow-xl transition flex items-center justify-center gap-2">
                            <i class="fas fa-paper-plane"></i> Publikasikan
                        </button>

                        <a href="{{ route('superadmin.pengumuman.index') }}"
                           class="w-full block py-3 rounded-xl bg-gray-200 hover:bg-gray-300 text-center text-gray-700 font-semibold shadow-sm transition">
                           <i class="fas fa-times mr-1"></i> Batal
                        </a>
                    </div>

                </div>

            </div>
        </form>

    </div>
</div>
@endsection

@push('scripts')
<script>
    const judul = document.getElementById('judul');
    const konten = document.getElementById('konten');
    const tanggal = document.getElementById('tanggal');

    const judulCount = document.getElementById('judulCount');
    const kontenCount = document.getElementById('kontenCount');

    const previewJudul = document.getElementById('previewJudul');
    const previewKonten = document.getElementById('previewKonten');
    const previewTanggal = document.getElementById('previewTanggal');

    // Live update Judul
    judul.addEventListener('input', () => {
        judulCount.textContent = judul.value.length;
        previewJudul.textContent = judul.value || '(Judul akan tampil di sini)';
    });

    // Live update Konten
    konten.addEventListener('input', () => {
        kontenCount.textContent = konten.value.length;
        previewKonten.textContent = konten.value || '(Konten akan tampil di sini)';
    });

    // Update Tanggal
    tanggal.addEventListener('change', () => {
        const t = new Date(tanggal.value);
        const options = { day: 'numeric', month: 'long', year: 'numeric' };
        previewTanggal.innerHTML = `<i class="fas fa-calendar mr-1"></i>${t.toLocaleDateString('id-ID', options)}`;
    });

    // Initialize
    judulCount.textContent = judul.value.length;
    kontenCount.textContent = konten.value.length;
</script>
@endpush
