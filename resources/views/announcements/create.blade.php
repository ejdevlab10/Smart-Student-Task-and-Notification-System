@extends('layouts.app')

@section('title', 'Create Announcement')

@section('page-heading', 'Create Announcement')

@section('content')

<div class="p-6 lg:p-8">

    <div class="max-w-3xl mx-auto">

        <a href="{{ route('announcements.index') }}"
           class="inline-flex items-center gap-2 text-sm text-blue-600 hover:text-blue-700">

            <i data-lucide="arrow-left" class="w-4 h-4"></i>

            Back to Announcements

        </a>


        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-8 mt-6">

            <div class="mb-8">

                <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center mb-4">

                    <i data-lucide="megaphone" class="w-6 h-6 text-blue-600"></i>

                </div>

                <h1 class="text-2xl font-bold text-slate-900">
                    Create Announcement
                </h1>

                <p class="text-slate-500 mt-1">
                    Share an important update with students.
                </p>

            </div>


            @if($errors->any())

                <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-4 mb-6">

                    <p class="font-medium mb-2">
                        Please correct the following:
                    </p>

                    <ul class="list-disc list-inside text-sm">

                        @foreach($errors->all() as $error)

                            <li>{{ $error }}</li>

                        @endforeach

                    </ul>

                </div>

            @endif


            <form action="{{ route('announcements.store') }}"
                  method="POST">

                @csrf


                <!-- Title -->
                <div class="mb-6">

                    <label for="title"
                           class="block text-sm font-medium text-slate-700 mb-2">

                        Announcement Title

                    </label>

                    <input
                        type="text"
                        id="title"
                        name="title"
                        value="{{ old('title') }}"
                        placeholder="e.g. Class Suspension Tomorrow"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                        required
                    >

                </div>


                <!-- Category -->
                <div class="mb-6">

                    <label for="category"
                           class="block text-sm font-medium text-slate-700 mb-2">

                        Category

                    </label>

                    <select
                        id="category"
                        name="category"
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                    >

                        <option value="General">
                            General
                        </option>

                        <option value="Academic">
                            Academic
                        </option>

                        <option value="Event">
                            Event
                        </option>

                        <option value="Important">
                            Important
                        </option>

                        <option value="Emergency">
                            Emergency
                        </option>

                    </select>

                </div>


                <!-- Content -->
                <div class="mb-6">

                    <label for="content"
                           class="block text-sm font-medium text-slate-700 mb-2">

                        Announcement Details

                    </label>

                    <textarea
                        id="content"
                        name="content"
                        rows="7"
                        placeholder="Write the announcement details here..."
                        class="w-full rounded-xl border border-slate-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none resize-none"
                        required
                    >{{ old('content') }}</textarea>

                </div>


                <!-- Pin -->
                <div class="mb-8">

                    <label class="flex items-center gap-3 cursor-pointer">

                        <input
                            type="checkbox"
                            name="is_pinned"
                            value="1"
                            class="w-4 h-4 text-blue-600 rounded border-slate-300"
                        >

                        <span>

                            <span class="block text-sm font-medium text-slate-700">
                                Pin this announcement
                            </span>

                            <span class="block text-xs text-slate-500 mt-1">
                                Pinned announcements appear at the top.
                            </span>

                        </span>

                    </label>

                </div>


                <!-- Buttons -->
                <div class="flex justify-end gap-3">

                    <a href="{{ route('announcements.index') }}"
                       class="px-5 py-3 rounded-xl border border-slate-300 text-slate-700 hover:bg-slate-50">

                        Cancel

                    </a>

                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-medium">

                        <i data-lucide="send" class="w-4 h-4"></i>

                        Post Announcement

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection