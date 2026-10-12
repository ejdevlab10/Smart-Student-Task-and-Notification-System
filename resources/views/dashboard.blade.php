@extends('layouts.app')

@section('title', 'Dashboard')

@section('page-heading', 'Dashboard')

@section('content')

<div class="min-h-screen bg-slate-100 p-6 lg:p-8">

    <div class="max-w-7xl mx-auto">


        {{-- ========================================================= --}}
        {{-- STUDENT DASHBOARD                                         --}}
        {{-- ========================================================= --}}

        @if(auth()->user()->isStudent())

            {{-- Welcome --}}
            <div class="mb-8">

                <p class="text-sm font-medium text-blue-600 mb-1">
                    Student Dashboard
                </p>

                <h1 class="text-3xl font-bold text-slate-900">
                    Welcome back, {{ auth()->user()->name }}! 👋
                </h1>

                <p class="mt-2 text-slate-500">
                    Here's what you need to stay on top of your academic tasks.
                </p>

            </div>


            {{-- Student Statistics --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-4 mb-8">

                <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
                    <p class="text-sm text-slate-500">Total Tasks</p>

                    <p class="mt-2 text-3xl font-bold text-slate-900">
                        {{ $totalTasks }}
                    </p>
                </div>


                <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
                    <p class="text-sm text-slate-500">Pending</p>

                    <p class="mt-2 text-3xl font-bold text-orange-600">
                        {{ $pendingTasks }}
                    </p>
                </div>


                <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
                    <p class="text-sm text-slate-500">Due Soon</p>

                    <p class="mt-2 text-3xl font-bold text-red-600">
                        {{ $dueSoon }}
                    </p>
                </div>


                <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
                    <p class="text-sm text-slate-500">Completed</p>

                    <p class="mt-2 text-3xl font-bold text-green-600">
                        {{ $completedTasks }}
                    </p>
                </div>


                <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
                    <p class="text-sm text-slate-500">Notifications</p>

                    <p class="mt-2 text-3xl font-bold text-blue-600">
                        {{ $unreadNotifications }}
                    </p>
                </div>

            </div>


            {{-- Student Main Content --}}
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">


                {{-- Upcoming Tasks --}}
                <section class="xl:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm">

                    <div class="p-6 border-b border-slate-200 flex items-center justify-between">

                        <div>

                            <h2 class="font-semibold text-slate-900">
                                Upcoming Deadlines
                            </h2>

                            <p class="text-sm text-slate-500 mt-1">
                                Your next tasks that need attention
                            </p>

                        </div>

                        <a
                            href="{{ route('tasks.index') }}"
                            class="text-sm text-blue-600 hover:text-blue-700 font-medium"
                        >
                            View all
                        </a>

                    </div>


                    <div class="divide-y divide-slate-100">

                        @forelse($upcomingTasks as $task)

                            <a
                                href="{{ route('tasks.show', $task) }}"
                                class="block p-5 hover:bg-slate-50 transition"
                            >

                                <div class="flex items-center justify-between gap-4">

                                    <div>

                                        <h3 class="font-medium text-slate-900">
                                            {{ $task->title }}
                                        </h3>

                                        <p class="text-sm text-slate-500 mt-1">
                                            {{ $task->subject }} · {{ $task->type }}
                                        </p>

                                    </div>


                                    <div class="text-right">

                                        <p class="text-sm font-semibold text-orange-600">
                                            {{ $task->due_date->diffForHumans() }}
                                        </p>

                                        <p class="text-xs text-slate-400 mt-1">
                                            {{ $task->due_date->format('M d, Y h:i A') }}
                                        </p>

                                    </div>

                                </div>

                            </a>

                        @empty

                            <div class="p-10 text-center">

                                <div class="text-4xl mb-3">
                                    🎉
                                </div>

                                <p class="font-medium text-slate-700">
                                    You're all caught up!
                                </p>

                                <p class="text-sm text-slate-500 mt-1">
                                    You have no upcoming pending tasks.
                                </p>

                            </div>

                        @endforelse

                    </div>

                </section>


                {{-- Progress --}}
                <section class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">

                    <h2 class="font-semibold text-slate-900">
                        Academic Progress
                    </h2>

                    <p class="text-sm text-slate-500 mt-1">
                        Your completed tasks
                    </p>


                    <div class="flex items-center justify-center py-8">

                        <div class="text-center">

                            <p class="text-5xl font-bold text-blue-600">
                                {{ $progress }}%
                            </p>

                            <p class="text-sm text-slate-500 mt-2">
                                Overall completion
                            </p>

                        </div>

                    </div>


                    <div class="w-full bg-slate-100 rounded-full h-3">

                        <div
                            class="bg-blue-600 h-3 rounded-full transition-all"
                            style="width: {{ $progress }}%"
                        ></div>

                    </div>


                    <div class="flex justify-between text-xs text-slate-500 mt-3">

                        <span>
                            {{ $completedTasks }} completed
                        </span>

                        <span>
                            {{ $totalTasks }} total
                        </span>

                    </div>


                    <a
                        href="{{ route('tasks.index') }}"
                        class="block text-center mt-6 px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700"
                    >
                        View My Tasks
                    </a>

                </section>

            </div>


            {{-- Student Lower Section --}}
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 mt-6">


                {{-- Recent Completed --}}
                <section class="bg-white rounded-2xl border border-slate-200 shadow-sm">

                    <div class="p-6 border-b border-slate-200">

                        <h2 class="font-semibold text-slate-900">
                            Recently Completed
                        </h2>

                        <p class="text-sm text-slate-500 mt-1">
                            Your latest completed tasks
                        </p>

                    </div>


                    <div class="divide-y divide-slate-100">

                        @forelse($recentCompletedTasks as $task)

                            <div class="p-5 flex items-center justify-between">

                                <div>

                                    <p class="font-medium text-slate-900">
                                        {{ $task->title }}
                                    </p>

                                    <p class="text-sm text-slate-500">
                                        {{ $task->subject }}
                                    </p>

                                </div>


                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                    Completed
                                </span>

                            </div>

                        @empty

                            <div class="p-8 text-center text-sm text-slate-500">
                                No completed tasks yet.
                            </div>

                        @endforelse

                    </div>

                </section>


                {{-- Announcements --}}
                <section class="bg-white rounded-2xl border border-slate-200 shadow-sm">

                    <div class="p-6 border-b border-slate-200 flex justify-between">

                        <div>

                            <h2 class="font-semibold text-slate-900">
                                Latest Announcements
                            </h2>

                            <p class="text-sm text-slate-500 mt-1">
                                Updates from your school
                            </p>

                        </div>

                        <a
                            href="{{ route('announcements.index') }}"
                            class="text-sm text-blue-600"
                        >
                            View all
                        </a>

                    </div>


                    <div class="divide-y divide-slate-100">

                        @forelse($announcements as $announcement)

                            <a
                                href="{{ route('announcements.show', $announcement) }}"
                                class="block p-5 hover:bg-slate-50"
                            >

                                <p class="font-medium text-slate-900">
                                    {{ $announcement->title }}
                                </p>

                                <p class="text-sm text-slate-500 mt-1">
                                    {{ Str::limit($announcement->content, 90) }}
                                </p>

                                <p class="text-xs text-slate-400 mt-2">
                                    {{ $announcement->created_at->diffForHumans() }}
                                </p>

                            </a>

                        @empty

                            <div class="p-8 text-center text-sm text-slate-500">
                                No announcements yet.
                            </div>

                        @endforelse

                    </div>

                </section>

            </div>


        {{-- ========================================================= --}}
        {{-- TEACHER DASHBOARD                                         --}}
        {{-- ========================================================= --}}

        @elseif(auth()->user()->isTeacher())

            {{-- Welcome --}}
            <div class="mb-8">

                <p class="text-sm font-medium text-blue-600 mb-1">
                    Teacher Dashboard
                </p>

                <h1 class="text-3xl font-bold text-slate-900">
                    Welcome back, {{ auth()->user()->name }}! 👋
                </h1>

                <p class="mt-2 text-slate-500">
                    Manage your assignments and monitor your students' progress.
                </p>

            </div>


            {{-- Teacher Statistics --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">


                {{-- Tasks Created --}}
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">

                    <p class="text-sm text-slate-500">
                        Tasks Created
                    </p>

                    <p class="mt-2 text-3xl font-bold text-slate-900">
                        {{ $totalTasks }}
                    </p>

                    <p class="text-xs text-slate-400 mt-2">
                        Assignments you've created
                    </p>

                </div>


                {{-- Active Assignments --}}
                    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">

                        <p class="text-sm text-slate-500">
                            Active Assignments
                        </p>

                        <p class="mt-2 text-3xl font-bold text-green-600">
                            {{ $activeAssignments }}
                        </p>

                        <p class="text-xs text-slate-400 mt-2">
                            Tasks with pending student work
                        </p>

                    </div>


                {{-- Students Assigned --}}
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">

                    <p class="text-sm text-slate-500">
                        Students Assigned
                    </p>

                    <p class="mt-2 text-3xl font-bold text-blue-600">
                        {{ $studentsAssigned }}
                    </p>

                    <p class="text-xs text-slate-400 mt-2">
                        Students receiving your tasks
                    </p>

                </div>


                {{-- Due Soon --}}
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">

                    <p class="text-sm text-slate-500">
                        Due Soon
                    </p>

                    <p class="mt-2 text-3xl font-bold text-orange-600">
                        {{ $dueSoon }}
                    </p>

                    <p class="text-xs text-slate-400 mt-2">
                        Tasks due within 7 days
                    </p>

                </div>

            </div>


            {{-- Teacher Main Content --}}
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">


                {{-- Upcoming Assignments --}}
                <section class="xl:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm">

                    <div class="p-6 border-b border-slate-200 flex justify-between">

                        <div>

                            <h2 class="font-semibold text-slate-900">
                                Upcoming Assignments
                            </h2>

                            <p class="text-sm text-slate-500 mt-1">
                                Assignments you've created that are coming up
                            </p>

                        </div>


                        <a
                            href="{{ route('tasks.create') }}"
                            class="px-3 py-2 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700"
                        >
                            + Create Task
                        </a>

                    </div>


                    <div class="divide-y divide-slate-100">

                        @forelse($upcomingTasks as $task)

                            <a
                                href="{{ route('tasks.show', $task) }}"
                                class="block p-5 hover:bg-slate-50 transition"
                            >

                                <div class="flex justify-between gap-4">

                                    <div>

                                        <h3 class="font-medium text-slate-900">
                                            {{ $task->title }}
                                        </h3>

                                        <p class="text-sm text-slate-500 mt-1">
                                            {{ $task->subject }} · {{ $task->type }}
                                        </p>

                                    </div>


                                    <div class="text-right">

                                        <p class="text-sm font-semibold text-orange-600">
                                            {{ $task->due_date->diffForHumans() }}
                                        </p>

                                        <p class="text-xs text-slate-400 mt-1">
                                            {{ $task->due_date->format('M d, Y h:i A') }}
                                        </p>

                                    </div>

                                </div>

                            </a>

                        @empty

                            <div class="p-10 text-center">

                                <div class="text-4xl mb-3">
                                    🎉
                                </div>

                                <p class="font-medium text-slate-700">
                                    No upcoming assignments
                                </p>

                                <p class="text-sm text-slate-500 mt-1">
                                    You currently have no upcoming deadlines.
                                </p>

                            </div>

                        @endforelse

                    </div>

                </section>


                {{-- Quick Actions --}}
                <section class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">

                    <h2 class="font-semibold text-slate-900">
                        Quick Actions
                    </h2>

                    <p class="text-sm text-slate-500 mt-1">
                        Common teacher actions
                    </p>


                    <div class="space-y-3 mt-5">

                        <a
                            href="{{ route('tasks.create') }}"
                            class="block p-4 rounded-xl bg-blue-50 hover:bg-blue-100 transition"
                        >

                            <p class="font-medium text-blue-800">
                                ➕ Create Task
                            </p>

                            <p class="text-xs text-blue-600 mt-1">
                                Assign work to students
                            </p>

                        </a>


                        <a
                            href="{{ route('tasks.index') }}"
                            class="block p-4 rounded-xl bg-slate-50 hover:bg-slate-100 transition"
                        >

                            <p class="font-medium text-slate-800">
                                📚 Manage Tasks
                            </p>

                            <p class="text-xs text-slate-500 mt-1">
                                View and manage your assignments
                            </p>

                        </a>


                        <a
                            href="{{ route('calendar.index') }}"
                            class="block p-4 rounded-xl bg-slate-50 hover:bg-slate-100 transition"
                        >

                            <p class="font-medium text-slate-800">
                                📅 Calendar
                            </p>

                            <p class="text-xs text-slate-500 mt-1">
                                View your academic deadlines
                            </p>

                        </a>

                    </div>

                </section>

            </div>


            {{-- Student Completion --}}
            <section class="mt-6 bg-white rounded-2xl border border-slate-200 shadow-sm">

                <div class="p-6 border-b border-slate-200">

                    <h2 class="font-semibold text-slate-900">
                        Student Task Progress
                    </h2>

                    <p class="text-sm text-slate-500 mt-1">
                        Monitor how students are progressing through your assignments.
                    </p>

                </div>


                <div class="divide-y divide-slate-100">

                    @forelse($completionStats as $stat)

                        <div class="p-5">

                            <div class="flex justify-between mb-2">

                                <div>

                                    <p class="font-medium text-slate-900">
                                        {{ $stat['task']->title }}
                                    </p>

                                    <p class="text-xs text-slate-500">
                                        {{ $stat['completed'] }}
                                        of
                                        {{ $stat['total'] }}
                                        students completed
                                    </p>

                                </div>


                                <span class="text-sm font-semibold text-blue-600">
                                    {{ $stat['progress'] }}%
                                </span>

                            </div>


                            <div class="w-full bg-slate-100 rounded-full h-2">

                                <div
                                    class="bg-blue-600 h-2 rounded-full transition-all"
                                    style="width: {{ $stat['progress'] }}%"
                                ></div>

                            </div>

                        </div>

                    @empty

                        <div class="p-8 text-center">

                            <p class="font-medium text-slate-700">
                                No student progress available yet.
                            </p>

                            <p class="text-sm text-slate-500 mt-1">
                                Student completion data will appear here once tasks are assigned.
                            </p>

                        </div>

                    @endforelse

                </div>

            </section>


            {{-- Teacher Announcements --}}
            <section class="mt-6 bg-white rounded-2xl border border-slate-200 shadow-sm">

                <div class="p-6 border-b border-slate-200 flex justify-between">

                    <div>

                        <h2 class="font-semibold text-slate-900">
                            Latest Announcements
                        </h2>

                        <p class="text-sm text-slate-500 mt-1">
                            Recent school announcements
                        </p>

                    </div>


                    <a
                        href="{{ route('announcements.index') }}"
                        class="text-sm text-blue-600 hover:text-blue-700"
                    >
                        View all
                    </a>

                </div>


                <div class="divide-y divide-slate-100">

                    @forelse($announcements as $announcement)

                        <a
                            href="{{ route('announcements.show', $announcement) }}"
                            class="block p-5 hover:bg-slate-50 transition"
                        >

                            <p class="font-medium text-slate-900">
                                {{ $announcement->title }}
                            </p>

                            <p class="text-sm text-slate-500 mt-1">
                                {{ Str::limit($announcement->content, 100) }}
                            </p>

                            <p class="text-xs text-slate-400 mt-2">
                                {{ $announcement->created_at->diffForHumans() }}
                            </p>

                        </a>

                    @empty

                        <div class="p-8 text-center text-sm text-slate-500">
                            No announcements yet.
                        </div>

                    @endforelse

                </div>

            </section>


        {{-- ========================================================= --}}
        {{-- ADMIN DASHBOARD                                           --}}
        {{-- ========================================================= --}}

        @else

            <div class="mb-8">

                <p class="text-sm font-medium text-purple-600 mb-1">
                    Administration Dashboard
                </p>

                <h1 class="text-3xl font-bold text-slate-900">
                    System Overview
                </h1>

                <p class="mt-2 text-slate-500">
                    Monitor users, tasks and academic activity across the system.
                </p>

            </div>


            {{-- Admin Statistics --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">

                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">

                    <p class="text-sm text-slate-500">
                        Students
                    </p>

                    <p class="mt-2 text-3xl font-bold text-blue-600">
                        {{ $totalStudents }}
                    </p>

                </div>


                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">

                    <p class="text-sm text-slate-500">
                        Teachers
                    </p>

                    <p class="mt-2 text-3xl font-bold text-purple-600">
                        {{ $totalTeachers }}
                    </p>

                </div>


                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">

                    <p class="text-sm text-slate-500">
                        Total Tasks
                    </p>

                    <p class="mt-2 text-3xl font-bold text-slate-900">
                        {{ $totalTasks }}
                    </p>

                </div>


                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">

                    <p class="text-sm text-slate-500">
                        Announcements
                    </p>

                    <p class="mt-2 text-3xl font-bold text-orange-600">
                        {{ $totalAnnouncements }}
                    </p>

                </div>

            </div>


            {{-- Admin Task Overview --}}
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">


                <section class="xl:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm">

                    <div class="p-6 border-b border-slate-200">

                        <h2 class="font-semibold text-slate-900">
                            Task Activity
                        </h2>

                        <p class="text-sm text-slate-500 mt-1">
                            Current system-wide task status
                        </p>

                    </div>


                    <div class="p-6 grid grid-cols-2 gap-4">

                        <div class="p-5 rounded-xl bg-orange-50">

                            <p class="text-sm text-orange-700">
                                Pending Tasks
                            </p>

                            <p class="text-3xl font-bold text-orange-700 mt-2">
                                {{ $pendingTasks }}
                            </p>

                        </div>


                        <div class="p-5 rounded-xl bg-green-50">

                            <p class="text-sm text-green-700">
                                Completed Tasks
                            </p>

                            <p class="text-3xl font-bold text-green-700 mt-2">
                                {{ $completedTasks }}
                            </p>

                        </div>

                    </div>


                    <div class="px-6 pb-6">

                        <div class="flex justify-between text-sm mb-2">

                            <span class="text-slate-500">
                                Overall completion
                            </span>

                            <span class="font-semibold text-slate-700">
                                {{ $systemProgress }}%
                            </span>

                        </div>


                        <div class="w-full bg-slate-100 rounded-full h-3">

                            <div
                                class="bg-purple-600 h-3 rounded-full"
                                style="width: {{ $systemProgress }}%"
                            ></div>

                        </div>

                    </div>

                </section>


                {{-- Admin Quick Actions --}}
                <section class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">

                    <h2 class="font-semibold text-slate-900">
                        Quick Actions
                    </h2>


                    <div class="space-y-3 mt-5">

                        <a
                            href="{{ route('tasks.index') }}"
                            class="block p-4 rounded-xl bg-slate-50 hover:bg-slate-100 transition"
                        >
                            📚 Manage Tasks
                        </a>


                        <a
                            href="{{ route('announcements.index') }}"
                            class="block p-4 rounded-xl bg-slate-50 hover:bg-slate-100 transition"
                        >
                            📢 Manage Announcements
                        </a>


                        <a
                            href="{{ route('calendar.index') }}"
                            class="block p-4 rounded-xl bg-slate-50 hover:bg-slate-100 transition"
                        >
                            📅 Open Calendar
                        </a>

                    </div>

                </section>

            </div>


            {{-- Recent Tasks --}}
            <section class="mt-6 bg-white rounded-2xl border border-slate-200 shadow-sm">

                <div class="p-6 border-b border-slate-200">

                    <h2 class="font-semibold text-slate-900">
                        Recent Tasks
                    </h2>

                    <p class="text-sm text-slate-500 mt-1">
                        Latest tasks created in the system.
                    </p>

                </div>


                <div class="divide-y divide-slate-100">

                    @forelse($recentTasks as $task)

                        <a
                            href="{{ route('tasks.show', $task) }}"
                            class="block p-5 hover:bg-slate-50 transition"
                        >

                            <div class="flex justify-between">

                                <div>

                                    <p class="font-medium text-slate-900">
                                        {{ $task->title }}
                                    </p>

                                    <p class="text-sm text-slate-500 mt-1">
                                        {{ $task->subject }} · {{ $task->type }}
                                    </p>

                                </div>


                                <div class="text-right">

                                    <p class="text-xs text-slate-400">
                                        {{ $task->created_at->diffForHumans() }}
                                    </p>

                                    <p class="text-sm font-medium mt-1">
                                        {{ $task->status }}
                                    </p>

                                </div>

                            </div>

                        </a>

                    @empty

                        <div class="p-8 text-center text-slate-500">
                            No tasks available.
                        </div>

                    @endforelse

                </div>

            </section>


            {{-- Admin Announcements --}}
            <section class="mt-6 bg-white rounded-2xl border border-slate-200 shadow-sm">

                <div class="p-6 border-b border-slate-200 flex justify-between">

                    <div>

                        <h2 class="font-semibold text-slate-900">
                            Recent Announcements
                        </h2>

                        <p class="text-sm text-slate-500 mt-1">
                            Latest announcements published in the system.
                        </p>

                    </div>


                    <a
                        href="{{ route('announcements.index') }}"
                        class="text-sm text-blue-600 hover:text-blue-700"
                    >
                        View all
                    </a>

                </div>


                <div class="divide-y divide-slate-100">

                    @forelse($announcements as $announcement)

                        <a
                            href="{{ route('announcements.show', $announcement) }}"
                            class="block p-5 hover:bg-slate-50 transition"
                        >

                            <p class="font-medium text-slate-900">
                                {{ $announcement->title }}
                            </p>

                            <p class="text-sm text-slate-500 mt-1">
                                {{ Str::limit($announcement->content, 100) }}
                            </p>

                        </a>

                    @empty

                        <div class="p-8 text-center text-slate-500">
                            No announcements yet.
                        </div>

                    @endforelse

                </div>

            </section>

        @endif

    </div>

</div>

@endsection