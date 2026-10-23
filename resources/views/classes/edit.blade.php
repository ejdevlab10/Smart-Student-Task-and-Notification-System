@extends('layouts.app')

@section('title', 'Edit Class')

@section('content')
<div class="space-y-6 px-4 sm:px-6 lg:px-8 py-6 max-w-4xl mx-auto">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Edit Class
            </h1>

            <p class="text-sm text-slate-500 mt-1">
                Update the class information and assigned teacher.
            </p>
        </div>

        <x-back-button
            fallback="admin.classes.index"
            label="Back"
            class="inline-flex items-center justify-center gap-2 rounded-xl
           border border-slate-300 bg-white px-4 py-2.5
           text-sm font-medium text-slate-700 hover:bg-slate-50"
        />
        </x>
    </div>

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-4">
            <div class="font-semibold text-red-700 mb-2">
                Please correct the following errors:
            </div>

            <ul class="list-disc list-inside text-sm text-red-600 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Edit Form --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5 sm:p-8">

        <form action="{{ route('admin.classes.update', $class) }}"
              method="POST"
              class="space-y-6">

            @csrf
            @method('PUT')

            {{-- Class Name --}}
            <div>
                <label for="name"
                       class="block text-sm font-medium text-slate-700 mb-2">
                    Class Name <span class="text-red-500">*</span>
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    value="{{ old('name', $class->name) }}"
                    required
                    maxlength="255"
                    placeholder="e.g. General Mathematics"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3
                           text-sm focus:border-indigo-500 focus:ring-2
                           focus:ring-indigo-200"
                >
            </div>

            {{-- Section --}}
            <div>
                <label for="section"
                       class="block text-sm font-medium text-slate-700 mb-2">
                    Section
                </label>

                <input
                    type="text"
                    id="section"
                    name="section"
                    value="{{ old('section', $class->section) }}"
                    maxlength="100"
                    placeholder="e.g. BSIT-1A"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3
                           text-sm focus:border-indigo-500 focus:ring-2
                           focus:ring-indigo-200"
                >
            </div>

            {{-- Academic Year --}}
            <div>
                <label for="academic_year"
                       class="block text-sm font-medium text-slate-700 mb-2">
                    Academic Year
                </label>

                <input
                    type="text"
                    id="academic_year"
                    value="{{ $class->academic_year }}"
                    readonly
                    class="w-full rounded-xl border border-slate-300
                           bg-slate-100 px-4 py-3 text-sm text-slate-600
                           cursor-not-allowed"
                >

                <p class="text-xs text-slate-500 mt-2">
                    The academic year is automatically generated and cannot
                    be changed here.
                </p>
            </div>

            {{-- Assigned Teacher --}}
            <div>
                <label for="teacher_id"
                       class="block text-sm font-medium text-slate-700 mb-2">
                    Assigned Teacher
                </label>

                <select
                    id="teacher_id"
                    name="teacher_id"
                    class="w-full rounded-xl border border-slate-300 bg-white
                           px-4 py-3 text-sm focus:border-indigo-500
                           focus:ring-2 focus:ring-indigo-200"
                >
                    <option value="">No teacher assigned</option>

                    @foreach ($teachers as $teacher)
                        <option
                            value="{{ $teacher->id }}"
                            {{ (string) old('teacher_id', $class->teacher_id) === (string) $teacher->id ? 'selected' : '' }}
                        >
                            {{ $teacher->name }}
                        </option>
                    @endforeach
                </select>

                <p class="text-xs text-slate-500 mt-2">
                    Select the teacher responsible for this class.
                </p>
            </div>

            {{-- Buttons --}}
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-4 border-t border-slate-100">

                <a href="{{ route('admin.classes.index') }}"
                   class="inline-flex items-center justify-center rounded-xl
                          border border-slate-300 px-5 py-3 text-sm
                          font-medium text-slate-700 hover:bg-slate-50">
                    Cancel
                </a>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2
                           rounded-xl bg-indigo-600 px-5 py-3 text-sm
                           font-semibold text-white hover:bg-indigo-700"
                >
                    <i data-lucide="save" class="w-4 h-4"></i>
                    Save Changes
                </button>

            </div>

        </form>
    </div>
</div>
@endsection