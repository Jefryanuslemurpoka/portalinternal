<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Get all notifications for current user
     */
    public function index()
    {
        $notifications = Auth::user()
            ->notifications()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Get unread notifications (for AJAX)
     */
    public function getUnread()
    {
        $notifications = Auth::user()
            ->notifications()
            ->unread()
            ->limit(10)
            ->get();

        $unreadCount = Auth::user()->unreadNotificationsCount();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount
        ]);
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $notification->markAsRead();

        // Redirect ke URL jika ada
        if ($notification->url) {
            return redirect($notification->url);
        }

        return redirect()->back()->with('success', 'Notifikasi ditandai sudah dibaca');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
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
        ]);
    }

    /**
     * Delete notification
     */
    public function destroy($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $notification->delete();

        return redirect()->back()->with('success', 'Notifikasi berhasil dihapus');
    }

    /**
     * Delete all read notifications
     */
    public function deleteAllRead()
    {
        Auth::user()
            ->notifications()
            ->read()
            ->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notifikasi yang sudah dibaca berhasil dihapus'
        ]);
    }
}