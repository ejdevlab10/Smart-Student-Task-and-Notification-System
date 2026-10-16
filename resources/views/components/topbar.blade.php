<header class="h-20 bg-white border-b border-slate-200 flex items-center justify-between px-4 sm:px-6 lg:px-8">

    {{-- Left Side --}}
    <div class="flex items-center gap-3">

        {{-- Mobile Menu Button --}}
        <button
            type="button"
            id="open-sidebar"
            class="lg:hidden inline-flex items-center justify-center p-2 rounded-lg
                   text-slate-600 hover:bg-slate-100 hover:text-slate-900"
            aria-label="Open navigation menu"
            aria-controls="mobile-sidebar"
            aria-expanded="false"
        >
            <i data-lucide="menu" class="w-6 h-6"></i>
        </button>

        {{-- Page Information --}}
        <div>
            <p class="text-sm text-slate-500">
                Smart Student
            </p>

            <h2 class="text-lg font-semibold text-slate-800">
                @yield('page-heading', 'Student Portal')
            </h2>
        </div>

    </div>

    {{-- Right Side --}}
    <div class="flex items-center gap-3 sm:gap-5">

        {{-- Notifications --}}
        <a
            href="{{ route('notifications.index') }}"
            class="relative text-slate-500 hover:text-slate-800"
            aria-label="Notifications"
        >
            <i data-lucide="bell" class="w-6 h-6"></i>

            @php
                $unreadNotifications = auth()->user()->unreadNotifications()->count();
            @endphp

            @if($unreadNotifications > 0)
                <span class="absolute -top-2 -right-2 bg-red-500 text-white
                             text-[10px] min-w-4 h-4 px-1 rounded-full
                             flex items-center justify-center">
                    {{ $unreadNotifications > 99 ? '99+' : $unreadNotifications }}
                </span>
            @endif
        </a>

        {{-- User Information --}}
        <div class="flex items-center gap-2 sm:gap-3">

            {{-- User Initial --}}
            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full bg-blue-600
                        text-white flex items-center justify-center font-semibold shrink-0">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>

            {{-- User Details --}}
            <div class="hidden sm:block">
                <p class="text-sm font-semibold text-slate-800">
                    {{ auth()->user()->name }}
                </p>

                <p class="text-xs text-slate-500">
                    {{ ucfirst(auth()->user()->role) }}
                </p>
            </div>

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button
                    type="submit"
                    class="text-xs sm:text-sm text-slate-500 hover:text-red-600 transition"
                >
                    Logout
                </button>
            </form>

        </div>

    </div>

</header>