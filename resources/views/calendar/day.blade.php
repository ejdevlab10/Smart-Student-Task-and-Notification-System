@extends('layouts.app')

@section('title', 'Day Schedule')

@section('page-heading', 'Day Schedule')

@section('content')

<div class="min-h-screen bg-slate-50 px-3 py-5 sm:px-6 sm:py-8">

    <div class="mx-auto max-w-4xl">

        {{-- Back to Calendar --}}
        <!-- <a
            href="{{ route('calendar.index', [
                'month' => $selectedDate->format('Y-m')
            ]) }}"
            class="mb-5 inline-flex items-center gap-2 text-sm font-medium
                   text-blue-700 hover:text-blue-800"
        >
            &larr; Back to Calendar
        </a> -->
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

        {{-- Page Header --}}
        <div class="mb-6 flex flex-wrap items-center justify-between gap-4">

            <div>
                <p class="text-sm font-medium text-blue-600">
                    Day Schedule
                </p>

                <h1 class="mt-1 text-2xl font-bold text-slate-900 sm:text-3xl">
                    {{ $selectedDate->format('F j, Y') }}
                </h1>

                <p class="mt-1 text-sm text-slate-500">
                    {{ $tasks->count() }}
                    {{ $tasks->count() === 1 ? 'task' : 'tasks' }} scheduled
                </p>
            </div>

            {{-- Add Task: Teachers and Admins Only --}}
            @if(auth()->user()->isTeacher() || auth()->user()->isAdmin())
                <a
                    href="{{ route('tasks.create', [
                        'due_date' => $selectedDate->format('Y-m-d')
                    ]) }}"
                    class="inline-flex min-h-11 items-center justify-center gap-2
                           rounded-xl bg-blue-600 px-4 py-2.5 text-sm
                           font-semibold text-white shadow-sm transition
                           hover:bg-blue-700 focus:outline-none focus:ring-2
                           focus:ring-blue-500 focus:ring-offset-2"
                >
                    <span class="text-lg leading-none">+</span>
                    Add Task
                </a>
            @endif

        </div>

        {{-- Task List --}}
        @if($tasks->isEmpty())

            <div class="rounded-2xl border border-dashed border-slate-300
                        bg-white px-5 py-12 text-center">

                <div class="mx-auto flex h-14 w-14 items-center justify-center
                            rounded-full bg-slate-100 text-2xl">
                    📅
                </div>

                <h2 class="mt-4 text-lg font-semibold text-slate-900">
                    No tasks scheduled
                </h2>

                <p class="mx-auto mt-2 max-w-sm text-sm text-slate-500">
                    There are no tasks scheduled for this date.
                </p>

                @if(auth()->user()->isTeacher() || auth()->user()->isAdmin())
                    <a
                        href="{{ route('tasks.create', [
                            'due_date' => $selectedDate->format('Y-m-d')
                        ]) }}"
                        class="mt-5 inline-flex min-h-11 items-center justify-center
                               rounded-xl bg-blue-600 px-4 py-2.5 text-sm
                               font-semibold text-white hover:bg-blue-700"
                    >
                        + Create a Task
                    </a>
                @endif

            </div>

        @else

            <div class="space-y-3">

                @foreach($tasks as $task)

                    @php
                        $status = auth()->user()->isStudent()
                            ? ($task->pivot->status ?? 'Pending')
                            : ($task->status ?? 'Pending');

                        $normalizedStatus = strtolower(trim($status));

                        $isOverdue = $task->due_date
                            && $task->due_date->isPast()
                            && !in_array($normalizedStatus, [
                                'completed',
                                'complete',
                                'submitted'
                            ]);

                        if ($normalizedStatus === 'completed') {
                            $statusClasses = 'bg-emerald-50 text-emerald-700';
                        } elseif ($isOverdue) {
                            $statusClasses = 'bg-rose-50 text-rose-700';
                        } else {
                            $statusClasses = 'bg-blue-50 text-blue-700';
                        }

                        $statusLabel = $normalizedStatus === 'completed'
                            ? 'Completed'
                            : ($isOverdue ? 'Overdue' : ucfirst($status));
                    @endphp

                    <a
                        href="{{ route('tasks.show', $task) }}"
                        class="block rounded-2xl border border-slate-200 bg-white
                               p-4 shadow-sm transition hover:border-blue-300
                               hover:shadow-md sm:p-5"
                    >

                        <div class="flex items-start justify-between gap-3">

                            <div class="min-w-0 flex-1">

                                <div class="flex flex-wrap items-center gap-2">

                                    <span class="rounded-md bg-slate-100 px-2 py-1
                                                 text-xs font-medium text-slate-600">
                                        {{ $task->type }}
                                    </span>

                                    <span class="rounded-full px-2.5 py-1
                                                 text-xs font-semibold
                                                 {{ $statusClasses }}">
                                        {{ $statusLabel }}
                                    </span>

                                </div>

                                <h2 class="mt-3 break-words text-base font-bold
                                           text-slate-900 sm:text-lg">
                                    {{ $task->title }}
                                </h2>

                                <p class="mt-1 text-sm text-slate-500">
                                    {{ $task->subject }}
                                </p>

                                <p class="mt-3 flex items-center gap-2 text-sm
                                          font-medium text-slate-600">
                                    <span aria-hidden="true">🕒</span>
                                    {{ $task->due_date->format('h:i A') }}
                                </p>

                            </div>

                            <span class="mt-1 shrink-0 text-slate-400"
                                  aria-hidden="true">
                                &rarr;
                            </span>

                        </div>

                    </a>

                @endforeach

            </div>

        @endif

    </div>

</div>

@endsection