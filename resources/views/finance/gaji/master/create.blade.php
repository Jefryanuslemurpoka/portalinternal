@extends('layouts.app')

@section('title', 'Tambah Master Gaji')
@section('page-title', 'Tambah Master Gaji')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Tambah Master Gaji</h2>
            <p class="text-gray-600 text-sm">Setup gaji pokok dan tunjangan tetap untuk karyawan</p>
        </div>
        <a href="{{ route('finance.gaji.master.index') }}" class="text-gray-600 hover:text-gray-800 transition">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-2xl shadow-lg p-8">
        <form action="{{ route('finance.gaji.master.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Pilih Karyawan -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Pilih Karyawan <span class="text-red-500">*</span>
                </label>
                <select name="user_id" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                    <option value="">-- Pilih Karyawan --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} - {{ $user->nik }} 
                            @if($user->jabatan)
                                ({{ $user->jabatan }})
                            @endif
                        </option>
                    @endforeach
                </select>
                @error('user_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Gaji Pokok -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Gaji Pokok <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-4 top-3 text-gray-500">Rp</span>
                    <input type="number" name="gaji_pokok" value="{{ old('gaji_pokok') }}" required min="0" step="1000"
                           class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                           placeholder="5000000">
                </div>
                @error('gaji_pokok')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tunjangan -->
            <div class="bg-blue-50 p-6 rounded-xl">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Tunjangan Tetap</h3>
                <p class="text-sm text-gray-600 mb-4">Tunjangan ini akan otomatis ditambahkan setiap bulan</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Tunjangan Jabatan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tunjangan Jabatan</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3 text-gray-500 text-sm">Rp</span>
                            <input type="number" name="tunjangan_jabatan" value="{{ old('tunjangan_jabatan', 0) }}" min="0" step="1000"
                                   class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                   placeholder="1000000">
                        </div>
                    </div>

                    <!-- Tunjangan Transport -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tunjangan Transport</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3 text-gray-500 text-sm">Rp</span>
                            <input type="number" name="tunjangan_transport" value="{{ old('tunjangan_transport', 0) }}" min="0" step="1000"
                                   class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                   placeholder="500000">
                        </div>
                    </div>

                    <!-- Tunjangan Makan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tunjangan Makan</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3 text-gray-500 text-sm">Rp</span>
                            <input type="number" name="tunjangan_makan" value="{{ old('tunjangan_makan', 0) }}" min="0" step="1000"
                                   class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                   placeholder="500000">
                        </div>
                    </div>

                    <!-- Tunjangan Kehadiran -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tunjangan Kehadiran</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3 text-gray-500 text-sm">Rp</span>
                            <input type="number" name="tunjangan_kehadiran" value="{{ old('tunjangan_kehadiran', 0) }}" min="0" step="1000"
                                   class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                   placeholder="300000">
                        </div>
                    </div>

                    <!-- Tunjangan Kesehatan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tunjangan Kesehatan</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3 text-gray-500 text-sm">Rp</span>
                            <input type="number" name="tunjangan_kesehatan" value="{{ old('tunjangan_kesehatan', 0) }}" min="0" step="1000"
                                   class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                   placeholder="200000">
                        </div>
                    </div>

                    <!-- Tunjangan Lainnya -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tunjangan Lainnya</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3 text-gray-500 text-sm">Rp</span>
                            <input type="number" name="tunjangan_lainnya" value="{{ old('tunjangan_lainnya', 0) }}" min="0" step="1000"
                                   class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                   placeholder="0">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Effective Date -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Berlaku Mulai <span class="text-red-500">*</span>
                </label>
                <input type="date" name="effective_date" value="{{ old('effective_date', date('Y-m-d')) }}" required
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                @error('effective_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Keterangan -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan (Opsional)</label>
                <textarea name="keterangan" rows="3"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                          placeholder="Catatan tambahan...">{{ old('keterangan') }}</textarea>
            </div>

            <!-- Submit Buttons -->
            <div class="flex gap-3 pt-4">
                <button type="submit" class="flex-1 bg-teal-600 hover:bg-teal-700 text-white py-3 rounded-lg font-semibold transition">
                    <i class="fas fa-save mr-2"></i>Simpan Data Gaji
                </button>
                <a href="{{ route('finance.gaji.master.index') }}" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 py-3 rounded-lg font-semibold text-center transition">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
            </div>
        </form>
    </div>

</div>
@endsection