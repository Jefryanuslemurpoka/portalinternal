@extends('layouts.app')

@section('title', 'Edit Master Gaji')
@section('page-title', 'Edit Master Gaji')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Edit Master Gaji</h2>
            <p class="text-gray-600 text-sm">Edit gaji untuk: <strong>{{ $salary->user->name }}</strong></p>
        </div>
        <a href="{{ route('finance.gaji.master.index') }}" class="text-gray-600 hover:text-gray-800 transition">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-2xl shadow-lg p-8">
        <form action="{{ route('finance.gaji.master.update', $salary->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Info Karyawan -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <div class="flex items-center">
                    <div class="h-12 w-12 rounded-full bg-teal-100 flex items-center justify-center">
                        <span class="text-teal-600 font-semibold text-lg">{{ substr($salary->user->name, 0, 2) }}</span>
                    </div>
                    <div class="ml-4">
                        <div class="font-semibold text-gray-900">{{ $salary->user->name }}</div>
                        <div class="text-sm text-gray-600">{{ $salary->user->nik }} - {{ $salary->user->jabatan }}</div>
                    </div>
                </div>
            </div>

            <!-- Gaji Pokok -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">
                    Gaji Pokok <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-4 top-3 text-gray-500">Rp</span>
                    <input type="number" name="gaji_pokok" value="{{ old('gaji_pokok', $salary->gaji_pokok) }}" required min="0" step="1000"
                           class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                </div>
                @error('gaji_pokok')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tunjangan -->
            <div class="bg-blue-50 p-6 rounded-xl">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Tunjangan Tetap</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Tunjangan Jabatan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tunjangan Jabatan</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3 text-gray-500 text-sm">Rp</span>
                            <input type="number" name="tunjangan_jabatan" value="{{ old('tunjangan_jabatan', $salary->tunjangan_jabatan) }}" min="0" step="1000"
                                   class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <!-- Tunjangan Transport -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tunjangan Transport</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3 text-gray-500 text-sm">Rp</span>
                            <input type="number" name="tunjangan_transport" value="{{ old('tunjangan_transport', $salary->tunjangan_transport) }}" min="0" step="1000"
                                   class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <!-- Tunjangan Makan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tunjangan Makan</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3 text-gray-500 text-sm">Rp</span>
                            <input type="number" name="tunjangan_makan" value="{{ old('tunjangan_makan', $salary->tunjangan_makan) }}" min="0" step="1000"
                                   class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <!-- Tunjangan Kehadiran -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tunjangan Kehadiran</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3 text-gray-500 text-sm">Rp</span>
                            <input type="number" name="tunjangan_kehadiran" value="{{ old('tunjangan_kehadiran', $salary->tunjangan_kehadiran) }}" min="0" step="1000"
                                   class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <!-- Tunjangan Kesehatan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tunjangan Kesehatan</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3 text-gray-500 text-sm">Rp</span>
                            <input type="number" name="tunjangan_kesehatan" value="{{ old('tunjangan_kesehatan', $salary->tunjangan_kesehatan) }}" min="0" step="1000"
                                   class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>

                    <!-- Tunjangan Lainnya -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tunjangan Lainnya</label>
                        <div class="relative">
                            <span class="absolute left-4 top-3 text-gray-500 text-sm">Rp</span>
                            <input type="number" name="tunjangan_lainnya" value="{{ old('tunjangan_lainnya', $salary->tunjangan_lainnya) }}" min="0" step="1000"
                                   class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Keterangan -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan Perubahan (Opsional)</label>
                <textarea name="keterangan" rows="3"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent"
                          placeholder="Alasan perubahan gaji...">{{ old('keterangan', $salary->keterangan) }}</textarea>
            </div>

            <!-- Submit Buttons -->
            <div class="flex gap-3 pt-4">
                <button type="submit" class="flex-1 bg-teal-600 hover:bg-teal-700 text-white py-3 rounded-lg font-semibold transition">
                    <i class="fas fa-save mr-2"></i>Update Data Gaji
                </button>
                <a href="{{ route('finance.gaji.master.index') }}" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 py-3 rounded-lg font-semibold text-center transition">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
            </div>
        </form>
    </div>

    <!-- History Perubahan -->
    @if($salary->histories && $salary->histories->count() > 0)
    <div class="bg-white rounded-2xl shadow-lg p-8">
        <h3 class="text-lg font-bold text-gray-800 mb-4">
            <i class="fas fa-history text-gray-600 mr-2"></i>Histori Perubahan Gaji
        </h3>
        <div class="space-y-3">
            @foreach($salary->histories->take(5) as $history)
                <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-lg">
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                            <i class="fas fa-pencil-alt text-blue-600"></i>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $history->field_changed }}</p>
                                <p class="text-sm text-gray-600">
                                    Dari: Rp {{ number_format($history->old_value, 0, ',', '.') }} 
                                    → Rp {{ number_format($history->new_value, 0, ',', '.') }}
                                </p>
                                @if($history->reason)
                                    <p class="text-sm text-gray-500 mt-1">Alasan: {{ $history->reason }}</p>
                                @endif
                            </div>
                            <div class="text-right">
                                <p class="text-xs text-gray-500">{{ $history->created_at->format('d M Y') }}</p>
                                <p class="text-xs text-gray-400">oleh: {{ $history->changedBy->name ?? 'System' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>

@endsection