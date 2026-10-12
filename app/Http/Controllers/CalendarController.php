<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Carbon\Carbon;

class CalendarController extends Controller
{
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Task::class);

        $user = auth()->user();

        /*
        |--------------------------------------------------------------------------
        | Calendar Month Navigation
        |--------------------------------------------------------------------------
        */

        $month = $request->input('month');

        if ($month && preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $month)) {

            try {
                $currentMonth = Carbon::createFromFormat(
                    '!Y-m',
                    $month
                )->startOfMonth();

            } catch (\Exception $e) {
                $currentMonth = now()->startOfMonth();
            }

        } else {
            $currentMonth = now()->startOfMonth();
        }

        /*
        |--------------------------------------------------------------------------
        | Calendar Date Range
        |--------------------------------------------------------------------------
        */

        $startDate = $currentMonth->copy()->startOfWeek();

        $endDate = $currentMonth->copy()
            ->endOfMonth()
            ->endOfWeek();

        /*
        |--------------------------------------------------------------------------
        | Role-Based Task Visibility
        |--------------------------------------------------------------------------
        */

        if ($user->isStudent()) {

            // Students see only their assigned tasks.
            $tasks = $user->assignedTasks()
                ->whereBetween('due_date', [
                    $startDate,
                    $endDate
                ])
                ->orderBy('due_date')
                ->get();

        } elseif ($user->isTeacher()) {

            // Teachers see only tasks they created.
            $tasks = Task::where('created_by', $user->id)
                ->whereBetween('due_date', [
                    $startDate,
                    $endDate
                ])
                ->orderBy('due_date')
                ->get();

        } elseif ($user->isAdmin()) {

            // Administrators see all tasks.
            $tasks = Task::whereBetween('due_date', [
                    $startDate,
                    $endDate
                ])
                ->orderBy('due_date')
                ->get();

        } else {

            abort(403);

        }

        /*
        |--------------------------------------------------------------------------
        | Return Calendar View
        |--------------------------------------------------------------------------
        */

        return view('calendar.index', compact(
            'tasks',
            'currentMonth'
        ));
    }
}