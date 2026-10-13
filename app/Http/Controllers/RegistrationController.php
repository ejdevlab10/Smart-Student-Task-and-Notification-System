<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class RegistrationController extends Controller
{
    /**
     * Display the registration options.
     */
    public function index()
    {
        return view('auth.register');
    }

    /**
     * Display the registration form for students or teachers.
     */
    public function create(string $role)
    {
        abort_unless(in_array($role, ['student', 'teacher'], true), 404);

        return view('auth.register-form', compact('role'));
    }

    /**
     * Register a new student or teacher.
     */
    public function store(Request $request, string $role)
    {
        abort_unless(in_array($role, ['student', 'teacher'], true), 404);

        $rules = [
            'first_name' => ['required', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'profile_picture' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
            ],
        ];

        if ($role === 'student') {
            $rules['student_id'] = [
                'required',
                'string',
                'max:50',
                'unique:users,student_id',
            ];
        } else {
            $rules['employee_id'] = [
                'required',
                'string',
                'max:50',
                'unique:users,employee_id',
            ];
        }

        $validated = $request->validate($rules);

        $name = trim(
            $validated['first_name'] . ' ' .
            (!empty($validated['middle_name'])
                ? $validated['middle_name'] . ' '
                : '') .
            $validated['last_name']
        );

        $profilePicturePath = null;

        if ($request->hasFile('profile_picture')) {
            $profilePicturePath = $request
                ->file('profile_picture')
                ->store('profile-pictures', 'public');
        }

        $userData = [
            'name' => $name,
            'first_name' => $validated['first_name'],
            'middle_name' => $validated['middle_name'] ?? null,
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $role,
            'profile_picture' => $profilePicturePath,

            // Students are approved immediately.
            // Teachers must be approved by an administrator.
            'is_approved' => $role === 'student',
        ];

        if ($role === 'student') {
            $userData['student_id'] = $validated['student_id'];
        } else {
            $userData['employee_id'] = $validated['employee_id'];
        }

        User::create($userData);

        if ($role === 'teacher') {
            return redirect()
                ->route('login')
                ->with(
                    'status',
                    'Your teacher registration was submitted successfully. Please wait for administrator approval before logging in.'
                );
        }

        return redirect()
            ->route('login')
            ->with(
                'status',
                'Your student account has been created successfully. You can now log in.'
            );
    }
}