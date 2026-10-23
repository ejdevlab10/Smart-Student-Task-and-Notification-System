@extends('layouts.app')

@section('title', 'Edit Task')

@section('page-heading', 'Edit Task')

@section('content')
@php
    $selectedClassIds = collect(
        old('class_ids', $task->school_class_id ? [$task->school_class_id] : [])
    )->map(fn ($id) => (string) $id)->all();

    $selectedStudentIds = collect(
        old('students', $task->students->pluck('id')->all())
    )->map(fn ($id) => (string) $id)->all();
@endphp

<div class="p-6 lg:p-8">
    <div class="max-w-4xl mx-auto">

        <div class="mb-6">
            <x-back-button
                fallback="tasks.index"
                label="Back"
            />
        </div>

        <div class="mt-6 bg-white rounded-2xl border border-slate-200 shadow-sm p-6 lg:p-8">

            <h1 class="text-2xl font-bold text-slate-900">Edit Task</h1>

            <p class="text-slate-500 mt-1 mb-8">
                Update the task details and manage class and individual student assignments.
            </p>

            @if ($errors->any())
                <div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4">
                    <p class="font-medium text-red-700">
                        Please correct the following errors:
                    </p>

                    <ul class="mt-2 list-disc pl-5 text-sm text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('tasks.update', $task) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Task Title --}}
                <div class="mb-6">
                    <label for="title"
                           class="block text-sm font-medium text-slate-700 mb-2">
                        Task Title
                    </label>

                    <input
                        id="title"
                        type="text"
                        name="title"
                        value="{{ old('title', $task->title) }}"
                        required
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
                        Subject
                    </label>

                    <input
                        id="subject"
                        type="text"
                        name="subject"
                        value="{{ old('subject', $task->subject) }}"
                        required
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
                        Task Type
                    </label>

                    <select
                        id="type"
                        name="type"
                        required
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >
                        @foreach (['Assignment', 'Project', 'Quiz', 'Exam'] as $type)
                            <option
                                value="{{ $type }}"
                                {{ old('type', $task->type) === $type ? 'selected' : '' }}
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
                        id="description"
                        name="description"
                        rows="5"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >{{ old('description', $task->description) }}</textarea>

                    @error('description')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Due Date --}}
                <div class="mb-8">
                    <label for="due_date"
                           class="block text-sm font-medium text-slate-700 mb-2">
                        Due Date
                    </label>

                    <input
                        id="due_date"
                        type="datetime-local"
                        name="due_date"
                        value="{{ old('due_date', $task->due_date ? $task->due_date->format('Y-m-d\TH:i') : '') }}"
                        required
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >

                    @error('due_date')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Multiple Classes --}}
                <div class="mb-8">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">
                                Assign to Classes
                            </label>

                            <p class="text-xs text-slate-500 mt-1">
                                Select one or more classes. Students in those classes will be assigned automatically.
                            </p>
                        </div>

                        <span class="text-sm text-blue-600 font-medium">
                            <span id="selected-class-count">0</span> selected
                        </span>
                    </div>

                    <input
                        id="class-search"
                        type="text"
                        placeholder="Search classes..."
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 mb-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >

                    <div class="flex flex-wrap gap-2 mb-3">
                        <button
                            type="button"
                            id="select-visible-classes"
                            class="px-3 py-2 text-sm rounded-lg border border-slate-300 hover:bg-slate-50"
                        >
                            Select visible
                        </button>

                        <button
                            type="button"
                            id="clear-classes"
                            class="px-3 py-2 text-sm rounded-lg border border-slate-300 hover:bg-slate-50"
                        >
                            Clear classes
                        </button>
                    </div>

                    <div id="class-list"
                         class="max-h-64 overflow-y-auto rounded-xl border border-slate-200 divide-y divide-slate-100">

                        @forelse ($classes as $class)
                            <label
                                class="class-option flex items-center gap-3 px-4 py-3 hover:bg-slate-50 cursor-pointer"
                                data-search="{{ strtolower($class->name . ' ' . $class->section . ' ' . $class->academic_year) }}"
                            >
                                <input
                                    type="checkbox"
                                    name="class_ids[]"
                                    value="{{ $class->id }}"
                                    class="class-checkbox rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                                    {{ in_array((string) $class->id, $selectedClassIds, true) ? 'checked' : '' }}
                                >

                                <span class="text-sm text-slate-700">
                                    <span class="font-medium">{{ $class->name }}</span>

                                    @if ($class->section)
                                        — {{ $class->section }}
                                    @endif

                                    @if ($class->academic_year)
                                        <span class="text-slate-400">
                                            ({{ $class->academic_year }})
                                        </span>
                                    @endif
                                </span>
                            </label>
                        @empty
                            <p class="p-4 text-sm text-slate-500">
                                No classes are available.
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

                {{-- Individual Students --}}
                <div class="mb-8">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-700">
                                Assign to Individual Students
                            </label>

                            <p class="text-xs text-slate-500 mt-1">
                                Select specific students in addition to the selected classes, or use individual students only.
                            </p>
                        </div>

                        <span class="text-sm text-blue-600 font-medium">
                            <span id="selected-student-count">0</span> selected
                        </span>
                    </div>

                    <input
                        id="student-search"
                        type="text"
                        placeholder="Search by student name, ID, or class..."
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 mb-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >

                    <div class="flex flex-wrap gap-2 mb-3">
                        <button
                            type="button"
                            id="select-visible-students"
                            class="px-3 py-2 text-sm rounded-lg border border-slate-300 hover:bg-slate-50"
                        >
                            Select visible
                        </button>

                        <button
                            type="button"
                            id="clear-students"
                            class="px-3 py-2 text-sm rounded-lg border border-slate-300 hover:bg-slate-50"
                        >
                            Clear students
                        </button>
                    </div>

                    <div id="student-list"
                         class="max-h-80 overflow-y-auto rounded-xl border border-slate-200 divide-y divide-slate-100">

                        @forelse ($students as $student)
                            @php
                                $studentClassNames = $student->schoolClasses
                                    ->map(function ($class) {
                                        return $class->name . ' ' . $class->section;
                                    })
                                    ->implode(' ');

                                $studentSearchText = strtolower(
                                    $student->name . ' ' .
                                    $student->email . ' ' .
                                    ($student->student_id ?? '') . ' ' .
                                    $studentClassNames
                                );
                            @endphp

                            <label
                                class="student-option flex items-start gap-3 px-4 py-3 hover:bg-slate-50 cursor-pointer"
                                data-search="{{ $studentSearchText }}"
                            >
                                <input
                                    type="checkbox"
                                    name="students[]"
                                    value="{{ $student->id }}"
                                    class="student-checkbox mt-1 rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                                    {{ in_array((string) $student->id, $selectedStudentIds, true) ? 'checked' : '' }}
                                >

                                <span class="text-sm text-slate-700">
                                    <span class="block font-medium">
                                        {{ $student->name }}
                                    </span>

                                    <span class="block text-slate-400">
                                        {{ $student->email }}

                                        @if ($student->student_id)
                                            &middot; Student ID: {{ $student->student_id }}
                                        @endif
                                    </span>

                                    <span class="block text-xs text-slate-500 mt-1">
                                        Classes:
                                        @if ($student->schoolClasses->isNotEmpty())
                                            {{ $student->schoolClasses->map(function ($class) {
                                                return $class->name . ($class->section ? ' - ' . $class->section : '');
                                            })->implode(', ') }}
                                        @else
                                            No class assigned
                                        @endif
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

                {{-- Actions --}}
                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                    <a
                        href="{{ route('tasks.show', $task) }}"
                        class="px-5 py-3 rounded-xl border border-slate-300 text-center hover:bg-slate-50"
                    >
                        Cancel
                    </a>

                    <button
                        type="submit"
                        class="px-6 py-3 rounded-xl bg-blue-600 text-white font-medium hover:bg-blue-700"
                    >
                        Save Changes
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Class selection
    const classSearch = document.getElementById('class-search');
    const classOptions = Array.from(document.querySelectorAll('.class-option'));
    const classCheckboxes = Array.from(document.querySelectorAll('.class-checkbox'));
    const classCount = document.getElementById('selected-class-count');

    function updateClassCount() {
        classCount.textContent = classCheckboxes.filter(box => box.checked).length;
    }

    classSearch.addEventListener('input', function () {
        const search = this.value.toLowerCase().trim();

        classOptions.forEach(option => {
            option.hidden = !option.dataset.search.includes(search);
        });
    });

    document.getElementById('select-visible-classes').addEventListener('click', function () {
        classOptions.forEach(option => {
            if (!option.hidden) {
                const checkbox = option.querySelector('.class-checkbox');
                checkbox.checked = true;
            }
        });

        updateClassCount();
    });

    document.getElementById('clear-classes').addEventListener('click', function () {
        classCheckboxes.forEach(box => box.checked = false);
        updateClassCount();
    });

    classCheckboxes.forEach(box => {
        box.addEventListener('change', updateClassCount);
    });

    // Individual student selection
    const studentSearch = document.getElementById('student-search');
    const studentOptions = Array.from(document.querySelectorAll('.student-option'));
    const studentCheckboxes = Array.from(document.querySelectorAll('.student-checkbox'));
    const studentCount = document.getElementById('selected-student-count');

    function updateStudentCount() {
        studentCount.textContent = studentCheckboxes.filter(box => box.checked).length;
    }

    studentSearch.addEventListener('input', function () {
        const search = this.value.toLowerCase().trim();

        studentOptions.forEach(option => {
            option.hidden = !option.dataset.search.includes(search);
        });
    });

    document.getElementById('select-visible-students').addEventListener('click', function () {
        studentOptions.forEach(option => {
            if (!option.hidden) {
                const checkbox = option.querySelector('.student-checkbox');
                checkbox.checked = true;
            }
        });

        updateStudentCount();
    });

    document.getElementById('clear-students').addEventListener('click', function () {
        studentCheckboxes.forEach(box => box.checked = false);
        updateStudentCount();
    });

    studentCheckboxes.forEach(box => {
        box.addEventListener('change', updateStudentCount);
    });

    // Initialize the selected counts.
    updateClassCount();
    updateStudentCount();
});
</script>
@endsection