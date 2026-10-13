<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    /**
     * Display registered teachers.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $teachers = User::where('role', 'teacher')
            ->when($search, function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('teachers.index', compact(
            'teachers',
            'search'
        ));
    }
}