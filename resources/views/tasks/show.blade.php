@extends('layouts.app')

@section('title', $task->title)

@section('page-heading', 'Task Details')

@section('content')

<div class="p-6 lg:p-8">

    <div class="max-w-4xl mx-auto">

        <!-- Back -->
        <span class="inline-flex items-center gap-2">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Back to Tasks
        </span>


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


                    <div class="bg-slate-50 rounded-xl p-5">

                        <p class="text-sm text-slate-500">
                            Status
                        </p>

                        <p class="font-semibold text-slate-900 mt-1">
                            {{ $task->status }}
                        </p>

                    </div>

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

            </div>


            <!-- Actions -->
            <div class="px-8 py-5 bg-slate-50 border-t border-slate-200 flex flex-wrap gap-3">

                @if($task->status === 'Pending')

                    <form action="{{ route('tasks.complete', $task) }}"
                          method="POST">

                        @csrf

                        @method('PATCH')

                        <button class="inline-flex items-center gap-2">
                            <i data-lucide="check" class="w-4 h-4"></i>
                            Mark Completed
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

            </div>

        </div>

    </div>

</div>

@endsection