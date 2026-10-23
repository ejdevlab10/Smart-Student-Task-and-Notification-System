<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\SchoolClass;
use App\Models\User;
use App\Notifications\TaskAssignedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class TaskController extends Controller
{
    /**
     * Display tasks according to the logged-in user's role.
     */
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

    /**
     * Show the task creation form.
     */
    public function create(Request $request)
    {
        Gate::authorize('create', Task::class);

        $user = auth()->user();

        $classes = $this->availableClasses($user);

        // Load students and their class memberships.
        $studentsQuery = User::where('role', 'student')
            ->with('schoolClasses')
            ->orderBy('name');

        // Teachers can select students enrolled in their classes.
        if (!$user->isAdmin()) {
            $studentsQuery->whereHas('schoolClasses', function ($query) use ($user) {
                $query->where('school_classes.teacher_id', $user->id);
            });
        }

        $students = $studentsQuery->get();

        // Prefill the deadline when opened from the calendar.
        $selectedDate = null;
        $date = $request->query('due_date');

        if (
            is_string($date) &&
            preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)
        ) {
            try {
                $parsedDate = Carbon::createFromFormat('!Y-m-d', $date);

                if ($parsedDate && $parsedDate->format('Y-m-d') === $date) {
                    $selectedDate = $parsedDate->format('Y-m-d') . 'T23:59';
                }
            } catch (\Exception $e) {
                $selectedDate = null;
            }
        }

        return view('tasks.create', compact(
            'classes',
            'students',
            'selectedDate'
        ));
    }

    /**
     * Store a new task and assign it to multiple classes,
     * individual students, or both.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Task::class);

        $user = auth()->user();

        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'subject' => [
                'required',
                'string',
                'max:255',
            ],

            'type' => [
                'required',
                Rule::in(['Assignment', 'Project', 'Quiz', 'Exam']),
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'due_date' => [
                'required',
                'date',
            ],

            'class_ids' => [
                'nullable',
                'array',
            ],

            'class_ids.*' => [
                'integer',
                'distinct',
                Rule::exists('school_classes', 'id')
                    ->where(function ($query) use ($user) {
                        if (!$user->isAdmin()) {
                            $query->where('teacher_id', $user->id);
                        }
                    }),
            ],

            'students' => [
                'nullable',
                'array',
            ],

            'students.*' => [
                'required',
                'integer',
                'distinct',
                Rule::exists('users', 'id')
                    ->where('role', 'student'),
            ],
        ]);

        // Ensure individually selected students are within the user's
        // permitted scope before creating the task.
        $classIds = collect($validated['class_ids'] ?? [])
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        $individualStudentIds = collect($validated['students'] ?? [])
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        $this->authorizeStudentSelection($user, $individualStudentIds);

        $task = DB::transaction(function () use (
            $validated,
            $user,
            $classIds,
            $individualStudentIds
        ) {
            // Collect students from all selected classes.
            $classStudentIds = collect();

            if ($classIds->isNotEmpty()) {
                $classStudentIds = User::where('role', 'student')
                    ->whereHas('schoolClasses', function ($query) use ($classIds) {
                        $query->whereIn('school_classes.id', $classIds);
                    })
                    ->pluck('id');
            }

            // Combine class students and individually selected students.
            $studentIds = $classStudentIds
                ->merge($individualStudentIds)
                ->unique()
                ->values();

            if ($studentIds->isEmpty()) {
                throw ValidationException::withMessages([
                    'students' => 'Select at least one class or individual student.',
                ]);
            }

            $task = Task::create([
                'title' => $validated['title'],
                'subject' => $validated['subject'],
                'type' => $validated['type'],
                'description' => $validated['description'] ?? null,
                'due_date' => $validated['due_date'],
                'status' => 'Pending',
                'created_by' => $user->id,

                // Keep the first selected class in the existing
                // single-class column for compatibility with older views.
                'school_class_id' => $classIds->first(),
            ]);

            $assignments = [];

            foreach ($studentIds as $studentId) {
                $assignments[$studentId] = [
                    'status' => 'Pending',
                    'completed_at' => null,
                ];
            }

            $task->students()->attach($assignments);

            return $task;
        });

        // Notify assigned students after the transaction succeeds.
        $assignedStudentIds = $task->students()->pluck('users.id');

        $studentsToNotify = User::whereIn('id', $assignedStudentIds)->get();

        foreach ($studentsToNotify as $student) {
            $student->notify(new TaskAssignedNotification($task));
        }

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task created successfully!');
    }

    /**
     * Display a specific task.
     */
    public function show(Task $task)
    {
        Gate::authorize('view', $task);

        $task->load([
            'students',
            'schoolClass.teacher',
        ]);

        return view('tasks.show', compact('task'));
    }

    /**
     * Show the task editing form.
     */
    public function edit(Task $task)
    {
        Gate::authorize('update', $task);

        $user = auth()->user();

        $classes = $this->availableClasses($user);

        $studentsQuery = User::where('role', 'student')
            ->with('schoolClasses')
            ->orderBy('name');

        if (!$user->isAdmin()) {
            $studentsQuery->whereHas('schoolClasses', function ($query) use ($user) {
                $query->where('school_classes.teacher_id', $user->id);
            });
        }

        $students = $studentsQuery->get();

        $task->load([
            'students',
            'schoolClass',
        ]);

        return view('tasks.edit', compact(
            'task',
            'classes',
            'students'
        ));
    }

    /**
     * Update a task and synchronize its student assignments.
     */
    public function update(Request $request, Task $task)
    {
        Gate::authorize('update', $task);

        $user = auth()->user();

        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'subject' => [
                'required',
                'string',
                'max:255',
            ],

            'type' => [
                'required',
                Rule::in(['Assignment', 'Project', 'Quiz', 'Exam']),
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'due_date' => [
                'required',
                'date',
            ],

            'class_ids' => [
                'nullable',
                'array',
            ],

            'class_ids.*' => [
                'integer',
                'distinct',
                Rule::exists('school_classes', 'id')
                    ->where(function ($query) use ($user) {
                        if (!$user->isAdmin()) {
                            $query->where('teacher_id', $user->id);
                        }
                    }),
            ],

            'students' => [
                'nullable',
                'array',
            ],

            'students.*' => [
                'required',
                'integer',
                'distinct',
                Rule::exists('users', 'id')
                    ->where('role', 'student'),
            ],
        ]);

        $classIds = collect($validated['class_ids'] ?? [])
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        $individualStudentIds = collect($validated['students'] ?? [])
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        $this->authorizeStudentSelection($user, $individualStudentIds);

        // Update the task and assignments atomically.
        $newStudentIds = DB::transaction(function () use (
            $validated,
            $user,
            $task,
            $classIds,
            $individualStudentIds
        ) {
            $classStudentIds = collect();

            if ($classIds->isNotEmpty()) {
                $classStudentIds = User::where('role', 'student')
                    ->whereHas('schoolClasses', function ($query) use ($classIds) {
                        $query->whereIn('school_classes.id', $classIds);
                    })
                    ->pluck('id');
            }

            $studentIds = $classStudentIds
                ->merge($individualStudentIds)
                ->unique()
                ->values();

            if ($studentIds->isEmpty()) {
                throw ValidationException::withMessages([
                    'students' => 'Select at least one class or individual student.',
                ]);
            }

            $task->update([
                'title' => $validated['title'],
                'subject' => $validated['subject'],
                'type' => $validated['type'],
                'description' => $validated['description'] ?? null,
                'due_date' => $validated['due_date'],
                'school_class_id' => $classIds->first(),
            ]);

            // Existing assignments are used to preserve progress.
            $existingAssignments = $task->students()
                ->get()
                ->keyBy('id');

            $syncData = [];
            $newlyAssignedIds = [];

            foreach ($studentIds as $studentId) {
                if ($existingAssignments->has($studentId)) {
                    $pivot = $existingAssignments[$studentId]->pivot;

                    $syncData[$studentId] = [
                        'status' => $pivot->status,
                        'completed_at' => $pivot->completed_at,
                    ];
                } else {
                    $syncData[$studentId] = [
                        'status' => 'Pending',
                        'completed_at' => null,
                    ];

                    $newlyAssignedIds[] = $studentId;
                }
            }

            // Selected students remain assigned; unselected students are removed.
            $task->students()->sync($syncData);

            return $newlyAssignedIds;
        });

        // Notify only newly assigned students.
        if (!empty($newStudentIds)) {
            $newStudents = User::whereIn('id', $newStudentIds)->get();

            foreach ($newStudents as $student) {
                $student->notify(new TaskAssignedNotification($task));
            }
        }

        return redirect()
            ->route('tasks.show', $task)
            ->with('success', 'Task and student assignments updated successfully!');
    }

    /**
     * Mark a task as completed.
     */
    public function complete(Task $task)
    {
        $user = auth()->user();

        if ($user->isStudent()) {
            // Verify that the student is assigned to this task.
            $assignment = $task->students()
                ->where('users.id', $user->id)
                ->first();

            if (!$assignment) {
                abort(403);
            }

            // Update only this student's progress.
            $task->students()->updateExistingPivot($user->id, [
                'status' => 'Completed',
                'completed_at' => now(),
            ]);
        } else {
            // Teachers can update their own tasks; admins can update all.
            Gate::authorize('update', $task);

            $task->update([
                'status' => 'Completed',
            ]);
        }

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task marked as completed!');
    }

    /**
     * Delete a task.
     */
    public function destroy(Task $task)
    {
        Gate::authorize('delete', $task);

        $task->delete();

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task deleted successfully!');
    }

    /**
     * Return classes the current user can assign tasks to.
     */
    private function availableClasses(User $user)
    {
        $query = SchoolClass::query();

        if (!$user->isAdmin()) {
            $query->where('teacher_id', $user->id);
        }

        return $query
            ->orderBy('name')
            ->get();
    }

    /**
     * Ensure teachers only assign tasks to students enrolled in one
     * of their classes. Admins may assign any student.
     */
    private function authorizeStudentSelection(User $user, $studentIds): void
    {
        if ($user->isAdmin() || $studentIds->isEmpty()) {
            return;
        }

        $allowedStudentIds = User::whereIn('id', $studentIds)
            ->whereHas('schoolClasses', function ($query) use ($user) {
                $query->where('school_classes.teacher_id', $user->id);
            })
            ->pluck('id')
            ->map(fn ($id) => (int) $id);

        $unauthorizedIds = $studentIds
            ->diff($allowedStudentIds);

        if ($unauthorizedIds->isNotEmpty()) {
            throw ValidationException::withMessages([
                'students' => 'You can only assign tasks to students enrolled in one of your classes.',
            ]);
        }
    }
}