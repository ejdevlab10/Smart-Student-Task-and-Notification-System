@extends('layouts.app')

@section('title', $announcement->title)

@section('page-heading', 'Announcement')

@section('content')

<div class="p-6 lg:p-8">

    <div class="max-w-4xl mx-auto">

        <a href="{{ route('announcements.index') }}"
           class="inline-flex items-center gap-2 text-sm text-blue-600 hover:text-blue-700">

            <i data-lucide="arrow-left" class="w-4 h-4"></i>

            Back to Announcements

        </a>


        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm mt-6 overflow-hidden">

            <!-- Header -->
            <div class="p-8 border-b border-slate-200">

                <div class="flex items-center gap-2 mb-4">

                    @if($announcement->is_pinned)

                        <span class="inline-flex items-center gap-1 text-xs bg-red-100 text-red-700 px-3 py-1 rounded-full">

                            <i data-lucide="pin" class="w-3 h-3"></i>

                            Pinned

                        </span>

                    @endif


                    <span class="text-xs bg-blue-100 text-blue-700 px-3 py-1 rounded-full">

                        {{ $announcement->category }}

                    </span>

                </div>


                <h1 class="text-3xl font-bold text-slate-900">

                    {{ $announcement->title }}

                </h1>


                <p class="text-sm text-slate-500 mt-3">

                    Posted {{ $announcement->created_at->format('F d, Y \a\t h:i A') }}

                </p>

            </div>


            <!-- Content -->
            <div class="p-8">

                <div class="text-slate-700 leading-7 whitespace-pre-line">

                    {{ $announcement->content }}

                </div>

            </div>


            <!-- Actions -->
            <div class="px-8 py-5 bg-slate-50 border-t border-slate-200 flex flex-wrap gap-3">

                <a href="{{ route('announcements.edit', $announcement) }}"
                   class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl font-medium">

                    <i data-lucide="pencil" class="w-4 h-4"></i>

                    Edit

                </a>


                <form action="{{ route('announcements.destroy', $announcement) }}"
                      method="POST"
                      onsubmit="return confirm('Are you sure you want to delete this announcement?');">

                    @csrf

                    @method('DELETE')

                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-5 py-3 rounded-xl font-medium">

                        <i data-lucide="trash-2" class="w-4 h-4"></i>

                        Delete

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

@endsection