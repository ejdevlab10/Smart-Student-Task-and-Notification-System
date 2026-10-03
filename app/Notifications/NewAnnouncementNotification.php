<?php

namespace App\Notifications;

use App\Models\Announcement;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewAnnouncementNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Announcement $announcement
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New Announcement',
            'message' => $this->announcement->title,
            'announcement_id' => $this->announcement->id,
            'category' => $this->announcement->category,
        ];
    }
}