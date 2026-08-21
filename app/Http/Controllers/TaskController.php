<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::orderBy('due_date')->get();

        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        return view('tasks.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:Assignment,Project,Quiz,Exam'],
            'description' => ['nullable', 'string'],
            'due_date' => ['required', 'date'],
        ]);

        Task::create([
            'title' => $validated['title'],
            'subject' => $validated['subject'],
            'type' => $validated['type'],
            'description' => $validated['description'] ?? null,
            'due_date' => $validated['due_date'],
            'status' => 'Pending',
        ]);

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task created successfully!');
    }
}