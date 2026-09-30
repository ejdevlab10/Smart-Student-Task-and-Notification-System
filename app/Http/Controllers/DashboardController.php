<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Announcement;

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

            $totalTasks = (clone $assignedTasks)->count();

            $progress = $totalTasks > 0
                ? round(($completedTasks / $totalTasks) * 100)
                : 0;

            $upcomingTasks = (clone $assignedTasks)
                ->wherePivot('status', 'Pending')
                ->where('due_date', '>=', now())
                ->orderBy('due_date')
                ->take(5)
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | Teacher Dashboard
        |--------------------------------------------------------------------------
        */
        elseif ($user->isTeacher()) {

            $teacherTasks = Task::where('created_by', $user->id);

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

            $totalTasks = (clone $teacherTasks)->count();

            $progress = $totalTasks > 0
                ? round(($completedTasks / $totalTasks) * 100)
                : 0;

            $upcomingTasks = (clone $teacherTasks)
                ->where('status', 'Pending')
                ->where('due_date', '>=', now())
                ->orderBy('due_date')
                ->take(5)
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | Admin Dashboard
        |--------------------------------------------------------------------------
        */
        else {

            $pendingTasks = Task::where('status', 'Pending')->count();

            $completedTasks = Task::where('status', 'Completed')->count();

            $dueSoon = Task::where('status', 'Pending')
                ->whereBetween('due_date', [
                    now(),
                    now()->addDays(7)
                ])
                ->count();

            $totalTasks = Task::count();

            $progress = $totalTasks > 0
                ? round(($completedTasks / $totalTasks) * 100)
                : 0;

            $upcomingTasks = Task::where('status', 'Pending')
                ->where('due_date', '>=', now())
                ->orderBy('due_date')
                ->take(5)
                ->get();
        }

        /*
        |--------------------------------------------------------------------------
        | Recent Announcements
        |--------------------------------------------------------------------------
        */
        $announcements = Announcement::orderByDesc('is_pinned')
            ->orderByDesc('created_at')
            ->take(3)
            ->get();

        return view('dashboard', compact(
            'pendingTasks',
            'dueSoon',
            'completedTasks',
            'progress',
            'upcomingTasks',
            'announcements'
        ));
    }
}