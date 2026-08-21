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
           class="relative text-slate-500 hover:text-slate-800 text-xl">

            🔔

            <span class="absolute -top-1 -right-2 bg-red-500 text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center">
                3
            </span>

        </a>


        <!-- User -->
        <div class="flex items-center gap-3">

            <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-semibold">
                EJ
            </div>

            <div class="hidden sm:block">

                <p class="text-sm font-semibold text-slate-800">
                    EJ Ricalde
                </p>

                <p class="text-xs text-slate-500">
                    Student
                </p>

            </div>

        </div>

    </div>

</header>