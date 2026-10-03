@extends('layouts.app')

@section('title', $task->title)
@section('page-heading', 'Task Details')

@section('content')

<div class="min-h-screen bg-slate-100 p-8">

    <div class="max-w-5xl mx-auto">

        {{-- Success Message --}}
        @if(session('success'))
            <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-5 py-4 rounded-xl">
                {{ session('success') }}
            </div>
        @endif


        {{-- Back Button --}}
        <div class="mb-6">
            <a href="{{ route('tasks.index') }}"
               class="inline-flex items-center gap-2 text-sm text-slate-600 hover:text-blue-600">

                <i data-lucide="arrow-left" class="w-4 h-4"></i>

                Back to Tasks

            </a>
        </div>


        {{-- Task Card --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

            {{-- Header --}}
            <div class="p-8 border-b border-slate-200">

                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">

                    <div>

                        {{-- Task Type --}}
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700 mb-3">
                            {{ $task->type }}
                        </span>

                        {{-- Title --}}
                        <h1 class="text-3xl font-bold text-slate-900">
                            {{ $task->title }}
                        </h1>

                        {{-- Subject --}}
                        <p class="text-slate-500 mt-2">
                            {{ $task->subject }}
                        </p>

                    </div>


                    

                </div>

            </div>


            {{-- Task Information --}}
            <div class="p-8">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


                    {{-- Due Date --}}
                    <div class="bg-slate-50 rounded-xl p-5">

                        <div class="flex items-center gap-3">

                            <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">

                                <i data-lucide="calendar" class="w-5 h-5 text-blue-600"></i>

                            </div>

                            <div>

                                <p class="text-xs text-slate-500 uppercase tracking-wide">
                                    Due Date
                                </p>

                                <p class="font-semibold text-slate-800">
                                    {{ $task->due_date->format('M d, Y h:i A') }}
                                </p>

                            </div>

                        </div>

                    </div>


                    {{-- Your Status / Task Status --}}
                    @if(auth()->user()->isStudent())

                        @php
                            $studentAssignment = $task->students->firstWhere('id', auth()->id());
                        @endphp

                        <div class="bg-slate-50 rounded-xl p-5">

                            <div class="flex items-center gap-3">

                                <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">

                                    <i data-lucide="check-circle" class="w-5 h-5 text-green-600"></i>

                                </div>

                                <div>

                                    <p class="text-xs text-slate-500 uppercase tracking-wide">
                                        Your Status
                                    </p>

                                    @if($studentAssignment && $studentAssignment->pivot->status === 'Completed')

                                        <p class="font-semibold text-green-600">
                                            Completed
                                        </p>

                                        @if($studentAssignment->pivot->completed_at)

                                            <p class="text-sm text-slate-500 mt-1">
                                                Completed
                                                {{ $studentAssignment->pivot->completed_at->format('M d, Y h:i A') }}
                                            </p>

                                        @endif

                                    @else

                                        <p class="font-semibold text-orange-600">
                                            Pending
                                        </p>

                                    @endif

                                </div>

                            </div>

                        </div>

                    @else

                        <div class="bg-slate-50 rounded-xl p-5">

                            <div class="flex items-center gap-3">

                                <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center">

                                    <i data-lucide="check-circle" class="w-5 h-5 text-green-600"></i>

                                </div>

                                <div>

                                    <p class="text-xs text-slate-500 uppercase tracking-wide">
                                        Task Status
                                    </p>

                                    <p class="font-semibold text-slate-800">
                                        {{ $task->status }}
                                    </p>

                                </div>

                            </div>

                        </div>

                    @endif

                </div>


                {{-- Description --}}
                <div class="mt-8">

                    <h2 class="text-lg font-semibold text-slate-900 mb-3">
                        Description
                    </h2>

                    <div class="bg-slate-50 rounded-xl p-5 text-slate-700 leading-relaxed">

                        @if($task->description)

                            {{ $task->description }}

                        @else

                            <span class="text-slate-400">
                                No description provided.
                            </span>

                        @endif

                    </div>

                </div>


                {{-- Student Progress --}}
                @if(!auth()->user()->isStudent())

                    <div class="mt-8">

                        <h2 class="text-lg font-semibold text-slate-900 mb-3">
                            Student Progress
                        </h2>

                        @if($task->students->count())

                            <div class="border border-slate-200 rounded-xl overflow-hidden">

                                <div class="overflow-x-auto">

                                    <table class="w-full">

                                        <thead class="bg-slate-50">

                                            <tr>

                                                <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase">
                                                    Student
                                                </th>

                                                <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase">
                                                    Status
                                                </th>

                                                <th class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase">
                                                    Completed At
                                                </th>

                                            </tr>

                                        </thead>

                                        <tbody class="divide-y divide-slate-100">

                                            @foreach($task->students as $student)

                                                <tr>

                                                    <td class="px-5 py-4">

                                                        <p class="font-medium text-slate-800">
                                                            {{ $student->name }}
                                                        </p>

                                                        <p class="text-sm text-slate-500">
                                                            {{ $student->email }}
                                                        </p>

                                                    </td>


                                                    <td class="px-5 py-4">

                                                        @if($student->pivot->status === 'Completed')

                                                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                                                Completed
                                                            </span>

                                                        @else

                                                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-700">
                                                                Pending
                                                            </span>

                                                        @endif

                                                    </td>


                                                    <td class="px-5 py-4 text-sm text-slate-500">

                                                        @if($student->pivot->completed_at)

                                                            {{ $student->pivot->completed_at->format('M d, Y h:i A') }}

                                                        @else

                                                            —

                                                        @endif

                                                    </td>

                                                </tr>

                                            @endforeach

                                        </tbody>

                                    </table>

                                </div>

                            </div>

                        @else

                            <div class="bg-slate-50 rounded-xl p-5 text-slate-500">
                                No students are assigned to this task.
                            </div>

                        @endif

                    </div>

                @endif


                {{-- Actions --}}
                <div class="mt-8 pt-6 border-t border-slate-200">

                    <div class="flex flex-wrap items-center gap-3">


                        {{-- Student Action --}}
                        @if(auth()->user()->isStudent())

                            @if($studentAssignment && $studentAssignment->pivot->status === 'Pending')

                                <form action="{{ route('tasks.complete', $task) }}"
                                      method="POST">

                                    @csrf

                                    @method('PATCH')

                                    <button type="submit"
                                            class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-xl font-medium">

                                        <i data-lucide="check" class="w-4 h-4"></i>

                                        Mark Completed

                                    </button>

                                </form>

                            @else

                                <span class="inline-flex items-center gap-2 bg-green-100 text-green-700 px-5 py-3 rounded-xl font-medium">

                                    <i data-lucide="check-circle" class="w-4 h-4"></i>

                                    Completed

                                </span>

                            @endif

                        @endif


                        {{-- Teacher/Admin Actions --}}
                        @if(!auth()->user()->isStudent())

                            @can('update', $task)

                                <a href="{{ route('tasks.edit', $task) }}"
                                   class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl font-medium">

                                    <i data-lucide="edit" class="w-4 h-4"></i>

                                    Edit Task

                                </a>

                            @endcan


                            @can('delete', $task)

                                <form action="{{ route('tasks.destroy', $task) }}"
                                      method="POST"
                                      onsubmit="return confirm('Are you sure you want to delete this task?');">

                                    @csrf

                                    @method('DELETE')

                                    <button type="submit"
                                            class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-5 py-3 rounded-xl font-medium">

                                        <i data-lucide="trash-2" class="w-4 h-4"></i>

                                        Delete Task

                                    </button>

                                </form>

                            @endcan

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection