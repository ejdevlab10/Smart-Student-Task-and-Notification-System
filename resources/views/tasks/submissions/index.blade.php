@extends('layouts.app')

@section('title', 'Task Submissions')
@section('page-heading', 'Task Submissions')

@section('content')

<div class="min-h-screen bg-slate-100 p-6 lg:p-8">

    <div class="mx-auto max-w-7xl">

        {{-- Back Button --}}
        <div class="mb-6">
            <x-back-button
                fallback="admin.classes.index"
                label="Back"
                class="inline-flex items-center justify-center gap-2 rounded-xl
            border border-slate-300 bg-white px-4 py-2.5
            text-sm font-medium text-slate-700 hover:bg-slate-50"
            />
            </x>
        </div>

        {{-- Success Message --}}
        @if(session('success'))
            <div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-5 py-4 text-green-700">
                <div class="flex items-center gap-2">
                    <i data-lucide="check-circle" class="h-5 w-5"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        {{-- Error Message --}}
        @if(session('error'))
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 px-5 py-4 text-red-700">
                {{ session('error') }}
            </div>
        @endif

        {{-- Page Header --}}
        <div class="mb-8 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm lg:p-8">

            <div class="flex flex-col gap-5 sm:flex-row sm:items-start sm:justify-between">

                <div>
                    <span class="mb-3 inline-flex rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                        {{ $task->type }}
                    </span>

                    <h1 class="text-2xl font-bold text-slate-900 sm:text-3xl">
                        {{ $task->title }}
                    </h1>

                    <p class="mt-2 text-sm text-slate-500">
                        {{ $task->subject }}
                    </p>

                    @if($task->schoolClass)
                        <p class="mt-3 inline-flex items-center gap-2 rounded-lg bg-purple-50 px-3 py-2 text-sm font-medium text-purple-700">
                            <i data-lucide="users" class="h-4 w-4"></i>

                            {{ $task->schoolClass->name }}

                            @if($task->schoolClass->section)
                                — {{ $task->schoolClass->section }}
                            @endif
                        </p>
                    @endif
                </div>

                <div class="rounded-xl bg-slate-50 p-4 sm:min-w-44">
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-500">
                        Due Date
                    </p>

                    <p class="mt-2 font-semibold text-slate-800">
                        {{ $task->due_date->format('M d, Y') }}
                    </p>

                    <p class="mt-1 text-sm text-slate-500">
                        {{ $task->due_date->format('h:i A') }}
                    </p>
                </div>

            </div>

        </div>

        {{-- Submission Statistics --}}
        @php
            $totalSubmissions = $submissions->total();

            $reviewedCount = $task->submissions()
                ->where('status', 'Reviewed')
                ->count();

            $awaitingReviewCount = $task->submissions()
                ->where('status', 'Submitted')
                ->count();
        @endphp

        <div class="mb-8 grid grid-cols-1 gap-4 sm:grid-cols-3">

            {{-- Total Submissions --}}
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                <div class="flex items-center justify-between">
                    <p class="text-sm text-slate-500">
                        Total Submissions
                    </p>

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-100">
                        <i data-lucide="files" class="h-5 w-5 text-blue-600"></i>
                    </div>
                </div>

                <p class="mt-4 text-3xl font-bold text-slate-900">
                    {{ $task->submissions()->count() }}
                </p>

                <p class="mt-1 text-xs text-slate-500">
                    Students who submitted work
                </p>

            </div>

            {{-- Awaiting Review --}}
            <div class="rounded-2xl border border-orange-200 bg-orange-50 p-5">

                <div class="flex items-center justify-between">
                    <p class="text-sm text-orange-700">
                        Awaiting Review
                    </p>

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-orange-100">
                        <i data-lucide="clock" class="h-5 w-5 text-orange-600"></i>
                    </div>
                </div>

                <p class="mt-4 text-3xl font-bold text-orange-700">
                    {{ $awaitingReviewCount }}
                </p>

                <p class="mt-1 text-xs text-orange-700">
                    Submissions awaiting grading
                </p>

            </div>

            {{-- Reviewed --}}
            <div class="rounded-2xl border border-green-200 bg-green-50 p-5">

                <div class="flex items-center justify-between">
                    <p class="text-sm text-green-700">
                        Reviewed
                    </p>

                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-green-100">
                        <i data-lucide="check-circle" class="h-5 w-5 text-green-600"></i>
                    </div>
                </div>

                <p class="mt-4 text-3xl font-bold text-green-700">
                    {{ $reviewedCount }}
                </p>

                <p class="mt-1 text-xs text-green-700">
                    Submissions already reviewed
                </p>

            </div>

        </div>

        {{-- Validation Errors --}}
        @if($errors->any())
            <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-5">

                <p class="font-semibold text-red-800">
                    Please correct the following:
                </p>

                <ul class="mt-2 list-inside list-disc text-sm text-red-700">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>
        @endif

        {{-- Submissions List --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            <div class="border-b border-slate-200 p-5 sm:p-6">

                <h2 class="text-lg font-bold text-slate-900">
                    Student Submissions
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Review student answers, download attachments, and provide grades and feedback.
                </p>

            </div>

            @if($submissions->count() > 0)

                <div class="divide-y divide-slate-200">

                    @foreach($submissions as $submission)

                        <div class="p-5 sm:p-6">

                            {{-- Student Header --}}
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">

                                <div class="flex items-start gap-3">

                                    <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-blue-100">
                                        <i data-lucide="user-round" class="h-5 w-5 text-blue-600"></i>
                                    </div>

                                    <div>
                                        <h3 class="font-semibold text-slate-900">
                                            {{ $submission->student->name ?? 'Unknown Student' }}
                                        </h3>

                                        <p class="mt-1 text-sm text-slate-500">
                                            {{ $submission->student->email ?? 'Email unavailable' }}
                                        </p>

                                        <p class="mt-2 text-xs text-slate-500">
                                            Submitted:
                                            @if($submission->submitted_at)
                                                {{ $submission->submitted_at->format('M d, Y h:i A') }}
                                            @else
                                                Date unavailable
                                            @endif
                                        </p>
                                    </div>

                                </div>

                                {{-- Submission Status --}}
                                @if($submission->status === 'Reviewed')

                                    <span class="inline-flex items-center gap-1.5 self-start rounded-full bg-green-100 px-3 py-1.5 text-xs font-semibold text-green-700">
                                        <i data-lucide="check-circle" class="h-4 w-4"></i>
                                        Reviewed
                                    </span>

                                @else

                                    <span class="inline-flex items-center gap-1.5 self-start rounded-full bg-orange-100 px-3 py-1.5 text-xs font-semibold text-orange-700">
                                        <i data-lucide="clock" class="h-4 w-4"></i>
                                        Awaiting Review
                                    </span>

                                @endif

                            </div>

                            {{-- Written Answer --}}
                            <div class="mt-6">

                                <h4 class="mb-2 text-sm font-semibold text-slate-700">
                                    Student's Answer
                                </h4>

                                @if($submission->answer)

                                    <div class="whitespace-pre-line break-words rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm leading-relaxed text-slate-700">{{ $submission->answer }}</div>

                                @else

                                    <p class="rounded-xl border border-dashed border-slate-300 p-4 text-sm italic text-slate-400">
                                        No written answer was provided.
                                    </p>

                                @endif

                            </div>

                            {{-- Attachment --}}
                            @if($submission->file_path)

                                <div class="mt-4 rounded-xl border border-blue-200 bg-blue-50 p-4">

                                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">

                                        <div class="flex items-center gap-3">

                                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-blue-100">
                                                <i data-lucide="file-text" class="h-5 w-5 text-blue-600"></i>
                                            </div>

                                            <div>
                                                <p class="text-sm font-medium text-slate-800">
                                                    Assignment Attachment
                                                </p>

                                                <p class="mt-1 text-xs text-slate-500">
                                                    Download the student's submitted file.
                                                </p>
                                            </div>

                                        </div>

                                        <a
                                            href="{{ route('task-submissions.download', $submission) }}"
                                            class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700"
                                        >
                                            <i data-lucide="download" class="h-4 w-4"></i>
                                            Download File
                                        </a>

                                    </div>

                                </div>

                            @endif

                            {{-- Existing Grade and Feedback --}}
                            @if($submission->status === 'Reviewed')

                                <div class="mt-5 rounded-xl border border-green-200 bg-green-50 p-5">

                                    <h4 class="font-semibold text-green-800">
                                        Review Results
                                    </h4>

                                    <div class="mt-3">

                                        <p class="text-sm text-slate-600">
                                            Grade
                                        </p>

                                        <p class="mt-1 text-2xl font-bold text-green-700">
                                            {{ $submission->grade !== null ? number_format((float) $submission->grade, 2) . ' / 100' : 'Not graded' }}
                                        </p>

                                    </div>

                                    <div class="mt-4">

                                        <p class="text-sm font-medium text-slate-700">
                                            Teacher Feedback
                                        </p>

                                        <p class="mt-1 whitespace-pre-line text-sm leading-relaxed text-slate-600">{{ $submission->feedback ?: 'No feedback provided.' }}</p>

                                    </div>

                                    @if($submission->reviewed_at)
                                        <p class="mt-3 text-xs text-slate-500">
                                            Reviewed on {{ $submission->reviewed_at->format('M d, Y h:i A') }}
                                        </p>
                                    @endif

                                </div>

                            @endif

                            {{-- Grading Form --}}
                            <div class="mt-6 rounded-xl border border-slate-200 p-5">

                                <div class="mb-5">
                                    <h4 class="font-semibold text-slate-900">
                                        {{ $submission->status === 'Reviewed' ? 'Update Review' : 'Grade Submission' }}
                                    </h4>

                                    <p class="mt-1 text-sm text-slate-500">
                                        Enter a grade and provide constructive feedback for the student.
                                    </p>
                                </div>

                                <form
                                    action="{{ route('task-submissions.review', $submission) }}"
                                    method="POST"
                                    class="space-y-5"
                                >
                                    @csrf
                                    @method('PATCH')

                                    {{-- Grade --}}
                                    <div>

                                        <label
                                            for="grade-{{ $submission->id }}"
                                            class="mb-2 block text-sm font-medium text-slate-700"
                                        >
                                            Grade (0–100)
                                        </label>

                                        <input
                                            id="grade-{{ $submission->id }}"
                                            type="number"
                                            name="grade"
                                            min="0"
                                            max="100"
                                            step="0.01"
                                            value="{{ old('grade', $submission->grade) }}"
                                            required
                                            placeholder="Enter grade"
                                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100 sm:max-w-xs"
                                        >

                                    </div>

                                    {{-- Feedback --}}
                                    <div>

                                        <label
                                            for="feedback-{{ $submission->id }}"
                                            class="mb-2 block text-sm font-medium text-slate-700"
                                        >
                                            Feedback
                                        </label>

                                        <textarea
                                            id="feedback-{{ $submission->id }}"
                                            name="feedback"
                                            rows="4"
                                            maxlength="5000"
                                            placeholder="Write feedback for the student..."
                                            class="w-full rounded-xl border border-slate-300 px-4 py-3 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
                                        >{{ old('feedback', $submission->feedback) }}</textarea>

                                        <p class="mt-1 text-xs text-slate-500">
                                            Feedback is visible to the student on the task details page.
                                        </p>

                                    </div>

                                    {{-- Submit Review --}}
                                    <div class="flex flex-wrap items-center gap-3">

                                        <button
                                            type="submit"
                                            class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-5 py-3 text-sm font-medium text-white transition hover:bg-blue-700"
                                        >
                                            <i data-lucide="clipboard-check" class="h-4 w-4"></i>

                                            {{ $submission->status === 'Reviewed' ? 'Update Grade and Feedback' : 'Save Review' }}
                                        </button>

                                    </div>

                                </form>

                            </div>

                        </div>

                    @endforeach

                </div>

                {{-- Pagination --}}
                @if(method_exists($submissions, 'links'))
                    <div class="border-t border-slate-200 p-5">
                        {{ $submissions->links() }}
                    </div>
                @endif

            @else

                {{-- Empty State --}}
                <div class="px-6 py-16 text-center">

                    <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-slate-100">
                        <i data-lucide="inbox" class="h-8 w-8 text-slate-400"></i>
                    </div>

                    <h3 class="text-lg font-semibold text-slate-800">
                        No Submissions Yet
                    </h3>

                    <p class="mx-auto mt-2 max-w-md text-sm leading-relaxed text-slate-500">
                        Students' submissions will appear here once they submit their written answers or upload assignment files.
                    </p>

                </div>

            @endif

        </div>

    </div>

</div>

@endsection