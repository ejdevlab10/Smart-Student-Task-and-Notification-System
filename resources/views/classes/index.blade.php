@extends('layouts.app')

@section('title', 'Class Management')
@section('page-heading', 'Class Management')

@section('content')

<div class="space-y-6 px-4 sm:px-6 lg:px-8 py-6 max-w-7xl mx-auto">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Class Management
            </h1>

            <p class="text-slate-500 mt-1">
                Create and manage academic classes.
            </p>
        </div>

        <a href="{{ route('admin.classes.create') }}"
           class="inline-flex items-center justify-center gap-2 bg-blue-600
                  hover:bg-blue-700 text-white px-5 py-3 rounded-xl
                  font-medium transition">

            <i data-lucide="plus" class="w-5 h-5"></i>
            Create Class

        </a>

    </div>


    {{-- Statistics --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm text-slate-500">
                        Total Classes
                    </p>

                    <h2 class="text-3xl font-bold text-slate-800 mt-2">
                        {{ $classes->total() }}
                    </h2>
                </div>

                <div class="bg-blue-100 text-blue-600 p-3 rounded-xl">
                    <i data-lucide="school" class="w-6 h-6"></i>
                </div>

            </div>

        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm text-slate-500">
                        Students in Current Results
                    </p>

                    <h2 class="text-3xl font-bold text-slate-800 mt-2">
                        {{ $classes->sum('students_count') }}
                    </h2>
                </div>

                <div class="bg-emerald-100 text-emerald-600 p-3 rounded-xl">
                    <i data-lucide="users" class="w-6 h-6"></i>
                </div>

            </div>

        </div>

    </div>


    {{-- Class Directory --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

        {{-- Search Header --}}
        <div class="p-6 border-b border-slate-200">

            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                <div>
                    <h2 class="text-lg font-semibold text-slate-800">
                        Class Directory
                    </h2>

                    <p class="text-sm text-slate-500 mt-1">
                        Browse classes and their assigned teachers.
                    </p>
                </div>

                <form method="GET"
                      action="{{ route('admin.classes.index') }}"
                      class="flex flex-col sm:flex-row gap-2">

                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Search classes..."
                        class="w-full sm:w-64 rounded-xl border border-slate-300
                               px-4 py-2 text-sm focus:ring-2
                               focus:ring-blue-500 focus:border-blue-500"
                    >

                    <button
                        type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white
                               px-5 py-2 rounded-xl text-sm font-medium transition">

                        Search

                    </button>

                    @if($search)
                        <a href="{{ route('admin.classes.index') }}"
                           class="bg-slate-100 hover:bg-slate-200 text-slate-700
                                  px-5 py-2 rounded-xl text-sm font-medium
                                  text-center transition">

                            Clear

                        </a>
                    @endif

                </form>

            </div>

        </div>


        {{-- Classes Table --}}
        <div class="overflow-x-auto">

            <table class="w-full text-left">

                <thead class="bg-slate-50 border-b border-slate-200">

                    <tr class="text-xs uppercase tracking-wider text-slate-500">

                        <th class="px-6 py-4 font-semibold">Class</th>
                        <th class="px-6 py-4 font-semibold">Academic Year</th>
                        <th class="px-6 py-4 font-semibold">Assigned Teacher</th>
                        <th class="px-6 py-4 font-semibold">Students</th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-slate-100">

                    @forelse($classes as $schoolClass)

                        <tr class="hover:bg-slate-50 transition">

                            {{-- Class Name --}}
                            <td class="px-6 py-4">

                                <div class="flex items-center gap-3">

                                    <div class="w-10 h-10 rounded-xl bg-blue-100
                                                text-blue-600 flex items-center
                                                justify-center">

                                        <i data-lucide="school" class="w-5 h-5"></i>

                                    </div>

                                    <div>
                                        <p class="font-medium text-slate-800">
                                            {{ $schoolClass->name }}
                                        </p>

                                        <p class="text-xs text-slate-500">
                                            Section:
                                            {{ $schoolClass->section ?: 'Not specified' }}
                                        </p>
                                    </div>

                                </div>

                            </td>


                            {{-- Academic Year --}}
                            <td class="px-6 py-4 text-sm text-slate-600">
                                {{ $schoolClass->academic_year ?: 'Not specified' }}
                            </td>


                            {{-- Assigned Teacher --}}
                            <td class="px-6 py-4">

                                @if($schoolClass->teacher)

                                    <div class="flex items-center gap-2">

                                        <div class="w-8 h-8 rounded-full bg-indigo-100
                                                    text-indigo-600 flex items-center
                                                    justify-center font-semibold text-sm">

                                            {{ strtoupper(substr($schoolClass->teacher->name, 0, 1)) }}

                                        </div>

                                        <span class="text-sm text-slate-700">
                                            {{ $schoolClass->teacher->name }}
                                        </span>

                                    </div>

                                @else

                                    <span class="text-sm text-slate-400">
                                        No teacher assigned
                                    </span>

                                @endif

                            </td>


                            {{-- Student Count --}}
                            <td class="px-6 py-4">

                                <span class="inline-flex items-center gap-2 px-3 py-1
                                             rounded-full bg-emerald-100
                                             text-emerald-700 text-xs font-semibold">

                                    <i data-lucide="users" class="w-3.5 h-3.5"></i>

                                    {{ $schoolClass->students_count }}

                                </span>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="4" class="px-6 py-14 text-center">

                                <div class="flex flex-col items-center">

                                    <i data-lucide="school"
                                       class="w-12 h-12 text-slate-300 mb-4"></i>

                                    <h3 class="text-lg font-semibold text-slate-700">
                                        No Classes Found
                                    </h3>

                                    <p class="text-sm text-slate-500 mt-2">
                                        @if($search)
                                            No classes match your search.
                                        @else
                                            Start by creating your first academic class.
                                        @endif
                                    </p>

                                    @if(!$search)
                                        <a href="{{ route('admin.classes.create') }}"
                                           class="mt-4 inline-flex items-center gap-2
                                                  bg-blue-600 hover:bg-blue-700 text-white
                                                  px-4 py-2 rounded-xl text-sm font-medium">

                                            <i data-lucide="plus" class="w-4 h-4"></i>
                                            Create First Class

                                        </a>
                                    @endif

                                </div>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}
        @if($classes->hasPages())

            <div class="px-6 py-4 border-t border-slate-200">
                {{ $classes->links() }}
            </div>

        @endif


        {{-- Results Count --}}
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">

            <p class="text-sm text-slate-500">

                Showing {{ $classes->firstItem() ?? 0 }}
                to {{ $classes->lastItem() ?? 0 }}
                of {{ $classes->total() }} classes

            </p>

        </div>

    </div>

</div>

@endsection