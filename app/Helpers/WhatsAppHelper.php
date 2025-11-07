<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppHelper
{
    /**
     * Kirim pesan ke grup WhatsApp
     * 
     * @param string $message
     * @return bool
     */
    public static function kirimKeGrup($message)
    {
        try {
            // Cek apakah WhatsApp enabled
            if (!config('whatsapp.enabled')) {
                Log::info('WhatsApp notification disabled');
                return false;
            }

            $apiToken = config('whatsapp.api_token');
            $apiUrl = config('whatsapp.api_url');
            $grupUrl = config('whatsapp.group_url');

            // Validasi konfigurasi
            if (empty($apiToken)) {
                Log::warning('WhatsApp API Token not configured');
                return false;
            }

            if (empty($grupUrl)) {
                Log::warning('WhatsApp Group URL not configured');
                return false;
            }

            // Kirim menggunakan Fonnte API dengan form-data
            $response = Http::timeout(config('whatsapp.timeout', 30))
                ->withHeaders([
                    'Authorization' => $apiToken,
                ])
                ->asForm() // Fonnte butuh form-data
                ->post($apiUrl, [
                    'target' => $grupUrl,
                    'message' => $message,
                    'countryCode' => '62',
                ]);

            // Log full response untuk debugging
            Log::info('WhatsApp API Response', [
                'status_code' => $response->status(),
                'body' => $response->body(),
                'json' => $response->json(),
            ]);

            // Check response
            if ($response->successful()) {
                $result = $response->json();
                
                // Fonnte success response bisa berbagai format
                if (isset($result['status'])) {
                    if ($result['status'] === true || $result['status'] === 'success') {
                        Log::info('WhatsApp sent successfully', $result);
                        return true;
                    }
                }

                // Kadang Fonnte return array dengan detail
                if (isset($result['detail']) && is_array($result['detail'])) {
                    $firstDetail = reset($result['detail']);
                    if (isset($firstDetail['status']) && ($firstDetail['status'] === 'success' || $firstDetail['status'] === 'pending')) {
                        Log::info('WhatsApp sent successfully', $result);
                        return true;
                    }
                }

                // Log unexpected format
                Log::warning('WhatsApp API unexpected response format', $result);
                return false;
            }

            // HTTP error
            Log::error('WhatsApp API HTTP error', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);
            return false;

        } catch (\Exception $e) {
            Log::error('Error mengirim pesan WhatsApp: ' . $e->getMessage(), [
                'exception' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Format pesan pengumuman untuk WhatsApp
     * 
     * @param \App\Models\Pengumuman $pengumuman
     * @return string
     */
    public static function formatPesanPengumuman($pengumuman)
    {
        $appName = config('app.name', 'Portal Internal');
        $tanggal = $pengumuman->tanggal->format('d F Y');
        $creator = $pengumuman->creator->name ?? 'Admin';
        
        $pesan = "🔔 *PENGUMUMAN BARU* 🔔\n\n";
        $pesan .= "📌 *{$pengumuman->judul}*\n";
        $pesan .= "━━━━━━━━━━━━━━━━━━━\n\n";
        $pesan .= "{$pengumuman->konten}\n\n";
        $pesan .= "━━━━━━━━━━━━━━━━━━━\n";
        $pesan .= "📅 Tanggal: {$tanggal}\n";
        $pesan .= "👤 Oleh: {$creator}\n\n";
        $pesan .= "_Cek detail lengkap di {$appName}_";

        return $pesan;
    }

    /**
     * Generate WhatsApp Web link untuk manual send
     * 
     * @param string $message
     * @return string
     */
    public static function generateWebLink($message)
    {
        $encodedMessage = urlencode($message);
        return "https://web.whatsapp.com/send?text={$encodedMessage}";
    }

    /**
     * Kirim pesan test ke grup
     * 
     * @return array
     */
    public static function testKoneksi()
    {
        $testMessage = "✅ *Test Koneksi WhatsApp*\n\n";
        $testMessage .= "Pesan ini adalah test koneksi dari sistem.\n";
        $testMessage .= "Jika Anda menerima pesan ini, artinya integrasi WhatsApp berhasil!\n\n";
        $testMessage .= "🕒 " . now()->format('d F Y H:i:s');

        $success = self::kirimKeGrup($testMessage);
        
        return [
            'success' => $success,
            'message' => $success ? 'Test berhasil! Cek grup WhatsApp Anda.' : 'Test gagal. Periksa log untuk detail.',
        ];
    }

    /**
     * Ekstrak ID grup dari URL WhatsApp
     * 
     * @param string $url
     * @return string|null
     */
    public static function extractGroupId($url)
    {
        // Format: https://chat.whatsapp.com/FK68KgjgdrmCizLlujQX15
        if (preg_match('/chat\.whatsapp\.com\/([A-Za-z0-9]+)/', $url, $matches)) {
            return $matches[1];
        }
        return null;
    }
}