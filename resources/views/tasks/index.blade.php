@extends('layouts.app')

@section('title', 'My Tasks')

@section('page-heading', 'My Tasks')

@section('content')

<div class="min-h-screen bg-slate-100 p-8">

    <div class="max-w-6xl mx-auto">
        @if(session('success'))

            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-xl">
                {{ session('success') }}
            </div>

        @endif

        <div class="flex items-center justify-between mb-8">

            <div>
                <h1 class="text-3xl font-bold text-slate-900">
                    My Tasks
                </h1>

                <p class="text-slate-500 mt-1">
                    View your assignments, projects, quizzes and exams.
                </p>
            </div>

            <a href="{{ route('tasks.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl font-medium">

                + Create Task

            </a>

        </div>


        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm">

            <div class="p-6">

                @forelse($tasks as $task)

                    <div class="border-b border-slate-100 py-5 last:border-0">

                        <div class="flex items-center justify-between">

                            <div>

                                <h2 class="font-semibold text-slate-900">
                                    {{ $task->title }}
                                </h2>

                                <p class="text-sm text-slate-500 mt-1">
                                    {{ $task->subject }}
                                </p>

                            </div>

                            <div class="text-right">

                                <span class="text-xs bg-blue-100 text-blue-700 px-3 py-1 rounded-full">
                                    {{ $task->type }}
                                </span>

                                <p class="text-sm text-slate-500 mt-2">
                                    Due {{ $task->due_date->format('M d, Y h:i A') }}
                                </p>

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="text-center py-12">

                        <div class="text-4xl mb-4">
                            📚
                        </div>

                        <h2 class="font-semibold text-slate-800">
                            No tasks yet
                        </h2>

                        <p class="text-slate-500 mt-1">
                            Create your first academic task.
                        </p>

                    </div>

                @endforelse

            </div>

        </div>

    </div>

</div>

@endsection