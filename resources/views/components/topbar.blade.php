<header class="h-20 bg-white border-b border-slate-200 flex items-center justify-between px-6 lg:px-8">

<!-- Page Information -->
<div>

    <p class="text-sm text-slate-500">
        Smart Student
    </p>

    <h2 class="text-lg font-semibold text-slate-800">
        @yield('page-heading', 'Student Portal')
    </h2>

</div>


<!-- Right Side -->
<div class="flex items-center gap-5">

    <!-- Notifications -->
    <a href="#"
       class="relative text-slate-500 hover:text-slate-800">

        <i data-lucide="bell" class="w-6 h-6"></i>

        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center">
            3
        </span>

    </a>


    <!-- User -->
    <div class="flex items-center gap-3">

        <!-- User Initial -->
        <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold">

            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}

        </div>


        <!-- User Information -->
        <div class="hidden sm:block">

            <p class="text-sm font-semibold text-slate-800">
                {{ auth()->user()->name }}
            </p>

            <p class="text-xs text-slate-500">
                {{ ucfirst(auth()->user()->role) }}
            </p>

        </div>


        <!-- Logout -->
        <form method="POST" action="{{ route('logout') }}">

            @csrf

            <button
                type="submit"
                class="text-sm text-slate-500 hover:text-red-600 transition"
            >
                Logout
            </button>

        </form>

    </div>

</div>

</header>
