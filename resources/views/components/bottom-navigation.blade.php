@auth
@php
    $user = auth()->user();

    $tabs = [
        [
            'label' => 'Home',
            'icon' => 'house',
            'route' => 'dashboard',
            'active' => request()->routeIs('dashboard'),
        ],
        [
            'label' => 'Calendar',
            'icon' => 'calendar-days',
            'route' => 'calendar.index',
            'active' => request()->routeIs('calendar.*'),
        ],
        [
            'label' => $user->isStudent() ? 'My Tasks' : 'Tasks',
            'icon' => 'clipboard-list',
            'route' => 'tasks.index',
            'active' => request()->routeIs('tasks.*'),
        ],
        [
            'label' => 'News',
            'icon' => 'megaphone',
            'route' => 'announcements.index',
            'active' => request()->routeIs('announcements.*'),
        ],
    ];
@endphp

{{-- Mobile Bottom Navigation --}}
<nav
    class="fixed bottom-0 inset-x-0 z-50 border-t border-slate-200
           bg-white/95 backdrop-blur-lg shadow-lg lg:hidden"
    aria-label="Mobile navigation"
>
    <div class="flex items-center justify-around px-2 pt-2 pb-[max(0.5rem,env(safe-area-inset-bottom))]">

        @foreach($tabs as $tab)
            <a
                href="{{ route($tab['route']) }}"
                class="flex flex-1 flex-col items-center justify-center gap-1
                       rounded-xl px-1 py-2 transition-colors
                       {{ $tab['active']
                            ? 'text-blue-600 bg-blue-50'
                            : 'text-slate-500 hover:text-blue-600 hover:bg-slate-50' }}"
                @if($tab['active']) aria-current="page" @endif
            >
                <i data-lucide="{{ $tab['icon'] }}" class="h-5 w-5"></i>

                <span class="text-[10px] font-semibold">
                    {{ $tab['label'] }}
                </span>
            </a>
        @endforeach

        {{-- More Menu --}}
        <button
            type="button"
            id="open-more-menu"
            class="flex flex-1 flex-col items-center justify-center gap-1
                   rounded-xl px-1 py-2 text-slate-500
                   hover:text-blue-600 hover:bg-slate-50"
            aria-label="Open more navigation options"
            aria-controls="mobile-more-menu"
            aria-expanded="false"
        >
            <i data-lucide="menu" class="h-5 w-5"></i>

            <span class="text-[10px] font-semibold">
                More
            </span>
        </button>

    </div>
</nav>

{{-- More Menu Overlay --}}
<div
    id="more-menu-overlay"
    class="fixed inset-0 z-[55] hidden bg-black/40 lg:hidden"
    aria-hidden="true"
></div>

{{-- More Menu Panel --}}
<div
    id="mobile-more-menu"
    class="fixed inset-x-0 bottom-0 z-[60] hidden rounded-t-3xl
           bg-white p-5 pb-[max(1.5rem,env(safe-area-inset-bottom))]
           shadow-2xl lg:hidden"
    role="dialog"
    aria-modal="true"
    aria-label="More navigation options"
>

    <div class="mb-5 flex items-center justify-between">

        <div>
            <h3 class="text-lg font-bold text-slate-800">
                More Options
            </h3>

            <p class="text-xs text-slate-500">
                {{ ucfirst($user->role) }} Account
            </p>
        </div>

        <button
            type="button"
            id="close-more-menu"
            class="rounded-lg p-2 text-slate-500 hover:bg-slate-100"
            aria-label="Close more options"
        >
            <i data-lucide="x" class="h-5 w-5"></i>
        </button>

    </div>

    <div class="max-h-[55vh] space-y-2 overflow-y-auto">

        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3 rounded-xl p-3 hover:bg-slate-100">
            <i data-lucide="layout-dashboard" class="h-5 w-5 text-blue-600"></i>
            <span>Dashboard</span>
        </a>

        {{-- Notifications --}}
        <a href="{{ route('notifications.index') }}"
           class="flex items-center gap-3 rounded-xl p-3 hover:bg-slate-100">
            <i data-lucide="bell" class="h-5 w-5 text-blue-600"></i>
            <span>Notifications</span>
        </a>

        {{-- Admin Management --}}
        @if($user->role === 'admin')

            <a href="{{ route('admin.students.index') }}"
               class="flex items-center gap-3 rounded-xl p-3 hover:bg-slate-100">
                <i data-lucide="users" class="h-5 w-5 text-blue-600"></i>
                <span>Manage Students</span>
            </a>

            <a href="{{ route('admin.teachers.index') }}"
               class="flex items-center gap-3 rounded-xl p-3 hover:bg-slate-100">
                <i data-lucide="graduation-cap" class="h-5 w-5 text-blue-600"></i>
                <span>Manage Teachers</span>
            </a>

            <a href="{{ route('admin.classes.index') }}"
               class="flex items-center gap-3 rounded-xl p-3 hover:bg-slate-100">
                <i data-lucide="school" class="h-5 w-5 text-blue-600"></i>
                <span>Manage Classes</span>
            </a>

        @endif

        {{-- Logout --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button
                type="submit"
                class="flex w-full items-center gap-3 rounded-xl p-3
                       text-red-600 hover:bg-red-50"
            >
                <i data-lucide="log-out" class="h-5 w-5"></i>
                <span>Logout</span>
            </button>
        </form>

    </div>
</div>
@endauth