<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\User;
use Illuminate\Http\Request;

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

        return view('classes.index', compact(
            'classes',
            'search'
        ));
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

        // Automatically generate the academic year.
        $currentYear = now()->year;

        $validated['academic_year'] = $currentYear . '-' . ($currentYear + 1);

        // Save the class with the automatically generated academic year.
        SchoolClass::create($validated);

        return redirect()
            ->route('admin.classes.index')
            ->with('success', 'Class created successfully.');
    }
}