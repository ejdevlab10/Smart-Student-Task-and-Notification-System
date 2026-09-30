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

        if (!empty($notification->data['task_id'])) {
            return redirect()->route(
                'tasks.show',
                $notification->data['task_id']
            );
        }

        return redirect()->route('notifications.index');
    }
}