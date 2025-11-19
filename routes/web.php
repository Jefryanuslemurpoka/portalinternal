<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\NotificationController;

// Redirect root ke login
Route::get('/', function () {
    return redirect('/login');
});

// Authentication Routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ✅ Notifications Routes (Semua dalam satu group)
Route::middleware(['auth'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread', [NotificationController::class, 'getUnread'])->name('notifications.unread');
    Route::get('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    Route::post('/notifications/delete-all-read', [NotificationController::class, 'deleteAllRead'])->name('notifications.deleteAllRead');
});
Route::get('/test-wa-grup', function() {
    $enabled = config('whatsapp.enabled');
    $token = config('whatsapp.api_token');
    $grupUrl = config('whatsapp.group_url');
    $apiUrl = config('whatsapp.api_url');
    
    if (!$token) {
        return response()->json([
            'error' => 'Token tidak ditemukan di .env',
            'hint' => 'Pastikan WHATSAPP_API_TOKEN sudah diisi dan php artisan config:clear sudah dijalankan'
        ]);
    }
    
    try {
        // Test dengan format Fonnte yang benar
        $response = \Illuminate\Support\Facades\Http::timeout(30)
            ->withHeaders([
                'Authorization' => $token,
            ])
            ->asForm() // Penting: Fonnte butuh form-data
            ->post($apiUrl, [
                'target' => $grupUrl,
                'message' => "🧪 *Test Kirim ke Grup*\n\nPesan ini dikirim dari Laravel Portal Internal\n\n🕐 " . now()->format('d M Y H:i:s'),
                'countryCode' => '62',
            ]);
        
        return response()->json([
            'success' => $response->successful(),
            'http_status' => $response->status(),
            'response_body' => $response->json(),
            'response_raw' => $response->body(),
            'config' => [
                'enabled' => $enabled,
                'token_length' => strlen($token),
                'token_preview' => substr($token, 0, 10) . '...',
                'group_url' => $grupUrl,
                'api_url' => $apiUrl,
            ]
        ], 200, [], JSON_PRETTY_PRINT);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ], 500, [], JSON_PRETTY_PRINT);
    }
})->middleware('auth');

// Include route files untuk Super Admin dan Karyawan
require __DIR__.'/superadmin.php';
require __DIR__.'/karyawan.php';
require __DIR__.'/finance.php';