@extends('layouts.app')

@section('title', 'Teacher Management')
@section('page-heading', 'Teacher Management')

@section('content')

<div class="space-y-6 px-4 sm:px-6 lg:px-8 py-6 max-w-7xl mx-auto">

    {{-- Page Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                Teacher Management
            </h1>

            <p class="text-slate-500 mt-1">
                View and search registered teachers in the system.
            </p>
        </div>

    </div>


    {{-- Teacher Statistics --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6">

            <div class="flex items-center justify-between">

                <div>
                    <p class="text-sm text-slate-500">
                        Registered Teachers
                    </p>

                    <h2 class="text-3xl font-bold text-slate-800 mt-2">
                        {{ $teachers->total() }}
                    </h2>
                </div>

                <div class="bg-blue-100 text-blue-600 p-3 rounded-xl">
                    <i data-lucide="graduation-cap" class="w-6 h-6"></i>
                </div>

            </div>

        </div>

    </div>


    {{-- Teacher Directory --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

        {{-- Header and Search --}}
        <div class="p-6 border-b border-slate-200">

            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                <div>
                    <h2 class="text-lg font-semibold text-slate-800">
                        Teacher Directory
                    </h2>

                    <p class="text-sm text-slate-500 mt-1">
                        Browse registered teacher accounts.
                    </p>
                </div>

                <form method="GET"
                      action="{{ route('admin.teachers.index') }}"
                      class="flex flex-col sm:flex-row gap-2">

                    <input
                        type="text"
                        name="search"
                        value="{{ $search }}"
                        placeholder="Search name or email..."
                        class="w-full sm:w-64 rounded-xl border border-slate-300
                               px-4 py-2 text-sm focus:ring-2
                               focus:ring-blue-500 focus:border-blue-500"
                    >

                    <button
                        type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white
                               px-5 py-2 rounded-xl text-sm font-medium
                               transition">

                        Search

                    </button>

                    @if($search)
                        <a href="{{ route('admin.teachers.index') }}"
                           class="bg-slate-100 hover:bg-slate-200 text-slate-700
                                  px-5 py-2 rounded-xl text-sm font-medium
                                  text-center transition">

                            Clear

                        </a>
                    @endif

                </form>

            </div>

        </div>


        {{-- Table --}}
        <div class="overflow-x-auto">

            <table class="w-full text-left">

                <thead class="bg-slate-50 border-b border-slate-200">

                    <tr class="text-xs uppercase tracking-wider text-slate-500">

                        <th class="px-6 py-4 font-semibold">
                            Teacher
                        </th>

                        <th class="px-6 py-4 font-semibold">
                            Email Address
                        </th>

                        <th class="px-6 py-4 font-semibold">
                            Date Registered
                        </th>

                        <th class="px-6 py-4 font-semibold">
                            Role
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-slate-100">

                    @forelse($teachers as $teacher)

                        <tr class="hover:bg-slate-50 transition">

                            {{-- Teacher Name --}}
                            <td class="px-6 py-4">

                                <div class="flex items-center gap-3">

                                    <div class="w-10 h-10 rounded-full
                                                bg-blue-100 text-blue-600
                                                flex items-center justify-center
                                                font-semibold">

                                        {{ strtoupper(substr($teacher->name, 0, 1)) }}

                                    </div>

                                    <div>
                                        <p class="font-medium text-slate-800">
                                            {{ $teacher->name }}
                                        </p>

                                        <p class="text-xs text-slate-400">
                                            Teacher #{{ $teacher->id }}
                                        </p>
                                    </div>

                                </div>

                            </td>


                            {{-- Email --}}
                            <td class="px-6 py-4 text-sm text-slate-600">

                                {{ $teacher->email }}

                            </td>


                            {{-- Registration Date --}}
                            <td class="px-6 py-4 text-sm text-slate-600">

                                {{ $teacher->created_at?->format('M d, Y') ?? 'N/A' }}

                            </td>


                            {{-- Role --}}
                            <td class="px-6 py-4">

                                <span class="inline-flex items-center px-3 py-1
                                             rounded-full bg-blue-100
                                             text-blue-700 text-xs font-semibold">

                                    Teacher

                                </span>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="4"
                                class="px-6 py-14 text-center">

                                <div class="flex flex-col items-center">

                                    <i data-lucide="users"
                                       class="w-12 h-12 text-slate-300 mb-4"></i>

                                    <h3 class="text-lg font-semibold text-slate-700">
                                        No Teachers Found
                                    </h3>

                                    <p class="text-sm text-slate-500 mt-2">
                                        @if($search)
                                            No teachers match your search.
                                        @else
                                            There are no registered teachers yet.
                                        @endif
                                    </p>

                                </div>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- Pagination --}}
        @if($teachers->hasPages())

            <div class="px-6 py-4 border-t border-slate-200">

                {{ $teachers->links() }}

            </div>

        @endif


        {{-- Results Count --}}
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">

            <p class="text-sm text-slate-500">

                Showing {{ $teachers->firstItem() ?? 0 }}
                to {{ $teachers->lastItem() ?? 0 }}
                of {{ $teachers->total() }} teachers

            </p>

        </div>

    </div>

</div>

@endsection