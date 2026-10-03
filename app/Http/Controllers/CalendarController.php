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

        $month = $request->input('month');

        if ($month) {
            try {
                $currentMonth = Carbon::createFromFormat('Y-m', $month)->startOfMonth();
            } catch (\Exception $e) {
                $currentMonth = now()->startOfMonth();
            }
        } else {
            $currentMonth = now()->startOfMonth();
        }

        if (auth()->user()->isStudent()) {
            $tasks = auth()->user()
                ->assignedTasks()
                ->whereBetween('due_date', [
                    $currentMonth->copy()->startOfWeek(),
                    $currentMonth->copy()->endOfMonth()->endOfWeek(),
                ])
                ->orderBy('due_date')
                ->get();
        } else {
            $tasks = Task::whereBetween('due_date', [
                $currentMonth->copy()->startOfWeek(),
                $currentMonth->copy()->endOfMonth()->endOfWeek(),
            ])
            ->orderBy('due_date')
            ->get();
        }

        return view('calendar.index', compact(
            'tasks',
            'currentMonth'
        ));
    }
}