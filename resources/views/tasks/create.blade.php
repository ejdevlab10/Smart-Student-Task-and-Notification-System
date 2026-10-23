@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">

    {{-- Header --}}
    <div class="mb-8">
        <a href="{{ route('tasks.index') }}"
           class="text-sm text-blue-600 hover:text-blue-800">
            &larr; Back to Tasks
        </a>

        <h1 class="text-3xl font-bold text-slate-800 mt-3">
            Create New Task
        </h1>

        <p class="text-slate-500 mt-2">
            Assign tasks to one or more classes, individual students, or both.
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

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 md:p-8">

        <form action="{{ route('tasks.store') }}" method="POST">
            @csrf

            {{-- Title --}}
            <div class="mb-6">
                <label for="title" class="block text-sm font-medium text-slate-700 mb-2">
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
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-blue-500"
                >

                @error('title')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Subject --}}
            <div class="mb-6">
                <label for="subject" class="block text-sm font-medium text-slate-700 mb-2">
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
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-blue-500"
                >

                @error('subject')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Task Type --}}
            <div class="mb-6">
                <label for="type" class="block text-sm font-medium text-slate-700 mb-2">
                    Task Type <span class="text-red-500">*</span>
                </label>

                <select
                    name="type"
                    id="type"
                    required
                    class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 focus:ring-2 focus:ring-blue-500"
                >
                    <option value="">Select task type</option>

                    @foreach (['Assignment', 'Project', 'Quiz', 'Exam'] as $type)
                        <option value="{{ $type }}"
                            {{ old('type') === $type ? 'selected' : '' }}>
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
                <label for="description" class="block text-sm font-medium text-slate-700 mb-2">
                    Description
                </label>

                <textarea
                    name="description"
                    id="description"
                    rows="4"
                    placeholder="Enter task instructions or additional details..."
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-blue-500"
                >{{ old('description') }}</textarea>

                @error('description')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Due Date --}}
            <div class="mb-8">
                <label for="due_date" class="block text-sm font-medium text-slate-700 mb-2">
                    Due Date and Time <span class="text-red-500">*</span>
                </label>

                <input
                    type="datetime-local"
                    name="due_date"
                    id="due_date"
                    value="{{ old('due_date', $selectedDate ?? '') }}"
                    required
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-blue-500"
                >

                @error('due_date')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Multiple Class Selection --}}
            <div class="mb-8">
                <label class="block text-sm font-medium text-slate-700 mb-2">
                    Assign to Classes
                </label>

                <p class="text-sm text-slate-500 mb-3">
                    Select one or more classes. All enrolled students in those classes will be assigned the task.
                </p>

                <div class="mb-3 flex flex-col sm:flex-row gap-3">
                    <input
                        type="search"
                        id="class_search"
                        placeholder="Search classes..."
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-blue-500"
                    >

                    <button
                        type="button"
                        id="clear_classes"
                        class="shrink-0 rounded-xl border border-slate-300 px-4 py-2 text-sm font-medium hover:bg-slate-50"
                    >
                        Clear Classes
                    </button>
                </div>

                <div class="flex items-center justify-between text-sm mb-2">
                    <span class="text-slate-500">
                        Selected: <strong id="selected_class_count">0</strong>
                    </span>

                    <button
                        type="button"
                        id="select_visible_classes"
                        class="text-blue-600 hover:text-blue-800 font-medium"
                    >
                        Select visible classes
                    </button>
                </div>

                <div class="max-h-64 overflow-y-auto rounded-xl border border-slate-200 divide-y divide-slate-100">
                    @forelse ($classes as $class)
                        <label
                            class="class-option flex items-center gap-3 px-4 py-3 hover:bg-slate-50 cursor-pointer"
                            data-search="{{ strtolower($class->name . ' ' . ($class->section ?? '') . ' ' . ($class->academic_year ?? '')) }}"
                        >
                            <input
                                type="checkbox"
                                name="class_ids[]"
                                value="{{ $class->id }}"
                                class="class-checkbox rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                                {{ in_array((string) $class->id, array_map('strval', old('class_ids', [])), true) ? 'checked' : '' }}
                            >

                            <span class="text-sm text-slate-700">
                                <span class="block font-medium">
                                    {{ $class->name }}
                                    @if ($class->section)
                                        — Section {{ $class->section }}
                                    @endif
                                </span>

                                @if ($class->academic_year)
                                    <span class="block text-xs text-slate-500">
                                        Academic Year: {{ $class->academic_year }}
                                    </span>
                                @endif
                            </span>
                        </label>
                    @empty
                        <p class="p-4 text-sm text-slate-500">
                            No classes are available. You can still assign the task to individual students.
                        </p>
                    @endforelse
                </div>

                @error('class_ids')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                @enderror

                @error('class_ids.*')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            {{-- Individual Student Selection --}}
            <div class="mb-8">
                <div class="mb-3">
                    <h2 class="text-lg font-semibold text-slate-800">
                        Individual Students
                    </h2>

                    <p class="text-sm text-slate-500 mt-1">
                        Optional: select students who should receive this task in addition to, or instead of, the selected classes.
                    </p>
                </div>

                <input
                    type="search"
                    id="student_search"
                    placeholder="Search by student name, ID, or class..."
                    class="w-full rounded-xl border border-slate-300 px-4 py-3 mb-3 focus:ring-2 focus:ring-blue-500"
                >

                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3 text-sm">
                    <span class="text-slate-500">
                        Selected individual students:
                        <strong id="selected_student_count">0</strong>
                    </span>

                    <div class="flex gap-4">
                        <button
                            type="button"
                            id="select_visible_students"
                            class="text-blue-600 hover:text-blue-800 font-medium"
                        >
                            Select visible
                        </button>

                        <button
                            type="button"
                            id="clear_students"
                            class="text-red-600 hover:text-red-800 font-medium"
                        >
                            Clear students
                        </button>
                    </div>
                </div>

                <div class="max-h-80 overflow-y-auto rounded-xl border border-slate-200 divide-y divide-slate-100">
                    @forelse ($students as $student)
                        @php
                            $studentClasses = $student->schoolClasses
                                ->map(function ($schoolClass) {
                                    return $schoolClass->name .
                                        ($schoolClass->section
                                            ? ' Section ' . $schoolClass->section
                                            : '');
                                })
                                ->implode(', ');

                            $searchText = strtolower(
                                ($student->name ?? '') . ' ' .
                                ($student->student_id ?? '') . ' ' .
                                $studentClasses
                            );
                        @endphp

                        <label
                            class="student-option flex items-start gap-3 px-4 py-3 hover:bg-slate-50 cursor-pointer"
                            data-search="{{ $searchText }}"
                        >
                            <input
                                type="checkbox"
                                name="students[]"
                                value="{{ $student->id }}"
                                class="student-checkbox mt-1 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                                {{ in_array((string) $student->id, array_map('strval', old('students', [])), true) ? 'checked' : '' }}
                            >

                            <span class="text-sm text-slate-700 min-w-0">
                                <span class="block font-medium">
                                    {{ $student->name }}
                                </span>

                                <span class="block text-xs text-slate-500">
                                    Student ID:
                                    {{ $student->student_id ?: 'Not provided' }}
                                </span>

                                <span class="block text-xs text-slate-500">
                                    Classes:
                                    {{ $studentClasses ?: 'No class assigned' }}
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
                    class="inline-flex justify-center items-center rounded-xl border border-slate-300 px-6 py-3 text-slate-700 font-medium hover:bg-slate-50"
                >
                    Cancel
                </a>

                <button
                    type="submit"
                    class="inline-flex justify-center items-center rounded-xl bg-blue-600 px-6 py-3 text-white font-semibold hover:bg-blue-700"
                >
                    Create Task
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const classSearch = document.getElementById('class_search');
    const studentSearch = document.getElementById('student_search');

    const classOptions = Array.from(document.querySelectorAll('.class-option'));
    const studentOptions = Array.from(document.querySelectorAll('.student-option'));

    const classCheckboxes = Array.from(document.querySelectorAll('.class-checkbox'));
    const studentCheckboxes = Array.from(document.querySelectorAll('.student-checkbox'));

    const selectedClassCount = document.getElementById('selected_class_count');
    const selectedStudentCount = document.getElementById('selected_student_count');

    function updateCount(checkboxes, output) {
        output.textContent = checkboxes.filter(input => input.checked).length;
    }

    function filterOptions(searchInput, options) {
        const query = searchInput.value.trim().toLowerCase();

        options.forEach(option => {
            const searchText = option.dataset.search || '';
            option.hidden = !searchText.includes(query);
        });
    }

    classSearch.addEventListener('input', function () {
        filterOptions(classSearch, classOptions);
    });

    studentSearch.addEventListener('input', function () {
        filterOptions(studentSearch, studentOptions);
    });

    classCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            updateCount(classCheckboxes, selectedClassCount);
        });
    });

    studentCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function () {
            updateCount(studentCheckboxes, selectedStudentCount);
        });
    });

    document.getElementById('select_visible_classes').addEventListener('click', function () {
        classOptions.forEach(option => {
            if (!option.hidden) {
                const checkbox = option.querySelector('.class-checkbox');
                checkbox.checked = true;
            }
        });

        updateCount(classCheckboxes, selectedClassCount);
    });

    document.getElementById('clear_classes').addEventListener('click', function () {
        classCheckboxes.forEach(checkbox => checkbox.checked = false);
        updateCount(classCheckboxes, selectedClassCount);
    });

    document.getElementById('select_visible_students').addEventListener('click', function () {
        studentOptions.forEach(option => {
            if (!option.hidden) {
                const checkbox = option.querySelector('.student-checkbox');
                checkbox.checked = true;
            }
        });

        updateCount(studentCheckboxes, selectedStudentCount);
    });

    document.getElementById('clear_students').addEventListener('click', function () {
        studentCheckboxes.forEach(checkbox => checkbox.checked = false);
        updateCount(studentCheckboxes, selectedStudentCount);
    });

    updateCount(classCheckboxes, selectedClassCount);
    updateCount(studentCheckboxes, selectedStudentCount);
});
</script>
@endsection