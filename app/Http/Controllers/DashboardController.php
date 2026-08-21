<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
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

        return view('dashboard', compact(
            'pendingTasks',
            'dueSoon',
            'completedTasks',
            'progress',
            'upcomingTasks'
        ));
    }
}