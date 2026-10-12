<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Announcement;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | Student Dashboard
        |--------------------------------------------------------------------------
        */
        if ($user->isStudent()) {

            $assignedTasks = $user->assignedTasks();

            $totalTasks = (clone $assignedTasks)->count();

            $pendingTasks = (clone $assignedTasks)
                ->wherePivot('status', 'Pending')
                ->count();

            $completedTasks = (clone $assignedTasks)
                ->wherePivot('status', 'Completed')
                ->count();

            $dueSoon = (clone $assignedTasks)
                ->wherePivot('status', 'Pending')
                ->whereBetween('due_date', [
                    now(),
                    now()->addDays(7)
                ])
                ->count();

            $overdueTasks = (clone $assignedTasks)
                ->wherePivot('status', 'Pending')
                ->where('due_date', '<', now())
                ->count();

            $progress = $totalTasks > 0
                ? round(($completedTasks / $totalTasks) * 100)
                : 0;

            $upcomingTasks = (clone $assignedTasks)
                ->wherePivot('status', 'Pending')
                ->where('due_date', '>=', now())
                ->orderBy('due_date')
                ->take(5)
                ->get();

            $recentCompletedTasks = (clone $assignedTasks)
                ->wherePivot('status', 'Completed')
                ->orderByDesc('pivot_completed_at')
                ->take(5)
                ->get();

            $announcements = Announcement::orderByDesc('is_pinned')
                ->orderByDesc('created_at')
                ->take(4)
                ->get();

            $unreadNotifications = $user->unreadNotifications()->count();

            return view('dashboard', compact(
                'totalTasks',
                'pendingTasks',
                'completedTasks',
                'dueSoon',
                'overdueTasks',
                'progress',
                'upcomingTasks',
                'recentCompletedTasks',
                'announcements',
                'unreadNotifications'
            ));
        }


        /*
        |--------------------------------------------------------------------------
        | Teacher Dashboard
        |--------------------------------------------------------------------------
        */
        if ($user->isTeacher()) {

            $teacherTasks = Task::where('created_by', $user->id);

            $totalTasks = (clone $teacherTasks)->count();

            $pendingTasks = (clone $teacherTasks)
                ->where('status', 'Pending')
                ->count();

            $completedTasks = (clone $teacherTasks)
                ->where('status', 'Completed')
                ->count();

            $dueSoon = (clone $teacherTasks)
                ->where('status', 'Pending')
                ->whereBetween('due_date', [
                    now(),
                    now()->addDays(7)
                ])
                ->count();

            $studentsAssigned = \DB::table('task_user')
                ->join('tasks', 'tasks.id', '=', 'task_user.task_id')
                ->where('tasks.created_by', $user->id)
                ->distinct('task_user.user_id')
                ->count('task_user.user_id');

            $upcomingTasks = (clone $teacherTasks)
                ->where('status', 'Pending')
                ->where('due_date', '>=', now())
                ->orderBy('due_date')
                ->take(5)
                ->get();

            $recentTasks = (clone $teacherTasks)
                ->orderByDesc('created_at')
                ->take(5)
                ->get();

            $completionStats = [];

            foreach ((clone $teacherTasks)->with('students')->get() as $task) {

                $totalStudents = $task->students->count();

                $completedStudents = $task->students
                    ->where('pivot.status', 'Completed')
                    ->count();

                $completionStats[] = [
                    'task' => $task,
                    'total' => $totalStudents,
                    'completed' => $completedStudents,
                    'progress' => $totalStudents > 0
                        ? round(($completedStudents / $totalStudents) * 100)
                        : 0,
                ];
            }

            $announcements = Announcement::orderByDesc('is_pinned')
                ->orderByDesc('created_at')
                ->take(4)
                ->get();

            return view('dashboard', compact(
                'totalTasks',
                'pendingTasks',
                'completedTasks',
                'dueSoon',
                'studentsAssigned',
                'upcomingTasks',
                'recentTasks',
                'completionStats',
                'announcements'
            ));
        }


        /*
        |--------------------------------------------------------------------------
        | Admin Dashboard
        |--------------------------------------------------------------------------
        */

        $totalStudents = User::where('role', 'student')->count();

        $totalTeachers = User::where('role', 'teacher')->count();

        $totalTasks = Task::count();

        $pendingTasks = Task::where('status', 'Pending')->count();

        $completedTasks = Task::where('status', 'Completed')->count();

        $totalAnnouncements = Announcement::count();

        $dueSoon = Task::where('status', 'Pending')
            ->whereBetween('due_date', [
                now(),
                now()->addDays(7)
            ])
            ->count();

        $recentTasks = Task::with('creator')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        $announcements = Announcement::orderByDesc('is_pinned')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        $systemProgress = $totalTasks > 0
            ? round(($completedTasks / $totalTasks) * 100)
            : 0;

        return view('dashboard', compact(
            'totalStudents',
            'totalTeachers',
            'totalTasks',
            'pendingTasks',
            'completedTasks',
            'totalAnnouncements',
            'dueSoon',
            'recentTasks',
            'announcements',
            'systemProgress'
        ));
    }
}