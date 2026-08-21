@extends('layouts.app')

@section('title', 'Create Task')

@section('page-heading', 'Create Academic Task')

@section('content')

<div class="min-h-screen bg-slate-100 p-8">

    <div class="max-w-3xl mx-auto">

        <!-- Header -->
        <div class="mb-8">

            <a href="{{ route('tasks.index') }}"
               class="text-sm text-blue-600 hover:text-blue-700">

                ← Back to Tasks

            </a>

            <h1 class="text-3xl font-bold text-slate-900 mt-4">
                Create Academic Task
            </h1>

            <p class="text-slate-500 mt-1">
                Add an assignment, project, quiz, or exam.
            </p>

        </div>


        <!-- Form -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8">

            <form action="{{ route('tasks.store') }}" method="POST">

                @csrf


                <!-- Title -->
                <div class="mb-6">

                    <label for="title"
                           class="block text-sm font-medium text-slate-700 mb-2">

                        Task Title

                    </label>

                    <input
                        type="text"
                        id="title"
                        name="title"
                        value="{{ old('title') }}"
                        placeholder="e.g. Database Activity"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >

                    @error('title')
                        <p class="text-sm text-red-600 mt-2">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                <!-- Subject -->
                <div class="mb-6">

                    <label for="subject"
                           class="block text-sm font-medium text-slate-700 mb-2">

                        Subject

                    </label>

                    <input
                        type="text"
                        id="subject"
                        name="subject"
                        value="{{ old('subject') }}"
                        placeholder="e.g. Database Management"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >

                    @error('subject')
                        <p class="text-sm text-red-600 mt-2">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                <!-- Type -->
                <div class="mb-6">

                    <label for="type"
                           class="block text-sm font-medium text-slate-700 mb-2">

                        Task Type

                    </label>

                    <select
                        id="type"
                        name="type"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >

                        <option value="">Select task type</option>

                        <option value="Assignment"
                            {{ old('type') == 'Assignment' ? 'selected' : '' }}>
                            Assignment
                        </option>

                        <option value="Project"
                            {{ old('type') == 'Project' ? 'selected' : '' }}>
                            Project
                        </option>

                        <option value="Quiz"
                            {{ old('type') == 'Quiz' ? 'selected' : '' }}>
                            Quiz
                        </option>

                        <option value="Exam"
                            {{ old('type') == 'Exam' ? 'selected' : '' }}>
                            Exam
                        </option>

                    </select>

                    @error('type')
                        <p class="text-sm text-red-600 mt-2">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                <!-- Description -->
                <div class="mb-6">

                    <label for="description"
                           class="block text-sm font-medium text-slate-700 mb-2">

                        Description

                    </label>

                    <textarea
                        id="description"
                        name="description"
                        rows="5"
                        placeholder="Describe the task or instructions..."
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >{{ old('description') }}</textarea>

                    @error('description')
                        <p class="text-sm text-red-600 mt-2">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                <!-- Due Date -->
                <div class="mb-8">

                    <label for="due_date"
                           class="block text-sm font-medium text-slate-700 mb-2">

                        Due Date

                    </label>

                    <input
                        type="datetime-local"
                        id="due_date"
                        name="due_date"
                        value="{{ old('due_date') }}"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >

                    @error('due_date')
                        <p class="text-sm text-red-600 mt-2">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                <!-- Buttons -->
                <div class="flex items-center justify-end gap-3">

                    <a href="{{ route('tasks.index') }}"
                       class="px-5 py-3 rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-50">

                        Cancel

                    </a>

                    <button
                        type="submit"
                        class="px-6 py-3 rounded-xl bg-blue-600 text-white font-medium hover:bg-blue-700"
                    >

                        Create Task

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection