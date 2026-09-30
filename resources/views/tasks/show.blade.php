@extends('layouts.app')

@section('title', $task->title)

@section('page-heading', 'Task Details')

@section('content')

<div class="p-6 lg:p-8">

    <div class="max-w-4xl mx-auto">

        <!-- Back -->
        <a href="{{ route('tasks.index') }}"
           class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-blue-600">

            <i data-lucide="arrow-left" class="w-4 h-4"></i>

            Back to Tasks

        </a>


        <!-- Task Card -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm mt-6 overflow-hidden">

            <!-- Header -->
            <div class="p-8 border-b border-slate-200">

                <div class="flex items-start justify-between gap-6">

                    <div>

                        <div class="flex items-center gap-3 mb-3">

                            <span class="text-xs bg-blue-100 text-blue-700 px-3 py-1 rounded-full">
                                {{ $task->type }}
                            </span>

                            @if($task->status === 'Pending')

                                <span class="text-xs bg-orange-100 text-orange-700 px-3 py-1 rounded-full">
                                    Pending
                                </span>

                            @else

                                <span class="text-xs bg-green-100 text-green-700 px-3 py-1 rounded-full">
                                    Completed
                                </span>

                            @endif

                        </div>

                        <h1 class="text-3xl font-bold text-slate-900">
                            {{ $task->title }}
                        </h1>

                        <p class="text-slate-500 mt-2">
                            {{ $task->subject }}
                        </p>

                    </div>

                </div>

            </div>


            <!-- Details -->
            <div class="p-8">

                <div class="grid md:grid-cols-2 gap-6 mb-8">

                    <div class="bg-slate-50 rounded-xl p-5">

                        <p class="text-sm text-slate-500">
                            Due Date
                        </p>

                        <p class="font-semibold text-slate-900 mt-1">
                            {{ $task->due_date->format('F d, Y') }}
                        </p>

                        <p class="text-sm text-slate-500 mt-1">
                            {{ $task->due_date->format('h:i A') }}
                        </p>

                    </div>


                    @if(auth()->user()->isStudent())

                        @php
                            $studentAssignment = $task->students
                                ->firstWhere('id', auth()->id());
                        @endphp

                        <div class="bg-slate-50 rounded-xl p-5">

                            <p class="text-sm text-slate-500">
                                Your Status
                            </p>

                            @if($studentAssignment)

                                @if($studentAssignment->pivot->status === 'Pending')

                                    <p class="font-semibold text-orange-600 mt-1">
                                        Pending
                                    </p>

                                @else

                                    <p class="font-semibold text-green-600 mt-1">
                                        Completed
                                    </p>

                                    @if($studentAssignment->pivot->completed_at)

                                        <p class="text-sm text-slate-500 mt-1">
                                            Completed
                                            {{ $studentAssignment->pivot->completed_at->format('M d, Y h:i A') }}
                                        </p>

                                    @endif

                                @endif

                            @endif

                        </div>

                    @else

                        <div class="bg-slate-50 rounded-xl p-5">

                            <p class="text-sm text-slate-500">
                                Task Status
                            </p>

                            <p class="font-semibold text-slate-900 mt-1">
                                {{ $task->status }}
                            </p>

                        </div>

                    @endif

                </div>


                <!-- Description -->
                <div>

                    <h2 class="text-lg font-semibold text-slate-900 mb-3">
                        Description
                    </h2>

                    <div class="text-slate-600 leading-relaxed">

                        @if($task->description)

                            {!! nl2br(e($task->description)) !!}

                        @else

                            <p class="text-slate-400">
                                No description provided.
                            </p>

                        @endif

                    </div>

                </div>


                <!-- Student Progress -->
                @if(!auth()->user()->isStudent())

                    <div class="mt-10">

                        <h2 class="text-lg font-semibold text-slate-900 mb-4">
                            Student Progress
                        </h2>

                        <div class="border border-slate-200 rounded-xl overflow-hidden">

                            <div class="grid grid-cols-3 bg-slate-50 px-5 py-3 text-sm font-medium text-slate-600">

                                <div>
                                    Student
                                </div>

                                <div>
                                    Status
                                </div>

                                <div>
                                    Completed
                                </div>

                            </div>


                            @forelse($task->students as $student)

                                <div class="grid grid-cols-3 px-5 py-4 border-t border-slate-100">

                                    <div class="font-medium text-slate-900">
                                        {{ $student->name }}
                                    </div>


                                    <div>

                                        @if($student->pivot->status === 'Completed')

                                            <span class="text-xs bg-green-100 text-green-700 px-3 py-1 rounded-full">
                                                Completed
                                            </span>

                                        @else

                                            <span class="text-xs bg-orange-100 text-orange-700 px-3 py-1 rounded-full">
                                                Pending
                                            </span>

                                        @endif

                                    </div>


                                    <div class="text-sm text-slate-500">

                                        @if($student->pivot->completed_at)

                                            {{ $student->pivot->completed_at->format('M d, Y h:i A') }}

                                        @else

                                            —

                                        @endif

                                    </div>

                                </div>

                            @empty

                                <div class="px-5 py-8 text-center text-slate-500">
                                    No students assigned to this task.
                                </div>

                            @endforelse

                        </div>

                    </div>

                @endif

            </div>


            <!-- Actions -->
            <div class="px-8 py-5 bg-slate-50 border-t border-slate-200 flex flex-wrap gap-3">

                @if(auth()->user()->isStudent())

                    @php
                        $studentAssignment = $task->students
                            ->firstWhere('id', auth()->id());
                    @endphp

                    @if($studentAssignment && $studentAssignment->pivot->status === 'Pending')

                        <form action="{{ route('tasks.complete', $task) }}"
                              method="POST">

                            @csrf

                            @method('PATCH')

                            <button
                                type="submit"
                                class="bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-xl font-medium">

                                <span class="inline-flex items-center gap-2">

                                    <i data-lucide="check" class="w-4 h-4"></i>

                                    Mark Completed

                                </span>

                            </button>

                        </form>

                    @endif

                @else

                    @if($task->status === 'Pending')

                        <form action="{{ route('tasks.complete', $task) }}"
                              method="POST">

                            @csrf

                            @method('PATCH')

                            <button
                                type="submit"
                                class="bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-xl font-medium">

                                <span class="inline-flex items-center gap-2">

                                    <i data-lucide="check" class="w-4 h-4"></i>

                                    Mark Completed

                                </span>

                            </button>

                        </form>

                    @endif


                    <a href="{{ route('tasks.edit', $task) }}"
                       class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl font-medium">

                        <span class="inline-flex items-center gap-2">

                            <i data-lucide="pencil" class="w-4 h-4"></i>

                            Edit Task

                        </span>

                    </a>


                    <form action="{{ route('tasks.destroy', $task) }}"
                          method="POST"
                          onsubmit="return confirm('Are you sure you want to delete this task?');">

                        @csrf

                        @method('DELETE')

                        <button
                            type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white px-5 py-3 rounded-xl font-medium">

                            <span class="inline-flex items-center gap-2">

                                <i data-lucide="trash-2" class="w-4 h-4"></i>

                                Delete

                            </span>

                        </button>

                    </form>

                @endif

            </div>

        </div>

    </div>

</div>

@endsection