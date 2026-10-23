@extends('layouts.app')

@section('title', $task->title)
@section('page-heading', 'Task Details')

@section('content')
@php
    $currentUser = auth()->user();

    $submission = $currentUser->isStudent()
        ? $task->submissions()
            ->where('student_id', $currentUser->id)
            ->first()
        : null;
@endphp
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

                {{-- Student Submission Section --}}
                    @if($currentUser->isStudent() && $task->students->contains('id', $currentUser->id))
                        <div class="mt-8 rounded-xl border border-slate-200 bg-white overflow-hidden">

                            {{-- Section Header --}}
                            <div class="border-b border-slate-200 px-5 py-4 sm:px-6">
                                <h2 class="text-lg font-semibold text-slate-900">
                                    Task Submission
                                </h2>

                                <p class="mt-1 text-sm text-slate-500">
                                    Submit your answer or upload your assignment file.
                                </p>
                            </div>

                            <div class="p-5 sm:p-6">

                                {{-- Submission Status --}}
                                @if($submission)
                                    <div class="mb-6 rounded-xl border border-slate-200 bg-slate-50 p-4">

                                        <div class="flex flex-wrap items-center justify-between gap-3">

                                            <div>
                                                <p class="text-sm font-medium text-slate-700">
                                                    Submission Status
                                                </p>

                                                <p class="mt-1 text-xs text-slate-500">
                                                    @if($submission->submitted_at)
                                                        Submitted {{ $submission->submitted_at->format('M d, Y h:i A') }}
                                                    @else
                                                        Submission date unavailable
                                                    @endif
                                                </p>
                                            </div>

                                            @if($submission->status === 'Reviewed')
                                                <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                                    Reviewed
                                                </span>
                                            @else
                                                <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                                                    Submitted
                                                </span>
                                            @endif

                                        </div>

                                        {{-- Existing Written Answer --}}
                                        @if($submission->answer)
                                            <div class="mt-4">
                                                <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">
                                                    Your Answer
                                                </p>

                                                <div class="mt-2 whitespace-pre-line break-words rounded-lg border border-slate-200 bg-white p-4 text-sm leading-relaxed text-slate-700">{{ $submission->answer }}</div>
                                            </div>
                                        @endif

                                        {{-- Existing Attachment --}}
                                        @if($submission->file_path)
                                            <div class="mt-4">
                                                <a
                                                    href="{{ route('task-submissions.download', $submission) }}"
                                                    class="inline-flex items-center gap-2 text-sm font-medium text-blue-600 hover:text-blue-800"
                                                >
                                                    <i data-lucide="download" class="h-4 w-4"></i>
                                                    Download Your Submitted File
                                                </a>
                                            </div>
                                        @endif

                                        {{-- Grade and Feedback --}}
                                        @if($submission->status === 'Reviewed')
                                            <div class="mt-5 rounded-xl border border-green-200 bg-green-50 p-4">

                                                <p class="text-sm font-semibold text-green-800">
                                                    Teacher Review
                                                </p>

                                                <p class="mt-3 text-sm text-slate-600">
                                                    Grade
                                                </p>

                                                <p class="mt-1 text-2xl font-bold text-green-700">
                                                    {{ $submission->grade !== null ? number_format((float) $submission->grade, 2) . ' / 100' : 'Not graded' }}
                                                </p>

                                                <p class="mt-4 text-sm font-medium text-slate-700">
                                                    Feedback
                                                </p>

                                                <p class="mt-1 whitespace-pre-line text-sm text-slate-600">
                                                    {{ $submission->feedback ?: 'No feedback provided.' }}
                                                </p>

                                                @if($submission->reviewed_at)
                                                    <p class="mt-3 text-xs text-slate-500">
                                                        Reviewed {{ $submission->reviewed_at->format('M d, Y h:i A') }}
                                                    </p>
                                                @endif

                                            </div>
                                        @endif

                                    </div>
                                @endif

                                {{-- Validation Errors --}}
                                @if($errors->any())
                                    <div class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4">
                                        <p class="font-medium text-red-800">
                                            Please check the following:
                                        </p>

                                        <ul class="mt-2 list-inside list-disc text-sm text-red-700">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                {{-- Submission Form --}}
                                @if(!$submission || $submission->status !== 'Reviewed')

                                    <form
                                        action="{{ route('tasks.submissions.store', $task) }}"
                                        method="POST"
                                        enctype="multipart/form-data"
                                        class="space-y-5"
                                    >
                                        @csrf

                                        {{-- Written Answer --}}
                                        <div>
                                            <label
                                                for="answer"
                                                class="mb-2 block text-sm font-medium text-slate-700"
                                            >
                                                Written Answer
                                            </label>

                                            <textarea
                                                id="answer"
                                                name="answer"
                                                rows="6"
                                                maxlength="20000"
                                                placeholder="Write your answer here..."
                                                class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm text-slate-800 outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                                            >{{ old('answer', $submission?->answer) }}</textarea>

                                            <p class="mt-1 text-xs text-slate-500">
                                                You may submit a written answer, an attachment, or both.
                                            </p>
                                        </div>

                                        {{-- File Upload --}}
                                        <div>
                                            <label
                                                for="file"
                                                class="mb-2 block text-sm font-medium text-slate-700"
                                            >
                                                Assignment File
                                            </label>

                                            <input
                                                id="file"
                                                name="file"
                                                type="file"
                                                accept=".pdf,.doc,.docx,.txt,.png,.jpg,.jpeg"
                                                class="block w-full rounded-xl border border-slate-300 bg-white text-sm text-slate-700 file:mr-4 file:border-0 file:bg-slate-100 file:px-4 file:py-3 file:font-medium hover:file:bg-slate-200"
                                            >

                                            <p class="mt-2 text-xs text-slate-500">
                                                Accepted formats: PDF, DOC, DOCX, TXT, PNG, JPG, JPEG. Maximum size: 10 MB.
                                            </p>

                                            @if($submission?->file_path)
                                                <p class="mt-2 text-xs text-slate-500">
                                                    Uploading a new file will replace your previous attachment.
                                                </p>
                                            @endif
                                        </div>

                                        {{-- Submit Button --}}
                                        <div class="flex flex-wrap items-center gap-3 pt-2">

                                            <button
                                                type="submit"
                                                class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-medium text-white transition hover:bg-blue-700"
                                            >
                                                <i data-lucide="upload" class="h-4 w-4"></i>

                                                {{ $submission ? 'Update Submission' : 'Submit Assignment' }}
                                            </button>

                                            @if($task->due_date->isPast() && !$submission)
                                                <p class="text-xs text-red-600">
                                                    The deadline has passed. Check your teacher's submission policy.
                                                </p>
                                            @endif

                                        </div>

                                    </form>

                                @else
                                    <p class="text-sm text-slate-500">
                                        Your submission has been reviewed and can no longer be edited.
                                    </p>
                                @endif

                            </div>
                        </div>
                    @endif

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

                        {{-- Student Submission Reminder --}}
                        @if(auth()->user()->isStudent())
                            @if(!$submission)
                                <p class="text-sm text-slate-500">
                                    Submit your work using the Task Submission section above.
                                </p>
                            @elseif($submission->status === 'Submitted')
                                <p class="text-sm text-blue-700">
                                    Your work has been submitted and is awaiting review.
                                </p>
                            @elseif($submission->status === 'Reviewed')
                                <p class="text-sm text-green-700">
                                    Your work has been reviewed by your teacher.
                                </p>
                            @endif
                        @endif

                        {{-- Teacher / Admin Actions --}}
                        @if(auth()->user()->isAdmin() || (
                            auth()->user()->isTeacher() &&
                            $task->created_by === auth()->id()
                        ))
                            <a
                                href="{{ route('tasks.submissions.index', $task) }}"
                                class="inline-flex items-center gap-2 rounded-xl border border-slate-300 bg-white px-5 py-3 font-medium text-slate-700 transition hover:bg-slate-50"
                            >
                                <i data-lucide="clipboard-check" class="h-4 w-4"></i>
                                View Submissions
                            </a>
                        @endif
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