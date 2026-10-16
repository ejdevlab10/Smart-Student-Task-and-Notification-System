<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Smart Student') | Smart Student</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50 text-slate-800 antialiased">

    @auth
        {{-- Sidebar --}}
        <x-sidebar />

        {{-- Mobile Sidebar Overlay --}}
        <div
            id="sidebar-overlay"
            class="fixed inset-0 z-40 hidden bg-black/50 lg:hidden"
            aria-hidden="true"
        ></div>

        {{-- Main Application --}}
        <div class="min-h-screen lg:ml-64">

            {{-- Topbar --}}
            <x-topbar />

            {{-- Page Content --}}
            <main class="min-w-0">
                @yield('content')
            </main>

            {{-- Footer --}}
            <footer class="border-t border-slate-200 bg-white px-4 py-5 sm:px-6 lg:px-8">
                <div class="mx-auto flex max-w-7xl flex-col gap-1 text-center text-xs text-slate-500 sm:flex-row sm:items-center sm:justify-between sm:text-left">
                    <p>&copy; {{ date('Y') }} Smart Student. All rights reserved.</p>
                    <p>Academic Task and Notification System</p>
                </div>
            </footer>

        </div>
    @else
        <main class="min-h-screen">
            @yield('content')
        </main>
    @endauth

    @stack('scripts')

    {{-- Mobile Sidebar Controls --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.getElementById('mobile-sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const openButton = document.getElementById('open-sidebar');
            const closeButton = document.getElementById('close-sidebar');

            if (!sidebar || !overlay || !openButton || !closeButton) {
                return;
            }

            function openSidebar() {
                sidebar.classList.remove('-translate-x-full');
                overlay.classList.remove('hidden');

                document.body.classList.add('overflow-hidden');

                openButton.setAttribute('aria-expanded', 'true');
            }

            function closeSidebar() {
                sidebar.classList.add('-translate-x-full');
                overlay.classList.add('hidden');

                document.body.classList.remove('overflow-hidden');

                openButton.setAttribute('aria-expanded', 'false');
            }

            openButton.addEventListener('click', openSidebar);
            closeButton.addEventListener('click', closeSidebar);
            overlay.addEventListener('click', closeSidebar);

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    closeSidebar();
                }
            });

            window.addEventListener('resize', function () {
                if (window.innerWidth >= 1024) {
                    closeSidebar();
                }
            });
        });
    </script>

</body>
</html>