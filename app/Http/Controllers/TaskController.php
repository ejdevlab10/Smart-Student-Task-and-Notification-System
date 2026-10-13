<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Notifications\TaskAssignedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Task::class);

        $user = auth()->user();

        if ($user->isStudent()) {

            // Students see only tasks assigned to them.
            $tasks = $user->assignedTasks()
                ->orderBy('due_date')
                ->get();

        } elseif ($user->isTeacher()) {

            // Teachers see only tasks they created.
            $tasks = Task::where('created_by', $user->id)
                ->orderBy('due_date')
                ->get();

        } elseif ($user->isAdmin()) {

            // Administrators can see every task.
            $tasks = Task::orderBy('due_date')->get();

        } else {

            abort(403);

        }

        return view('tasks.index', compact('tasks'));
    }

    public function create()
    {
        Gate::authorize('create', Task::class);

        $user = auth()->user();

        // Teachers can assign tasks only to classes assigned to them.
        $classes = SchoolClass::where('teacher_id', $user->id)
            ->orderBy('name')
            ->get();

        // Preserve the existing option to assign tasks to individual students.
        $students = User::where('role', 'student')
            ->orderBy('name')
            ->get();

        return view('tasks.create', compact('classes', 'students'));
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Task::class);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],

            'subject' => ['required', 'string', 'max:255'],

            'type' => ['required', 'in:Assignment,Project,Quiz,Exam'],

            'description' => ['nullable', 'string'],

            'due_date' => ['required', 'date'],

            'class_id' => [
                'nullable',
                'integer',
                Rule::exists('school_classes', 'id')
                    ->where('teacher_id', auth()->id()),
            ],

            'students' => ['nullable', 'array'],

            'students.*' => [
                'integer',
                Rule::exists('users', 'id')
                    ->where('role', 'student'),
            ],
        ]);

        // Create the task.
        $task = Task::create([
            'title' => $validated['title'],
            'subject' => $validated['subject'],
            'type' => $validated['type'],
            'description' => $validated['description'] ?? null,
            'due_date' => $validated['due_date'],
            'status' => 'Pending',
            'created_by' => auth()->id(),
            'school_class_id' => $validated['class_id'] ?? null,
        ]);

        // Start with any individually selected students.
        $studentIds = collect($validated['students'] ?? []);

        // Add everyone enrolled in the selected class.
        if (!empty($validated['class_id'])) {
            $class = SchoolClass::with('students')
                ->findOrFail($validated['class_id']);

            $classStudentIds = $class->students->pluck('id');

            $studentIds = $studentIds->merge($classStudentIds);
        }

        // Remove duplicate student IDs.
        $studentIds = $studentIds->unique()->values();

        // Assign the task and send notifications.
        if ($studentIds->isNotEmpty()) {
            $assignments = [];

            foreach ($studentIds as $studentId) {
                $assignments[$studentId] = [
                    'status' => 'Pending',
                    'completed_at' => null,
                ];
            }

            $task->students()->attach($assignments);

            foreach ($studentIds as $studentId) {
                $student = User::find($studentId);

                if ($student) {
                    $student->notify(
                        new TaskAssignedNotification($task)
                    );
                }
            }
        }

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task created and assigned successfully!');
    }

    public function show(Task $task)
    {
        Gate::authorize('view', $task);

        $task->load('students');

        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        Gate::authorize('update', $task);

        return view('tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        Gate::authorize('update', $task);

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
        if (auth()->user()->isStudent()) {

            $assignment = $task->students()
                ->where('users.id', auth()->id())
                ->first();

            if (!$assignment) {
                abort(403);
            }

            $task->students()->updateExistingPivot(auth()->id(), [
                'status' => 'Completed',
                'completed_at' => now(),
            ]);

        } else {

            $task->update([
                'status' => 'Completed',
            ]);
        }

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task marked as completed!');
    }

    public function destroy(Task $task)
    {
        Gate::authorize('delete', $task);

        $task->delete();

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task deleted successfully!');
    }
}