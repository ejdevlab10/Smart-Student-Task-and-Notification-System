<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\User;
use App\Notifications\NewAnnouncementNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AnnouncementController extends Controller
{
    /**
     * Display announcements.
     */
    public function index()
    {
        Gate::authorize('viewAny', Announcement::class);

        $announcements = Announcement::orderByDesc('is_pinned')
            ->orderByDesc('created_at')
            ->get();

        return view('announcements.index', compact('announcements'));
    }

    /**
     * Show the form for creating an announcement.
     */
    public function create()
    {
        Gate::authorize('create', Announcement::class);

        return view('announcements.create');
    }

    /**
     * Store a new announcement.
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Announcement::class);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'category' => ['required', 'string', 'max:50'],
            'is_pinned' => ['nullable', 'boolean'],
        ]);

        $announcement = Announcement::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'category' => $validated['category'],
            'is_pinned' => $validated['is_pinned'] ?? false,
        ]);

        // Notify all students
        $students = User::where('role', 'student')->get();

        foreach ($students as $student) {
            $student->notify(
                new NewAnnouncementNotification($announcement)
            );
        }

        return redirect()
            ->route('announcements.index')
            ->with('success', 'Announcement created successfully!');
    }

    /**
     * Display a single announcement.
     */
    public function show(Announcement $announcement)
    {
        Gate::authorize('view', $announcement);

        return view('announcements.show', compact('announcement'));
    }

    /**
     * Show the form for editing an announcement.
     */
    public function edit(Announcement $announcement)
    {
        Gate::authorize('update', $announcement);

        return view('announcements.edit', compact('announcement'));
    }

    /**
     * Update an announcement.
     */
    public function update(Request $request, Announcement $announcement)
    {
        Gate::authorize('update', $announcement);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'category' => ['required', 'string', 'max:50'],
            'is_pinned' => ['nullable', 'boolean'],
        ]);

        $announcement->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'category' => $validated['category'],
            'is_pinned' => $validated['is_pinned'] ?? false,
        ]);

        return redirect()
            ->route('announcements.index')
            ->with('success', 'Announcement updated successfully!');
    }

    /**
     * Delete an announcement.
     */
    public function destroy(Announcement $announcement)
    {
        Gate::authorize('delete', $announcement);

        $announcement->delete();

        return redirect()
            ->route('announcements.index')
            ->with('success', 'Announcement deleted successfully!');
    }
}