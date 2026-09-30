<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TaskDeadlineReminderNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Task $task,
        public string $reminderType
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $message = match ($this->reminderType) {
            '24_hours' => 'Your task is due within 24 hours: ' . $this->task->title,
            '1_hour' => 'Your task is due within 1 hour: ' . $this->task->title,
            default => 'Reminder: ' . $this->task->title,
        };

        return [
            'title' => 'Deadline Reminder',
            'message' => $message,
            'task_id' => $this->task->id,
            'reminder_type' => $this->reminderType,
            'due_date' => $this->task->due_date->format('M d, Y h:i A'),
        ];
    }
}