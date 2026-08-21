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
        <a href="{{ url('/') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl
           {{ request()->is('/') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">

            <span>🏠</span>
            <span class="font-medium">Dashboard</span>

        </a>


        <!-- Tasks -->
        <a href="{{ route('tasks.index') }}"
           class="flex items-center gap-3 px-4 py-3 rounded-xl
           {{ request()->is('tasks*') ? 'bg-blue-600 text-white' : 'text-slate-300 hover:bg-slate-800' }}">

            <span>📚</span>
            <span class="font-medium">My Tasks</span>

        </a>


        <!-- Calendar -->
        <a href="#"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-300 hover:bg-slate-800">

            <span>📅</span>
            <span>Calendar</span>

        </a>


        <!-- Notifications -->
        <a href="#"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-300 hover:bg-slate-800">

            <span>🔔</span>
            <span>Notifications</span>

            <span class="ml-auto bg-red-500 text-white text-xs px-2 py-1 rounded-full">
                3
            </span>

        </a>


        <!-- Announcements -->
        <a href="#"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-300 hover:bg-slate-800">

            <span>📢</span>
            <span>Announcements</span>

        </a>

    </nav>


    <!-- Settings -->
    <div class="p-4 border-t border-slate-700">

        <a href="#"
           class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-300 hover:bg-slate-800">

            <span>⚙️</span>
            <span>Settings</span>

        </a>

    </div>

</aside>