@auth
@php
    $user = auth()->user();

    $tabs = [
        [
            'label' => 'Home',
            'icon' => 'house',
            'route' => 'dashboard',
            'active' => request()->routeIs('dashboard', 'dashboard.index'),
        ],
        [
            'label' => $user->isStudent() ? 'My Tasks' : 'Tasks',
            'icon' => 'clipboard-list',
            'route' => 'tasks.index',
            'active' => request()->routeIs('tasks.*'),
        ],
        [
            'label' => 'Calendar',
            'icon' => 'calendar-days',
            'route' => 'calendar.index',
            'active' => request()->routeIs('calendar.*'),
        ],
        [
            'label' => 'News',
            'icon' => 'megaphone',
            'route' => 'announcements.index',
            'active' => request()->routeIs('announcements.*'),
        ],
    ];
@endphp

{{-- Animation for the active navigation icon --}}
<style>
    @keyframes nav-icon-pop {
        0% {
            transform: scale(0.8);
        }

        65% {
            transform: scale(1.2);
        }

        100% {
            transform: scale(1);
        }
    }

    .nav-icon-active {
        animation: nav-icon-pop 350ms cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    @media (prefers-reduced-motion: reduce) {
        .nav-icon-active {
            animation: none;
        }
    }
</style>

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
                aria-label="{{ $tab['label'] }}"
                title="{{ $tab['label'] }}"
                @if($tab['active']) aria-current="page" @endif
                class="group flex flex-1 items-center justify-center
                       rounded-xl py-1.5 transition-colors duration-200
                       {{ $tab['active']
                            ? 'text-blue-600'
                            : 'text-slate-500 hover:text-blue-600' }}"
            >
                <span
                    class="flex h-12 w-14 items-center justify-center
                           rounded-2xl transition-all duration-300 ease-out
                           {{ $tab['active']
                                ? 'bg-blue-100'
                                : 'bg-transparent group-hover:bg-slate-100' }}"
                >
                    <i
                        data-lucide="{{ $tab['icon'] }}"
                        class="{{ $tab['active']
                            ? 'nav-icon-active h-7 w-7'
                            : 'h-6 w-6' }}
                            transition-transform duration-300 ease-out"
                    ></i>
                </span>
            </a>
        @endforeach

        {{-- More Menu Button --}}
        <button
            type="button"
            id="open-more-menu"
            class="group flex flex-1 items-center justify-center
                   rounded-xl py-1.5 text-slate-500
                   hover:text-blue-600 transition-colors duration-200"
            aria-label="Open more navigation options"
            aria-controls="mobile-more-menu"
            aria-expanded="false"
            title="More"
        >
            <span
                class="flex h-12 w-14 items-center justify-center
                       rounded-2xl bg-transparent
                       transition-all duration-300 ease-out
                       group-hover:bg-slate-100"
            >
                <i data-lucide="menu" class="h-6 w-6"></i>
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
        <a
            href="{{ route('dashboard') }}"
            class="flex items-center gap-3 rounded-xl p-3 hover:bg-slate-100"
        >
            <i data-lucide="layout-dashboard" class="h-5 w-5 text-blue-600"></i>
            <span>Dashboard</span>
        </a>
        {{-- Account Settings --}}
        <a
            href="{{ route('settings.index') }}"
            class="flex items-center gap-3 rounded-xl p-3
                {{ request()->routeIs('settings.*')
                        ? 'bg-blue-50 text-blue-700'
                        : 'text-slate-700 hover:bg-slate-100' }}"
            @if(request()->routeIs('settings.*')) aria-current="page" @endif
        >
            <i data-lucide="settings" class="h-5 w-5 text-blue-600"></i>
            <span class="font-medium">Account Settings</span>
        </a>

        {{-- Admin Management --}}
        @if($user->role === 'admin')

            <a
                href="{{ route('admin.students.index') }}"
                class="flex items-center gap-3 rounded-xl p-3 hover:bg-slate-100"
            >
                <i data-lucide="users" class="h-5 w-5 text-blue-600"></i>
                <span>Manage Students</span>
            </a>

            <a
                href="{{ route('admin.teachers.index') }}"
                class="flex items-center gap-3 rounded-xl p-3 hover:bg-slate-100"
            >
                <i data-lucide="graduation-cap" class="h-5 w-5 text-blue-600"></i>
                <span>Manage Teachers</span>
            </a>

            <a
                href="{{ route('admin.classes.index') }}"
                class="flex items-center gap-3 rounded-xl p-3 hover:bg-slate-100"
            >
                <i data-lucide="school" class="h-5 w-5 text-blue-600"></i>
                <span>Manage Classes</span>
            </a>

        @endif

    </div>
</div>
@endauth