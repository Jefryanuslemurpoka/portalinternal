@extends('layouts.app')

@section('title', 'Pengajuan Cuti & Izin')
@section('page-title', 'Pengajuan Cuti & Izin')

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

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-gradient-to-br from-blue-500 to-indigo-500 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-white/80 mb-1">Total Pengajuan</p>
                    <h3 class="text-3xl font-bold">{{ $statistik['total'] }}</h3>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-file-alt text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-white/80 mb-1">Pending</p>
                    <h3 class="text-3xl font-bold">{{ $statistik['pending'] }}</h3>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-clock text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-teal-500 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-white/80 mb-1">Disetujui</p>
                    <h3 class="text-3xl font-bold">{{ $statistik['disetujui'] }}</h3>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-check-circle text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-white/80 mb-1">Sisa Cuti</p>
                    <h3 class="text-3xl font-bold">{{ $statistik['sisa_cuti'] }}</h3>
                    <p class="text-sm text-white/70">hari</p>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-calendar-check text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Pengajuan -->
    <x-table 
        id="cutiIzinTable"
        title="Riwayat Pengajuan"
        :headers="['No', 'Tanggal Pengajuan', 'Jenis', 'Periode', 'Durasi', 'Alasan', 'Status', 'Aksi']"
        addButtonText="Ajukan Baru"
        addButtonIcon="fas fa-plus"
        :addButton="true"
        addButtonOnclick="openModal('modalPengajuan')"
    >
        <x-slot name="filters">
            <select id="filterStatus" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Status</option>
                <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="disetujui" {{ $status == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                <option value="ditolak" {{ $status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
            </select>
            <select id="filterJenis" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Jenis</option>
                <option value="cuti" {{ $jenis == 'cuti' ? 'selected' : '' }}>Cuti</option>
                <option value="izin" {{ $jenis == 'izin' ? 'selected' : '' }}>Izin</option>
                <option value="sakit" {{ $jenis == 'sakit' ? 'selected' : '' }}>Sakit</option>
            </select>
        </x-slot>

        @forelse($pengajuanList as $index => $pengajuan)
        <tr class="hover:bg-gray-50 transition">
            <td class="px-6 py-4 text-sm text-gray-900">
                {{ $pengajuanList->firstItem() + $index }}
            </td>
            <td class="px-6 py-4 text-sm text-gray-900">
                {{ \Carbon\Carbon::parse($pengajuan->tanggal_pengajuan)->format('d/m/Y H:i') }}
            </td>
            <td class="px-6 py-4">
                <span class="px-3 py-1 rounded-full text-xs font-semibold
                    {{ $pengajuan->jenis == 'cuti' ? 'bg-blue-100 text-blue-700' : '' }}
                    {{ $pengajuan->jenis == 'izin' ? 'bg-purple-100 text-purple-700' : '' }}
                    {{ $pengajuan->jenis == 'sakit' ? 'bg-red-100 text-red-700' : '' }}">
                    {{ ucfirst($pengajuan->jenis) }}
                </span>
            </td>
            <td class="px-6 py-4">
                <div class="text-sm">
                    <p class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->format('d M Y') }}</p>
                    <p class="text-xs text-gray-500">s/d {{ \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->format('d M Y') }}</p>
                </div>
            </td>
            <td class="px-6 py-4 text-sm text-gray-900">
                {{ $pengajuan->jumlah_hari }} hari
            </td>
            <td class="px-6 py-4">
                <div class="max-w-xs">
                    <p class="text-sm text-gray-700 line-clamp-2">{{ Str::limit($pengajuan->alasan, 50) }}</p>
                </div>
            </td>
            <td class="px-6 py-4">
                @if($pengajuan->status == 'pending')
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                        <i class="fas fa-clock"></i> Pending
                    </span>
                @elseif($pengajuan->status == 'disetujui')
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                        <i class="fas fa-check"></i> Disetujui
                    </span>
                @else
                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                        <i class="fas fa-times"></i> Ditolak
                    </span>
                @endif
            </td>
            <td class="px-6 py-4">
                <div class="flex items-center space-x-2">
                    <button onclick="openDetailModal({{ json_encode($pengajuan) }})" 
                            class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition"
                            title="Detail">
                        <i class="fas fa-eye"></i>
                    </button>
                    
                    @if($pengajuan->status == 'pending')
                        <button onclick="confirmDelete({{ $pengajuan->id }})" 
                                class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition"
                                title="Batalkan">
                            <i class="fas fa-trash"></i>
                        </button>
                    @endif
                </div>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8" class="px-6 py-12 text-center">
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>Belum ada pengajuan</p>
                    <button onclick="openModal('modalPengajuan')" class="mt-4 px-5 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-purple-700 transition shadow-md">
                        <i class="fas fa-plus mr-2"></i>Buat Pengajuan Pertama
                    </button>
                </div>
            </td>
        </tr>
        @endforelse

        <x-slot name="pagination">
            {{ $pengajuanList->links() }}
        </x-slot>
    </x-table>

</div>

<!-- Modal Form Pengajuan -->
<x-modal id="modalPengajuan" title="Form Pengajuan Cuti/Izin" size="lg" type="info">
    <form action="{{ route('karyawan.cutiizin.store') }}" method="POST" enctype="multipart/form-data" id="formPengajuan">
        @csrf
        
        <!-- Info Sisa Cuti -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <div class="flex items-center">
                <i class="fas fa-info-circle text-blue-600 text-xl mr-3"></i>
                <p class="text-blue-800"><strong>Sisa Cuti Anda: {{ $statistik['sisa_cuti'] }} hari</strong></p>
            </div>
        </div>

        <!-- Jenis -->
        <div class="mb-6">
            <label class="form-label">
                <i class="fas fa-list mr-2"></i>Jenis Pengajuan <span class="text-red-500">*</span>
            </label>
            <select name="jenis" class="form-input @error('jenis') border-red-500 @enderror" required id="jenisPengajuan">
                <option value="">-- Pilih Jenis --</option>
                <option value="cuti" {{ old('jenis') == 'cuti' ? 'selected' : '' }}>Cuti</option>
                <option value="izin" {{ old('jenis') == 'izin' ? 'selected' : '' }}>Izin</option>
                <option value="sakit" {{ old('jenis') == 'sakit' ? 'selected' : '' }}>Sakit</option>
            </select>
            @error('jenis')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
            <div class="mt-2 text-xs text-gray-600 space-y-1">
                <p><strong>Cuti:</strong> Untuk keperluan pribadi/liburan</p>
                <p><strong>Izin:</strong> Untuk keperluan mendadak</p>
                <p><strong>Sakit:</strong> Ketika sedang sakit (disarankan melampirkan surat dokter)</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-6 mb-6">
            <!-- Tanggal Mulai -->
            <div>
                <label class="form-label">
                    <i class="fas fa-calendar mr-2"></i>Tanggal Mulai <span class="text-red-500">*</span>
                </label>
                <input type="date" 
                       name="tanggal_mulai" 
                       class="form-input @error('tanggal_mulai') border-red-500 @enderror" 
                       value="{{ old('tanggal_mulai') }}"
                       min="{{ date('Y-m-d') }}"
                       required
                       id="tanggalMulai">
                @error('tanggal_mulai')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tanggal Selesai -->
            <div>
                <label class="form-label">
                    <i class="fas fa-calendar-check mr-2"></i>Tanggal Selesai <span class="text-red-500">*</span>
                </label>
                <input type="date" 
                       name="tanggal_selesai" 
                       class="form-input @error('tanggal_selesai') border-red-500 @enderror" 
                       value="{{ old('tanggal_selesai') }}"
                       min="{{ date('Y-m-d') }}"
                       required
                       id="tanggalSelesai">
                @error('tanggal_selesai')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Info Jumlah Hari -->
        <div class="mb-6" id="infoJumlahHari" style="display: none;">
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                <div class="flex items-center">
                    <i class="fas fa-calculator text-yellow-600 text-xl mr-3"></i>
                    <p class="text-yellow-800">Total: <strong id="totalHari">0</strong> hari</p>
                </div>
            </div>
        </div>

        <!-- Alasan -->
        <div class="mb-6">
            <label class="form-label">
                <i class="fas fa-comment mr-2"></i>Alasan <span class="text-red-500">*</span>
            </label>
            <textarea name="alasan" 
                      rows="4" 
                      class="form-input @error('alasan') border-red-500 @enderror" 
                      placeholder="Jelaskan alasan pengajuan Anda..."
                      required
                      maxlength="500">{{ old('alasan') }}</textarea>
            @error('alasan')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
            <p class="text-xs text-gray-500 mt-1">Maksimal 500 karakter</p>
        </div>

        <!-- File Pendukung -->
        <div class="mb-6">
            <label class="form-label">
                <i class="fas fa-paperclip mr-2"></i>File Pendukung (Opsional)
            </label>
            <input type="file" 
                   name="file_pendukung" 
                   class="form-input @error('file_pendukung') border-red-500 @enderror"
                   accept=".jpg,.jpeg,.png,.pdf"
                   id="filePendukung">
            @error('file_pendukung')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
            <p class="text-xs text-gray-500 mt-1">Format: JPG, JPEG, PNG, PDF. Maksimal 2MB. (Disarankan untuk sakit)</p>
        </div>
    </form>
    
    <x-slot name="footerButtons">
        <button type="button" onclick="closeModal('modalPengajuan')" class="px-5 py-2.5 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition">
            Batal
        </button>
        <button type="submit" form="formPengajuan" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-purple-700 transition shadow-md">
            <i class="fas fa-paper-plane mr-2"></i>Ajukan
        </button>
    </x-slot>
</x-modal>

<!-- Modal Detail -->
<x-modal id="detailModal" title="Detail Pengajuan" size="lg" type="info">
    <div class="space-y-4">
        <div class="flex items-center space-x-4 pb-4 border-b">
            <img id="detailFoto" src="" alt="" class="w-16 h-16 rounded-full object-cover border-2 border-gray-200">
            <div>
                <h4 id="detailNama" class="text-lg font-bold text-gray-900"></h4>
                <p id="detailDivisi" class="text-sm text-gray-500"></p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <p class="text-xs text-gray-500 mb-1">Jenis</p>
                <p id="detailJenis" class="font-semibold text-gray-900"></p>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">Status</p>
                <span id="detailStatus"></span>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">Tanggal Pengajuan</p>
                <p id="detailTanggalPengajuan" class="font-semibold text-gray-900"></p>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">Durasi</p>
                <p id="detailDurasi" class="font-semibold text-gray-900"></p>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">Tanggal Mulai</p>
                <p id="detailTanggalMulai" class="font-semibold text-gray-900"></p>
            </div>
            <div>
                <p class="text-xs text-gray-500 mb-1">Tanggal Selesai</p>
                <p id="detailTanggalSelesai" class="font-semibold text-gray-900"></p>
            </div>
        </div>

        <div>
            <p class="text-xs text-gray-500 mb-1">Alasan</p>
            <div id="detailAlasan" class="bg-gray-50 rounded-lg p-4 text-sm text-gray-700"></div>
        </div>

        <div id="detailFileSection" class="hidden">
            <p class="text-xs text-gray-500 mb-1">File Pendukung</p>
            <a id="detailFileLink" href="" target="_blank" class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition">
                <i class="fas fa-download mr-2"></i>Lihat File
            </a>
        </div>

        <div id="detailApprovalSection" class="hidden">
            <p class="text-xs text-gray-500 mb-1">Catatan Admin</p>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <p id="detailCatatan" class="text-sm text-blue-800"></p>
                <p id="detailApprover" class="text-xs text-blue-600 mt-2"></p>
            </div>
        </div>
    </div>
    <x-slot name="footerButtons">
        <button type="button" onclick="closeModal('detailModal')" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-purple-700 transition shadow-md">
            Tutup
        </button>
    </x-slot>
</x-modal>

@endsection

@push('scripts')
<script>
// Hitung jumlah hari otomatis
document.getElementById('tanggalMulai').addEventListener('change', hitungJumlahHari);
document.getElementById('tanggalSelesai').addEventListener('change', hitungJumlahHari);

function hitungJumlahHari() {
    const tanggalMulai = document.getElementById('tanggalMulai').value;
    const tanggalSelesai = document.getElementById('tanggalSelesai').value;
    
    if (tanggalMulai && tanggalSelesai) {
        const mulai = new Date(tanggalMulai);
        const selesai = new Date(tanggalSelesai);
        
        const diffTime = Math.abs(selesai - mulai);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
        
        document.getElementById('totalHari').textContent = diffDays;
        document.getElementById('infoJumlahHari').style.display = 'block';
        
        // Update min tanggal selesai
        document.getElementById('tanggalSelesai').min = tanggalMulai;
    }
}

// Validasi file size
document.getElementById('filePendukung').addEventListener('change', function() {
    const file = this.files[0];
    if (file && file.size > 2048000) { // 2MB
        alert('Ukuran file maksimal 2MB!');
        this.value = '';
    }
});

// Filter Status & Jenis
document.getElementById('filterStatus').addEventListener('change', function() {
    const status = this.value;
    const jenis = document.getElementById('filterJenis').value;
    window.location.href = `{{ route('karyawan.cutiizin.index') }}?status=${status}&jenis=${jenis}`;
});

document.getElementById('filterJenis').addEventListener('change', function() {
    const jenis = this.value;
    const status = document.getElementById('filterStatus').value;
    window.location.href = `{{ route('karyawan.cutiizin.index') }}?status=${status}&jenis=${jenis}`;
});

// Open Detail Modal
function openDetailModal(data) {
    const pengajuan = typeof data === 'string' ? JSON.parse(data) : data;
    
    document.getElementById('detailFoto').src = pengajuan.user.foto 
        ? `/storage/${pengajuan.user.foto}` 
        : `https://ui-avatars.com/api/?name=${encodeURIComponent(pengajuan.user.name)}&background=4F46E5&color=fff`;
    document.getElementById('detailNama').textContent = pengajuan.user.name;
    document.getElementById('detailDivisi').textContent = pengajuan.user.divisi || '-';
    document.getElementById('detailJenis').textContent = pengajuan.jenis.charAt(0).toUpperCase() + pengajuan.jenis.slice(1);
    
    let statusClass = 'bg-yellow-100 text-yellow-700';
    if (pengajuan.status === 'disetujui') statusClass = 'bg-green-100 text-green-700';
    if (pengajuan.status === 'ditolak') statusClass = 'bg-red-100 text-red-700';
    
    const statusBadge = `<span class="px-3 py-1 rounded-full text-xs font-semibold ${statusClass}">${pengajuan.status.charAt(0).toUpperCase() + pengajuan.status.slice(1)}</span>`;
    document.getElementById('detailStatus').innerHTML = statusBadge;
    
    document.getElementById('detailTanggalPengajuan').textContent = new Date(pengajuan.tanggal_pengajuan).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
    document.getElementById('detailTanggalMulai').textContent = new Date(pengajuan.tanggal_mulai).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
    document.getElementById('detailTanggalSelesai').textContent = new Date(pengajuan.tanggal_selesai).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
    document.getElementById('detailDurasi').textContent = `${pengajuan.jumlah_hari} hari`;
    document.getElementById('detailAlasan').textContent = pengajuan.alasan;
    
    // File Pendukung
    if (pengajuan.file_pendukung) {
        document.getElementById('detailFileSection').classList.remove('hidden');
        document.getElementById('detailFileLink').href = `/storage/${pengajuan.file_pendukung}`;
    } else {
        document.getElementById('detailFileSection').classList.add('hidden');
    }
    
    // Catatan Admin
    if (pengajuan.catatan_admin && pengajuan.status !== 'pending') {
        document.getElementById('detailApprovalSection').classList.remove('hidden');
        document.getElementById('detailCatatan').textContent = pengajuan.catatan_admin;
        document.getElementById('detailApprover').textContent = `Diproses pada ${new Date(pengajuan.tanggal_approval).toLocaleDateString('id-ID')}`;
    } else {
        document.getElementById('detailApprovalSection').classList.add('hidden');
    }
    
    openModal('detailModal');
}

// Confirm Delete
function confirmDelete(id) {
    if (confirm('Yakin ingin membatalkan pengajuan ini?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/karyawan/cuti-izin/${id}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}

// Auto dismiss alerts
setTimeout(function() {
    const alerts = document.querySelectorAll('.bg-green-50, .bg-red-50');
    alerts.forEach(function(alert) {
        alert.style.transition = 'opacity 0.5s';
        alert.style.opacity = '0';
        setTimeout(() => alert.remove(), 500);
    });
}, 5000);
</script>
@endpush