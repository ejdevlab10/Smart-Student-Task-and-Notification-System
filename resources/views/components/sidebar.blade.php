<aside class="fixed inset-y-0 left-0 z-40 w-64 bg-slate-900 text-white hidden lg:flex flex-col">

    <!-- Logo -->
    <div class="h-20 flex items-center px-6 border-b border-slate-700">

        <div>
            <h1 class="text-xl font-bold tracking-tight">
                Smart Student
            </h1>

            <p class="text-xs text-slate-400">
                Academic Task System
            </p>
        </div>

    </div>


    <!-- Navigation -->
    <nav class="flex-1 px-4 py-6 space-y-2">

        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl
           {{ request()->routeIs('dashboard') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">

            <i data-lucide="house" class="w-5 h-5"></i>

            <span class="font-medium">
                Dashboard
            </span>

        </a>


        {{-- ================= STUDENT ================= --}}
        @if(auth()->user()->isStudent())

            <!-- My Tasks -->
            <a href="{{ route('tasks.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl
               {{ request()->is('tasks*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">

                <i data-lucide="clipboard-list" class="w-5 h-5"></i>

                <span class="font-medium">
                    My Tasks
                </span>

            </a>


            <!-- Calendar -->
            <a href="#"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-300 hover:bg-slate-800">

                <i data-lucide="calendar-days" class="w-5 h-5"></i>

                <span>
                    Calendar
                </span>

            </a>


            <!-- Notifications -->
            <a href="#"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-300 hover:bg-slate-800">

                <i data-lucide="bell" class="w-5 h-5"></i>

                <span>
                    Notifications
                </span>

                <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                    3
                </span>

            </a>

        @endif


        {{-- ================= TEACHER ================= --}}
        @if(auth()->user()->isTeacher())

            <!-- My Classes -->
            <a href="#"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-300 hover:bg-slate-800">

                <i data-lucide="school" class="w-5 h-5"></i>

                <span class="font-medium">
                    My Classes
                </span>

            </a>


            <!-- Assignments -->
            <a href="{{ route('tasks.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl
               {{ request()->is('tasks*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">

                <i data-lucide="clipboard-list" class="w-5 h-5"></i>

                <span class="font-medium">
                    Assignments
                </span>

            </a>


            <!-- Calendar -->
            <a href="#"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-300 hover:bg-slate-800">

                <i data-lucide="calendar-days" class="w-5 h-5"></i>

                <span>
                    Calendar
                </span>

            </a>

        @endif


        {{-- ================= ADMIN ================= --}}
        @if(auth()->user()->role === 'admin')

            <!-- Students -->
            <a href="#"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-300 hover:bg-slate-800">

                <i data-lucide="users" class="w-5 h-5"></i>

                <span class="font-medium">
                    Students
                </span>

            </a>


            <!-- Teachers -->
            <a href="#"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-300 hover:bg-slate-800">

                <i data-lucide="graduation-cap" class="w-5 h-5"></i>

                <span class="font-medium">
                    Teachers
                </span>

            </a>


            <!-- Classes -->
            <a href="#"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-300 hover:bg-slate-800">

                <i data-lucide="school" class="w-5 h-5"></i>

                <span class="font-medium">
                    Classes
                </span>

            </a>


            <!-- Subjects -->
            <a href="#"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-300 hover:bg-slate-800">

                <i data-lucide="book-open" class="w-5 h-5"></i>

                <span class="font-medium">
                    Subjects
                </span>

            </a>


            <!-- Assignments -->
            <a href="{{ route('tasks.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl
               {{ request()->is('tasks*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">

                <i data-lucide="clipboard-list" class="w-5 h-5"></i>

                <span class="font-medium">
                    Assignments
                </span>

            </a>

        @endif


        <!-- Announcements -->
        <a href="{{ route('announcements.index') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl
           {{ request()->is('announcements*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">

            <i data-lucide="megaphone" class="w-5 h-5"></i>

            <span>
                Announcements
            </span>

        </a>

    </nav>


    <!-- Settings -->
    <div class="p-4 border-t border-slate-700">

        <a href="#"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-300 hover:bg-slate-800">

            <i data-lucide="settings" class="w-5 h-5"></i>

            <span>
                Settings
            </span>

        </a>

    </div>

</aside>