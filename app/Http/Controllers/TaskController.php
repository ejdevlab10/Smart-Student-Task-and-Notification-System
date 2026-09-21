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
            'created_by' => auth()->id(),
        ]);

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task created successfully!');
    }

    public function show(Task $task)
    {
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:Assignment,Project,Quiz,Exam'],
            'description' => ['nullable', 'string'],
            'due_date' => ['required', 'date'],
        ]);

        $task->update($validated);

        return redirect()
            ->route('tasks.show', $task)
            ->with('success', 'Task updated successfully!');
    }

    public function complete(Task $task)
    {
        $task->update([
            'status' => 'Completed',
        ]);

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task marked as completed!');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task deleted successfully!');
    }
}