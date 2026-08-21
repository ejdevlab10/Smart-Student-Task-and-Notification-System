@extends('layouts.app')

@section('title', 'Edit Task')

@section('page-heading', 'Edit Task')

@section('content')

<div class="p-6 lg:p-8">

    <div class="max-w-3xl mx-auto">

        <a href="{{ route('tasks.show', $task) }}"
           class="text-sm text-blue-600 hover:text-blue-700">

            ← Back to Task

        </a>


        <div class="mt-6 bg-white rounded-2xl border border-slate-200 shadow-sm p-8">

            <h1 class="text-2xl font-bold text-slate-900">
                Edit Task
            </h1>

            <p class="text-slate-500 mt-1 mb-8">
                Update the details of this academic task.
            </p>


            <form action="{{ route('tasks.update', $task) }}"
                  method="POST">

                @csrf
                @method('PUT')


                <div class="mb-6">

                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Task Title
                    </label>

                    <input
                        type="text"
                        name="title"
                        value="{{ old('title', $task->title) }}"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3"
                    >

                    @error('title')
                        <p class="text-sm text-red-600 mt-2">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                <div class="mb-6">

                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Subject
                    </label>

                    <input
                        type="text"
                        name="subject"
                        value="{{ old('subject', $task->subject) }}"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3"
                    >

                    @error('subject')
                        <p class="text-sm text-red-600 mt-2">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                <div class="mb-6">

                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Task Type
                    </label>

                    <select
                        name="type"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 bg-white"
                    >

                        @foreach(['Assignment', 'Project', 'Quiz', 'Exam'] as $type)

                            <option value="{{ $type }}"
                                {{ old('type', $task->type) === $type ? 'selected' : '' }}>

                                {{ $type }}

                            </option>

                        @endforeach

                    </select>

                </div>


                <div class="mb-6">

                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Description
                    </label>

                    <textarea
                        name="description"
                        rows="5"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3"
                    >{{ old('description', $task->description) }}</textarea>

                </div>


                <div class="mb-8">

                    <label class="block text-sm font-medium text-slate-700 mb-2">
                        Due Date
                    </label>

                    <input
                        type="datetime-local"
                        name="due_date"
                        value="{{ old('due_date', $task->due_date->format('Y-m-d\TH:i')) }}"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3"
                    >

                    @error('due_date')
                        <p class="text-sm text-red-600 mt-2">
                            {{ $message }}
                        </p>
                    @enderror

                </div>


                <div class="flex justify-end gap-3">

                    <a href="{{ route('tasks.show', $task) }}"
                       class="px-5 py-3 rounded-xl border border-slate-300">

                        Cancel

                    </a>

                    <button
                        type="submit"
                        class="px-6 py-3 rounded-xl bg-blue-600 text-white font-medium">

                        Save Changes

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection