<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="theme-color" content="#2563eb">

    <title>Login - Smart Student</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50 flex items-center justify-center p-4 sm:p-6">

    <main class="w-full max-w-md">

        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-6 sm:p-9">

            <div class="text-center mb-8">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-600 text-white text-2xl font-bold shadow-md">
                    SS
                </div>

                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">
                    Smart Student
                </h1>

                <p class="text-slate-500 mt-2 text-sm sm:text-base">
                    Welcome back! Sign in to continue.
                </p>
            </div>

            @if ($errors->any())
                <div role="alert" class="mb-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                    <p class="font-semibold mb-1">Unable to sign in</p>

                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('status'))
                <div role="status" class="mb-5 rounded-xl border border-green-200 bg-green-50 p-4 text-sm text-green-700">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="login" class="block text-sm font-semibold text-slate-700 mb-2">
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
                        class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-base text-slate-900 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                    >
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-2">
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
                            class="w-full rounded-xl border border-slate-300 bg-white px-4 py-3 pr-20 text-base text-slate-900 outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                        >

                        <button
                            type="button"
                            data-toggle-password="password"
                            aria-label="Show password"
                            aria-pressed="false"
                            class="absolute inset-y-0 right-0 px-4 text-sm font-semibold text-blue-600"
                        >
                            Show
                        </button>
                    </div>
                </div>

                <button
                    type="submit"
                    class="w-full rounded-xl bg-blue-600 px-4 py-3.5 text-base font-semibold text-white shadow-sm transition hover:bg-blue-700 active:scale-[0.99] focus:outline-none focus:ring-4 focus:ring-blue-200"
                >
                    Sign In
                </button>
            </form>

            <div class="my-6 flex items-center gap-3">
                <div class="h-px flex-1 bg-slate-200"></div>
                <span class="text-xs text-slate-400">NEW TO SMART STUDENT?</span>
                <div class="h-px flex-1 bg-slate-200"></div>
            </div>

            <a
                href="{{ route('register') }}"
                class="block w-full rounded-xl border border-blue-200 bg-blue-50 px-4 py-3 text-center font-semibold text-blue-700 transition hover:bg-blue-100 focus:outline-none focus:ring-4 focus:ring-blue-100"
            >
                Create an Account
            </a>

        </div>

        <p class="mt-5 text-center text-xs text-slate-400">
            Smart Student Task and Notification System
        </p>

    </main>

    <script>
        document.querySelectorAll('[data-toggle-password]').forEach(button => {
            button.addEventListener('click', () => {
                const input = document.getElementById(button.dataset.togglePassword);
                const showing = input.type === 'text';

                input.type = showing ? 'password' : 'text';
                button.textContent = showing ? 'Show' : 'Hide';
                button.setAttribute('aria-label', showing ? 'Show password' : 'Hide password');
                button.setAttribute('aria-pressed', String(!showing));
            });
        });
    </script>

</body>
</html>