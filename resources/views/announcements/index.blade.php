@extends('layouts.app')

@section('title', 'Announcements')

@section('page-heading', 'Announcements')

@section('content')

<div class="p-6 lg:p-8">

    <div class="max-w-5xl mx-auto">

        <div class="flex items-center justify-between mb-8">

            <div>
                <h1 class="text-2xl font-bold text-slate-900">
                    Announcements
                </h1>

                <p class="text-slate-500 mt-1">
                    Stay updated with the latest school information.
                </p>
            </div>

            <a href="{{ route('announcements.create') }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl font-medium">

                + New Announcement

            </a>

        </div>


        @if(session('success'))

            <div class="mb-6 bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-xl">

                {{ session('success') }}

            </div>

        @endif


        <div class="space-y-4">

            @forelse($announcements as $announcement)

                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">

                    <div class="flex items-start justify-between gap-6">

                        <div>

                            <div class="flex items-center gap-2 mb-3">

                                @if($announcement->is_pinned)

                                    <span class="text-xs bg-red-100 text-red-700 px-3 py-1 rounded-full">
                                        <i data-lucide="pin" class="w-4 h-4"></i> Pinned
                                    </span>

                                @endif

                                <span class="text-xs bg-blue-100 text-blue-700 px-3 py-1 rounded-full">
                                    {{ $announcement->category }}
                                </span>

                            </div>


                            <h2 class="text-xl font-semibold text-slate-900">

                                {{ $announcement->title }}

                            </h2>


                            <p class="text-slate-600 mt-3">

                                {{ Str::limit($announcement->content, 180) }}

                            </p>


                            <p class="text-xs text-slate-400 mt-4">

                                Posted {{ $announcement->created_at->diffForHumans() }}

                            </p>

                        </div>


                        <a href="{{ route('announcements.show', $announcement) }}"
                           class="text-blue-600 hover:text-blue-700 font-medium text-sm">

                            View →

                        </a>

                    </div>

                </div>

            @empty

                <div class="bg-white border border-slate-200 rounded-2xl p-12 text-center">

                    <div class="flex justify-center mb-4">
                        <i data-lucide="megaphone" class="w-10 h-10 text-slate-400"></i>
                    </div>

                    <h3 class="text-lg font-semibold text-slate-800">
                        No announcements yet
                    </h3>

                    <p class="text-slate-500 mt-1">
                        Important school updates will appear here.
                    </p>

                </div>

            @endforelse

        </div>

    </div>

</div>

@endsection