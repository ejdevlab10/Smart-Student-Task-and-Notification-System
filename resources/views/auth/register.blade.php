<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="theme-color" content="#2563eb">

    <title>Register - Smart Student</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50 flex items-center justify-center p-4 sm:p-6">

    <main class="w-full max-w-lg">

        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-lg sm:p-9">

            <div class="text-center mb-8">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-600 text-2xl font-bold text-white shadow-md">
                    SS
                </div>

                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900">
                    Join Smart Student
                </h1>

                <p class="mt-2 text-sm sm:text-base text-slate-500">
                    Select the account type that applies to you.
                </p>
            </div>

            <div class="space-y-4">

                <a
                    href="{{ route('register.create', ['role' => 'student']) }}"
                    class="group flex items-center gap-4 rounded-2xl border border-slate-200 p-4 transition hover:border-blue-400 hover:bg-blue-50 focus:outline-none focus:ring-4 focus:ring-blue-100"
                >
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-2xl">
                        🎓
                    </div>

                    <div class="min-w-0 flex-1">
                        <h2 class="font-bold text-slate-900">
                            Student Registration
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Register using your Student ID and personal details.
                        </p>
                    </div>

                    <span class="text-xl text-slate-400 transition group-hover:translate-x-1 group-hover:text-blue-600" aria-hidden="true">
                        →
                    </span>
                </a>

                <a
                    href="{{ route('register.create', ['role' => 'teacher']) }}"
                    class="group flex items-center gap-4 rounded-2xl border border-slate-200 p-4 transition hover:border-green-400 hover:bg-green-50 focus:outline-none focus:ring-4 focus:ring-green-100"
                >
                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-green-100 text-2xl">
                        👨‍🏫
                    </div>

                    <div class="min-w-0 flex-1">
                        <h2 class="font-bold text-slate-900">
                            Teacher Registration
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Register using your Employee ID and school email.
                        </p>
                    </div>

                    <span class="text-xl text-slate-400 transition group-hover:translate-x-1 group-hover:text-green-600" aria-hidden="true">
                        →
                    </span>
                </a>

            </div>

            <div class="mt-6 rounded-xl bg-amber-50 p-4 text-sm text-amber-800">
                <p class="font-semibold">For teachers</p>
                <p class="mt-1">
                    New teacher accounts require administrator approval before login.
                </p>
            </div>

            <p class="mt-7 text-center text-sm text-slate-600">
                Already have an account?

                <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:underline">
                    Sign in
                </a>
            </p>

        </div>

    </main>

</body>
</html>