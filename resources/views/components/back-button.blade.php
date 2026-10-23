@props([
    'fallback' => 'dashboard',
    'label' => 'Back',
])

@php
    $fallbackUrl = \Illuminate\Support\Facades\Route::has($fallback)
        ? route($fallback)
        : url('/dashboard');
@endphp

<a
    href="{{ $fallbackUrl }}"
    data-smart-back
    {{ $attributes->merge([
        'class' => 'inline-flex items-center gap-2 text-sm font-medium text-slate-600 transition hover:text-blue-600'
    ]) }}
>
    <i data-lucide="arrow-left" class="h-4 w-4"></i>
    {{ $label }}
</a>

@once
    <script>
        document.addEventListener('click', function (event) {
            const button = event.target.closest('[data-smart-back]');

            if (!button) return;

            if (
                event.defaultPrevented ||
                event.button !== 0 ||
                event.metaKey ||
                event.ctrlKey ||
                event.shiftKey ||
                event.altKey
            ) {
                return;
            }

            const fallback = button.href;
            const referrer = document.referrer;

            if (!referrer) return;

            try {
                const previousUrl = new URL(referrer);

                if (
                    previousUrl.origin === window.location.origin &&
                    previousUrl.href !== window.location.href &&
                    window.history.length > 1
                ) {
                    event.preventDefault();
                    window.history.back();
                }
            } catch (error) {
                // Use the fallback link if the referrer is invalid.
            }
        });
    </script>
@endonce