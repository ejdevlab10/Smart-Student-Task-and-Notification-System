@extends('layouts.app')

@section('title', 'Edit Task')

@section('page-heading', 'Edit Task')

@section('content')
<div class="p-6 lg:p-8">
    <div class="max-w-3xl mx-auto">

        <a href="{{ route('tasks.show', $task) }}"
           class="text-sm text-blue-600 hover:text-blue-700">
            &larr; Back to Task
        </a>

        <div class="mt-6 bg-white rounded-2xl border border-slate-200 shadow-sm p-8">

            <h1 class="text-2xl font-bold text-slate-900">Edit Task</h1>

            <p class="text-slate-500 mt-1 mb-8">
                Update the task details, class, and student assignments.
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

                <div class="mb-6">
                    <label for="title"
                           class="block text-sm font-medium text-slate-700 mb-2">
                        Task Title
                    </label>

                    <input id="title"
                           type="text"
                           name="title"
                           value="{{ old('title', $task->title) }}"
                           required
                           class="w-full rounded-xl border border-slate-300 px-4 py-3">

                    @error('title')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="subject"
                           class="block text-sm font-medium text-slate-700 mb-2">
                        Subject
                    </label>

                    <input id="subject"
                           type="text"
                           name="subject"
                           value="{{ old('subject', $task->subject) }}"
                           required
                           class="w-full rounded-xl border border-slate-300 px-4 py-3">

                    @error('subject')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="type"
                           class="block text-sm font-medium text-slate-700 mb-2">
                        Task Type
                    </label>

                    <select id="type"
                            name="type"
                            required
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 bg-white">

                        @foreach (['Assignment', 'Project', 'Quiz', 'Exam'] as $type)
                            <option value="{{ $type }}"
                                {{ old('type', $task->type) === $type ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>

                    @error('type')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="description"
                           class="block text-sm font-medium text-slate-700 mb-2">
                        Description
                    </label>

                    <textarea id="description"
                              name="description"
                              rows="5"
                              class="w-full rounded-xl border border-slate-300 px-4 py-3">{{ old('description', $task->description) }}</textarea>

                    @error('description')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="due_date"
                           class="block text-sm font-medium text-slate-700 mb-2">
                        Due Date
                    </label>

                    <input id="due_date"
                           type="datetime-local"
                           name="due_date"
                           value="{{ old('due_date', $task->due_date ? $task->due_date->format('Y-m-d\TH:i') : '') }}"
                           required
                           class="w-full rounded-xl border border-slate-300 px-4 py-3">

                    @error('due_date')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="class_id"
                           class="block text-sm font-medium text-slate-700 mb-2">
                        Assign to Class
                    </label>

                    <select id="class_id"
                            name="class_id"
                            class="w-full rounded-xl border border-slate-300 px-4 py-3 bg-white">

                        <option value="">No class — individual students only</option>

                        @foreach ($classes as $class)
                            <option value="{{ $class->id }}"
                                {{ (string) old('class_id', $task->school_class_id) === (string) $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                                @if ($class->section)
                                    — {{ $class->section }}
                                @endif
                                @if ($class->academic_year)
                                    ({{ $class->academic_year }})
                                @endif
                            </option>
                        @endforeach
                    </select>

                    <p class="text-xs text-slate-500 mt-2">
                        Students currently enrolled in the selected class will be assigned automatically.
                    </p>

                    @error('class_id')
                        <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-8">
                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Assign to Individual Students
                    </label>

                    <p class="text-xs text-slate-500 mb-3">
                        Select individual students in addition to the class, if needed.
                        Students already included through the selected class will not be duplicated.
                    </p>

                    <div class="max-h-64 overflow-y-auto rounded-xl border border-slate-200 divide-y divide-slate-100">

                        @forelse ($students as $student)
                            <label class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 cursor-pointer">

                                <input type="checkbox"
                                       name="students[]"
                                       value="{{ $student->id }}"
                                       class="rounded border-slate-300 text-blue-600 focus:ring-blue-500"
                                       {{ in_array(
                                            (string) $student->id,
                                            array_map('strval', old(
                                                'students',
                                                $task->students->pluck('id')->all()
                                            )),
                                            true
                                       ) ? 'checked' : '' }}>

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

                <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                    <a href="{{ route('tasks.show', $task) }}"
                       class="px-5 py-3 rounded-xl border border-slate-300 text-center hover:bg-slate-50">
                        Cancel
                    </a>

                    <button type="submit"
                            class="px-6 py-3 rounded-xl bg-blue-600 text-white font-medium hover:bg-blue-700">
                        Save Changes
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection