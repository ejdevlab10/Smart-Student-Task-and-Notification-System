<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\SchoolClass;
use App\Models\User;
use App\Notifications\TaskAssignedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Task::class);

        $user = auth()->user();

        if ($user->isStudent()) {
            $tasks = $user->assignedTasks()
                ->orderBy('due_date')
                ->get();
        } elseif ($user->isTeacher()) {
            $tasks = Task::where('created_by', $user->id)
                ->orderBy('due_date')
                ->get();
        } elseif ($user->isAdmin()) {
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

        $classes = $this->availableClasses($user);

        $students = User::where('role', 'student')
            ->orderBy('name')
            ->get();

        return view('tasks.create', compact('classes', 'students'));
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Task::class);

        $user = auth()->user();

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
                    ->where(function ($query) use ($user) {
                        if (!$user->isAdmin()) {
                            $query->where('teacher_id', $user->id);
                        }
                    }),
            ],

            'students' => ['nullable', 'array'],
            'students.*' => [
                'integer',
                'distinct',
                Rule::exists('users', 'id')
                    ->where('role', 'student'),
            ],
        ]);

        $task = Task::create([
            'title' => $validated['title'],
            'subject' => $validated['subject'],
            'type' => $validated['type'],
            'description' => $validated['description'] ?? null,
            'due_date' => $validated['due_date'],
            'status' => 'Pending',
            'created_by' => $user->id,
            'school_class_id' => $validated['class_id'] ?? null,
        ]);

        $studentIds = collect($validated['students'] ?? []);

        if (!empty($validated['class_id'])) {
            $class = SchoolClass::with('students')
                ->findOrFail($validated['class_id']);

            $studentIds = $studentIds->merge(
                $class->students->pluck('id')
            );
        }

        $studentIds = $studentIds->unique()->values();

        if ($studentIds->isNotEmpty()) {
            $assignments = [];

            foreach ($studentIds as $studentId) {
                $assignments[$studentId] = [
                    'status' => 'Pending',
                    'completed_at' => null,
                ];
            }

            $task->students()->attach($assignments);

            $studentsToNotify = User::whereIn('id', $studentIds)->get();

            foreach ($studentsToNotify as $student) {
                $student->notify(
                    new TaskAssignedNotification($task)
                );
            }
        }

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task created and assigned successfully!');
    }

    public function show(Task $task)
    {
        Gate::authorize('view', $task);

        $task->load(['students', 'schoolClass.teacher']);

        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task)
    {
        Gate::authorize('update', $task);

        $user = auth()->user();

        $classes = $this->availableClasses($user);

        $students = User::where('role', 'student')
            ->orderBy('name')
            ->get();

        $task->load(['students', 'schoolClass']);

        return view('tasks.edit', compact(
            'task',
            'classes',
            'students'
        ));
    }

    public function update(Request $request, Task $task)
    {
        Gate::authorize('update', $task);

        $user = auth()->user();

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
                    ->where(function ($query) use ($user) {
                        if (!$user->isAdmin()) {
                            $query->where('teacher_id', $user->id);
                        }
                    }),
            ],

            'students' => ['nullable', 'array'],
            'students.*' => [
                'integer',
                'distinct',
                Rule::exists('users', 'id')
                    ->where('role', 'student'),
            ],
        ]);

        // Update the task's basic information and selected class.
        $task->update([
            'title' => $validated['title'],
            'subject' => $validated['subject'],
            'type' => $validated['type'],
            'description' => $validated['description'] ?? null,
            'due_date' => $validated['due_date'],
            'school_class_id' => $validated['class_id'] ?? null,
        ]);

        // Build the new list of students from individual selections.
        $studentIds = collect($validated['students'] ?? []);

        // Include all students currently enrolled in the selected class.
        if (!empty($validated['class_id'])) {
            $class = SchoolClass::with('students')
                ->findOrFail($validated['class_id']);

            $studentIds = $studentIds->merge(
                $class->students->pluck('id')
            );
        }

        $studentIds = $studentIds
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        // Remember existing assignments so their progress is preserved.
        $existingAssignments = $task->students()
            ->get()
            ->keyBy('id');

        $syncData = [];
        $newStudentIds = [];

        foreach ($studentIds as $studentId) {
            if ($existingAssignments->has($studentId)) {
                // Keep this student's current completion status and date.
                $pivot = $existingAssignments[$studentId]->pivot;

                $syncData[$studentId] = [
                    'status' => $pivot->status,
                    'completed_at' => $pivot->completed_at,
                ];
            } else {
                // Newly assigned students start as Pending.
                $syncData[$studentId] = [
                    'status' => 'Pending',
                    'completed_at' => null,
                ];

                $newStudentIds[] = $studentId;
            }
        }

        // Synchronize assignments, removing students no longer selected.
        $task->students()->sync($syncData);

        // Notify only students who have just been assigned this task.
        if (!empty($newStudentIds)) {
            $newStudents = User::whereIn('id', $newStudentIds)->get();

            foreach ($newStudents as $student) {
                $student->notify(
                    new TaskAssignedNotification($task)
                );
            }
        }

        return redirect()
            ->route('tasks.show', $task)
            ->with('success', 'Task and student assignments updated successfully!');
    }

    public function complete(Task $task)
    {
        $user = auth()->user();

        if ($user->isStudent()) {
            $assignment = $task->students()
                ->where('users.id', $user->id)
                ->first();

            if (!$assignment) {
                abort(403);
            }

            $task->students()->updateExistingPivot($user->id, [
                'status' => 'Completed',
                'completed_at' => now(),
            ]);
        } else {
            Gate::authorize('update', $task);

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

    /**
     * Return classes the current user is allowed to assign tasks to.
     */
    private function availableClasses(User $user)
    {
        $query = SchoolClass::query();

        if (!$user->isAdmin()) {
            $query->where('teacher_id', $user->id);
        }

        return $query->orderBy('name')->get();
    }
}