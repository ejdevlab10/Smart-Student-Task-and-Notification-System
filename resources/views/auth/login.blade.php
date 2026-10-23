<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#2563eb">

    <title>Sign In | Smart Student</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-100 text-slate-800 antialiased">

    <main class="flex min-h-screen items-center justify-center px-4 py-10 sm:px-6">

        <div class="w-full max-w-md">

            {{-- Login Card --}}
            <section class="rounded-2xl border border-slate-200 bg-white shadow-lg shadow-slate-200/60">

                {{-- Header --}}
                <div class="px-6 pb-6 pt-9 text-center sm:px-9 sm:pt-10">

                    <h1 class="text-3xl font-bold tracking-tight text-slate-900">
                        Smart Student
                    </h1>

                    <p class="mt-3 text-sm leading-6 text-slate-500">
                        Welcome back! Sign in to manage your academic tasks.
                    </p>

                </div>

                <div class="px-6 pb-8 sm:px-9 sm:pb-9">

                    {{-- Validation Errors --}}
                    @if ($errors->any())
                        <div
                            role="alert"
                            class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700"
                        >
                            <p class="mb-1 font-semibold">Unable to sign in</p>

                            <ul class="list-inside list-disc space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- Status Message --}}
                    @if (session('status'))
                        <div
                            role="status"
                            class="mb-5 rounded-xl border border-green-200 bg-green-50 p-4 text-sm leading-5 text-green-700"
                        >
                            {{ session('status') }}
                        </div>
                    @endif

                    {{-- Login Form --}}
                    <form
                        method="POST"
                        action="{{ route('login') }}"
                        class="space-y-5"
                    >
                        @csrf

                        {{-- Email or Student ID --}}
                        <div>
                            <label
                                for="login"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Email or Student ID
                            </label>

                            <input
                                id="login"
                                type="text"
                                name="login"
                                value="{{ old('login') }}"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="Enter your email or Student ID"
                                class="block w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3.5 text-base text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100"
                            >
                        </div>

                        {{-- Password --}}
                        <div>
                            <label
                                for="password"
                                class="mb-2 block text-sm font-semibold text-slate-700"
                            >
                                Password
                            </label>

                            <div class="relative">
                                <input
                                    id="password"
                                    type="password"
                                    name="password"
                                    required
                                    autocomplete="current-password"
                                    placeholder="Enter your password"
                                    class="block w-full rounded-xl border border-slate-300 bg-slate-50 px-4 py-3.5 pr-20 text-base text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-100"
                                >

                                <button
                                    type="button"
                                    data-toggle-password="password"
                                    aria-label="Show password"
                                    aria-pressed="false"
                                    class="absolute inset-y-0 right-0 rounded-r-xl px-4 text-sm font-semibold text-blue-600 transition hover:text-blue-800 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500"
                                >
                                    Show
                                </button>
                            </div>
                        </div>

                        {{-- Sign In --}}
                        <button
                            type="submit"
                            class="flex w-full items-center justify-center rounded-xl bg-blue-600 px-4 py-3.5 text-base font-semibold text-white shadow-sm transition hover:bg-blue-700 active:scale-[0.99] focus:outline-none focus:ring-4 focus:ring-blue-200"
                        >
                            Sign In
                        </button>

                    </form>

                    {{-- Registration Divider --}}
                    <div class="my-7 flex items-center gap-4">
                        <div class="h-px flex-1 bg-slate-200"></div>

                        <span class="whitespace-nowrap text-[11px] font-semibold tracking-wider text-slate-400">
                            NEW TO SMART STUDENT?
                        </span>

                        <div class="h-px flex-1 bg-slate-200"></div>
                    </div>

                    {{-- Registration Link --}}
                    <a
                        href="{{ route('register') }}"
                        class="flex w-full items-center justify-center rounded-xl border border-slate-300 bg-white px-4 py-3.5 text-sm font-semibold text-slate-700 transition hover:border-blue-300 hover:bg-blue-50 hover:text-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-100"
                    >
                        Create an Account
                    </a>

                </div>
            </section>

            {{-- Footer --}}
            <footer class="mt-6 space-y-1 text-center">
                <p class="text-xs font-medium text-slate-500">
                    Smart Student Task and Notification System
                </p>

                <p class="text-xs text-slate-400">
                    Stay organized. Stay on track.
                </p>
            </footer>

        </div>

    </main>

    {{-- Password Visibility Toggle --}}
    <script>
        document.querySelectorAll('[data-toggle-password]').forEach(function (button) {
            button.addEventListener('click', function () {
                const input = document.getElementById(
                    button.dataset.togglePassword
                );

                if (!input) {
                    return;
                }

                const showing = input.type === 'text';

                input.type = showing ? 'password' : 'text';

                button.textContent = showing ? 'Show' : 'Hide';

                button.setAttribute(
                    'aria-label',
                    showing ? 'Show password' : 'Hide password'
                );

                button.setAttribute('aria-pressed', String(!showing));
            });
        });
    </script>

</body>
</html>