@extends('layouts.app')

@section('title', 'Calendar')

@section('page-heading', 'Calendar')

@section('content')

<div class="min-h-screen bg-slate-100 p-8">

    <div class="max-w-6xl mx-auto">

        <div class="mb-8">
            <h1 class="text-3xl font-bold text-slate-900">
                Academic Calendar
            </h1>

            <p class="text-slate-500 mt-1">
                View your upcoming assignments, projects, quizzes and exams.
            </p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm">

            <div class="p-6">

                @forelse($tasks as $task)

                    <div class="flex items-center justify-between border-b border-slate-100 py-5 last:border-0">

                        <div>

                            <h2 class="font-semibold text-slate-900">
                                {{ $task->title }}
                            </h2>

                            <p class="text-sm text-slate-500 mt-1">
                                {{ $task->subject }}
                                ·
                                {{ $task->type }}
                            </p>

                        </div>

                        <div class="text-right">

                            <p class="font-medium text-slate-700">
                                {{ $task->due_date->format('M d, Y') }}
                            </p>

                            <p class="text-sm text-slate-500">
                                {{ $task->due_date->format('h:i A') }}
                            </p>

                        </div>

                    </div>

                @empty

                    <div class="text-center py-12">

                        <div class="text-4xl mb-4">
                            📅
                        </div>

                        <h2 class="font-semibold text-slate-800">
                            No upcoming tasks
                        </h2>

                        <p class="text-slate-500 mt-1">
                            Your academic calendar is currently empty.
                        </p>

                    </div>

                @endforelse

            </div>

        </div>

    </div>

</div>

@endsection