@extends('layouts.app')

@section('title', 'Notifications')

@section('page-heading', 'Notifications')

@section('content')

<div class="min-h-screen bg-slate-100">

    <div class="p-6 lg:p-8">

        <div class="mb-8">

            <h1 class="text-3xl font-bold text-slate-900">
                Notifications
            </h1>

            <p class="mt-2 text-slate-500">
                View your latest task assignments and reminders.
            </p>

        </div>


        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm">

            @forelse($notifications as $notification)

                <div class="p-6 border-b border-slate-100
                    {{ $notification->read_at ? '' : 'bg-blue-50' }}">

                    <div class="flex items-start justify-between gap-4">

                        {{-- Notification Information --}}
                        <div class="flex gap-4">

                            <div class="w-11 h-11 rounded-xl bg-blue-100
                                flex items-center justify-center">
                                🔔
                            </div>

                            <div>

                                <h3 class="font-semibold text-slate-900">
                                    {{ $notification->data['title'] ?? 'Notification' }}
                                </h3>

                                <p class="text-sm text-slate-600 mt-1">
                                    {{ $notification->data['message'] ?? '' }}
                                </p>

                                @if(isset($notification->data['due_date']))

                                    <p class="text-xs text-slate-400 mt-2">
                                        Due: {{ $notification->data['due_date'] }}
                                    </p>

                                @endif

                                <p class="text-xs text-slate-400 mt-2">
                                    {{ $notification->created_at->diffForHumans() }}
                                </p>

                            </div>

                        </div>


                        {{-- Notification Action --}}
                        <div>

                            @if(!empty($notification->data['task_id']))

                                {{-- Task Notification --}}
                                <form
                                    action="{{ route('notifications.read', $notification->id) }}"
                                    method="POST"
                                >

                                    @csrf
                                    @method('PATCH')

                                    <button
                                        type="submit"
                                        class="text-sm text-blue-600 hover:text-blue-700 font-medium"
                                    >
                                        View Task
                                    </button>

                                </form>

                            @else

                                {{-- Notification Without Task --}}
                                @if(!$notification->read_at)

                                    <form
                                        action="{{ route('notifications.read', $notification->id) }}"
                                        method="POST"
                                    >

                                        @csrf
                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="text-sm text-blue-600 hover:text-blue-700 font-medium"
                                        >
                                            Mark Read
                                        </button>

                                    </form>

                                @else

                                    <span class="text-xs text-slate-400">
                                        Read
                                    </span>

                                @endif

                            @endif

                        </div>

                    </div>

                </div>

            @empty

                <div class="p-10 text-center">

                    <div class="text-4xl mb-3">
                        🔔
                    </div>

                    <p class="text-slate-500">
                        You don't have any notifications yet.
                    </p>

                </div>

            @endforelse

        </div>

    </div>

</div>

@endsection