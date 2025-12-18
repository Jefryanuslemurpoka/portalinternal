@extends('layouts.app')

@section('title', 'Edit Pemasukan')
@section('page-title', 'Edit Data Pemasukan')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <a href="{{ route('finance.pemasukan.index') }}" class="text-teal-600 hover:text-teal-700 font-medium inline-flex items-center mb-4">
                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar
            </a>
            <h2 class="text-2xl font-bold text-gray-800">Edit Pemasukan</h2>
            <p class="text-gray-600 text-sm">Ubah data pemasukan yang sudah ada</p>
        </div>
        <div class="bg-yellow-100 p-4 rounded-xl">
            <i class="fas fa-edit text-4xl text-yellow-600"></i>
        </div>
    </div>

    <!-- Alert Info -->
    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
        <div class="flex items-center">
            <i class="fas fa-info-circle text-blue-600 text-xl mr-3"></i>
            <div>
                <p class="font-semibold text-blue-900">Anda sedang mengedit data pemasukan</p>
                <p class="text-sm text-blue-700">Invoice: <span class="font-bold">INV-IN-2024-0242</span> | Status: <span class="font-bold">Pending</span></p>
            </div>
        </div>
    </div>

    <!-- Form -->
    <form id="formPemasukan" class="space-y-6">
        
        <!-- Informasi Umum -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center mb-6">
                <div class="bg-teal-100 p-3 rounded-lg mr-3">
                    <i class="fas fa-info-circle text-teal-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Informasi Umum</h3>
                    <p class="text-sm text-gray-500">Data dasar transaksi pemasukan</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nomor Invoice -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Nomor Invoice <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="text" id="invoice_number" value="INV-IN-2024-0242" readonly
                            class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        <div class="absolute right-3 top-3 text-gray-400">
                            <i class="fas fa-lock"></i>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Nomor invoice tidak dapat diubah</p>
                </div>

                <!-- Nomor SO -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Nomor SO (Opsional)
                    </label>
                    <input type="text" id="so_number" value="SO-2024-0188" placeholder="Contoh: SO-2024-0190"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                </div>

                <!-- Tanggal -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Tanggal <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="tanggal" value="2024-12-15"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                </div>

                <!-- Waktu -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Waktu <span class="text-red-500">*</span>
                    </label>
                    <input type="time" id="waktu" value="11:45"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                </div>

                <!-- Sumber Pemasukan -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Sumber Pemasukan <span class="text-red-500">*</span>
                    </label>
                    <select id="sumber" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        <option value="">Pilih Sumber</option>
                        <option value="penjualan">Penjualan</option>
                        <option value="jasa" selected>Jasa/Layanan</option>
                        <option value="investasi">Investasi</option>
                        <option value="bunga">Bunga Bank</option>
                        <option value="hibah">Hibah/Donasi</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select id="status" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                        <option value="">Pilih Status</option>
                        <option value="pending" selected>Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="received">Received</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Informasi Pelanggan/Pihak -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center mb-6">
                <div class="bg-blue-100 p-3 rounded-lg mr-3">
                    <i class="fas fa-user-tie text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Informasi Pelanggan/Pihak</h3>
                    <p class="text-sm text-gray-500">Data pelanggan atau pihak pembayar</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Pelanggan -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Nama Pelanggan/Pihak <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="pelanggan" value="CV Teknologi Maju" placeholder="Contoh: PT Mitra Sejahtera" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                </div>

                <!-- Kontak -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Nomor Kontak
                    </label>
                    <input type="text" id="kontak" value="0812-3456-7890" placeholder="Contoh: 0821-9876-5432"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                </div>

                <!-- Email -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Email
                    </label>
                    <input type="email" id="email" value="info@teknologimaju.com" placeholder="contoh@email.com"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                </div>

                <!-- Alamat -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Alamat
                    </label>
                    <input type="text" id="alamat" value="Jl. Sudirman No. 123, Jakarta Pusat" placeholder="Alamat lengkap"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                </div>
            </div>
        </div>

        <!-- Detail Transaksi -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <div class="bg-purple-100 p-3 rounded-lg mr-3">
                        <i class="fas fa-list text-purple-600 text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Detail Item/Transaksi</h3>
                        <p class="text-sm text-gray-500">Daftar item atau rincian transaksi</p>
                    </div>
                </div>
                <button type="button" onclick="addItem()" class="bg-teal-600 hover:bg-teal-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-plus mr-2"></i>Tambah Item
                </button>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full" id="itemTable">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Nama Item</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Qty</th>
                            <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700">Harga Satuan</th>
                            <th class="px-4 py-3 text-right text-sm font-semibold text-gray-700">Subtotal</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="itemTableBody" class="divide-y divide-gray-200">
                        <!-- Item rows will be added here -->
                    </tbody>
                </table>
            </div>

            <div class="mt-4 text-center text-gray-500 hidden" id="emptyState">
                <i class="fas fa-box-open text-4xl mb-2"></i>
                <p class="text-sm">Belum ada item. Klik "Tambah Item" untuk menambahkan</p>
            </div>
        </div>

        <!-- Ringkasan Pembayaran -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center mb-6">
                <div class="bg-green-100 p-3 rounded-lg mr-3">
                    <i class="fas fa-calculator text-green-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Ringkasan Pembayaran</h3>
                    <p class="text-sm text-gray-500">Total dan rincian pembayaran</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <!-- Metode Pembayaran -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Metode Pembayaran <span class="text-red-500">*</span>
                        </label>
                        <select id="metode_pembayaran" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                            <option value="">Pilih Metode</option>
                            <option value="transfer">Transfer Bank</option>
                            <option value="virtual_account" selected>Virtual Account</option>
                            <option value="cash">Cash</option>
                            <option value="auto_credit">Auto Credit</option>
                        </select>
                    </div>

                    <!-- PPN -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            PPN (%)
                        </label>
                        <input type="number" id="ppn" value="0" min="0" max="100" step="0.01" onchange="hitungTotal()"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                    </div>

                    <!-- Biaya Admin -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Biaya Admin
                        </label>
                        <input type="number" id="biaya_admin" value="0" min="0" onchange="hitungTotal()"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                    </div>

                    <!-- Diskon -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Diskon
                        </label>
                        <input type="number" id="diskon" value="0" min="0" onchange="hitungTotal()"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                    </div>
                </div>

                <div class="bg-gradient-to-br from-teal-50 to-green-50 rounded-lg p-6 border border-teal-200">
                    <h4 class="text-sm font-semibold text-gray-700 mb-4">Rincian Total</h4>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-semibold text-gray-900" id="subtotalDisplay">Rp 0</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">PPN</span>
                            <span class="font-semibold text-gray-900" id="ppnDisplay">Rp 0</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Biaya Admin</span>
                            <span class="font-semibold text-gray-900" id="biayaAdminDisplay">Rp 0</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Diskon</span>
                            <span class="font-semibold text-red-600" id="diskonDisplay">- Rp 0</span>
                        </div>
                        <div class="border-t border-teal-300 pt-3 mt-3">
                            <div class="flex justify-between">
                                <span class="font-bold text-gray-900">Total Pemasukan</span>
                                <span class="font-bold text-2xl text-green-600" id="totalDisplay">Rp 0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Deskripsi & Catatan -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center mb-6">
                <div class="bg-yellow-100 p-3 rounded-lg mr-3">
                    <i class="fas fa-sticky-note text-yellow-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Deskripsi & Catatan</h3>
                    <p class="text-sm text-gray-500">Informasi tambahan tentang transaksi</p>
                </div>
            </div>

            <div class="space-y-4">
                <!-- Deskripsi -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Deskripsi <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="deskripsi" value="Jasa Konsultasi IT" placeholder="Contoh: Penjualan Produk Premium" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">
                </div>

                <!-- Catatan -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Catatan
                    </label>
                    <textarea id="catatan" rows="4" placeholder="Tambahkan catatan atau keterangan tambahan..."
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent">Project Development Q4 2024. Menunggu konfirmasi pembayaran dari klien.</textarea>
                </div>
            </div>
        </div>

        <!-- Riwayat Perubahan -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex items-center mb-6">
                <div class="bg-gray-100 p-3 rounded-lg mr-3">
                    <i class="fas fa-history text-gray-600 text-xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Riwayat Perubahan</h3>
                    <p class="text-sm text-gray-500">Log aktivitas dan perubahan data</p>
                </div>
            </div>

            <div class="space-y-3">
                <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                    <div class="w-8 h-8 bg-teal-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-plus text-teal-600 text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-1">
                            <p class="font-semibold text-gray-900 text-sm">Data dibuat</p>
                            <span class="text-xs text-gray-500">15 Des 2024, 11:45 WIB</span>
                        </div>
                        <p class="text-sm text-gray-600">Dibuat oleh: <span class="font-medium">Siti Nurhaliza</span></p>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-edit text-blue-600 text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-1">
                            <p class="font-semibold text-gray-900 text-sm">Perubahan terakhir</p>
                            <span class="text-xs text-gray-500">16 Des 2024, 09:15 WIB</span>
                        </div>
                        <p class="text-sm text-gray-600">Diubah oleh: <span class="font-medium">Ahmad Fauzi</span></p>
                        <p class="text-xs text-gray-500 mt-1">Update informasi pelanggan dan deskripsi</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <div class="flex justify-between items-center">
                <a href="{{ route('finance.pemasukan.index') }}" class="text-gray-600 hover:text-gray-800 font-medium">
                    <i class="fas fa-times mr-2"></i>Batal
                </a>
                <div class="flex gap-3">
                    <button type="button" onclick="confirmDelete()" class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg font-semibold transition">
                        <i class="fas fa-trash mr-2"></i>Hapus Data
                    </button>
                    <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-white px-8 py-3 rounded-lg font-semibold shadow-lg transition">
                        <i class="fas fa-save mr-2"></i>Simpan Perubahan
                    </button>
                </div>
            </div>
        </div>

    </form>

</div>

<!-- Modal Add Item -->
<div id="modalAddItem" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
        <div class="bg-gradient-to-r from-teal-600 to-teal-700 text-white p-6 rounded-t-xl">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold">Tambah Item</h3>
                <button onclick="closeModalItem()" class="text-white hover:text-gray-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>

        <div class="p-6 space-y-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Item</label>
                <input type="text" id="item_name" placeholder="Contoh: Product A - Premium Edition"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Quantity</label>
                <input type="number" id="item_qty" value="1" min="1"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Harga Satuan</label>
                <input type="number" id="item_price" value="0" min="0"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500">
            </div>

            <div class="flex gap-3 pt-4">
                <button onclick="closeModalItem()" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white py-2 rounded-lg transition">
                    Batal
                </button>
                <button onclick="saveItem()" class="flex-1 bg-teal-600 hover:bg-teal-700 text-white py-2 rounded-lg transition">
                    <i class="fas fa-plus mr-2"></i>Tambah
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Item -->
<div id="modalEditItem" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full">
        <div class="bg-gradient-to-r from-yellow-600 to-yellow-700 text-white p-6 rounded-t-xl">
            <div class="flex items-center justify-between">
                <h3 class="text-xl font-bold">Edit Item</h3>
                <button onclick="closeModalEditItem()" class="text-white hover:text-gray-200">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
        </div>

        <div class="p-6 space-y-4">
            <input type="hidden" id="edit_item_id">
            
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Item</label>
                <input type="text" id="edit_item_name" placeholder="Contoh: Product A - Premium Edition"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Quantity</label>
                <input type="number" id="edit_item_qty" value="1" min="1"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500">
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Harga Satuan</label>
                <input type="number" id="edit_item_price" value="0" min="0"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500">
            </div>

            <div class="flex gap-3 pt-4">
                <button onclick="closeModalEditItem()" class="flex-1 bg-gray-600 hover:bg-gray-700 text-white py-2 rounded-lg transition">
                    Batal
                </button>
                <button onclick="updateItem()" class="flex-1 bg-yellow-600 hover:bg-yellow-700 text-white py-2 rounded-lg transition">
                    <i class="fas fa-save mr-2"></i>Simpan
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let items = [];
let itemCounter = 2;

// Pre-filled existing items
items = [
    {
        id: 1,
        name: 'Konsultasi IT Development',
        qty: 1,
        price: 28500000,
        subtotal: 28500000
    }
];

// Format Rupiah
function formatRupiah(angka) {
    return 'Rp ' + parseInt(angka).toLocaleString('id-ID');
}

// Add Item
function addItem() {
    document.getElementById('modalAddItem').classList.remove('hidden');
}

function closeModalItem() {
    document.getElementById('modalAddItem').classList.add('hidden');
    document.getElementById('item_name').value = '';
    document.getElementById('item_qty').value = '1';
    document.getElementById('item_price').value = '0';
}

function saveItem() {
    const name = document.getElementById('item_name').value;
    const qty = parseInt(document.getElementById('item_qty').value);
    const price = parseInt(document.getElementById('item_price').value);

    if (!name || qty <= 0 || price < 0) {
        alert('Mohon lengkapi data item dengan benar');
        return;
    }

    const subtotal = qty * price;
    itemCounter++;

    items.push({
        id: itemCounter,
        name: name,
        qty: qty,
        price: price,
        subtotal: subtotal
    });

    renderItems();
    closeModalItem();
    hitungTotal();
}

// Edit Item
function editItem(id) {
    const item = items.find(i => i.id === id);
    if (!item) return;

    document.getElementById('edit_item_id').value = item.id;
    document.getElementById('edit_item_name').value = item.name;
    document.getElementById('edit_item_qty').value = item.qty;
    document.getElementById('edit_item_price').value = item.price;
    
    document.getElementById('modalEditItem').classList.remove('hidden');
}

function closeModalEditItem() {
    document.getElementById('modalEditItem').classList.add('hidden');
}

function updateItem() {
    const id = parseInt(document.getElementById('edit_item_id').value);
    const name = document.getElementById('edit_item_name').value;
    const qty = parseInt(document.getElementById('edit_item_qty').value);
    const price = parseInt(document.getElementById('edit_item_price').value);

    if (!name || qty <= 0 || price < 0) {
        alert('Mohon lengkapi data item dengan benar');
        return;
    }

    const itemIndex = items.findIndex(i => i.id === id);
    if (itemIndex !== -1) {
        items[itemIndex] = {
            id: id,
            name: name,
            qty: qty,
            price: price,
            subtotal: qty * price
        };
    }

    renderItems();
    closeModalEditItem();
    hitungTotal();
}

function renderItems() {
    const tbody = document.getElementById('itemTableBody');
    const emptyState = document.getElementById('emptyState');

    if (items.length === 0) {
        emptyState.classList.remove('hidden');
        tbody.innerHTML = '';
        return;
    }

    emptyState.classList.add('hidden');

    tbody.innerHTML = items.map(item => `
        <tr class="hover:bg-gray-50">
            <td class="px-4 py-3 text-sm text-gray-900">${item.name}</td>
            <td class="px-4 py-3 text-center text-sm text-gray-900">${item.qty} unit</td>
            <td class="px-4 py-3 text-right text-sm text-gray-900">${formatRupiah(item.price)}</td>
            <td class="px-4 py-3 text-right text-sm font-semibold text-gray-900">${formatRupiah(item.subtotal)}</td>
            <td class="px-4 py-3 text-center">
                <button type="button" onclick="editItem(${item.id})" class="text-yellow-600 hover:text-yellow-800 mr-2">
                    <i class="fas fa-edit"></i>
                </button>
                <button type="button" onclick="removeItem(${item.id})" class="text-red-600 hover:text-red-800">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `).join('');
}

function removeItem(id) {
    if (confirm('Hapus item ini?')) {
        items = items.filter(item => item.id !== id);
        renderItems();
        hitungTotal();
    }
}

function hitungTotal() {
    const subtotal = items.reduce((sum, item) => sum + item.subtotal, 0);
    const ppnPercent = parseFloat(document.getElementById('ppn').value) || 0;
    const ppnAmount = subtotal * (ppnPercent / 100);
    const biayaAdmin = parseInt(document.getElementById('biaya_admin').value) || 0;
    const diskon = parseInt(document.getElementById('diskon').value) || 0;
    const total = subtotal + ppnAmount + biayaAdmin - diskon;

    document.getElementById('subtotalDisplay').textContent = formatRupiah(subtotal);
    document.getElementById('ppnDisplay').textContent = formatRupiah(ppnAmount);
    document.getElementById('biayaAdminDisplay').textContent = formatRupiah(biayaAdmin);
    document.getElementById('diskonDisplay').textContent = '- ' + formatRupiah(diskon);
    document.getElementById('totalDisplay').textContent = formatRupiah(total);
}

// Form Submit
document.getElementById('formPemasukan').addEventListener('submit', function(e) {
    e.preventDefault();

    if (items.length === 0) {
        alert('Mohon tambahkan minimal 1 item');
        return;
    }

    // Validasi form
    const requiredFields = ['sumber', 'status', 'pelanggan', 'deskripsi', 'metode_pembayaran'];
    let isValid = true;

    requiredFields.forEach(field => {
        const input = document.getElementById(field);
        if (!input.value) {
            isValid = false;
            input.classList.add('border-red-500');
        } else {
            input.classList.remove('border-red-500');
        }
    });

    if (!isValid) {
        alert('Mohon lengkapi semua field yang wajib diisi');
        return;
    }

    // Simulasi submit
    if (confirm('Simpan perubahan data pemasukan ini?')) {
        alert('Perubahan berhasil disimpan!');
        window.location.href = '{{ route("finance.pemasukan.index") }}';
    }
});

function confirmDelete() {
    if (confirm('Apakah Anda yakin ingin menghapus data ini? Data yang dihapus tidak dapat dikembalikan.')) {
        alert('Data berhasil dihapus');
        window.location.href = '{{ route("finance.pemasukan.index") }}';
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    renderItems();
    hitungTotal();
});

// Close modal when clicking outside
document.getElementById('modalAddItem').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModalItem();
    }
});

document.getElementById('modalEditItem').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModalEditItem();
    }
});
</script>

@endsection
