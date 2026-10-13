<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="theme-color" content="#2563eb">

    <title>Register as {{ ucfirst($role) }} - Smart Student</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-slate-50 px-3 py-6 sm:px-6 sm:py-10">

    <main class="mx-auto w-full max-w-2xl">

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-lg">

            <div class="bg-blue-600 px-5 py-7 text-white sm:px-8 sm:py-9">

                <a
                    href="{{ route('register') }}"
                    class="mb-5 inline-flex items-center gap-2 text-sm text-blue-100 transition hover:text-white"
                >
                    <span aria-hidden="true">←</span>
                    Back to account selection
                </a>

                <h1 class="text-2xl font-bold sm:text-3xl">
                    {{ ucfirst($role) }} Registration
                </h1>

                <p class="mt-2 text-sm text-blue-100 sm:text-base">
                    Enter your details to create your account.
                </p>

            </div>

            <div class="p-5 sm:p-8">

                @if ($errors->any())
                    <div role="alert" class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                        <p class="mb-2 font-semibold">Please correct the following:</p>

                        <ul class="list-disc space-y-1 pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form
                    method="POST"
                    action="{{ route('register.store', ['role' => $role]) }}"
                    enctype="multipart/form-data"
                    class="space-y-6"
                >
                    @csrf

                    <!-- Profile picture -->
                    <section class="rounded-2xl border border-slate-200 bg-slate-50 p-4 sm:p-5">

                        <h2 class="mb-4 font-bold text-slate-800">
                            Profile Picture
                        </h2>

                        <div class="flex flex-col items-center gap-4 sm:flex-row sm:items-center">

                            <div class="shrink-0">
                                <img
                                    id="profile-preview"
                                    src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 120 120'%3E%3Crect width='120' height='120' rx='60' fill='%23dbeafe'/%3E%3Ccircle cx='60' cy='43' r='22' fill='%2360a5fa'/%3E%3Cpath d='M20 112c3-24 19-37 40-37s37 13 40 37' fill='%2360a5fa'/%3E%3C/svg%3E"
                                    alt="Profile picture preview"
                                    class="h-28 w-28 rounded-full border-4 border-white object-cover shadow-md"
                                >
                            </div>

                            <div class="w-full min-w-0 text-center sm:text-left">

                                <p class="font-semibold text-slate-800">
                                    Preview your picture
                                </p>

                                <p class="mt-1 text-sm text-slate-500">
                                    Select an image to see how your profile picture will look.
                                </p>

                                <label
                                    for="profile_picture"
                                    class="mt-4 inline-flex min-h-11 cursor-pointer items-center justify-center rounded-xl bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700 focus-within:ring-4 focus-within:ring-blue-200"
                                >
                                    Choose Picture
                                </label>

                                <input
                                    id="profile_picture"
                                    type="file"
                                    name="profile_picture"
                                    accept="image/jpeg,image/png,image/webp"
                                    class="sr-only"
                                >

                                <p id="profile-file-name" class="mt-2 break-all text-xs text-slate-500">
                                    No picture selected
                                </p>

                                <button
                                    id="remove-picture"
                                    type="button"
                                    class="mt-2 hidden text-sm font-semibold text-red-600 hover:text-red-700"
                                >
                                    Remove picture
                                </button>

                                <p class="mt-2 text-xs text-slate-500">
                                    JPG, PNG, or WEBP · Maximum 2 MB
                                </p>

                            </div>

                        </div>

                    </section>

                    <!-- ID -->
                    <section>
                        <h2 class="mb-4 text-lg font-bold text-slate-900">
                            Account Information
                        </h2>

                        @if ($role === 'student')
                            <div>
                                <label for="student_id" class="mb-2 block text-sm font-semibold text-slate-700">
                                    Student ID <span class="text-red-500">*</span>
                                </label>

                                <input
                                    id="student_id"
                                    type="text"
                                    name="student_id"
                                    value="{{ old('student_id') }}"
                                    required
                                    maxlength="50"
                                    autocomplete="off"
                                    placeholder="Enter your Student ID"
                                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-base outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                                >
                            </div>
                        @else
                            <div>
                                <label for="employee_id" class="mb-2 block text-sm font-semibold text-slate-700">
                                    Employee ID <span class="text-red-500">*</span>
                                </label>

                                <input
                                    id="employee_id"
                                    type="text"
                                    name="employee_id"
                                    value="{{ old('employee_id') }}"
                                    required
                                    maxlength="50"
                                    autocomplete="off"
                                    placeholder="Enter your Employee ID"
                                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-base outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                                >
                            </div>
                        @endif
                    </section>

                    <!-- Personal information -->
                    <section>
                        <h2 class="mb-4 text-lg font-bold text-slate-900">
                            Personal Information
                        </h2>

                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                            <div>
                                <label for="first_name" class="mb-2 block text-sm font-semibold text-slate-700">
                                    First Name <span class="text-red-500">*</span>
                                </label>

                                <input
                                    id="first_name"
                                    type="text"
                                    name="first_name"
                                    value="{{ old('first_name') }}"
                                    required
                                    maxlength="100"
                                    autocomplete="given-name"
                                    placeholder="First name"
                                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-base outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                                >
                            </div>

                            <div>
                                <label for="middle_name" class="mb-2 block text-sm font-semibold text-slate-700">
                                    Middle Name <span class="text-slate-400 font-normal">(Optional)</span>
                                </label>

                                <input
                                    id="middle_name"
                                    type="text"
                                    name="middle_name"
                                    value="{{ old('middle_name') }}"
                                    maxlength="100"
                                    autocomplete="additional-name"
                                    placeholder="Middle name"
                                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-base outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                                >
                            </div>

                            <div class="sm:col-span-2">
                                <label for="last_name" class="mb-2 block text-sm font-semibold text-slate-700">
                                    Last Name <span class="text-red-500">*</span>
                                </label>

                                <input
                                    id="last_name"
                                    type="text"
                                    name="last_name"
                                    value="{{ old('last_name') }}"
                                    required
                                    maxlength="100"
                                    autocomplete="family-name"
                                    placeholder="Last name"
                                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-base outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                                >
                            </div>

                            <div class="sm:col-span-2">
                                <label for="email" class="mb-2 block text-sm font-semibold text-slate-700">
                                    {{ $role === 'teacher' ? 'School Email' : 'Email Address' }}
                                    <span class="text-red-500">*</span>
                                </label>

                                <input
                                    id="email"
                                    type="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    maxlength="255"
                                    autocomplete="email"
                                    inputmode="email"
                                    placeholder="{{ $role === 'teacher' ? 'Enter your school email' : 'Enter your email address' }}"
                                    class="w-full rounded-xl border border-slate-300 px-4 py-3 text-base outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                                >
                            </div>

                        </div>
                    </section>

                    <!-- Password -->
                    <section>
                        <h2 class="mb-4 text-lg font-bold text-slate-900">
                            Security
                        </h2>

                        <div class="space-y-5">

                            <div>
                                <label for="password" class="mb-2 block text-sm font-semibold text-slate-700">
                                    Password <span class="text-red-500">*</span>
                                </label>

                                <div class="relative">
                                    <input
                                        id="password"
                                        type="password"
                                        name="password"
                                        required
                                        minlength="8"
                                        autocomplete="new-password"
                                        placeholder="Create a password"
                                        class="w-full rounded-xl border border-slate-300 px-4 py-3 pr-20 text-base outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
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

                                <p class="mt-2 text-xs text-slate-500">
                                    Use at least 8 characters.
                                </p>
                            </div>

                            <div>
                                <label for="password_confirmation" class="mb-2 block text-sm font-semibold text-slate-700">
                                    Confirm Password <span class="text-red-500">*</span>
                                </label>

                                <div class="relative">
                                    <input
                                        id="password_confirmation"
                                        type="password"
                                        name="password_confirmation"
                                        required
                                        minlength="8"
                                        autocomplete="new-password"
                                        placeholder="Enter your password again"
                                        class="w-full rounded-xl border border-slate-300 px-4 py-3 pr-20 text-base outline-none transition focus:border-blue-500 focus:ring-4 focus:ring-blue-100"
                                    >

                                    <button
                                        type="button"
                                        data-toggle-password="password_confirmation"
                                        aria-label="Show confirmation password"
                                        aria-pressed="false"
                                        class="absolute inset-y-0 right-0 px-4 text-sm font-semibold text-blue-600"
                                    >
                                        Show
                                    </button>
                                </div>
                            </div>

                        </div>
                    </section>

                    @if ($role === 'teacher')
                        <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm text-amber-800">
                            <p class="font-bold">Administrator approval required</p>

                            <p class="mt-1">
                                Your teacher account must be approved by an administrator before you can log in.
                            </p>
                        </div>
                    @endif

                    <div class="space-y-3 border-t border-slate-200 pt-6">

                        <button
                            type="submit"
                            class="w-full rounded-xl bg-blue-600 px-5 py-3.5 text-base font-semibold text-white shadow-sm transition hover:bg-blue-700 active:scale-[0.99] focus:outline-none focus:ring-4 focus:ring-blue-200"
                        >
                            Create {{ ucfirst($role) }} Account
                        </button>

                        <a
                            href="{{ route('login') }}"
                            class="block w-full rounded-xl border border-slate-300 px-5 py-3 text-center text-sm font-semibold text-slate-700 transition hover:bg-slate-50"
                        >
                            Already registered? Sign in
                        </a>

                    </div>

                </form>

            </div>

        </div>

        <p class="mt-5 text-center text-xs text-slate-400">
            Smart Student Task and Notification System
        </p>

    </main>

    <script>
        const pictureInput = document.getElementById('profile_picture');
        const preview = document.getElementById('profile-preview');
        const fileName = document.getElementById('profile-file-name');
        const removeButton = document.getElementById('remove-picture');

        const defaultAvatar = preview.src;
        let selectedImageUrl = null;

        pictureInput.addEventListener('change', function () {
            const file = this.files[0];

            if (!file) {
                return;
            }

            const allowedTypes = [
                'image/jpeg',
                'image/png',
                'image/webp'
            ];

            if (!allowedTypes.includes(file.type)) {
                alert('Please select a JPG, PNG, or WEBP image.');
                this.value = '';
                return;
            }

            if (file.size > 2 * 1024 * 1024) {
                alert('The selected image must not exceed 2 MB.');
                this.value = '';
                return;
            }

            if (selectedImageUrl) {
                URL.revokeObjectURL(selectedImageUrl);
            }

            selectedImageUrl = URL.createObjectURL(file);

            preview.src = selectedImageUrl;
            fileName.textContent = file.name;
            removeButton.classList.remove('hidden');
        });

        removeButton.addEventListener('click', function () {
            pictureInput.value = '';

            if (selectedImageUrl) {
                URL.revokeObjectURL(selectedImageUrl);
                selectedImageUrl = null;
            }

            preview.src = defaultAvatar;
            fileName.textContent = 'No picture selected';
            removeButton.classList.add('hidden');
        });

        document.querySelectorAll('[data-toggle-password]').forEach(button => {
            button.addEventListener('click', function () {
                const input = document.getElementById(this.dataset.togglePassword);
                const showing = input.type === 'text';

                input.type = showing ? 'password' : 'text';
                this.textContent = showing ? 'Show' : 'Hide';

                this.setAttribute(
                    'aria-label',
                    showing ? 'Show password' : 'Hide password'
                );

                this.setAttribute('aria-pressed', String(!showing));
            });
        });
    </script>

</body>
</html>