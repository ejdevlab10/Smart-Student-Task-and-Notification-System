@extends('layouts.app')

@section('title', 'Class Details')
@section('page-heading', 'Class Details')

@section('content')
<div class="space-y-6 px-4 sm:px-6 lg:px-8 py-6 max-w-7xl mx-auto">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                {{ $class->name }}
            </h1>

            <p class="text-sm text-slate-500 mt-1">
                Section: {{ $class->section ?: 'Not specified' }}
                · Academic Year: {{ $class->academic_year ?: 'Not specified' }}
            </p>
        </div>

        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.classes.index') }}"
               class="inline-flex items-center gap-2 rounded-xl border border-slate-300
                      px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Back
            </a>

            <a href="{{ route('admin.classes.edit', $class) }}"
               class="inline-flex items-center gap-2 rounded-xl bg-indigo-600
                      px-4 py-2.5 text-sm font-medium text-white hover:bg-indigo-700">
                <i data-lucide="pencil" class="w-4 h-4"></i>
                Edit Class
            </a>
        </div>
    </div>

    {{-- Success Message --}}
    @if (session('success'))
        <div class="rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    {{-- Validation Errors --}}
    @if ($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-4">
            <ul class="list-disc list-inside space-y-1 text-sm text-red-600">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Class Information --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Assigned Teacher</p>
            <p class="mt-2 font-semibold text-slate-800">
                {{ $class->teacher?->name ?? 'No teacher assigned' }}
            </p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Enrolled Students</p>
            <p class="mt-2 text-2xl font-bold text-slate-800">
                {{ $class->students->count() }}
            </p>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <p class="text-sm text-slate-500">Academic Year</p>
            <p class="mt-2 font-semibold text-slate-800">
                {{ $class->academic_year ?: 'Not specified' }}
            </p>
        </div>

    </div>

    {{-- Enroll Student --}}
    <div class="rounded-2xl border border-slate-200 bg-white p-5 sm:p-6 shadow-sm">
        <h2 class="text-lg font-semibold text-slate-800">
            Enroll a Student
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            Select a student account to add to this class.
        </p>

        @if ($students->isNotEmpty())
            <form action="{{ route('admin.classes.enroll', $class) }}"
                  method="POST"
                  class="mt-5 flex flex-col sm:flex-row gap-3">
                @csrf

                <select name="student_id"
                        required
                        class="flex-1 rounded-xl border border-slate-300 bg-white px-4 py-3 text-sm">
                    <option value="">Choose a student...</option>

                    @foreach ($students as $student)
                        <option value="{{ $student->id }}"
                            {{ (string) old('student_id') === (string) $student->id ? 'selected' : '' }}>
                            {{ $student->name }} ({{ $student->email }})
                        </option>
                    @endforeach
                </select>

                <button type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl
                               bg-blue-600 px-5 py-3 text-sm font-semibold text-white
                               hover:bg-blue-700">
                    <i data-lucide="user-plus" class="w-4 h-4"></i>
                    Enroll Student
                </button>
            </form>
        @else
            <div class="mt-4 rounded-xl bg-slate-50 p-4 text-sm text-slate-500">
                All registered students are already enrolled in this class, or no student accounts exist yet.
            </div>
        @endif
    </div>

    {{-- Enrolled Students Table --}}
    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">

        <div class="border-b border-slate-200 p-5 sm:p-6">
            <h2 class="text-lg font-semibold text-slate-800">
                Enrolled Students
            </h2>
            <p class="mt-1 text-sm text-slate-500">
                Students currently assigned to this class.
            </p>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="border-b border-slate-200 bg-slate-50">
                    <tr class="text-xs uppercase tracking-wider text-slate-500">
                        <th class="px-6 py-4 font-semibold">Student</th>
                        <th class="px-6 py-4 font-semibold">Email</th>
                        <th class="px-6 py-4 text-right font-semibold">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-100">
                    @forelse ($class->students as $student)
                        <tr class="hover:bg-slate-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center
                                                rounded-full bg-indigo-100 font-semibold text-indigo-600">
                                        {{ strtoupper(substr($student->name, 0, 1)) }}
                                    </div>

                                    <span class="font-medium text-slate-800">
                                        {{ $student->name }}
                                    </span>
                                </div>
                            </td>

                            <td class="px-6 py-4 text-sm text-slate-600">
                                {{ $student->email }}
                            </td>

                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('admin.classes.students.remove', [$class, $student]) }}"
                                      method="POST"
                                      onsubmit="return confirm('Remove this student from the class?');">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            class="inline-flex items-center gap-2 rounded-lg
                                                   border border-red-200 px-3 py-2 text-sm
                                                   font-medium text-red-600 hover:bg-red-50">
                                        <i data-lucide="user-minus" class="w-4 h-4"></i>
                                        Remove
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-12 text-center">
                                <i data-lucide="users"
                                   class="mx-auto mb-3 h-10 w-10 text-slate-300"></i>

                                <p class="font-semibold text-slate-700">
                                    No Students Enrolled
                                </p>

                                <p class="mt-1 text-sm text-slate-500">
                                    Use the enrollment form above to add students.
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection