@extends('layouts.app')

@section('title', 'Calendar')

@section('page-heading', 'Calendar')

@section('content')

<div class="min-h-screen bg-slate-100 p-6 lg:p-8">

    <div class="max-w-7xl mx-auto">

        {{-- Page Header --}}
        <div class="mb-6">

            <h1 class="text-3xl font-bold text-slate-900">
                Academic Calendar
            </h1>

            <p class="text-slate-500 mt-1">
                View your assignments, projects, quizzes and exams by date.
            </p>

        </div>

        {{-- Calendar --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

            {{-- Calendar Navigation --}}
            <div class="flex items-center justify-between p-5 border-b border-slate-200">

                {{-- Previous Month --}}
                <a
                    href="{{ route('calendar.index', [
                        'month' => $currentMonth->copy()->subMonth()->format('Y-m')
                    ]) }}"
                    class="px-4 py-2 rounded-lg border border-slate-200
                           text-sm font-medium text-slate-700
                           hover:bg-slate-50 transition"
                >
                    ← Previous
                </a>

                {{-- Month --}}
                <div class="text-center">

                    <h2 class="text-xl font-bold text-slate-900">
                        {{ $currentMonth->format('F Y') }}
                    </h2>

                </div>

                {{-- Next Month --}}
                <a
                    href="{{ route('calendar.index', [
                        'month' => $currentMonth->copy()->addMonth()->format('Y-m')
                    ]) }}"
                    class="px-4 py-2 rounded-lg border border-slate-200
                           text-sm font-medium text-slate-700
                           hover:bg-slate-50 transition"
                >
                    Next →
                </a>

            </div>

            {{-- Today Button --}}
            <div class="px-5 py-3 border-b border-slate-100">

                <a
                    href="{{ route('calendar.index') }}"
                    class="inline-flex items-center px-3 py-1.5 rounded-lg
                           bg-blue-50 text-blue-700 text-sm font-medium
                           hover:bg-blue-100 transition"
                >
                    Today
                </a>

            </div>

            {{-- Weekday Header --}}
            <div class="grid grid-cols-7 border-b border-slate-200 bg-slate-50">

                @foreach([
                    'Sunday',
                    'Monday',
                    'Tuesday',
                    'Wednesday',
                    'Thursday',
                    'Friday',
                    'Saturday'
                ] as $day)

                    <div class="p-3 text-center text-sm font-semibold text-slate-600">
                        {{ $day }}
                    </div>

                @endforeach

            </div>

            {{-- Calendar Days --}}
            <div class="grid grid-cols-7">

                @php
                    $startDate = $currentMonth->copy()->startOfWeek();
                    $endDate = $currentMonth->copy()->endOfMonth()->endOfWeek();

                    $calendarDate = $startDate->copy();
                @endphp

                @while($calendarDate <= $endDate)

                    @php

                        $dayTasks = $tasks->filter(function ($task) use ($calendarDate) {
                            return $task->due_date->isSameDay($calendarDate);
                        });

                        $isToday = $calendarDate->isToday();

                        $isCurrentMonth =
                            $calendarDate->month === $currentMonth->month &&
                            $calendarDate->year === $currentMonth->year;

                    @endphp

                    <div
                        class="
                            min-h-[140px]
                            border-b border-r border-slate-200
                            p-2
                            {{ $isCurrentMonth ? 'bg-white' : 'bg-slate-50' }}
                        "
                    >

                        {{-- Date Number --}}
                        <div class="flex justify-between items-start mb-2">

                            <span
                                class="
                                    inline-flex
                                    items-center
                                    justify-center
                                    w-8
                                    h-8
                                    rounded-full
                                    text-sm
                                    font-semibold

                                    {{ $isToday
                                        ? 'bg-blue-600 text-white'
                                        : ($isCurrentMonth
                                            ? 'text-slate-700'
                                            : 'text-slate-400')
                                    }}
                                "
                            >
                                {{ $calendarDate->day }}
                            </span>

                        </div>

                        {{-- Tasks --}}
                        <div class="space-y-2">

                            @foreach($dayTasks as $task)

                                <a
                                    href="{{ route('tasks.show', $task) }}"
                                    class="
                                        block
                                        rounded-lg
                                        bg-blue-50
                                        border
                                        border-blue-100
                                        p-2
                                        hover:bg-blue-100
                                        transition
                                    "
                                >

                                    <p class="text-xs font-semibold text-blue-800 truncate">
                                        {{ $task->title }}
                                    </p>

                                    <p class="text-[11px] text-blue-600 mt-1">
                                        {{ $task->due_date->format('h:i A') }}
                                    </p>

                                    <p class="text-[11px] text-slate-500 truncate">
                                        {{ $task->type }}
                                    </p>

                                </a>

                            @endforeach

                        </div>

                    </div>

                    @php
                        $calendarDate->addDay();
                    @endphp

                @endwhile

            </div>

        </div>

        {{-- Legend --}}
        <div class="mt-6 flex flex-wrap gap-4 text-sm text-slate-600">

            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full bg-blue-600"></span>
                Today
            </div>

            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded bg-blue-50 border border-blue-100"></span>
                Task / Deadline
            </div>

        </div>

    </div>

</div>

@endsection