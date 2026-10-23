@extends('layouts.app')

@section('title', 'Calendar')

@section('page-heading', 'Calendar')

@section('content')

@php
    use Illuminate\Support\Carbon;

    // Group tasks by date once to avoid filtering the entire collection
    // repeatedly for every calendar cell.
    $tasksByDate = $tasks->filter(function ($task) {
        return $task->due_date !== null;
    })->groupBy(function ($task) {
        return $task->due_date->format('Y-m-d');
    });

    $startDate = $currentMonth->copy()->startOfWeek(Carbon::SUNDAY);
    $endDate = $currentMonth->copy()->endOfMonth()->endOfWeek(Carbon::SATURDAY);

    $calendarDate = $startDate->copy();

    $isStudent = auth()->user()->role === 'student';

    $getTaskStatus = function ($task) use ($isStudent) {
        // Students have their own task status in the task_user pivot.
        // Teachers/admins use the task's main status.
        return $isStudent
            ? ($task->pivot->status ?? 'Pending')
            : ($task->status ?? 'Pending');
    };

    $getStatusColor = function ($task) use ($getTaskStatus) {
        $status = strtolower(trim($getTaskStatus($task)));

        if ($status === 'completed') {
            return 'bg-emerald-50 border-emerald-200 text-emerald-800';
        }

        if (
            $task->due_date &&
            $task->due_date->isPast() &&
            !in_array($status, ['completed', 'complete', 'submitted'])
        ) {
            return 'bg-rose-50 border-rose-200 text-rose-800';
        }

        return 'bg-blue-50 border-blue-200 text-blue-800';
    };

    $getStatusLabel = function ($task) use ($getTaskStatus) {
        $status = strtolower(trim($getTaskStatus($task)));

        if ($status === 'completed') {
            return 'Completed';
        }

        if (
            $task->due_date &&
            $task->due_date->isPast() &&
            !in_array($status, ['completed', 'complete', 'submitted'])
        ) {
            return 'Overdue';
        }

        return ucfirst($status);
    };
@endphp

<div class="min-h-screen bg-slate-50 px-3 py-4 sm:px-5 sm:py-6 lg:p-8">

    <div class="mx-auto max-w-7xl">

        {{-- Page Header --}}
        <div class="mb-5 sm:mb-6">
            <h1 class="text-2xl font-bold tracking-tight text-slate-900 sm:text-3xl">
                Academic Calendar
            </h1>

            <p class="mt-1 text-sm text-slate-500 sm:text-base">
                View your assignments, projects, quizzes, and exams by date.
            </p>
        </div>

        {{-- Calendar Card --}}
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

            {{-- Month Navigation --}}
            <div class="flex items-center justify-between gap-2 border-b border-slate-200 p-3 sm:p-5">

                {{-- Previous Month --}}
                <a
                    href="{{ route('calendar.index', [
                        'month' => $currentMonth->copy()->subMonth()->format('Y-m')
                    ]) }}"
                    aria-label="Previous month"
                    class="inline-flex min-h-10 items-center justify-center gap-1 rounded-xl
                           border border-slate-200 px-2.5 text-sm font-medium text-slate-700
                           transition hover:bg-slate-50 focus:outline-none focus:ring-2
                           focus:ring-blue-500 sm:px-4"
                >
                    <span aria-hidden="true">&larr;</span>
                    <span class="hidden sm:inline">Previous</span>
                </a>

                {{-- Current Month --}}
                <div class="min-w-0 text-center">
                    <h2 class="text-lg font-bold text-slate-900 sm:text-2xl">
                        {{ $currentMonth->format('F Y') }}
                    </h2>

                    <p class="mt-0.5 text-xs text-slate-500 sm:text-sm">
                        Academic schedule
                    </p>
                </div>

                {{-- Next Month --}}
                <a
                    href="{{ route('calendar.index', [
                        'month' => $currentMonth->copy()->addMonth()->format('Y-m')
                    ]) }}"
                    aria-label="Next month"
                    class="inline-flex min-h-10 items-center justify-center gap-1 rounded-xl
                           border border-slate-200 px-2.5 text-sm font-medium text-slate-700
                           transition hover:bg-slate-50 focus:outline-none focus:ring-2
                           focus:ring-blue-500 sm:px-4"
                >
                    <span class="hidden sm:inline">Next</span>
                    <span aria-hidden="true">&rarr;</span>
                </a>

            </div>

            {{-- Today Button and Legend --}}
            <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 px-3 py-3 sm:px-5">

                <a
                    href="{{ route('calendar.index') }}"
                    class="inline-flex min-h-9 items-center justify-center rounded-lg
                           bg-blue-50 px-3 py-2 text-sm font-semibold text-blue-700
                           transition hover:bg-blue-100 focus:outline-none focus:ring-2
                           focus:ring-blue-500"
                >
                    Today
                </a>

                <div class="flex flex-wrap items-center gap-x-3 gap-y-2 text-xs text-slate-600 sm:gap-x-4 sm:text-sm">

                    <span class="inline-flex items-center gap-1.5">
                        <span class="h-2.5 w-2.5 rounded-full bg-blue-500"></span>
                        Pending
                    </span>

                    <span class="inline-flex items-center gap-1.5">
                        <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                        Completed
                    </span>

                    <span class="inline-flex items-center gap-1.5">
                        <span class="h-2.5 w-2.5 rounded-full bg-rose-500"></span>
                        Overdue
                    </span>

                </div>
            </div>

            {{-- Weekday Header --}}
            <div class="grid grid-cols-7 border-b border-slate-200 bg-slate-50">

                @foreach([
                    'Sun',
                    'Mon',
                    'Tue',
                    'Wed',
                    'Thu',
                    'Fri',
                    'Sat'
                ] as $day)

                    <div class="py-2 text-center text-[10px] font-bold uppercase tracking-wide
                                text-slate-500 sm:py-3 sm:text-xs">
                        <span class="sm:hidden">{{ substr($day, 0, 1) }}</span>
                        <span class="hidden sm:inline">{{ $day }}</span>
                    </div>

                @endforeach

            </div>

            {{-- Calendar Days --}}
            <div class="grid grid-cols-7">

                @while($calendarDate <= $endDate)

                    @php
                        $dateKey = $calendarDate->format('Y-m-d');

                        $dayTasks = $tasksByDate->get($dateKey, collect());

                        $isToday = $calendarDate->isToday();

                        $isCurrentMonth =
                            $calendarDate->month === $currentMonth->month &&
                            $calendarDate->year === $currentMonth->year;

                        $visibleTasks = $dayTasks->take(3);
                        $remainingTasks = max(0, $dayTasks->count() - $visibleTasks->count());
                    @endphp

                    <div
                        class="min-w-0 min-h-[76px] border-b border-r border-slate-200
                               p-1 sm:min-h-[120px] sm:p-2 lg:min-h-[145px]
                               {{ $isCurrentMonth ? 'bg-white' : 'bg-slate-50/70' }}"
                    >

                        {{-- Clickable Date Number --}}
                        <div class="mb-1 flex items-center justify-center sm:mb-2 sm:justify-start">

                            <a
                                href="{{ route('calendar.day', [
                                    'date' => $calendarDate->format('Y-m-d')
                                ]) }}"
                                aria-label="View schedule for {{ $calendarDate->format('F j, Y') }}"
                                class="inline-flex h-6 w-6 items-center justify-center rounded-full
                                    text-[11px] font-semibold transition hover:ring-2
                                    hover:ring-blue-300 focus:outline-none focus:ring-2
                                    focus:ring-blue-500 sm:h-8 sm:w-8 sm:text-sm
                                    {{ $isToday
                                            ? 'bg-blue-600 text-white shadow-sm'
                                            : ($isCurrentMonth
                                                ? 'text-slate-700 hover:bg-blue-50'
                                                : 'text-slate-400 hover:bg-slate-100') }}"
                            >
                                {{ $calendarDate->day }}
                            </a>

                        </div>

                        {{-- Tasks for This Date --}}
                        <div class="space-y-1">

                            @foreach($visibleTasks as $task)

                                @php
                                    $taskColor = $getStatusColor($task);
                                    $taskStatusLabel = $getStatusLabel($task);
                                @endphp

                                <a
                                    href="{{ route('tasks.show', $task) }}"
                                    title="{{ $task->title }} — {{ $taskStatusLabel }}"
                                    class="block min-w-0 overflow-hidden rounded-md border p-1
                                           transition hover:brightness-95 focus:outline-none
                                           focus:ring-2 focus:ring-blue-500 sm:rounded-lg
                                           sm:p-2 {{ $taskColor }}"
                                >

                                    {{-- Task Title --}}
                                    <p class="truncate text-[9px] font-semibold leading-tight
                                              sm:text-xs sm:leading-snug">
                                        {{ $task->title }}
                                    </p>

                                    {{-- Time: hidden on very small screens --}}
                                    <p class="mt-1 hidden truncate text-[10px] opacity-80 sm:block">
                                        {{ $task->due_date->format('h:i A') }}
                                    </p>

                                    {{-- Task Type: desktop only --}}
                                    <p class="mt-1 hidden truncate text-[10px] opacity-80 lg:block">
                                        {{ $task->type }}
                                    </p>

                                    {{-- Status: desktop only --}}
                                    <p class="mt-1 hidden truncate text-[10px] font-medium sm:block">
                                        {{ $taskStatusLabel }}
                                    </p>

                                </a>

                            @endforeach

                            {{-- Additional Tasks Indicator --}}
                            @if($remainingTasks > 0)
                                <p class="truncate px-0.5 text-center text-[9px] font-semibold
                                          text-slate-500 sm:text-left sm:text-[11px]">
                                    +{{ $remainingTasks }} more
                                </p>
                            @endif

                        </div>

                    </div>

                    @php
                        $calendarDate->addDay();
                    @endphp

                @endwhile

            </div>

        </div>

        

    </div>

</div>

@endsection