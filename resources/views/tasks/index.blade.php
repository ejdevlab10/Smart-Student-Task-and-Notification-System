@extends('layouts.app')

@section('title', 'My Tasks')
@section('page-heading', 'My Tasks')

@section('content')

<div class="min-h-screen bg-slate-100 p-8">
    <div class="max-w-6xl mx-auto">

        {{-- Success Message --}}
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-xl">
                {{ session('success') }}
            </div>
        @endif

        {{-- Page Header --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-slate-900">My Tasks</h1>
                <p class="text-slate-500 mt-1">
                    View your assignments, projects, quizzes and exams.
                </p>
            </div>

            {{-- Only teachers and admins can create tasks --}}
            @if(auth()->user()->isTeacher() || auth()->user()->isAdmin())
                <a href="{{ route('tasks.create') }}"
                   class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl font-medium transition">
                    + Create Task
                </a>
            @endif
        </div>

        {{-- Task List --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm">
            <div class="p-6">

                @forelse($tasks as $task)

                    <div class="border-b border-slate-100 py-5 last:border-0">
                        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-5">

                            {{-- Task Information --}}
                            <div class="flex-1 min-w-0">
                                <h2 class="font-semibold text-lg text-slate-900">
                                    {{ $task->title }}
                                </h2>

                                <p class="text-sm text-slate-500 mt-1">
                                    {{ $task->subject }}
                                </p>

                                {{-- Task Type and Class --}}
                                <div class="flex flex-wrap items-center gap-2 mt-3">
                                    <span class="inline-flex items-center text-xs bg-blue-50 text-blue-700 px-3 py-1 rounded-full">
                                        {{ $task->type }}
                                    </span>

                                    @if($task->schoolClass)
                                        <span class="inline-flex items-center gap-1 text-xs bg-purple-50 text-purple-700 px-3 py-1 rounded-full">
                                            <i data-lucide="users" class="w-3 h-3"></i>
                                            {{ $task->schoolClass->name }}

                                            @if($task->schoolClass->section)
                                                — {{ $task->schoolClass->section }}
                                            @endif
                                        </span>
                                    @else
                                        <span class="inline-flex items-center text-xs bg-slate-100 text-slate-600 px-3 py-1 rounded-full">
                                            Individual Assignment
                                        </span>
                                    @endif
                                </div>

                                {{-- Due Date --}}
                                <p class="text-sm text-slate-500 mt-3">
                                    <i data-lucide="calendar-clock"
                                       class="w-4 h-4 inline-block align-text-bottom"></i>

                                    Due
                                    {{ $task->due_date
                                        ? $task->due_date->format('M d, Y h:i A')
                                        : 'No due date' }}
                                </p>
                            </div>

                            {{-- Task Actions and Status --}}
                            <div class="flex flex-col items-start md:items-end gap-3">

                                {{-- View Task --}}
                                <a href="{{ route('tasks.show', $task) }}"
                                   class="inline-flex items-center gap-2 text-sm text-blue-600 hover:text-blue-700 font-medium">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                    <span>View Details</span>
                                </a>

                                {{-- STUDENT CONTROLS --}}
                                @if(auth()->user()->isStudent())

                                    @php
                                        $submission = $task->submissions
                                            ->firstWhere('student_id', auth()->id());
                                    @endphp

                                    @if($submission)

                                        {{-- Submission has been reviewed --}}
                                        @if($submission->status === 'Reviewed')
                                            <span class="text-xs bg-green-100 text-green-700 px-3 py-1 rounded-full">
                                                Reviewed
                                            </span>

                                            <a href="{{ route('tasks.show', $task) }}"
                                               class="inline-flex items-center gap-2 text-sm bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg transition">
                                                <i data-lucide="file-check" class="w-4 h-4"></i>
                                                <span>View Grade & Feedback</span>
                                            </a>

                                        {{-- Submission is awaiting teacher review --}}
                                        @else
                                            <span class="text-xs bg-blue-100 text-blue-700 px-3 py-1 rounded-full">
                                                Submitted — Awaiting Review
                                            </span>

                                            <a href="{{ route('tasks.show', $task) }}"
                                               class="inline-flex items-center gap-2 text-sm bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg transition">
                                                <i data-lucide="eye" class="w-4 h-4"></i>
                                                <span>View Submission</span>
                                            </a>
                                        @endif

                                    @else

                                        {{-- No submission yet --}}
                                        @if($task->pivot->status === 'Completed')
                                            <span class="text-xs bg-green-100 text-green-700 px-3 py-1 rounded-full">
                                                Completed
                                            </span>
                                        @else
                                            <span class="text-xs bg-orange-100 text-orange-700 px-3 py-1 rounded-full">
                                                Pending
                                            </span>
                                        @endif

                                        <a href="{{ route('tasks.show', $task) }}"
                                           class="inline-flex items-center gap-2 text-sm bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg transition">
                                            <i data-lucide="upload" class="w-4 h-4"></i>
                                            <span>Submit Assignment</span>
                                        </a>

                                    @endif

                                {{-- TEACHER AND ADMIN CONTROLS --}}
                                @else

                                    @if($task->status === 'Pending')
                                        <span class="text-xs bg-orange-100 text-orange-700 px-3 py-1 rounded-full">
                                            Pending
                                        </span>
                                    @else
                                        <span class="text-xs bg-green-100 text-green-700 px-3 py-1 rounded-full">
                                            Completed
                                        </span>
                                    @endif

                                    {{-- Teachers can review student submissions --}}
                                    @if(auth()->user()->isAdmin() ||
                                        (auth()->user()->isTeacher() && $task->created_by === auth()->id()))

                                        <a href="{{ route('tasks.submissions.index', $task) }}"
                                        class="inline-flex items-center gap-2 text-sm bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-2 rounded-lg transition">

                                            <i data-lucide="clipboard-check" class="w-4 h-4"></i>
                                            <span>Review Submissions</span>

                                        </a>

                                    @endif

                                @endif

                            </div>
                        </div>
                    </div>

                @empty

                    {{-- Empty State --}}
                    <div class="text-center py-12">
                        <div class="flex justify-center mb-4">
                            <i data-lucide="book-open" class="w-12 h-12 text-slate-300"></i>
                        </div>

                        <h2 class="font-semibold text-slate-800">
                            No tasks yet
                        </h2>

                        <p class="text-slate-500 mt-1">
                            @if(auth()->user()->isStudent())
                                You don't have any assigned tasks yet.
                            @else
                                Create your first academic task.
                            @endif
                        </p>
                    </div>

                @endforelse

            </div>
        </div>

    </div>
</div>

@endsection