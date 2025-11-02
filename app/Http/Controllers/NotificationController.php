<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    /**
     * Get all notifications for current user
     */
    public function index()
    {
        try {
            $notifications = Auth::user()
                ->notifications()
                ->paginate(20);

            return view('notifications.index', compact('notifications'));
            
        } catch (\Exception $e) {
            Log::error('Notification Index Error: ' . $e->getMessage());
            return view('notifications.index', ['notifications' => collect()]);
        }
    }

    /**
     * Get unread notifications (for AJAX polling)
     */
    public function getUnread()
    {
        try {
            // ✅ CRITICAL: Release session immediately
            if (session()->isStarted()) {
                session()->save();
            }
            
            // ✅ Gunakan scope unread() dari Model
            $notifications = Auth::user()
                ->notifications()
                ->unread()
                ->limit(10)
                ->get();
            
            // ✅ Gunakan method dari User model
            $unreadCount = Auth::user()->unreadNotificationsCount();
            
            return response()->json([
                'notifications' => $notifications,
                'unread_count' => $unreadCount
            ], 200);
            
        } catch (\Exception $e) {
            // ✅ Log error tapi tetap return valid response
            Log::error('Notification getUnread Error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'notifications' => [],
                'unread_count' => 0
            ], 200);
        }
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        try {
            $notification = Notification::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            // ✅ Gunakan method markAsRead() dari Model
            $notification->markAsRead();

            // Redirect ke URL jika ada
            if ($notification->url) {
                return redirect($notification->url);
            }

            return redirect()->back()->with('success', 'Notifikasi ditandai sudah dibaca');
            
        } catch (\Exception $e) {
            Log::error('Notification markAsRead Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menandai notifikasi');
        }
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        try {
            // ✅ Release session untuk AJAX
            if (session()->isStarted()) {
                session()->save();
            }
            
            // ✅ Gunakan scope unread() dari Model
            Auth::user()
                ->notifications()
                ->unread()
                ->update([
                    'is_read' => true,
                    'read_at' => now()
                ]);

            return response()->json([
                'success' => true,
                'message' => 'Semua notifikasi ditandai sudah dibaca'
            ], 200);
            
        } catch (\Exception $e) {
            Log::error('Notification markAllAsRead Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menandai notifikasi'
            ], 200);
        }
    }

    /**
     * Delete notification
     */
    public function destroy($id)
    {
        try {
            $notification = Notification::where('id', $id)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            $notification->delete();

            return redirect()->back()->with('success', 'Notifikasi berhasil dihapus');
            
        } catch (\Exception $e) {
            Log::error('Notification destroy Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus notifikasi');
        }
    }

    /**
     * Delete all read notifications
     */
    public function deleteAllRead()
    {
        try {
            // ✅ Release session untuk AJAX
            if (session()->isStarted()) {
                session()->save();
            }
            
            // ✅ Gunakan scope read() dari Model
            Auth::user()
                ->notifications()
                ->read()
                ->delete();

            return response()->json([
                'success' => true,
                'message' => 'Notifikasi yang sudah dibaca berhasil dihapus'
            ], 200);
            
        } catch (\Exception $e) {
            Log::error('Notification deleteAllRead Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus notifikasi'
            ], 200);
        }
    }
}