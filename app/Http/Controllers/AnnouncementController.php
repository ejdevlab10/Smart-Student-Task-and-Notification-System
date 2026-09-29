<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
class AnnouncementController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Announcement::class);

        $announcements = Announcement::orderByDesc('is_pinned')
            ->orderByDesc('created_at')
            ->get();

        return view('announcements.index', compact('announcements'));
    }

    public function create()
    {
        Gate::authorize('create', Announcement::class);

        return view('announcements.create');
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Announcement::class);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'category' => ['required', 'string', 'max:50'],
            'is_pinned' => ['nullable', 'boolean'],
        ]);

        Announcement::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'category' => $validated['category'],
            'is_pinned' => $request->boolean('is_pinned'),
        ]);

        return redirect()
            ->route('announcements.index')
            ->with('success', 'Announcement posted successfully!');
    }

    public function show(Announcement $announcement)
    {
        Gate::authorize('view', $announcement);
        return view('announcements.show', compact('announcement'));
    }

    public function edit(Announcement $announcement)
    {
        Gate::authorize('update', $announcement);
        return view('announcements.edit', compact('announcement'));
    }

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
            'is_pinned' => $request->boolean('is_pinned'),
        ]);

        return redirect()
            ->route('announcements.show', $announcement)
            ->with('success', 'Announcement updated successfully!');
    }

    public function destroy(Announcement $announcement)
    {
        Gate::authorize('delete', $announcement);
        $announcement->delete();

        return redirect()
            ->route('announcements.index')
            ->with('success', 'Announcement deleted successfully!');
    }
}