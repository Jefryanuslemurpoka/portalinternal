@extends('layouts.app')

@section('title', 'Generate Slip Gaji')
@section('page-title', 'Generate Slip Gaji Karyawan')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Generate Slip Gaji</h2>
            <p class="text-gray-600 text-sm">Buat slip gaji untuk karyawan</p>
        </div>
        <a href="{{ route('finance.gaji.index') }}" class="text-gray-600 hover:text-gray-800 transition">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <!-- Generate Individual -->
    <div class="bg-white rounded-2xl shadow-lg p-8">
        <div class="flex items-center mb-6">
            <div class="bg-teal-100 p-3 rounded-xl">
                <i class="fas fa-user text-teal-600 text-2xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-xl font-bold text-gray-800">Generate Slip Individual</h3>
                <p class="text-gray-600 text-sm">Buat slip gaji untuk satu karyawan</p>
            </div>
        </div>

        <form action="{{ route('finance.gaji.generate') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Pilih Karyawan -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Pilih Karyawan <span class="text-red-500">*</span>
                    </label>
                    <select name="user_id" id="user_id" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        <option value="">-- Pilih Karyawan --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" 
                                    data-gaji="{{ $user->salary?->gaji_pokok ?? 0 }}"
                                    data-tunjangan="{{ $user->salary?->total_tunjangan ?? 0 }}">
                                {{ $user->name }} - {{ $user->nik }}
                                @if($user->salary)
                                    (Gaji Pokok: Rp {{ number_format($user->salary->gaji_pokok, 0, ',', '.') }})
                                @else
                                    <span class="text-red-500">(Belum ada data gaji)</span>
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tahun -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Tahun <span class="text-red-500">*</span>
                    </label>
                    <select name="tahun" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        @for($y = date('Y'); $y >= date('Y') - 2; $y--)
                            <option value="{{ $y }}" {{ $y == $currentYear ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                    @error('tahun')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Bulan -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Bulan <span class="text-red-500">*</span>
                    </label>
                    <select name="bulan" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        @foreach(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $index => $namaBulan)
                            <option value="{{ $index + 1 }}" {{ ($index + 1) == $currentMonth ? 'selected' : '' }}>
                                {{ $namaBulan }}
                            </option>
                        @endforeach
                    </select>
                    @error('bulan')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Info Box -->
            <div id="salary-info" class="hidden bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="font-semibold text-blue-800 mb-2">Informasi Gaji Karyawan</h4>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-600">Gaji Pokok:</p>
                        <p class="font-semibold text-gray-800" id="info-gaji-pokok">-</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Total Tunjangan:</p>
                        <p class="font-semibold text-gray-800" id="info-tunjangan">-</p>
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex gap-3">
                <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-8 py-3 rounded-lg shadow-lg transition font-semibold">
                    <i class="fas fa-cogs mr-2"></i>Generate Slip Gaji
                </button>
                <a href="{{ route('finance.gaji.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-8 py-3 rounded-lg transition font-semibold">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
            </div>
        </form>
    </div>

    <!-- Generate All -->
    <div class="bg-white rounded-2xl shadow-lg p-8">
        <div class="flex items-center mb-6">
            <div class="bg-purple-100 p-3 rounded-xl">
                <i class="fas fa-users text-purple-600 text-2xl"></i>
            </div>
            <div class="ml-4">
                <h3 class="text-xl font-bold text-gray-800">Generate Slip Massal</h3>
                <p class="text-gray-600 text-sm">Buat slip gaji untuk semua karyawan aktif sekaligus</p>
            </div>
        </div>

        <form action="{{ route('finance.gaji.generate-all') }}" method="POST" class="space-y-6"
              onsubmit="return confirm('Yakin ingin generate slip gaji untuk SEMUA karyawan aktif?')">
            @csrf

            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex">
                    <i class="fas fa-exclamation-triangle text-yellow-600 mt-1 mr-3"></i>
                    <div class="text-sm text-yellow-800">
                        <p class="font-semibold mb-1">Perhatian!</p>
                        <p>Fitur ini akan membuat slip gaji untuk <strong>SEMUA karyawan aktif</strong> pada periode yang dipilih. Pastikan data sudah benar sebelum melanjutkan.</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Tahun -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Tahun <span class="text-red-500">*</span>
                    </label>
                    <select name="tahun" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        @for($y = date('Y'); $y >= date('Y') - 2; $y--)
                            <option value="{{ $y }}" {{ $y == $currentYear ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>

                <!-- Bulan -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Bulan <span class="text-red-500">*</span>
                    </label>
                    <select name="bulan" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        @foreach(['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'] as $index => $namaBulan)
                            <option value="{{ $index + 1 }}" {{ ($index + 1) == $currentMonth ? 'selected' : '' }}>
                                {{ $namaBulan }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Info -->
            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                <div class="flex items-center text-sm text-gray-700">
                    <i class="fas fa-info-circle text-gray-400 mr-3"></i>
                    <p>Total karyawan aktif yang akan diproses: <strong class="text-teal-600">{{ $users->count() }} orang</strong></p>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-8 py-3 rounded-lg shadow-lg transition font-semibold">
                <i class="fas fa-users-cog mr-2"></i>Generate Slip untuk Semua Karyawan
            </button>
        </form>
    </div>

</div>

@push('scripts')
<script>
document.getElementById('user_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const gajiPokok = selectedOption.dataset.gaji || 0;
    const tunjangan = selectedOption.dataset.tunjangan || 0;
    
    const infoBox = document.getElementById('salary-info');
    
    if (this.value && gajiPokok > 0) {
        infoBox.classList.remove('hidden');
        document.getElementById('info-gaji-pokok').textContent = 'Rp ' + parseInt(gajiPokok).toLocaleString('id-ID');
        document.getElementById('info-tunjangan').textContent = 'Rp ' + parseInt(tunjangan).toLocaleString('id-ID');
    } else {
        infoBox.classList.add('hidden');
    }
});
</script>
@endpush
@endsection