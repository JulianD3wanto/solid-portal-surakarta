<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $notifications = AdminNotification::visibleFor($request->user())
            ->latest()
            ->limit(15)
            ->get()
            ->map(fn (AdminNotification $n) => [
                'id' => $n->id,
                'title' => $n->title,
                'message' => $n->message,
                'link' => $n->link,
                'is_read' => $n->is_read,
                'created_at' => $n->created_at->diffForHumans(),
                'time' => $n->created_at->format('d M Y, H:i'),
            ]);

        $unread = AdminNotification::visibleFor($request->user())->where('is_read', false)->count();

        return response()->json(['notifications' => $notifications, 'unread' => $unread]);
    }

    public function unreadCount(Request $request): JsonResponse
    {
        return response()->json([
            'unread' => AdminNotification::visibleFor($request->user())->where('is_read', false)->count(),
        ]);
    }

    public function markRead(Request $request, AdminNotification $notification): JsonResponse
    {
        abort_unless(
            AdminNotification::visibleFor($request->user())->whereKey($notification->getKey())->exists(),
            403
        );

        $notification->update(['is_read' => true]);

        return response()->json(['ok' => true]);
    }

    public function markAllRead(Request $request): JsonResponse
    {
        AdminNotification::visibleFor($request->user())->where('is_read', false)->update(['is_read' => true]);

        return response()->json(['ok' => true]);
    }
}
