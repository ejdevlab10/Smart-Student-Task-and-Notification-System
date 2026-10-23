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

        {{-- Desktop Sidebar --}}
        <x-sidebar />

        {{-- Main Application --}}
        <div class="min-h-screen lg:ml-64">

            {{-- Topbar --}}
            <x-topbar />

            {{-- Page Content --}}
            <main class="min-w-0 pb-24 lg:pb-0">
                @yield('content')
            </main>

            {{-- Footer --}}
            <footer class="hidden border-t border-slate-200 bg-white
                           px-4 py-5 sm:px-6 lg:block lg:px-8">

                <div class="mx-auto flex max-w-7xl flex-col gap-1
                            text-center text-xs text-slate-500
                            sm:flex-row sm:items-center sm:justify-between sm:text-left">

                    <p>&copy; {{ date('Y') }} Smart Student. All rights reserved.</p>

                    <p>Academic Task and Notification System</p>

                </div>

            </footer>

        </div>

        {{-- Mobile Bottom Navigation --}}
        <x-bottom-navigation />

    @else

        <main class="min-h-screen">
            @yield('content')
        </main>

    @endauth

    @stack('scripts')

    {{-- Mobile More Menu Controls --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const openButton = document.getElementById('open-more-menu');
            const closeButton = document.getElementById('close-more-menu');
            const menu = document.getElementById('mobile-more-menu');
            const overlay = document.getElementById('more-menu-overlay');

            if (!openButton || !closeButton || !menu || !overlay) {
                return;
            }

            let previousFocus = null;

            function openMenu() {
                previousFocus = document.activeElement;

                menu.classList.remove('hidden');
                overlay.classList.remove('hidden');

                document.body.classList.add('overflow-hidden');
                openButton.setAttribute('aria-expanded', 'true');

                closeButton.focus();
            }

            function closeMenu() {
                menu.classList.add('hidden');
                overlay.classList.add('hidden');

                document.body.classList.remove('overflow-hidden');
                openButton.setAttribute('aria-expanded', 'false');

                if (previousFocus) {
                    previousFocus.focus();
                }
            }

            openButton.addEventListener('click', openMenu);
            closeButton.addEventListener('click', closeMenu);
            overlay.addEventListener('click', closeMenu);

            document.addEventListener('keydown', function (event) {
                if (menu.classList.contains('hidden')) {
                    return;
                }

                if (event.key === 'Escape') {
                    closeMenu();
                }

                if (event.key === 'Tab') {
                    const focusable = Array.from(
                        menu.querySelectorAll(
                            'a[href], button:not([disabled]), input:not([disabled])'
                        )
                    ).filter(element => element.getClientRects().length > 0);

                    if (!focusable.length) {
                        event.preventDefault();
                        return;
                    }

                    const first = focusable[0];
                    const last = focusable[focusable.length - 1];

                    if (event.shiftKey && document.activeElement === first) {
                        event.preventDefault();
                        last.focus();
                    } else if (!event.shiftKey && document.activeElement === last) {
                        event.preventDefault();
                        first.focus();
                    }
                }
            });

            window.addEventListener('resize', function () {
                if (window.innerWidth >= 1024 &&
                    !menu.classList.contains('hidden')) {
                    closeMenu();
                }
            });

        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const logoutAction = @json(route('logout'));

            document.querySelectorAll('form').forEach(function (form) {
                if (form.action !== logoutAction) {
                    return;
                }

                form.addEventListener('submit', function (event) {
                    // Stop duplicate submissions.
                    if (form.dataset.logoutSubmitting === 'true') {
                        event.preventDefault();
                        return;
                    }

                    // Ask for confirmation before logging out.
                    const confirmed = window.confirm(
                        'Are you sure you want to log out?'
                    );

                    if (!confirmed) {
                        event.preventDefault();
                        return;
                    }

                    // Prevent additional clicks after confirmation.
                    form.dataset.logoutSubmitting = 'true';

                    form.querySelectorAll('button[type="submit"], input[type="submit"]')
                        .forEach(function (button) {
                            button.disabled = true;
                            button.classList.add(
                                'opacity-60',
                                'cursor-not-allowed'
                            );
                        });
                });
            });
        });
    </script>

</body>
</html>