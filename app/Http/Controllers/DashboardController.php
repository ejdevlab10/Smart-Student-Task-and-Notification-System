<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Announcement;
use App\Models\User;
use Illuminate\Support\Facades\DB;

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
                    now()->copy()->addDays(7),
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

            // Number of tasks created by this teacher.
            $totalTasks = (clone $teacherTasks)->count();

            // Active assignments are tasks that have at least one
            // assigned student whose pivot status is still Pending.
            $activeAssignments = (clone $teacherTasks)
                ->whereHas('students', function ($query) {
                    $query->where('task_user.status', 'Pending');
                })
                ->count();

            // Count distinct students assigned to this teacher's tasks.
            $studentsAssigned = DB::table('task_user')
                ->join('tasks', 'tasks.id', '=', 'task_user.task_id')
                ->where('tasks.created_by', $user->id)
                ->distinct()
                ->count('task_user.user_id');

            // Count teacher-created tasks with deadlines in the next 7 days.
            $dueSoon = (clone $teacherTasks)
                ->where('due_date', '>=', now())
                ->where('due_date', '<=', now()->copy()->addDays(7))
                ->whereHas('students', function ($query) {
                    $query->where('task_user.status', 'Pending');
                })
                ->count();

            $upcomingTasks = (clone $teacherTasks)
                ->where('due_date', '>=', now())
                ->whereHas('students', function ($query) {
                    $query->where('task_user.status', 'Pending');
                })
                ->orderBy('due_date')
                ->take(5)
                ->get();

            $recentTasks = (clone $teacherTasks)
                ->orderByDesc('created_at')
                ->take(5)
                ->get();

            // Calculate progress using student assignment statuses,
            // rather than the task's own status column.
            $completionStats = [];

            foreach ((clone $teacherTasks)->with('students')->get() as $task) {

                $totalStudents = $task->students->count();

                $completedStudents = $task->students
                    ->filter(function ($student) {
                        return $student->pivot->status === 'Completed';
                    })
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
                'activeAssignments',
                'studentsAssigned',
                'dueSoon',
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

        $totalAnnouncements = Announcement::count();

        // System-wide task assignment statistics.
        $totalAssignments = DB::table('task_user')->count();

        $completedAssignments = DB::table('task_user')
            ->where('status', 'Completed')
            ->count();

        $pendingAssignments = DB::table('task_user')
            ->where('status', 'Pending')
            ->count();

        // Keep the existing Blade variable names for compatibility.
        $pendingTasks = $pendingAssignments;
        $completedTasks = $completedAssignments;

        $dueSoon = Task::where('due_date', '>=', now())
            ->where('due_date', '<=', now()->copy()->addDays(7))
            ->whereHas('students', function ($query) {
                $query->where('task_user.status', 'Pending');
            })
            ->count();

        $recentTasks = Task::with('creator')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        $announcements = Announcement::orderByDesc('is_pinned')
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        $systemProgress = $totalAssignments > 0
            ? round(($completedAssignments / $totalAssignments) * 100)
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