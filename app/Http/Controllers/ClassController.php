<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskAssignedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassController extends Controller
{
    /**
     * Display all classes.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $classes = SchoolClass::with('teacher')
            ->withCount('students')
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('section', 'like', "%{$search}%")
                        ->orWhere('academic_year', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->orderBy('section')
            ->paginate(10)
            ->withQueryString();

        return view('classes.index', compact('classes', 'search'));
    }

    /**
     * Show the form for creating a class.
     */
    public function create()
    {
        $teachers = User::where('role', 'teacher')
            ->orderBy('name')
            ->get();

        return view('classes.create', compact('teachers'));
    }

    /**
     * Store a newly created class.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'section' => ['nullable', 'string', 'max:100'],
            'teacher_id' => [
                'nullable',
                'integer',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    if (
                        $value &&
                        !User::where('id', $value)
                            ->where('role', 'teacher')
                            ->exists()
                    ) {
                        $fail('The selected user must have a teacher account.');
                    }
                },
            ],
        ]);

        $currentYear = now()->year;

        $validated['academic_year'] =
            $currentYear . '-' . ($currentYear + 1);

        SchoolClass::create($validated);

        return redirect()
            ->route('admin.classes.index')
            ->with('success', 'Class created successfully.');
    }

    /**
     * Show the edit form for a class.
     */
    public function edit(SchoolClass $class)
    {
        $teachers = User::where('role', 'teacher')
            ->orderBy('name')
            ->get();

        return view('classes.edit', compact('class', 'teachers'));
    }

    /**
     * Update an existing class.
     */
    public function update(Request $request, SchoolClass $class)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'section' => ['nullable', 'string', 'max:100'],
            'teacher_id' => [
                'nullable',
                'integer',
                'exists:users,id',
                function ($attribute, $value, $fail) {
                    if (
                        $value &&
                        !User::where('id', $value)
                            ->where('role', 'teacher')
                            ->exists()
                    ) {
                        $fail('The selected user must have a teacher account.');
                    }
                },
            ],
        ]);

        $class->update($validated);

        return redirect()
            ->route('admin.classes.index')
            ->with('success', 'Class updated successfully.');
    }

    /**
     * Show a class and its enrolled students.
     */
    public function show(SchoolClass $class)
    {
        $class->load('teacher');

        $class->load([
            'students' => function ($query) {
                $query->orderBy('name');
            },
        ]);

        $students = User::where('role', 'student')
            ->whereNotIn('id', $class->students->pluck('id'))
            ->orderBy('name')
            ->get();

        return view('classes.show', compact('class', 'students'));
    }

    /**
     * Enroll a student in a class and assign existing class tasks.
     */
    public function enroll(Request $request, SchoolClass $class)
    {
        $validated = $request->validate([
            'student_id' => ['required', 'integer', 'exists:users,id'],
        ]);

        $student = User::where('id', $validated['student_id'])
            ->where('role', 'student')
            ->first();

        if (!$student) {
            return back()->withErrors([
                'student_id' => 'Please select a valid student account.',
            ]);
        }

        if ($class->students()->where('users.id', $student->id)->exists()) {
            return back()->withErrors([
                'student_id' => 'This student is already enrolled in the class.',
            ]);
        }

        DB::transaction(function () use ($class, $student) {
            // Enroll the student in the class.
            $class->students()->attach($student->id);

            // Find tasks associated with this class.
            $tasks = Task::where('school_class_id', $class->id)->get();

            foreach ($tasks as $task) {
                // Do not duplicate an existing assignment.
                $alreadyAssigned = $task->students()
                    ->where('users.id', $student->id)
                    ->exists();

                if ($alreadyAssigned) {
                    // Preserve the student's existing status and progress.
                    continue;
                }

                // Assign the existing class task to the new student.
                $task->students()->attach($student->id, [
                    'status' => 'Pending',
                    'completed_at' => null,
                ]);

                // Notify the student about the newly assigned task.
                $student->notify(
                    new TaskAssignedNotification($task)
                );
            }
        });

        return redirect()
            ->route('admin.classes.show', $class)
            ->with(
                'success',
                'Student enrolled successfully. Existing class tasks have been assigned and notifications sent.'
            );
    }

    /**
     * Remove a student from a class.
     *
     * Existing task assignments are preserved because the same tasks
     * may also have been assigned to the student individually.
     */
    public function removeStudent(SchoolClass $class, User $student)
    {
        if ($student->role !== 'student') {
            abort(404);
        }

        if (!$class->students()->where('users.id', $student->id)->exists()) {
            return back()->withErrors([
                'student' => 'This student is not enrolled in the class.',
            ]);
        }

        $class->students()->detach($student->id);

        return redirect()
            ->route('admin.classes.show', $class)
            ->with(
                'success',
                'Student removed from the class. Existing task assignments and completion records have been preserved.'
            );
    }
}