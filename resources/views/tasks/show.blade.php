@extends('layouts.app')

@section('title', $task->title)
@section('page-heading', 'Task Details')

@section('content')

<div class="min-h-screen bg-slate-100 p-6 lg:p-8">

    <div class="max-w-6xl mx-auto">

        {{-- Success Message --}}
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-xl">
                {{ session('success') }}
            </div>
        @endif

        {{-- Back Button --}}
        <div class="mb-6">
            <a href="{{ route('tasks.index') }}"
               class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-blue-600">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Back to Tasks
            </a>
        </div>

        {{-- Task Card --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

            {{-- Task Header --}}
            <div class="p-6 lg:p-8 border-b border-slate-200">

                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-5">

                    <div>

                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700 mb-3">
                            {{ $task->type }}
                        </span>

                        <h1 class="text-3xl font-bold text-slate-900">
                            {{ $task->title }}
                        </h1>

                        <p class="text-slate-500 mt-2">
                            {{ $task->subject }}
                        </p>

                        {{-- Class Badge --}}
                        @if($task->schoolClass)
                            <div class="mt-4">
                                <span class="inline-flex items-center gap-2 px-3 py-2 rounded-xl bg-purple-50 text-purple-700 text-sm font-medium">
                                    <i data-lucide="users" class="w-4 h-4"></i>

                                    {{ $task->schoolClass->name }}

                                    @if($task->schoolClass->section)
                                        — {{ $task->schoolClass->section }}
                                    @endif
                                </span>
                            </div>
                        @endif

                    </div>

                    {{-- Task Status Badge --}}
                    @if(auth()->user()->isStudent())

                        @php
                            $studentAssignment = $task->students->firstWhere('id', auth()->id());
                        @endphp

                        @if($studentAssignment && $studentAssignment->pivot->status === 'Completed')

                            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-green-100 text-green-700 text-sm font-semibold">
                                <i data-lucide="check-circle" class="w-4 h-4"></i>
                                Completed
                            </span>

                        @else

                            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-orange-100 text-orange-700 text-sm font-semibold">
                                <i data-lucide="clock" class="w-4 h-4"></i>
                                Pending
                            </span>

                        @endif

                    @else

                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-100 text-slate-700 text-sm font-semibold">
                            <i data-lucide="clipboard-list" class="w-4 h-4"></i>
                            Task Details
                        </span>

                    @endif

                </div>

            </div>

            {{-- Task Information --}}
            <div class="p-6 lg:p-8">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                    {{-- Due Date --}}
                    <div class="bg-slate-50 rounded-xl p-5">

                        <div class="flex items-center gap-3">

                            <div class="w-11 h-11 rounded-xl bg-blue-100 flex items-center justify-center">
                                <i data-lucide="calendar" class="w-5 h-5 text-blue-600"></i>
                            </div>

                            <div>

                                <p class="text-xs text-slate-500 uppercase tracking-wide">
                                    Due Date
                                </p>

                                <p class="font-semibold text-slate-800 mt-1">
                                    {{ $task->due_date->format('M d, Y h:i A') }}
                                </p>

                                @if($task->due_date->isPast())

                                    <p class="text-xs text-red-600 mt-1">
                                        Deadline has passed
                                    </p>

                                @else

                                    <p class="text-xs text-slate-500 mt-1">
                                        {{ $task->due_date->diffForHumans() }}
                                    </p>

                                @endif

                            </div>

                        </div>

                    </div>

                    {{-- Class Information --}}
                    <div class="bg-slate-50 rounded-xl p-5">

                        <div class="flex items-start gap-3">

                            <div class="w-11 h-11 rounded-xl bg-purple-100 flex items-center justify-center shrink-0">
                                <i data-lucide="graduation-cap" class="w-5 h-5 text-purple-600"></i>
                            </div>

                            <div>

                                <p class="text-xs text-slate-500 uppercase tracking-wide">
                                    Assigned Class
                                </p>

                                @if($task->schoolClass)

                                    <p class="font-semibold text-slate-800 mt-1">
                                        {{ $task->schoolClass->name }}

                                        @if($task->schoolClass->section)
                                            — {{ $task->schoolClass->section }}
                                        @endif
                                    </p>

                                    @if($task->schoolClass->academic_year)
                                        <p class="text-xs text-slate-500 mt-1">
                                            Academic Year: {{ $task->schoolClass->academic_year }}
                                        </p>
                                    @endif

                                    @if($task->schoolClass->teacher)
                                        <p class="text-xs text-slate-500 mt-1">
                                            Teacher: {{ $task->schoolClass->teacher->name }}
                                        </p>
                                    @endif

                                @else

                                    <p class="font-semibold text-slate-800 mt-1">
                                        Individual Assignment
                                    </p>

                                    <p class="text-xs text-slate-500 mt-1">
                                        This task is not linked to a specific class.
                                    </p>

                                @endif

                            </div>

                        </div>

                    </div>

                    {{-- Student Status --}}
                    @if(auth()->user()->isStudent())

                        <div class="bg-slate-50 rounded-xl p-5">

                            <div class="flex items-center gap-3">

                                <div class="w-11 h-11 rounded-xl bg-green-100 flex items-center justify-center">
                                    <i data-lucide="check-circle" class="w-5 h-5 text-green-600"></i>
                                </div>

                                <div>

                                    <p class="text-xs text-slate-500 uppercase tracking-wide">
                                        Your Status
                                    </p>

                                    @if($studentAssignment && $studentAssignment->pivot->status === 'Completed')

                                        <p class="font-semibold text-green-600 mt-1">
                                            Completed
                                        </p>

                                        @if($studentAssignment->pivot->completed_at)

                                            <p class="text-xs text-slate-500 mt-1">
                                                Completed on
                                                {{ \Illuminate\Support\Carbon::parse($studentAssignment->pivot->completed_at)->format('M d, Y h:i A') }}
                                            </p>

                                        @endif

                                    @else

                                        <p class="font-semibold text-orange-600 mt-1">
                                            Pending
                                        </p>

                                        <p class="text-xs text-slate-500 mt-1">
                                            Mark the task completed when you're finished.
                                        </p>

                                    @endif

                                </div>

                            </div>

                        </div>

                    @else

                        {{-- Assigned Students Summary --}}
                        <div class="bg-slate-50 rounded-xl p-5">

                            <div class="flex items-center gap-3">

                                <div class="w-11 h-11 rounded-xl bg-blue-100 flex items-center justify-center">
                                    <i data-lucide="users" class="w-5 h-5 text-blue-600"></i>
                                </div>

                                <div>

                                    <p class="text-xs text-slate-500 uppercase tracking-wide">
                                        Assigned Students
                                    </p>

                                    <p class="font-semibold text-slate-800 mt-1">
                                        {{ $task->students->count() }} students
                                    </p>

                                    <p class="text-xs text-slate-500 mt-1">
                                        Students assigned to this task
                                    </p>

                                </div>

                            </div>

                        </div>

                    @endif

                </div>

                {{-- Description --}}
                <div class="mt-8">

                    <h2 class="text-lg font-semibold text-slate-900 mb-3">
                        Description
                    </h2>

                    <div class="bg-slate-50 rounded-xl p-5 text-slate-700 leading-relaxed whitespace-pre-line">

                        @if($task->description)
                            {{ $task->description }}
                        @else
                            <span class="text-slate-400">
                                No description provided.
                            </span>
                        @endif

                    </div>

                </div>

                {{-- Teacher / Admin Student Tracking --}}
                @if(!auth()->user()->isStudent())

                    @php
                        $totalStudents = $task->students->count();

                        $completedStudents = $task->students
                            ->filter(function ($student) {
                                return $student->pivot->status === 'Completed';
                            })
                            ->count();

                        $pendingStudents = $totalStudents - $completedStudents;

                        $completionPercentage = $totalStudents > 0
                            ? round(($completedStudents / $totalStudents) * 100)
                            : 0;
                    @endphp

                    {{-- Student Completion Summary --}}
                    <div class="mt-8">

                        <div class="mb-4">

                            <h2 class="text-xl font-bold text-slate-900">
                                Student Progress
                            </h2>

                            <p class="text-sm text-slate-500 mt-1">
                                Track the completion status of students assigned to this task.
                            </p>

                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">

                            {{-- Total Students --}}
                            <div class="rounded-xl border border-slate-200 bg-white p-5">

                                <div class="flex items-center justify-between">
                                    <p class="text-sm text-slate-500">Total Assigned</p>

                                    <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                        <i data-lucide="users" class="w-5 h-5 text-blue-600"></i>
                                    </div>
                                </div>

                                <p class="text-3xl font-bold text-slate-900 mt-3">
                                    {{ $totalStudents }}
                                </p>

                                <p class="text-xs text-slate-500 mt-1">
                                    Students assigned
                                </p>

                            </div>

                            {{-- Completed Students --}}
                            <div class="rounded-xl border border-green-200 bg-green-50 p-5">

                                <div class="flex items-center justify-between">
                                    <p class="text-sm text-green-700">Completed</p>

                                    <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">
                                        <i data-lucide="check-circle" class="w-5 h-5 text-green-600"></i>
                                    </div>
                                </div>

                                <p class="text-3xl font-bold text-green-700 mt-3">
                                    {{ $completedStudents }}
                                </p>

                                <p class="text-xs text-green-700 mt-1">
                                    Students finished
                                </p>

                            </div>

                            {{-- Pending Students --}}
                            <div class="rounded-xl border border-orange-200 bg-orange-50 p-5">

                                <div class="flex items-center justify-between">
                                    <p class="text-sm text-orange-700">Pending</p>

                                    <div class="w-10 h-10 rounded-lg bg-orange-100 flex items-center justify-center">
                                        <i data-lucide="clock" class="w-5 h-5 text-orange-600"></i>
                                    </div>
                                </div>

                                <p class="text-3xl font-bold text-orange-700 mt-3">
                                    {{ $pendingStudents }}
                                </p>

                                <p class="text-xs text-orange-700 mt-1">
                                    Still pending
                                </p>

                            </div>

                        </div>

                        {{-- Completion Progress Bar --}}
                        <div class="bg-white border border-slate-200 rounded-xl p-5 mb-6">

                            <div class="flex items-center justify-between gap-4 mb-3">

                                <div>
                                    <h3 class="font-semibold text-slate-800">
                                        Overall Completion
                                    </h3>

                                    <p class="text-xs text-slate-500 mt-1">
                                        Based on assigned students who marked this task completed.
                                    </p>
                                </div>

                                <span class="text-xl font-bold text-blue-600">
                                    {{ $completionPercentage }}%
                                </span>

                            </div>

                            <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                                <div
                                    class="bg-blue-600 h-3 rounded-full transition-all duration-500"
                                    style="width: {{ $completionPercentage }}%"
                                ></div>
                            </div>

                            <div class="flex justify-between text-xs text-slate-500 mt-2">
                                <span>{{ $completedStudents }} completed</span>
                                <span>{{ $totalStudents }} assigned</span>
                            </div>

                        </div>

                        {{-- Student Tracking Table --}}
                        <div class="border border-slate-200 rounded-xl overflow-hidden">

                            <div class="p-5 bg-slate-50 border-b border-slate-200">

                                <h3 class="font-semibold text-slate-800">
                                    Student Completion Details
                                </h3>

                                <p class="text-sm text-slate-500 mt-1">
                                    View each student's current status and completion date.
                                </p>

                            </div>

                            @if($totalStudents > 0)

                                <div class="overflow-x-auto">

                                    <table class="w-full">

                                        <thead class="bg-white">
                                            <tr>
                                                <th class="text-left px-5 py-4 text-xs font-semibold text-slate-500 uppercase">
                                                    Student
                                                </th>

                                                <th class="text-left px-5 py-4 text-xs font-semibold text-slate-500 uppercase">
                                                    Status
                                                </th>

                                                <th class="text-left px-5 py-4 text-xs font-semibold text-slate-500 uppercase">
                                                    Completed At
                                                </th>
                                            </tr>
                                        </thead>

                                        <tbody class="divide-y divide-slate-100">

                                            @foreach($task->students as $student)

                                                <tr class="hover:bg-slate-50 transition">

                                                    {{-- Student Information --}}
                                                    <td class="px-5 py-4">

                                                        <p class="font-medium text-slate-800">
                                                            {{ $student->name }}
                                                        </p>

                                                        <p class="text-sm text-slate-500">
                                                            {{ $student->email }}
                                                        </p>

                                                    </td>

                                                    {{-- Student Status --}}
                                                    <td class="px-5 py-4">

                                                        @if($student->pivot->status === 'Completed')

                                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                                                <i data-lucide="check-circle" class="w-3.5 h-3.5"></i>
                                                                Completed
                                                            </span>

                                                        @else

                                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-700">
                                                                <i data-lucide="clock" class="w-3.5 h-3.5"></i>
                                                                Pending
                                                            </span>

                                                        @endif

                                                    </td>

                                                    {{-- Completion Date --}}
                                                    <td class="px-5 py-4 text-sm text-slate-500">

                                                        @if($student->pivot->completed_at)

                                                            {{ \Illuminate\Support\Carbon::parse($student->pivot->completed_at)->format('M d, Y h:i A') }}

                                                        @else

                                                            <span class="text-slate-400">
                                                                Not completed
                                                            </span>

                                                        @endif

                                                    </td>

                                                </tr>

                                            @endforeach

                                        </tbody>

                                    </table>

                                </div>

                            @else

                                <div class="p-10 text-center">

                                    <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center mx-auto mb-3">
                                        <i data-lucide="users" class="w-6 h-6 text-slate-400"></i>
                                    </div>

                                    <p class="font-medium text-slate-700">
                                        No students assigned
                                    </p>

                                    <p class="text-sm text-slate-500 mt-1">
                                        Assign students to this task to track their progress here.
                                    </p>

                                </div>

                            @endif

                        </div>

                    </div>

                @endif

                {{-- Actions --}}
                <div class="mt-8 pt-6 border-t border-slate-200">

                    <div class="flex flex-wrap items-center gap-3">

                        {{-- Student Completion Action --}}
                        @if(auth()->user()->isStudent())

                            @if($studentAssignment && $studentAssignment->pivot->status === 'Pending')

                                <form action="{{ route('tasks.complete', $task) }}" method="POST">
                                    @csrf
                                    @method('PATCH')

                                    <button
                                        type="submit"
                                        class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-xl font-medium transition"
                                    >
                                        <i data-lucide="check" class="w-4 h-4"></i>
                                        Mark Completed
                                    </button>
                                </form>

                            @endif

                        @endif

                        {{-- Teacher / Admin Actions --}}
                        @if(!auth()->user()->isStudent())

                            @can('update', $task)
                                <a
                                    href="{{ route('tasks.edit', $task) }}"
                                    class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl font-medium transition"
                                >
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                    Edit Task
                                </a>
                            @endcan

                            @can('delete', $task)
                                <form
                                    action="{{ route('tasks.destroy', $task) }}"
                                    method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this task?');"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-5 py-3 rounded-xl font-medium transition"
                                    >
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        Delete Task
                                    </button>
                                </form>
                            @endcan

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection