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

            {{-- Header --}}
            <div class="text-center mb-8">

                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-600 text-white shadow-md">
                    <i data-lucide="graduation-cap" class="h-9 w-9"></i>
                </div>

                <h1 class="text-2xl sm:text-3xl font-bold text-slate-900">
                    Join Smart Student
                </h1>

                <p class="mt-2 text-sm sm:text-base text-slate-500">
                    Select the account type that applies to you.
                </p>

            </div>

            {{-- Registration Options --}}
            <div class="space-y-4">

                {{-- Student Registration --}}
                <a
                    href="{{ route('register.create', ['role' => 'student']) }}"
                    class="group flex items-center gap-4 rounded-2xl border border-slate-200 p-4 transition duration-200 hover:border-blue-400 hover:bg-blue-50 focus:outline-none focus:ring-4 focus:ring-blue-100"
                >

                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-600 transition group-hover:bg-blue-200">
                        <i data-lucide="book-open" class="h-7 w-7"></i>
                    </div>

                    <div class="min-w-0 flex-1">

                        <h2 class="font-bold text-slate-900">
                            Student Registration
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Register using your Student ID and personal details.
                        </p>

                    </div>

                    <i
                        data-lucide="chevron-right"
                        class="h-5 w-5 shrink-0 text-slate-400 transition group-hover:translate-x-1 group-hover:text-blue-600"
                    ></i>

                </a>

                {{-- Teacher Registration --}}
                <a
                    href="{{ route('register.create', ['role' => 'teacher']) }}"
                    class="group flex items-center gap-4 rounded-2xl border border-slate-200 p-4 transition duration-200 hover:border-emerald-400 hover:bg-emerald-50 focus:outline-none focus:ring-4 focus:ring-emerald-100"
                >

                    <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-xl bg-emerald-100 text-emerald-600 transition group-hover:bg-emerald-200">
                        <i data-lucide="presentation" class="h-7 w-7"></i>
                    </div>

                    <div class="min-w-0 flex-1">

                        <h2 class="font-bold text-slate-900">
                            Teacher Registration
                        </h2>

                        <p class="mt-1 text-sm text-slate-500">
                            Register using your Employee ID and school email.
                        </p>

                    </div>

                    <i
                        data-lucide="chevron-right"
                        class="h-5 w-5 shrink-0 text-slate-400 transition group-hover:translate-x-1 group-hover:text-emerald-600"
                    ></i>

                </a>

            </div>

            {{-- Teacher Approval Notice --}}
            <div class="mt-6 flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800">

                <i data-lucide="shield-check" class="mt-0.5 h-5 w-5 shrink-0"></i>

                <div>
                    <p class="font-semibold">
                        For teachers
                    </p>

                    <p class="mt-1 leading-relaxed">
                        New teacher accounts require administrator approval before login.
                    </p>
                </div>

            </div>

            {{-- Login Link --}}
            <p class="mt-7 text-center text-sm text-slate-600">

                Already have an account?

                <a
                    href="{{ route('login') }}"
                    class="font-semibold text-blue-600 hover:text-blue-700 hover:underline focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded"
                >
                    Sign in
                </a>

            </p>

        </div>

        {{-- Footer --}}
        <p class="mt-6 text-center text-xs text-slate-400">
            &copy; {{ date('Y') }} Smart Student Task and Notification System
        </p>

    </main>

</body>
</html>