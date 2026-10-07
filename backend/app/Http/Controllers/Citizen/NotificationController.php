<?php

namespace App\Http\Controllers\Citizen;

use App\Http\Controllers\Controller;
use App\Models\CitizenNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $notifications = CitizenNotification::visibleFor($request->user())
            ->latest()
            ->limit(15)
            ->get()
            ->map(fn (CitizenNotification $n) => [
                'id' => $n->id,
                'title' => $n->title,
                'message' => $n->message,
                'link' => $n->link,
                'is_read' => $n->is_read,
                'created_at' => $n->created_at->diffForHumans(),
                'time' => $n->created_at->format('d M Y, H:i'),
            ]);

        $unread = CitizenNotification::visibleFor($request->user())->where('is_read', false)->count();

        return response()->json(['notifications' => $notifications, 'unread' => $unread]);
    }

    public function markRead(Request $request, CitizenNotification $notification): JsonResponse
    {
        abort_unless(
            CitizenNotification::visibleFor($request->user())->whereKey($notification->getKey())->exists(),
            403
        );

        $notification->update(['is_read' => true]);

        return response()->json(['ok' => true]);
    }

    public function markAllRead(Request $request): JsonResponse
    {
        CitizenNotification::visibleFor($request->user())->where('is_read', false)->update(['is_read' => true]);

        return response()->json(['ok' => true]);
    }
}
