<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class LandingController extends Controller
{
    /**
     * Display landing page
     */
    public function index()
    {
        return view('landing.index');
    }

    /**
     * Handle contact form submission
     */
    public function contact(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'nullable|string|max:255',
            'message' => 'required|string|max:1000',
        ], [
            'name.required' => 'Nama wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'message.required' => 'Pesan wajib diisi',
            'message.max' => 'Pesan maksimal 1000 karakter',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Mohon periksa kembali form Anda.');
        }

        try {
            // Data yang akan dikirim
            $data = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'subject' => $request->subject ?? 'Pesan dari Landing Page',
                'message' => $request->message,
                'sent_at' => now()->format('d M Y H:i:s'),
            ];

            // Kirim email ke admin (opsional)
            // Uncomment jika ingin kirim email
            /*
            Mail::send('emails.contact', $data, function($message) use ($data) {
                $message->to(config('mail.from.address'))
                        ->subject('Pesan Baru: ' . $data['subject'])
                        ->replyTo($data['email'], $data['name']);
            });
            */

            // Kirim notifikasi WhatsApp (opsional)
            // Uncomment jika ingin kirim ke WhatsApp
            /*
            if (config('whatsapp.enabled')) {
                $waMessage = "📩 *PESAN BARU DARI WEBSITE*\n\n";
                $waMessage .= "👤 *Nama:* {$data['name']}\n";
                $waMessage .= "📧 *Email:* {$data['email']}\n";
                $waMessage .= "📱 *No. HP:* {$data['phone']}\n";
                $waMessage .= "📝 *Subjek:* {$data['subject']}\n\n";
                $waMessage .= "💬 *Pesan:*\n{$data['message']}\n\n";
                $waMessage .= "🕐 *Waktu:* {$data['sent_at']}";

                \Illuminate\Support\Facades\Http::timeout(30)
                    ->withHeaders([
                        'Authorization' => config('whatsapp.api_token'),
                    ])
                    ->asForm()
                    ->post(config('whatsapp.api_url'), [
                        'target' => config('whatsapp.group_url'),
                        'message' => $waMessage,
                        'countryCode' => '62',
                    ]);
            }
            */

            // Simpan ke database (opsional)
            // Uncomment jika ingin simpan ke database
            /*
            \App\Models\Contact::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'],
                'subject' => $data['subject'],
                'message' => $data['message'],
            ]);
            */

            return back()
                ->with('success', 'Terima kasih! Pesan Anda telah terkirim. Kami akan segera menghubungi Anda.');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Maaf, terjadi kesalahan. Silakan coba lagi atau hubungi kami langsung.');
        }
    }
}