@extends('layouts.app')

@section('title', 'Detail Slip Gaji')
@section('page-title', 'Detail Slip Gaji')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex flex-col lg:flex-row lg:justify-between lg:items-start gap-4">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <h2 class="text-2xl font-bold text-gray-800">Detail Slip Gaji</h2>
                <span class="px-4 py-1 text-sm font-semibold rounded-full {{ $slip->status_badge }}">
                    {{ $slip->status_label }}
                </span>
            </div>
            <p class="text-gray-600 text-sm">{{ $slip->slip_number }}</p>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('finance.gaji.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-2 rounded-lg transition">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </a>
            @if($slip->canEdit())
                <a href="{{ route('finance.gaji.edit', $slip->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
            @endif
            <a href="{{ route('finance.gaji.pdf', $slip->id) }}" target="_blank" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition">
                <i class="fas fa-file-pdf mr-2"></i>Download PDF
            </a>
            
            @if($slip->isDraft())
                <form action="{{ route('finance.gaji.submit', $slip->id) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition"
                            onclick="return confirm('Kirim slip ini untuk persetujuan?')">
                        <i class="fas fa-paper-plane mr-2"></i>Submit untuk Approval
                    </button>
                </form>
            @endif

            @if($slip->isPending() && auth()->user()->role == 'super_admin')
                <button onclick="openApprovalModal()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-check mr-2"></i>Approve
                </button>
                <button onclick="openRejectModal()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-times mr-2"></i>Reject
                </button>
            @endif

            @if($slip->isApproved() && auth()->user()->role == 'super_admin')
                <button onclick="openPaymentModal()" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-money-bill-wave mr-2"></i>Tandai Sudah Dibayar
                </button>
            @endif
        </div>
    </div>

    <!-- Info Karyawan & Periode -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Info Karyawan -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center mb-4">
                <div class="bg-teal-100 p-3 rounded-xl">
                    <i class="fas fa-user text-teal-600 text-2xl"></i>
                </div>
                <h3 class="ml-3 text-lg font-bold text-gray-800">Informasi Karyawan</h3>
            </div>
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-gray-500">Nama Lengkap</p>
                    <p class="font-semibold text-gray-800">{{ $slip->user->name }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">NIK</p>
                    <p class="font-semibold text-gray-800">{{ $slip->user->nik }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Divisi</p>
                    <p class="font-semibold text-gray-800">{{ $slip->user->divisi ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Jabatan</p>
                    <p class="font-semibold text-gray-800">{{ $slip->user->jabatan ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Info Periode -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center mb-4">
                <div class="bg-blue-100 p-3 rounded-xl">
                    <i class="fas fa-calendar text-blue-600 text-2xl"></i>
                </div>
                <h3 class="ml-3 text-lg font-bold text-gray-800">Periode Penggajian</h3>
            </div>
            <div class="space-y-3">
                <div>
                    <p class="text-xs text-gray-500">Bulan/Tahun</p>
                    <p class="font-semibold text-gray-800">{{ $slip->bulan_name }} {{ $slip->tahun }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Periode</p>
                    <p class="font-semibold text-gray-800">{{ $slip->periode_start_formatted }} - {{ $slip->periode_end_formatted }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500">Tanggal Generate</p>
                    <p class="font-semibold text-gray-800">{{ $slip->created_at->format('d M Y H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Summary Gaji -->
        <div class="bg-gradient-to-br from-teal-500 to-teal-600 rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center mb-4">
                <div class="bg-white/20 p-3 rounded-xl">
                    <i class="fas fa-wallet text-2xl"></i>
                </div>
                <h3 class="ml-3 text-lg font-bold">Total Gaji Bersih</h3>
            </div>
            <div class="mt-6">
                <p class="text-teal-100 text-sm mb-1">Take Home Pay</p>
                <p class="text-4xl font-bold">Rp {{ number_format($slip->gaji_bersih, 0, ',', '.') }}</p>
            </div>
            <div class="mt-6 pt-4 border-t border-teal-400/30">
                <div class="flex justify-between text-sm">
                    <span class="text-teal-100">Gaji Kotor:</span>
                    <span class="font-semibold">Rp {{ number_format($slip->gaji_kotor, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-sm mt-2">
                    <span class="text-teal-100">Potongan:</span>
                    <span class="font-semibold">Rp {{ number_format($slip->total_potongan, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Detail Kehadiran -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <div class="flex items-center mb-6">
            <div class="bg-purple-100 p-3 rounded-xl">
                <i class="fas fa-clock text-purple-600 text-2xl"></i>
            </div>
            <h3 class="ml-3 text-lg font-bold text-gray-800">Data Kehadiran</h3>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-6 gap-4">
            <div class="bg-gray-50 rounded-lg p-4 text-center">
                <p class="text-gray-600 text-sm mb-1">Hari Kerja</p>
                <p class="text-2xl font-bold text-gray-800">{{ $slip->hari_kerja }}</p>
            </div>
            <div class="bg-green-50 rounded-lg p-4 text-center">
                <p class="text-green-600 text-sm mb-1">Hadir</p>
                <p class="text-2xl font-bold text-green-700">{{ $slip->hari_hadir }}</p>
            </div>
            <div class="bg-blue-50 rounded-lg p-4 text-center">
                <p class="text-blue-600 text-sm mb-1">Izin</p>
                <p class="text-2xl font-bold text-blue-700">{{ $slip->hari_izin }}</p>
            </div>
            <div class="bg-yellow-50 rounded-lg p-4 text-center">
                <p class="text-yellow-600 text-sm mb-1">Sakit</p>
                <p class="text-2xl font-bold text-yellow-700">{{ $slip->hari_sakit }}</p>
            </div>
            <div class="bg-red-50 rounded-lg p-4 text-center">
                <p class="text-red-600 text-sm mb-1">Alpha</p>
                <p class="text-2xl font-bold text-red-700">{{ $slip->hari_alpha }}</p>
            </div>
            <div class="bg-orange-50 rounded-lg p-4 text-center">
                <p class="text-orange-600 text-sm mb-1">Jam Lembur</p>
                <p class="text-2xl font-bold text-orange-700">{{ $slip->jam_lembur }}</p>
            </div>
        </div>
    </div>

    <!-- Rincian Gaji -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Komponen Pendapatan -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <div class="bg-green-100 p-3 rounded-xl">
                        <i class="fas fa-plus-circle text-green-600 text-2xl"></i>
                    </div>
                    <h3 class="ml-3 text-lg font-bold text-gray-800">Pendapatan</h3>
                </div>
                <span class="text-2xl font-bold text-green-600">
                    Rp {{ number_format($slip->gaji_pokok + $slip->total_tunjangan, 0, ',', '.') }}
                </span>
            </div>

            <div class="space-y-3">
                <!-- Gaji Pokok -->
                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                    <div>
                        <p class="font-semibold text-gray-800">Gaji Pokok</p>
                        <p class="text-xs text-gray-500">Gaji dasar bulanan</p>
                    </div>
                    <p class="font-semibold text-gray-800">Rp {{ number_format($slip->gaji_pokok, 0, ',', '.') }}</p>
                </div>

                <!-- Tunjangan -->
                @if($slip->tunjangan_detail)
                    @foreach($slip->tunjangan_detail as $tunjangan)
                        <div class="flex justify-between items-center py-3 border-b border-gray-100">
                            <div>
                                <p class="font-medium text-gray-700">{{ $tunjangan['nama'] }}</p>
                                @if(isset($tunjangan['keterangan']))
                                    <p class="text-xs text-gray-500">{{ $tunjangan['keterangan'] }}</p>
                                @endif
                            </div>
                            <p class="font-semibold text-gray-800">Rp {{ number_format($tunjangan['nilai'], 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                @endif

                <!-- Lembur -->
                @if($slip->upah_lembur > 0)
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <div>
                            <p class="font-medium text-gray-700">Upah Lembur</p>
                            <p class="text-xs text-gray-500">{{ $slip->jam_lembur }} jam</p>
                        </div>
                        <p class="font-semibold text-gray-800">Rp {{ number_format($slip->upah_lembur, 0, ',', '.') }}</p>
                    </div>
                @endif

                <!-- Total -->
                <div class="flex justify-between items-center py-3 bg-green-50 rounded-lg px-4 mt-4">
                    <p class="font-bold text-green-700">Total Pendapatan</p>
                    <p class="text-xl font-bold text-green-700">Rp {{ number_format($slip->gaji_pokok + $slip->total_tunjangan, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Komponen Potongan -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <div class="bg-red-100 p-3 rounded-xl">
                        <i class="fas fa-minus-circle text-red-600 text-2xl"></i>
                    </div>
                    <h3 class="ml-3 text-lg font-bold text-gray-800">Potongan</h3>
                </div>
                <span class="text-2xl font-bold text-red-600">
                    Rp {{ number_format($slip->total_potongan, 0, ',', '.') }}
                </span>
            </div>

            <div class="space-y-3">
                @if($slip->potongan_detail && count($slip->potongan_detail) > 0)
                    @foreach($slip->potongan_detail as $potongan)
                        <div class="flex justify-between items-center py-3 border-b border-gray-100">
                            <div>
                                <p class="font-medium text-gray-700">{{ $potongan['nama'] }}</p>
                                @if(isset($potongan['keterangan']))
                                    <p class="text-xs text-gray-500">{{ $potongan['keterangan'] }}</p>
                                @endif
                            </div>
                            <p class="font-semibold text-red-600">Rp {{ number_format($potongan['nilai'], 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-8 text-gray-400">
                        <i class="fas fa-info-circle text-3xl mb-2"></i>
                        <p class="text-sm">Tidak ada potongan</p>
                    </div>
                @endif

                <!-- Total -->
                <div class="flex justify-between items-center py-3 bg-red-50 rounded-lg px-4 mt-4">
                    <p class="font-bold text-red-700">Total Potongan</p>
                    <p class="text-xl font-bold text-red-700">Rp {{ number_format($slip->total_potongan, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Approval & Payment -->
    @if($slip->isApproved() || $slip->isPaid())
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @if($slip->approved_by)
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-3">Informasi Persetujuan</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Disetujui oleh:</span>
                                <span class="font-semibold">{{ $slip->approvedBy->name ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tanggal:</span>
                                <span class="font-semibold">{{ $slip->approved_at_formatted }}</span>
                            </div>
                        </div>
                    </div>
                @endif

                @if($slip->isPaid())
                    <div>
                        <h4 class="font-semibold text-gray-800 mb-3">Informasi Pembayaran</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tanggal Bayar:</span>
                                <span class="font-semibold">{{ $slip->payment_date_formatted }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Metode:</span>
                                <span class="font-semibold">{{ $slip->payment_method ?? '-' }}</span>
                            </div>
                            @if($slip->payment_notes)
                                <div class="mt-2">
                                    <span class="text-gray-600">Catatan:</span>
                                    <p class="text-gray-800 mt-1">{{ $slip->payment_notes }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @endif

    @if($slip->isRejected())
        <div class="bg-red-50 border border-red-200 rounded-2xl p-6">
            <div class="flex">
                <i class="fas fa-exclamation-circle text-red-600 text-2xl mr-4"></i>
                <div>
                    <h4 class="font-semibold text-red-800 mb-2">Slip Ditolak</h4>
                    <p class="text-red-700 text-sm mb-2">{{ $slip->rejection_reason }}</p>
                    <p class="text-xs text-red-600">Ditolak oleh: {{ $slip->approvedBy->name ?? '-' }} pada {{ $slip->approved_at_formatted }}</p>
                </div>
            </div>
        </div>
    @endif

</div>

<!-- Modal Approve -->
<div id="approvalModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl p-8 max-w-md w-full">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Konfirmasi Approval</h3>
        <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menyetujui slip gaji ini?</p>
        <form action="{{ route('finance.gaji.approve', $slip->id) }}" method="POST">
            @csrf
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-green-600 hover:bg-green-700 text-white py-3 rounded-lg font-semibold transition">
                    <i class="fas fa-check mr-2"></i>Ya, Setujui
                </button>
                <button type="button" onclick="closeApprovalModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 py-3 rounded-lg font-semibold transition">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Reject -->
<div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl p-8 max-w-md w-full">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Tolak Slip Gaji</h3>
        <form action="{{ route('finance.gaji.reject', $slip->id) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Alasan Penolakan</label>
                <textarea name="rejection_reason" rows="4" required
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500"
                          placeholder="Jelaskan alasan penolakan..."></textarea>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white py-3 rounded-lg font-semibold transition">
                    <i class="fas fa-times mr-2"></i>Tolak
                </button>
                <button type="button" onclick="closeRejectModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 py-3 rounded-lg font-semibold transition">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Payment -->
<div id="paymentModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-2xl p-8 max-w-md w-full">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Tandai Sudah Dibayar</h3>
        <form action="{{ route('finance.gaji.mark-paid', $slip->id) }}" method="POST">
            @csrf
            <div class="space-y-4 mb-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Pembayaran</label>
                    <input type="date" name="payment_date" value="{{ date('Y-m-d') }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Metode Pembayaran</label>
                    <select name="payment_method" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
                        <option value="">-- Pilih Metode --</option>
                        <option value="Transfer Bank">Transfer Bank</option>
                        <option value="Cash">Cash</option>
                        <option value="Cek">Cek</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan (Opsional)</label>
                    <textarea name="payment_notes" rows="3"
                              class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500"
                              placeholder="Catatan pembayaran..."></textarea>
                </div>
            </div>
            <div class="flex gap-3">
                <button type="submit" class="flex-1 bg-teal-600 hover:bg-teal-700 text-white py-3 rounded-lg font-semibold transition">
                    <i class="fas fa-check mr-2"></i>Konfirmasi Pembayaran
                </button>
                <button type="button" onclick="closePaymentModal()" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 py-3 rounded-lg font-semibold transition">
                    Batal
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openApprovalModal() {
    document.getElementById('approvalModal').classList.remove('hidden');
}
function closeApprovalModal() {
    document.getElementById('approvalModal').classList.add('hidden');
}
function openRejectModal() {
    document.getElementById('rejectModal').classList.remove('hidden');
}
function closeRejectModal() {
    document.getElementById('rejectModal').classList.add('hidden');
}
function openPaymentModal() {
    document.getElementById('paymentModal').classList.remove('hidden');
}
function closePaymentModal() {
    document.getElementById('paymentModal').classList.add('hidden');
}
</script>
@endpush
@endsection