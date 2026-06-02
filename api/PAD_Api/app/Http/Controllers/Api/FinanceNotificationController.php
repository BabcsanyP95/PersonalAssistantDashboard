<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FinanceNotification;
use Illuminate\Http\Request;

class FinanceNotificationController extends Controller
{
    public function index(Request $request)
    {
        return FinanceNotification::where('user_id', $request->user()->id)
            ->latest()
            ->get();
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'nullable|in:info,warning,success',
        ]);

        return FinanceNotification::create([
            ...$validated,
            'user_id' => $request->user()->id,
            'type' => $validated['type'] ?? 'info',
            'is_read' => false,
        ]);
    }

    public function markAsRead(Request $request, FinanceNotification $notification)
    {
        $this->authorize($request, $notification);

        $notification->update([
            'is_read' => true,
        ]);

        return response()->json(['message' => 'Marked as read']);
    }

    public function destroy(Request $request, FinanceNotification $notification)
    {
        $this->authorize($request, $notification);

        $notification->delete();

        return response()->json(['message' => 'Deleted']);
    }

    public function markAllAsRead(Request $request)
    {
        FinanceNotification::where('user_id', $request->user()->id)
            ->update(['is_read' => true]);

        return response()->json(['message' => 'All marked as read']);
    }

    private function authorize($request, $notification)
    {
        abort_if(
            $notification->user_id !== $request->user()->id,
            403
        );
    }
}