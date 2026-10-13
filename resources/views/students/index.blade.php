@extends('layouts.app')

@section('title', 'Student Management')

@section('page-heading', 'Student Management')

@section('content')

<div class="min-h-screen bg-slate-100 p-6 lg:p-8">

    <div class="max-w-7xl mx-auto">

        {{-- Page Header --}}
        <div class="mb-8">

            <p class="text-sm font-medium text-blue-600 mb-1">
                Administration
            </p>

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

                <div>
                    <h1 class="text-3xl font-bold text-slate-900">
                        Student Directory
                    </h1>

                    <p class="mt-2 text-slate-500">
                        View and search registered student accounts.
                    </p>
                </div>

                <div class="bg-white border border-slate-200 rounded-xl px-5 py-4 shadow-sm">

                    <p class="text-sm text-slate-500">
                        Total Students
                    </p>

                    <p class="text-2xl font-bold text-blue-600 mt-1">
                        {{ $students->total() }}
                    </p>

                </div>

            </div>

        </div>


        {{-- Search Section --}}
        <section class="bg-white rounded-2xl border border-slate-200 shadow-sm mb-6">

            <div class="p-6">

                <form
                    action="{{ route('admin.students.index') }}"
                    method="GET"
                    class="flex flex-col sm:flex-row gap-3"
                >

                    <div class="flex-1">

                        <label for="search" class="sr-only">
                            Search students
                        </label>

                        <div class="relative">

                            <i
                                data-lucide="search"
                                class="w-5 h-5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2"
                            ></i>

                            <input
                                type="search"
                                id="search"
                                name="search"
                                value="{{ $search ?? '' }}"
                                placeholder="Search by student name or email..."
                                class="w-full pl-10 pr-4 py-3 border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                            >

                        </div>

                    </div>

                    <button
                        type="submit"
                        class="px-6 py-3 bg-blue-600 text-white rounded-xl font-medium hover:bg-blue-700 transition"
                    >
                        Search
                    </button>

                    @if(!empty($search))

                        <a
                            href="{{ route('admin.students.index') }}"
                            class="px-6 py-3 bg-slate-100 text-slate-700 rounded-xl font-medium hover:bg-slate-200 text-center transition"
                        >
                            Clear
                        </a>

                    @endif

                </form>

            </div>

        </section>


        {{-- Student Table --}}
        <section class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

            <div class="p-6 border-b border-slate-200">

                <h2 class="text-lg font-semibold text-slate-900">
                    Registered Students
                </h2>

                <p class="text-sm text-slate-500 mt-1">
                    @if(!empty($search))
                        Search results for "{{ $search }}"
                    @else
                        All student accounts registered in the system.
                    @endif
                </p>

            </div>


            <div class="overflow-x-auto">

                <table class="w-full text-left">

                    <thead class="bg-slate-50 border-b border-slate-200">

                        <tr>

                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Student
                            </th>

                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Email Address
                            </th>

                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Registered
                            </th>

                            <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">
                                Account Type
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-slate-100">

                        @forelse($students as $student)

                            <tr class="hover:bg-slate-50 transition">

                                {{-- Student Name --}}
                                <td class="px-6 py-4">

                                    <div class="flex items-center gap-3">

                                        <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-semibold flex-shrink-0">

                                            {{ strtoupper(substr($student->name, 0, 1)) }}

                                        </div>

                                        <div>

                                            <p class="font-medium text-slate-900">
                                                {{ $student->name }}
                                            </p>

                                            <p class="text-xs text-slate-400 mt-1">
                                                Student ID: {{ $student->id }}
                                            </p>

                                        </div>

                                    </div>

                                </td>


                                {{-- Email --}}
                                <td class="px-6 py-4">

                                    <span class="text-sm text-slate-600">
                                        {{ $student->email }}
                                    </span>

                                </td>


                                {{-- Registration Date --}}
                                <td class="px-6 py-4">

                                    <span class="text-sm text-slate-600">
                                        {{ $student->created_at?->format('M d, Y') ?? 'N/A' }}
                                    </span>

                                </td>


                                {{-- Role --}}
                                <td class="px-6 py-4 text-right">

                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">
                                        Student
                                    </span>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="4" class="px-6 py-16 text-center">

                                    <div class="flex flex-col items-center">

                                        <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-4">

                                            <i
                                                data-lucide="users"
                                                class="w-8 h-8 text-slate-400"
                                            ></i>

                                        </div>

                                        <h3 class="font-semibold text-slate-800">
                                            No students found
                                        </h3>

                                        <p class="text-sm text-slate-500 mt-2 max-w-sm">
                                            @if(!empty($search))
                                                No students match your search. Try a different name or email address.
                                            @else
                                                There are currently no registered student accounts.
                                            @endif
                                        </p>

                                        @if(!empty($search))

                                            <a
                                                href="{{ route('admin.students.index') }}"
                                                class="mt-4 text-sm text-blue-600 hover:text-blue-700 font-medium"
                                            >
                                                View all students
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
            @if($students->hasPages())

                <div class="p-6 border-t border-slate-200">

                    {{ $students->links() }}

                </div>

            @endif

        </section>


        {{-- Results Information --}}
        @if($students->total() > 0)

            <div class="mt-4 text-sm text-slate-500">

                Showing
                <span class="font-medium text-slate-700">
                    {{ $students->firstItem() }}–{{ $students->lastItem() }}
                </span>

                of

                <span class="font-medium text-slate-700">
                    {{ $students->total() }}
                </span>

                students

            </div>

        @endif

    </div>

</div>

@endsection