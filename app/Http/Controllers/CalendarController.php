<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Support\Facades\Gate;

class CalendarController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Task::class);

        if (auth()->user()->isStudent()) {
            $tasks = auth()->user()
                ->assignedTasks()
                ->orderBy('due_date')
                ->get();
        } else {
            $tasks = Task::orderBy('due_date')->get();
        }

        return view('calendar.index', compact('tasks'));
    }
}