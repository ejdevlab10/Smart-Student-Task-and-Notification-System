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
        <x-sidebar />

        <div class="min-h-screen lg:ml-64">

            <x-topbar />

            <main class="min-w-0">
                @yield('content')
            </main>

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
</body>
</html>