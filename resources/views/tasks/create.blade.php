@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">

    {{-- Page Header --}}
    <div class="mb-8">
        <a href="{{ route('tasks.index') }}"
           class="text-sm text-blue-600 hover:text-blue-800">
            &larr; Back to Tasks
        </a>

        <h1 class="text-3xl font-bold text-slate-800 mt-3">
            Create New Task
        </h1>

        <p class="text-slate-500 mt-2">
            Create an assignment, project, quiz, or exam and assign it to students or an entire class.
        </p>
    </div>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4">
            <p class="font-semibold text-red-700 mb-2">
                Please correct the following errors:
            </p>

            <ul class="list-disc list-inside text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Create Task Form --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 md:p-8">

        <form action="{{ route('tasks.store') }}" method="POST">
            @csrf

            {{-- Task Title --}}
            <div class="mb-6">
                <label for="title"
                       class="block text-sm font-medium text-slate-700 mb-2">
                    Task Title <span class="text-red-500">*</span>
                </label>

                <input
                    type="text"
                    name="title"
                    id="title"
                    value="{{ old('title') }}"
                    required
                    maxlength="255"
                    placeholder="e.g. Chapter 5 Assignment"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >

                @error('title')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Subject --}}
            <div class="mb-6">
                <label for="subject"
                       class="block text-sm font-medium text-slate-700 mb-2">
                    Subject <span class="text-red-500">*</span>
                </label>

                <input
                    type="text"
                    name="subject"
                    id="subject"
                    value="{{ old('subject') }}"
                    required
                    maxlength="255"
                    placeholder="e.g. General Calculus"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >

                @error('subject')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Task Type --}}
            <div class="mb-6">
                <label for="type"
                       class="block text-sm font-medium text-slate-700 mb-2">
                    Task Type <span class="text-red-500">*</span>
                </label>

                <select
                    name="type"
                    id="type"
                    required
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                    <option value="">Select task type</option>

                    @foreach (['Assignment', 'Project', 'Quiz', 'Exam'] as $type)
                        <option
                            value="{{ $type }}"
                            {{ old('type') === $type ? 'selected' : '' }}
                        >
                            {{ $type }}
                        </option>
                    @endforeach
                </select>

                @error('type')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Description --}}
            <div class="mb-6">
                <label for="description"
                       class="block text-sm font-medium text-slate-700 mb-2">
                    Description
                </label>

                <textarea
                    name="description"
                    id="description"
                    rows="4"
                    placeholder="Enter task instructions or additional details..."
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >{{ old('description') }}</textarea>

                @error('description')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Due Date --}}
            <div class="mb-6">
                <label for="due_date"
                       class="block text-sm font-medium text-slate-700 mb-2">
                    Due Date and Time <span class="text-red-500">*</span>
                </label>

                <input
                    type="datetime-local"
                    name="due_date"
                    id="due_date"
                    value="{{ old('due_date') }}"
                    required
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >

                @error('due_date')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Class Selection --}}
            <div class="mb-6">
                <label for="class_id"
                       class="block text-sm font-medium text-slate-700 mb-2">
                    Assign to Class
                </label>

                <select
                    name="class_id"
                    id="class_id"
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >
                    <option value="">
                        No class selected — assign individually
                    </option>

                    @foreach ($classes as $class)
                        <option
                            value="{{ $class->id }}"
                            {{ (string) old('class_id') === (string) $class->id ? 'selected' : '' }}
                        >
                            {{ $class->name }}
                            @if ($class->section)
                                — {{ $class->section }}
                            @endif
                        </option>
                    @endforeach
                </select>

                @error('class_id')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                @enderror

                <p class="text-sm text-slate-500 mt-2">
                    Selecting a class assigns this task to all students enrolled in that class.
                </p>

                @if ($classes->isEmpty())
                    <p class="text-sm text-amber-600 mt-2">
                        You don't have any classes assigned to you yet.
                        You can still assign tasks to individual students.
                    </p>
                @endif
            </div>

            {{-- Individual Student Selection --}}
            <div class="mb-8">
                <label class="block text-sm font-medium text-slate-700 mb-2">
                    Assign to Individual Students
                </label>

                <p class="text-xs text-slate-500 mb-3">
                    Select individual students in addition to the class, if needed.
                    Students already included through the selected class will not be assigned twice.
                </p>

                <div class="max-h-64 overflow-y-auto rounded-xl border border-slate-200 divide-y divide-slate-100">

                    @forelse ($students as $student)
                        <label class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 cursor-pointer">

                            <input
                                type="checkbox"
                                name="students[]"
                                value="{{ $student->id }}"
                                class="rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                                {{ in_array(
                                    (string) $student->id,
                                    array_map('strval', old('students', [])),
                                    true
                                ) ? 'checked' : '' }}
                            >

                            <span class="text-sm text-slate-700">
                                {{ $student->name }}
                                <span class="text-slate-400">
                                    ({{ $student->email }})
                                </span>
                            </span>

                        </label>
                    @empty
                        <p class="p-4 text-sm text-slate-500">
                            No students are available.
                        </p>
                    @endforelse

                </div>

                @error('students')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                @enderror

                @error('students.*')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Form Actions --}}
            <div class="flex flex-col sm:flex-row gap-3 justify-end border-t border-slate-200 pt-6">
                <a
                    href="{{ route('tasks.index') }}"
                    class="inline-flex justify-center items-center rounded-xl border border-slate-300 px-6 py-3 text-slate-700 font-medium hover:bg-slate-50 transition"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="inline-flex justify-center items-center rounded-xl bg-blue-600 px-6 py-3 text-white font-semibold hover:bg-blue-700 transition"
                >
                    Create Task
                </button>
            </div>

        </form>
    </div>
</div>
@endsection