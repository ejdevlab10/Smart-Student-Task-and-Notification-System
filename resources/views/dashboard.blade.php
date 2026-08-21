@extends('layouts.app')

@section('title', 'Dashboard')

@section('page-heading', 'Academic Overview')

@section('content')

<div class="min-h-screen bg-slate-100">


        <!-- Dashboard Content -->
        <div class="p-6 lg:p-8">

            <!-- Welcome -->
            <section class="mb-8">

                <h1 class="text-3xl font-bold text-slate-900">
                    Good afternoon, EJ! 👋
                </h1>

                <p class="mt-2 text-slate-500">
                    Here's what's happening with your academic tasks.
                </p>

            </section>


            <!-- Statistics -->
            <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">

                <!-- Pending -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">

                    <div class="flex items-center justify-between">

                        <div>
                            <p class="text-sm text-slate-500">
                                Pending Tasks
                            </p>

                            <p class="mt-2 text-3xl font-bold text-slate-900">
                                {{ $pendingTasks }}
                            </p>
                        </div>

                        <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center text-xl">
                            📚
                        </div>

                    </div>

                </div>


                <!-- Due Soon -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">

                    <div class="flex items-center justify-between">

                        <div>
                            <p class="text-sm text-slate-500">
                                Due Soon
                            </p>

                            <p class="mt-2 text-3xl font-bold text-slate-900">
                                {{ $dueSoon }}
                            </p>
                        </div>

                        <div class="w-12 h-12 rounded-xl bg-orange-100 flex items-center justify-center text-xl">
                            ⏰
                        </div>

                    </div>

                </div>


                <!-- Completed -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">

                    <div class="flex items-center justify-between">

                        <div>
                            <p class="text-sm text-slate-500">
                                Completed
                            </p>

                            <p class="mt-2 text-3xl font-bold text-slate-900">
                                {{ $completedTasks }}
                            </p>
                        </div>

                        <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center text-xl">
                            ✅
                        </div>

                    </div>

                </div>


                <!-- Progress -->
                <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">

                    <div class="flex items-center justify-between">

                        <div>
                            <p class="text-sm text-slate-500">
                                Overall Progress
                            </p>

                            <p class="mt-2 text-3xl font-bold text-slate-900">
                                {{ $progress }}%
                            </p>
                        </div>

                        <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center text-xl">
                            📊
                        </div>

                    </div>

                </div>

            </section>


            <!-- Main Grid -->
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">


                <!-- Upcoming Tasks -->
                <section class="xl:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm">

                    <div class="p-6 border-b border-slate-200 flex items-center justify-between">

                        <div>
                            <h3 class="font-semibold text-slate-900">
                                Upcoming Deadlines
                            </h3>

                            <p class="text-sm text-slate-500 mt-1">
                                Tasks that need your attention
                            </p>
                        </div>

                        <a href="{{ route('tasks.index') }}"
                        class="text-sm text-blue-600 hover:text-blue-700 font-medium">

                            View all

                        </a>

                    </div>


                    <div class="divide-y divide-slate-100">

                        @forelse($upcomingTasks as $task)

                            <div class="p-6 flex items-center justify-between gap-4">

                                <div class="flex items-center gap-4">

                                    <div class="w-11 h-11 rounded-xl bg-blue-100 flex items-center justify-center">
                                        📚
                                    </div>

                                    <div>

                                        <h4 class="font-medium text-slate-900">
                                            {{ $task->title }}
                                        </h4>

                                        <p class="text-sm text-slate-500">
                                            {{ $task->subject }}
                                        </p>

                                    </div>

                                </div>


                                <div class="text-right">

                                    <p class="text-sm font-semibold text-orange-600">

                                        {{ $task->due_date->diffForHumans() }}

                                    </p>

                                    <p class="text-xs text-slate-400">

                                        {{ $task->due_date->format('M d, Y') }}

                                    </p>

                                </div>

                            </div>

                        @empty

                            <div class="p-8 text-center">

                                <div class="text-3xl mb-3">
                                    📚
                                </div>

                                <p class="text-slate-500">
                                    No upcoming tasks.
                                </p>

                            </div>

                        @endforelse

                    </div>

                </section>


                <!-- Announcements -->
                <section class="bg-white rounded-2xl border border-slate-200 shadow-sm">

                    <div class="p-6 border-b border-slate-200">

                        <h3 class="font-semibold text-slate-900">
                            Recent Announcements
                        </h3>

                        <p class="text-sm text-slate-500 mt-1">
                            Latest updates from your teachers
                        </p>

                    </div>


                    <div class="p-6 space-y-5">

                        <!-- Announcement -->
                        <div>

                            <div class="flex gap-3">

                                <div class="w-10 h-10 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                                    📢
                                </div>

                                <div>

                                    <h4 class="text-sm font-semibold text-slate-900">
                                        Class Schedule Update
                                    </h4>

                                    <p class="text-sm text-slate-500 mt-1">
                                        The updated class schedule has been posted.
                                    </p>

                                    <p class="text-xs text-slate-400 mt-2">
                                        2 hours ago
                                    </p>

                                </div>

                            </div>

                        </div>


                        <!-- Announcement -->
                        <div>

                            <div class="flex gap-3">

                                <div class="w-10 h-10 rounded-xl bg-orange-100 flex items-center justify-center flex-shrink-0">
                                    📌
                                </div>

                                <div>

                                    <h4 class="text-sm font-semibold text-slate-900">
                                        Project Requirements
                                    </h4>

                                    <p class="text-sm text-slate-500 mt-1">
                                        New requirements have been added to the project.
                                    </p>

                                    <p class="text-xs text-slate-400 mt-2">
                                        Yesterday
                                    </p>

                                </div>

                            </div>

                        </div>


                        <!-- Announcement -->
                        <div>

                            <div class="flex gap-3">

                                <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center flex-shrink-0">
                                    🎓
                                </div>

                                <div>

                                    <h4 class="text-sm font-semibold text-slate-900">
                                        School Activity
                                    </h4>

                                    <p class="text-sm text-slate-500 mt-1">
                                        Don't forget about the upcoming school activity.
                                    </p>

                                    <p class="text-xs text-slate-400 mt-2">
                                        2 days ago
                                    </p>

                                </div>

                            </div>

                        </div>

                    </div>

                </section>

            </div>


            <!-- Quick Actions -->
            <section class="mt-6">

                <h3 class="font-semibold text-slate-900 mb-4">
                    Quick Actions
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

                    <a href="{{ route('tasks.index') }}"
                       class="bg-white border border-slate-200 rounded-2xl p-5 hover:border-blue-400 hover:shadow-sm transition">

                        <div class="text-2xl mb-3">
                            📚
                        </div>

                        <h4 class="font-semibold text-slate-900">
                            View My Tasks
                        </h4>

                        <p class="text-sm text-slate-500 mt-1">
                            Check assignments and projects
                        </p>

                    </a>


                    <a href="#"
                       class="bg-white border border-slate-200 rounded-2xl p-5 hover:border-blue-400 hover:shadow-sm transition">

                        <div class="text-2xl mb-3">
                            📅
                        </div>

                        <h4 class="font-semibold text-slate-900">
                            Open Calendar
                        </h4>

                        <p class="text-sm text-slate-500 mt-1">
                            View your academic schedule
                        </p>

                    </a>


                    <a href="#"
                       class="bg-white border border-slate-200 rounded-2xl p-5 hover:border-blue-400 hover:shadow-sm transition">

                        <div class="text-2xl mb-3">
                            🔔
                        </div>

                        <h4 class="font-semibold text-slate-900">
                            Notifications
                        </h4>

                        <p class="text-sm text-slate-500 mt-1">
                            View recent alerts and reminders
                        </p>

                    </a>

                </div>

            </section>

        </div>

    </main>

</div>

@endsection