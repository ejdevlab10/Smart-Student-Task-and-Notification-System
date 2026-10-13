@extends('layouts.app')

@section('title', 'Create Class')
@section('page-heading', 'Create Class')

@section('content')

<div class="space-y-6 px-4 sm:px-6 lg:px-8 py-6 max-w-4xl mx-auto">

    {{-- Page Header --}}
    <div>
        <a href="{{ route('admin.classes.index') }}"
           class="inline-flex items-center gap-2 text-sm text-slate-500
                  hover:text-blue-600 transition mb-4">

            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Back to Classes

        </a>

        <h1 class="text-2xl font-bold text-slate-800">
            Create Class
        </h1>

        <p class="text-slate-500 mt-1">
            Register a new academic class and assign its teacher.
        </p>
    </div>


    {{-- Form --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8">

        <form action="{{ route('admin.classes.store') }}"
              method="POST"
              class="space-y-6">

            @csrf

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
                    value="{{ old('name') }}"
                    placeholder="e.g. BSIT 1"
                    required
                    maxlength="255"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3
                           text-sm focus:ring-2 focus:ring-blue-500
                           focus:border-blue-500 @error('name') border-red-500 @enderror"
                >

                @error('name')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                @enderror
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
                    value="{{ old('section') }}"
                    placeholder="e.g. A"
                    maxlength="100"
                    class="w-full rounded-xl border border-slate-300 px-4 py-3
                           text-sm focus:ring-2 focus:ring-blue-500
                           focus:border-blue-500 @error('section') border-red-500 @enderror"
                >

                @error('section')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>


            {{-- Automatic Academic Year --}}
            <div>
                <label for="academic_year"
                    class="block text-sm font-medium text-slate-700 mb-2">

                    Academic Year

                </label>

                <input
                    type="text"
                    id="academic_year"
                    value="{{ now()->year }}-{{ now()->year + 1 }}"
                    readonly
                    class="w-full rounded-xl border border-slate-300 bg-slate-100
                        px-4 py-3 text-sm text-slate-600 cursor-not-allowed"
                >

                <p class="text-xs text-slate-500 mt-2">
                    <i data-lucide="info" class="inline w-3.5 h-3.5"></i>
                    The academic year is generated automatically and cannot be changed.
                </p>
            </div>


            {{-- Teacher --}}
            <div>
                <label for="teacher_id"
                       class="block text-sm font-medium text-slate-700 mb-2">

                    Assigned Teacher

                </label>

                <select
                    id="teacher_id"
                    name="teacher_id"
                    class="w-full rounded-xl border border-slate-300 bg-white
                           px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500
                           focus:border-blue-500 @error('teacher_id') border-red-500 @enderror"
                >

                    <option value="">No teacher assigned</option>

                    @foreach($teachers as $teacher)

                        <option value="{{ $teacher->id }}"
                            @selected(old('teacher_id') == $teacher->id)>

                            {{ $teacher->name }} — {{ $teacher->email }}

                        </option>

                    @endforeach

                </select>

                @error('teacher_id')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                @enderror

                @if($teachers->isEmpty())
                    <p class="text-sm text-amber-600 mt-2">
                        No teacher accounts are registered yet. You can create
                        the class now and assign a teacher later.
                    </p>
                @endif

            </div>


            {{-- Actions --}}
            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 pt-4
                        border-t border-slate-200">

                <a href="{{ route('admin.classes.index') }}"
                   class="inline-flex items-center justify-center px-5 py-3
                          rounded-xl border border-slate-300 text-slate-700
                          hover:bg-slate-50 font-medium transition">

                    Cancel

                </a>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2
                           bg-blue-600 hover:bg-blue-700 text-white px-5 py-3
                           rounded-xl font-medium transition">

                    <i data-lucide="save" class="w-5 h-5"></i>
                    Create Class

                </button>

            </div>

        </form>

    </div>

</div>

@endsection