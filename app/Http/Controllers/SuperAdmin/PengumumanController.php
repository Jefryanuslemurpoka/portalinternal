<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengumuman;
use App\Models\LogAktivitas;
use App\Helpers\NotificationHelper;
use App\Helpers\WhatsAppHelper;
use Illuminate\Support\Facades\Log;

class PengumumanController extends Controller
{
    /**
     * Tampilkan daftar pengumuman
     */
    public function index()
    {
        $pengumuman = Pengumuman::with('creator')
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        return view('superadmin.pengumuman.index', compact('pengumuman'));
    }

    /**
     * Tampilkan form tambah pengumuman
     */
    public function create()
    {
        return view('superadmin.pengumuman.create');
    }

    /**
     * Simpan pengumuman baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'tanggal' => 'required|date',
        ], [
            'judul.required' => 'Judul pengumuman harus diisi',
            'konten.required' => 'Konten pengumuman harus diisi',
            'tanggal.required' => 'Tanggal pengumuman harus diisi',
        ]);

        // Simpan pengumuman
        $pengumuman = Pengumuman::create([
            'judul' => $request->judul,
            'konten' => $request->konten,
            'tanggal' => $request->tanggal,
            'created_by' => auth()->id(),
        ]);

        // Kirim notifikasi ke dashboard karyawan
        NotificationHelper::pengumumanBaru($pengumuman);

        // Base success message
        $successMessage = 'Pengumuman berhasil ditambahkan';
        $waStatus = [];

        // Kirim ke WhatsApp Group
        if (config('whatsapp.enabled')) {
            try {
                // Format pesan WhatsApp
                $pesanWA = WhatsAppHelper::formatPesanPengumuman($pengumuman);
                
                // Kirim ke grup
                $berhasilKirim = WhatsAppHelper::kirimKeGrup($pesanWA);
                
                if ($berhasilKirim) {
                    $waStatus[] = '✅ Notifikasi WhatsApp terkirim ke grup';
                } else {
                    $waStatus[] = '⚠️ Gagal mengirim ke WhatsApp (cek log)';
                }
            } catch (\Exception $e) {
                Log::error('WhatsApp notification error: ' . $e->getMessage(), [
                    'pengumuman_id' => $pengumuman->id,
                    'exception' => $e->getTraceAsString()
                ]);
                $waStatus[] = '⚠️ Error mengirim WhatsApp: ' . $e->getMessage();
            }
        } else {
            $waStatus[] = 'ℹ️ Notifikasi WhatsApp dinonaktifkan';
        }

        // Gabungkan message
        if (!empty($waStatus)) {
            $successMessage .= '. ' . implode('. ', $waStatus);
        }

        // Log aktivitas
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Tambah Pengumuman',
            'deskripsi' => 'Menambahkan pengumuman: ' . $pengumuman->judul,
        ]);

        return redirect()->route('superadmin.pengumuman.index')
            ->with('success', $successMessage);
    }

    /**
     * Tampilkan form edit pengumuman
     */
    public function edit($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        return view('superadmin.pengumuman.edit', compact('pengumuman'));
    }

    /**
     * Update pengumuman
     */
    public function update(Request $request, $id)
    {
        $pengumuman = Pengumuman::findOrFail($id);

        $request->validate([
            'judul' => 'required|string|max:255',
            'konten' => 'required|string',
            'tanggal' => 'required|date',
        ], [
            'judul.required' => 'Judul pengumuman harus diisi',
            'konten.required' => 'Konten pengumuman harus diisi',
            'tanggal.required' => 'Tanggal pengumuman harus diisi',
        ]);

        $pengumuman->update([
            'judul' => $request->judul,
            'konten' => $request->konten,
            'tanggal' => $request->tanggal,
        ]);

        // Log aktivitas
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Update Pengumuman',
            'deskripsi' => 'Mengupdate pengumuman: ' . $pengumuman->judul,
        ]);

        return redirect()->route('superadmin.pengumuman.index')
            ->with('success', 'Pengumuman berhasil diperbarui');
    }

    /**
     * Hapus pengumuman
     */
    public function destroy($id)
    {
        $pengumuman = Pengumuman::findOrFail($id);
        $judul = $pengumuman->judul;

        $pengumuman->delete();

        // Log aktivitas
        LogAktivitas::create([
            'user_id' => auth()->id(),
            'aktivitas' => 'Hapus Pengumuman',
            'deskripsi' => 'Menghapus pengumuman: ' . $judul,
        ]);

        return redirect()->route('superadmin.pengumuman.index')
            ->with('success', 'Pengumuman berhasil dihapus');
    }

    /**
     * Test koneksi WhatsApp
     */
    public function testWhatsApp()
    {
        $result = WhatsAppHelper::testKoneksi();
        
        return redirect()->back()->with(
            $result['success'] ? 'success' : 'error',
            $result['message']
        );
    }
}