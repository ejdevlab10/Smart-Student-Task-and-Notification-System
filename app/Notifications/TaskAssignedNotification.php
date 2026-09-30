<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TaskAssignedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Task $task
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'New Task Assigned',
            'message' => 'You have been assigned: ' . $this->task->title,
            'task_id' => $this->task->id,
            'due_date' => $this->task->due_date->format('M d, Y h:i A'),
        ];
    }
}