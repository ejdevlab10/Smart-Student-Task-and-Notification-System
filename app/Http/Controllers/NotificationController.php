<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()
            ->notifications()
            ->latest()
            ->get();

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(string $id)
    {
        $notification = auth()->user()
            ->notifications()
            ->findOrFail($id);

        $notification->markAsRead();

        // Open task notification
        if (!empty($notification->data['task_id'])) {
            return redirect()->route(
                'tasks.show',
                $notification->data['task_id']
            );
        }

        // Open announcement notification
        if (!empty($notification->data['announcement_id'])) {
            return redirect()->route(
                'announcements.show',
                $notification->data['announcement_id']
            );
        }

        return redirect()->route('notifications.index');
    }
}