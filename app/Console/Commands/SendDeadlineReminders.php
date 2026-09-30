<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Notifications\TaskDeadlineReminderNotification;
use Illuminate\Console\Command;

class SendDeadlineReminders extends Command
{
    protected $signature = 'tasks:send-deadline-reminders';

    protected $description = 'Send notifications for upcoming task deadlines';

    public function handle()
    {
        $now = now();

        $tasks = Task::where('status', 'Pending')
            ->where('due_date', '>', $now)
            ->where('due_date', '<=', $now->copy()->addHours(24))
            ->with('students')
            ->get();

        foreach ($tasks as $task) {

            $hoursRemaining = $now->diffInHours($task->due_date);

            if ($hoursRemaining <= 1) {
                $reminderType = '1_hour';
            } elseif ($hoursRemaining <= 24) {
                $reminderType = '24_hours';
            } else {
                continue;
            }

            foreach ($task->students as $student) {

                $alreadySent = $student->notifications()
                    ->where('type', TaskDeadlineReminderNotification::class)
                    ->where('data->task_id', $task->id)
                    ->where('data->reminder_type', $reminderType)
                    ->exists();

                if ($alreadySent) {
                    continue;
                }

                $student->notify(
                    new TaskDeadlineReminderNotification(
                        $task,
                        $reminderType
                    )
                );

                $this->info(
                    "Sent {$reminderType} reminder for Task #{$task->id} to {$student->email}"
                );
            }
        }

        return Command::SUCCESS;
    }
}