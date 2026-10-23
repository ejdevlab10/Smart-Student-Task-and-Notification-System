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
            // Administrators see all tasks.
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

        $students = User::where('role', 'student')
            ->orderBy('name')
            ->get();

        // Allow a selected calendar date to prefill the deadline.
        $selectedDate = null;
        $date = $request->query('due_date');

        if (
            is_string($date) &&
            preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)
        ) {
            try {
                $parsedDate = Carbon::createFromFormat('!Y-m-d', $date);

                if (
                    $parsedDate &&
                    $parsedDate->format('Y-m-d') === $date
                ) {
                    // Default the deadline to 11:59 PM.
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
     * Store a newly created task and assign it to selected students.
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

            'students' => [
                'nullable',
                'array',
            ],

            'students.*' => [
                'integer',
                'distinct',
                Rule::exists('users', 'id')
                    ->where('role', 'student'),
            ],
        ]);

        /*
         * Create the task and its student assignments in a transaction.
         * This prevents a partially saved task if assignment creation fails.
         */
        $task = DB::transaction(function () use ($validated, $user) {

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

            // Start with individually selected students.
            $studentIds = collect($validated['students'] ?? [])
                ->map(fn ($id) => (int) $id);

            // Include all students belonging to the selected class.
            if (!empty($validated['class_id'])) {
                $class = SchoolClass::with('students')
                    ->findOrFail($validated['class_id']);

                $studentIds = $studentIds->merge(
                    $class->students->pluck('id')
                );
            }

            $studentIds = $studentIds
                ->unique()
                ->values();

            // Attach each student with an initial Pending status.
            if ($studentIds->isNotEmpty()) {
                $assignments = [];

                foreach ($studentIds as $studentId) {
                    $assignments[$studentId] = [
                        'status' => 'Pending',
                        'completed_at' => null,
                    ];
                }

                $task->students()->attach($assignments);
            }

            return $task;
        });

        /*
         * Send notifications after the database transaction succeeds.
         */
        $assignedStudentIds = $task->students()
            ->pluck('users.id');

        $studentsToNotify = User::whereIn('id', $assignedStudentIds)
            ->get();

        foreach ($studentsToNotify as $student) {
            $student->notify(
                new TaskAssignedNotification($task)
            );
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

        $students = User::where('role', 'student')
            ->orderBy('name')
            ->get();

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

            'students' => [
                'nullable',
                'array',
            ],

            'students.*' => [
                'integer',
                'distinct',
                Rule::exists('users', 'id')
                    ->where('role', 'student'),
            ],
        ]);

        /*
         * Update the task and synchronize assignments atomically.
         * Existing students retain their completion status.
         */
        $newStudentIds = DB::transaction(function () use (
            $validated,
            $user,
            $task
        ) {
            $task->update([
                'title' => $validated['title'],
                'subject' => $validated['subject'],
                'type' => $validated['type'],
                'description' => $validated['description'] ?? null,
                'due_date' => $validated['due_date'],
                'school_class_id' => $validated['class_id'] ?? null,
            ]);

            // Collect individually selected students.
            $studentIds = collect($validated['students'] ?? [])
                ->map(fn ($id) => (int) $id);

            // Include students from the selected class.
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

            // Get the task's current student assignments.
            $existingAssignments = $task->students()
                ->get()
                ->keyBy('id');

            $syncData = [];
            $newlyAssignedIds = [];

            foreach ($studentIds as $studentId) {
                if ($existingAssignments->has($studentId)) {
                    // Preserve existing student progress.
                    $pivot = $existingAssignments[$studentId]->pivot;

                    $syncData[$studentId] = [
                        'status' => $pivot->status,
                        'completed_at' => $pivot->completed_at,
                    ];
                } else {
                    // New assignments start as Pending.
                    $syncData[$studentId] = [
                        'status' => 'Pending',
                        'completed_at' => null,
                    ];

                    $newlyAssignedIds[] = $studentId;
                }
            }

            // Add selected students and remove unselected students.
            $task->students()->sync($syncData);

            return $newlyAssignedIds;
        });

        // Notify only students who were newly assigned.
        if (!empty($newStudentIds)) {
            $newStudents = User::whereIn('id', $newStudentIds)
                ->get();

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

    /**
     * Mark a task as completed.
     */
    public function complete(Task $task)
    {
        $user = auth()->user();

        if ($user->isStudent()) {
            // Verify that this student is assigned to the task.
            $assignment = $task->students()
                ->where('users.id', $user->id)
                ->first();

            if (!$assignment) {
                abort(403);
            }

            // Update this student's individual progress.
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

        // Teachers see their own classes; admins can see all classes.
        if (!$user->isAdmin()) {
            $query->where('teacher_id', $user->id);
        }

        return $query
            ->orderBy('name')
            ->get();
    }
}