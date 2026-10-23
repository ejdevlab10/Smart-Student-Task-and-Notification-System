@extends('layouts.app')

@section('title', 'Account Settings')
@section('page-heading', 'Account Settings')

@section('content')
@php
    $user = auth()->user();

    $profilePictureUrl = !empty($user->profile_picture)
        ? \Illuminate\Support\Facades\Storage::url($user->profile_picture)
        : null;

    $fullName = trim(
        ($user->first_name ?? '') . ' ' .
        ($user->middle_name ?? '') . ' ' .
        ($user->last_name ?? '')
    );

    $initials = collect(explode(' ', $fullName))
        ->filter()
        ->map(fn ($part) => strtoupper(substr($part, 0, 1)))
        ->take(2)
        ->implode('');

    $initials = $initials ?: strtoupper(substr($user->name ?? 'U', 0, 1));
@endphp

<style>
    .settings-page {
        max-width: 920px;
        margin: 0 auto;
        padding: 24px 16px 48px;
        color: #1e293b;
    }

    .settings-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(15, 23, 42, .04);
    }

    .settings-header {
        padding: 24px;
        border-bottom: 1px solid #e2e8f0;
    }

    .settings-section {
        padding: 24px;
    }

    .settings-divider {
        height: 1px;
        background: #e2e8f0;
    }

    .settings-label {
        display: block;
        margin-bottom: 7px;
        font-size: 14px;
        font-weight: 600;
        color: #334155;
    }

    .settings-input {
        display: block;
        width: 100%;
        min-height: 44px;
        padding: 10px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 9px;
        background: white;
        color: #0f172a;
        outline: none;
        transition: border-color .15s, box-shadow .15s;
    }

    .settings-input:focus {
        border-color: #4f46e5;
        box-shadow: 0 0 0 3px rgba(79, 70, 229, .12);
    }

    .settings-error {
        margin-top: 6px;
        color: #dc2626;
        font-size: 13px;
    }

    .settings-avatar {
        width: 112px;
        height: 112px;
        flex-shrink: 0;
        border-radius: 50%;
        border: 3px solid #e2e8f0;
        background: #eef2ff;
        object-fit: cover;
        display: block;
    }

    .settings-avatar-fallback {
        display: flex;
        align-items: center;
        justify-content: center;
        color: #4338ca;
        font-size: 32px;
        font-weight: 700;
    }

    .settings-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 42px;
        padding: 10px 16px;
        border: 1px solid #cbd5e1;
        border-radius: 9px;
        background: white;
        color: #334155;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: background .15s, border-color .15s;
    }

    .settings-button:hover {
        background: #f8fafc;
    }

    .settings-button-primary {
        border-color: #4f46e5;
        background: #4f46e5;
        color: white;
    }

    .settings-button-primary:hover {
        background: #4338ca;
    }

    .settings-button:disabled {
        opacity: .5;
        cursor: not-allowed;
    }

    .settings-help {
        margin-top: 8px;
        color: #64748b;
        font-size: 13px;
        line-height: 1.5;
    }

    /* Image cropping popup */
    #crop-modal[hidden] {
        display: none !important;
    }

    #crop-modal {
        position: fixed;
        inset: 0;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 16px;
    }

    .crop-backdrop {
        position: absolute;
        inset: 0;
        background: rgba(15, 23, 42, .72);
    }

    .crop-dialog {
        position: relative;
        z-index: 1;
        width: 100%;
        max-width: 500px;
        max-height: 95vh;
        overflow-y: auto;
        border-radius: 16px;
        background: white;
        box-shadow: 0 24px 80px rgba(0, 0, 0, .3);
    }

    .crop-dialog-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 18px 20px;
        border-bottom: 1px solid #e2e8f0;
    }

    .crop-workspace {
        padding: 20px;
    }

    #crop-canvas {
        display: block;
        width: 100%;
        max-width: 360px;
        height: auto;
        aspect-ratio: 1 / 1;
        margin: 0 auto;
        border-radius: 8px;
        background: #0f172a;
        cursor: grab;
        touch-action: none;
    }

    #crop-canvas:active {
        cursor: grabbing;
    }

    .crop-zoom {
        display: block;
        width: 100%;
        margin-top: 10px;
        accent-color: #4f46e5;
    }

    .crop-dialog-footer {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        padding: 16px 20px;
        border-top: 1px solid #e2e8f0;
    }

    @media (max-width: 600px) {
        .settings-page {
            padding: 16px 10px 32px;
        }

        .settings-header,
        .settings-section {
            padding: 18px;
        }

        .settings-avatar {
            width: 88px;
            height: 88px;
        }

        .crop-dialog-footer {
            flex-wrap: wrap;
        }
    }
</style>

<div class="settings-page">

    {{-- Page heading --}}
    <div style="margin-bottom: 24px;">
        <h1 style="font-size: 26px; font-weight: 750; letter-spacing: -.5px;">
            Account Settings
        </h1>

        <p style="margin-top: 6px; color: #64748b; font-size: 14px;">
            Manage your personal information and profile picture.
        </p>
    </div>

    {{-- Success message --}}
    @if (session('success'))
        <div role="status"
             style="margin-bottom: 20px; padding: 13px 16px; border: 1px solid #bbf7d0; border-radius: 10px; background: #f0fdf4; color: #166534; font-size: 14px;">
            {{ session('success') }}
        </div>
    @endif

    {{-- General validation errors --}}
    @if ($errors->any())
        <div role="alert"
             style="margin-bottom: 20px; padding: 13px 16px; border: 1px solid #fecaca; border-radius: 10px; background: #fef2f2; color: #991b1b; font-size: 14px;">
            <p style="font-weight: 700; margin-bottom: 5px;">
                Please check the following:
            </p>

            <ul style="padding-left: 20px; list-style: disc;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        id="settings-form"
        action="{{ route('settings.profile.update') }}"
        method="POST"
        enctype="multipart/form-data"
    >
        @csrf
        @method('PUT')

        <div class="settings-card">

            {{-- Profile picture --}}
            <div class="settings-header">
                <h2 style="font-size: 17px; font-weight: 700;">
                    Profile Picture
                </h2>

                <p style="margin-top: 5px; color: #64748b; font-size: 14px;">
                    Choose a photo and crop it before saving.
                </p>
            </div>

            <div class="settings-section">
                <div style="display: flex; align-items: center; gap: 22px; flex-wrap: wrap;">

                    <div
                        class="settings-avatar settings-avatar-fallback"
                        style="position: relative; overflow: hidden;"
                    >
                        <img
                            id="profile-preview"
                            src="{{ $profilePictureUrl ?? '' }}"
                            alt="Profile picture"
                            style="
                                position: absolute;
                                inset: 0;
                                width: 100%;
                                height: 100%;
                                object-fit: cover;
                                border-radius: 50%;
                            "
                            @if (!$profilePictureUrl) hidden @endif
                            onerror="this.hidden = true; document.getElementById('profile-fallback').hidden = false;"
                        >

                        <span
                            id="profile-fallback"
                            @if ($profilePictureUrl) hidden @endif
                        >
                            {{ $initials }}
                        </span>
                    </div>

                    <div style="flex: 1; min-width: 200px;">
                        <p style="font-weight: 650; font-size: 15px;">
                            Your profile photo
                        </p>

                        <p class="settings-help" style="margin-top: 4px;">
                            JPG, PNG, or WebP. Maximum upload size: 2 MB.
                            Your photo will be cropped to a square.
                        </p>

                        <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-top: 14px;">

                            <button
                                type="button"
                                id="choose-photo"
                                class="settings-button settings-button-primary"
                            >
                                Choose Photo
                            </button>

                            <button
                                type="button"
                                id="remove-photo"
                                class="settings-button"
                                @if (!$profilePictureUrl) hidden @endif
                            >
                                Remove Photo
                            </button>

                        </div>

                        <input
                            type="file"
                            id="profile_picture"
                            name="profile_picture"
                            accept="image/jpeg,image/png,image/webp"
                            hidden
                        >

                        <input
                            type="hidden"
                            name="remove_profile_picture"
                            id="remove_profile_picture"
                            value="0"
                        >

                        @error('profile_picture')
                            <p class="settings-error">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="settings-divider"></div>

            {{-- Personal information --}}
            <div class="settings-header">
                <h2 style="font-size: 17px; font-weight: 700;">
                    Personal Information
                </h2>

                <p style="margin-top: 5px; color: #64748b; font-size: 14px;">
                    Keep your account details up to date.
                </p>
            </div>

            <div class="settings-section">

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 220px), 1fr)); gap: 20px;">

                    <div>
                        <label for="first_name" class="settings-label">
                            First Name
                        </label>

                        <input
                            type="text"
                            id="first_name"
                            name="first_name"
                            class="settings-input"
                            value="{{ old('first_name', $user->first_name) }}"
                            autocomplete="given-name"
                            required
                        >

                        @error('first_name')
                            <p class="settings-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="middle_name" class="settings-label">
                            Middle Name
                            <span style="font-weight: 400; color: #94a3b8;">
                                (Optional)
                            </span>
                        </label>

                        <input
                            type="text"
                            id="middle_name"
                            name="middle_name"
                            class="settings-input"
                            value="{{ old('middle_name', $user->middle_name) }}"
                            autocomplete="additional-name"
                        >

                        @error('middle_name')
                            <p class="settings-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="last_name" class="settings-label">
                            Last Name
                        </label>

                        <input
                            type="text"
                            id="last_name"
                            name="last_name"
                            class="settings-input"
                            value="{{ old('last_name', $user->last_name) }}"
                            autocomplete="family-name"
                            required
                        >

                        @error('last_name')
                            <p class="settings-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email" class="settings-label">
                            Email Address
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="settings-input"
                            value="{{ old('email', $user->email) }}"
                            autocomplete="email"
                            required
                        >

                        @error('email')
                            <p class="settings-error">{{ $message }}</p>
                        @enderror
                    </div>

                </div>
            </div>

            {{-- Save --}}
            <div style="display: flex; justify-content: flex-end; gap: 10px; flex-wrap: wrap; padding: 18px 24px; border-top: 1px solid #e2e8f0; background: #f8fafc;">

                <button
                    type="reset"
                    id="reset-form"
                    class="settings-button"
                >
                    Reset
                </button>

                <button
                    type="submit"
                    class="settings-button settings-button-primary"
                >
                    Save Changes
                </button>

            </div>

        </div>
    </form>
</div>

{{-- Custom Canvas Cropper --}}
<div id="crop-modal" hidden>
    <div class="crop-backdrop" id="crop-backdrop"></div>

    <div
        class="crop-dialog"
        role="dialog"
        aria-modal="true"
        aria-labelledby="crop-title"
    >
        <div class="crop-dialog-header">
            <div>
                <h2 id="crop-title" style="font-size: 17px; font-weight: 700;">
                    Crop Profile Picture
                </h2>

                <p style="margin-top: 4px; color: #64748b; font-size: 13px;">
                    Drag to reposition the image.
                </p>
            </div>

            <button
                type="button"
                id="close-crop"
                aria-label="Close crop editor"
                class="settings-button"
                style="min-height: 36px; padding: 6px 11px;"
            >
                ✕
            </button>
        </div>

        <div class="crop-workspace">
            <canvas
                id="crop-canvas"
                width="360"
                height="360"
                aria-label="Square image cropping area"
            ></canvas>

            <div style="margin-top: 20px;">
                <div style="display: flex; justify-content: space-between; gap: 12px; font-size: 13px; color: #475569;">
                    <label for="crop-zoom" style="font-weight: 600;">
                        Zoom
                    </label>

                    <span id="zoom-value">100%</span>
                </div>

                <input
                    type="range"
                    id="crop-zoom"
                    class="crop-zoom"
                    min="1"
                    max="3"
                    step="0.01"
                    value="1"
                >

                <div style="display: flex; justify-content: space-between; margin-top: 6px; color: #94a3b8; font-size: 12px;">
                    <span>Fit</span>
                    <span>Zoom in</span>
                </div>
            </div>

            <p class="settings-help" style="text-align: center; margin-top: 15px;">
                The square area is what will appear in your profile picture.
            </p>

            <p
                id="crop-error"
                role="alert"
                hidden
                style="margin-top: 12px; color: #dc2626; font-size: 13px;"
            ></p>
        </div>

        <div class="crop-dialog-footer">
            <button
                type="button"
                id="reset-crop"
                class="settings-button"
            >
                Reset Crop
            </button>

            <button
                type="button"
                id="cancel-crop"
                class="settings-button"
            >
                Cancel
            </button>

            <button
                type="button"
                id="apply-crop"
                class="settings-button settings-button-primary"
            >
                Apply Crop
            </button>
        </div>
    </div>
</div>

<script>
(function () {
    'use strict';

    const form = document.getElementById('settings-form');
    const fileInput = document.getElementById('profile_picture');
    const chooseButton = document.getElementById('choose-photo');
    const removeButton = document.getElementById('remove-photo');

    const preview = document.getElementById('profile-preview');
    const fallback = document.getElementById('profile-fallback');
    const removeField = document.getElementById('remove_profile_picture');

    const modal = document.getElementById('crop-modal');
    const backdrop = document.getElementById('crop-backdrop');
    const closeButton = document.getElementById('close-crop');
    const cancelButton = document.getElementById('cancel-crop');
    const applyButton = document.getElementById('apply-crop');
    const resetButton = document.getElementById('reset-crop');

    const canvas = document.getElementById('crop-canvas');
    const ctx = canvas.getContext('2d');
    const zoomSlider = document.getElementById('crop-zoom');
    const zoomValue = document.getElementById('zoom-value');
    const cropError = document.getElementById('crop-error');

    if (!ctx) {
        chooseButton.disabled = true;
        console.error('The browser could not initialize the crop canvas.');
        return;
    }

    const SIZE = 360;
    const MAX_FILE_SIZE = 2 * 1024 * 1024;

    let image = null;
    let imageUrl = null;
    let baseScale = 1;
    let zoom = 1;
    let offsetX = 0;
    let offsetY = 0;
    let dragging = false;
    let lastPointerX = 0;
    let lastPointerY = 0;
    let previousOverflow = '';

    function showCropError(message) {
        cropError.textContent = message;
        cropError.hidden = false;
    }

    function clearCropError() {
        cropError.textContent = '';
        cropError.hidden = true;
    }

    function showModal() {
        previousOverflow = document.body.style.overflow;
        document.body.style.overflow = 'hidden';
        modal.hidden = false;
    }

    function closeModal() {
        modal.hidden = true;
        document.body.style.overflow = previousOverflow;
        dragging = false;

        if (imageUrl) {
            URL.revokeObjectURL(imageUrl);
            imageUrl = null;
        }

        image = null;
        clearCropError();
    }

    function imageDimensions() {
        return {
            width: image.naturalWidth * baseScale * zoom,
            height: image.naturalHeight * baseScale * zoom
        };
    }

    function clampOffsets() {
        const dimensions = imageDimensions();

        const minX = SIZE - dimensions.width;
        const minY = SIZE - dimensions.height;

        offsetX = Math.min(0, Math.max(minX, offsetX));
        offsetY = Math.min(0, Math.max(minY, offsetY));
    }

    function drawImage() {
        if (!image) {
            return;
        }

        ctx.clearRect(0, 0, SIZE, SIZE);
        ctx.fillStyle = '#0f172a';
        ctx.fillRect(0, 0, SIZE, SIZE);

        const dimensions = imageDimensions();

        ctx.drawImage(
            image,
            offsetX,
            offsetY,
            dimensions.width,
            dimensions.height
        );

        zoomValue.textContent = Math.round(zoom * 100) + '%';
    }

    function resetCrop() {
        if (!image) {
            return;
        }

        baseScale = Math.max(
            SIZE / image.naturalWidth,
            SIZE / image.naturalHeight
        );

        zoom = 1;
        offsetX = (SIZE - image.naturalWidth * baseScale) / 2;
        offsetY = (SIZE - image.naturalHeight * baseScale) / 2;

        zoomSlider.value = '1';
        clampOffsets();
        drawImage();
    }

    function updateZoom(newZoom) {
        if (!image) {
            return;
        }

        const oldDimensions = imageDimensions();

        const centerX = offsetX + oldDimensions.width / 2;
        const centerY = offsetY + oldDimensions.height / 2;

        zoom = Number(newZoom);

        const newDimensions = imageDimensions();

        offsetX = centerX - newDimensions.width / 2;
        offsetY = centerY - newDimensions.height / 2;

        clampOffsets();
        drawImage();
    }

    function openCropper(file) {
        clearCropError();

        if (!file) {
            return;
        }

        const allowedTypes = [
            'image/jpeg',
            'image/png',
            'image/webp'
        ];

        if (!allowedTypes.includes(file.type)) {
            showCropError('Please choose a JPG, PNG, or WebP image.');
            showModal();
            return;
        }

        if (file.size > MAX_FILE_SIZE) {
            showCropError('The image must be 2 MB or smaller.');
            showModal();
            return;
        }

        if (imageUrl) {
            URL.revokeObjectURL(imageUrl);
            imageUrl = null;
        }

        imageUrl = URL.createObjectURL(file);

        const newImage = new Image();

        newImage.onload = function () {
            image = newImage;
            resetCrop();
            showModal();
        };

        newImage.onerror = function () {
            showCropError('The selected image could not be opened.');
            showModal();
        };

        newImage.src = imageUrl;
    }

    chooseButton.addEventListener('click', function () {
        fileInput.click();
    });

    fileInput.addEventListener('change', function () {
        const file = fileInput.files && fileInput.files[0];

        if (!file) {
            return;
        }

        openCropper(file);
    });

    zoomSlider.addEventListener('input', function () {
        updateZoom(this.value);
    });

    resetButton.addEventListener('click', resetCrop);

    canvas.addEventListener('pointerdown', function (event) {
        if (!image) {
            return;
        }

        dragging = true;
        lastPointerX = event.clientX;
        lastPointerY = event.clientY;

        canvas.setPointerCapture(event.pointerId);
    });

    canvas.addEventListener('pointermove', function (event) {
        if (!dragging || !image) {
            return;
        }

        const rect = canvas.getBoundingClientRect();

        const scaleX = SIZE / rect.width;
        const scaleY = SIZE / rect.height;

        offsetX += (event.clientX - lastPointerX) * scaleX;
        offsetY += (event.clientY - lastPointerY) * scaleY;

        lastPointerX = event.clientX;
        lastPointerY = event.clientY;

        clampOffsets();
        drawImage();
    });

    function stopDragging() {
        dragging = false;
    }

    canvas.addEventListener('pointerup', stopDragging);
    canvas.addEventListener('pointercancel', stopDragging);
    canvas.addEventListener('lostpointercapture', stopDragging);

    function cancelCrop() {
        closeModal();
    }

    closeButton.addEventListener('click', cancelCrop);
    cancelButton.addEventListener('click', cancelCrop);
    backdrop.addEventListener('click', cancelCrop);

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && !modal.hidden) {
            closeModal();
        }
    });

    applyButton.addEventListener('click', function () {
        if (!image) {
            showCropError('Choose an image before applying the crop.');
            return;
        }

        clearCropError();
        applyButton.disabled = true;
        applyButton.textContent = 'Processing...';

        const output = document.createElement('canvas');
        output.width = 512;
        output.height = 512;

        const outputContext = output.getContext('2d');

        if (!outputContext) {
            showCropError('Your browser could not process this image.');
            applyButton.disabled = false;
            applyButton.textContent = 'Apply Crop';
            return;
        }

        const ratio = 512 / SIZE;
        const dimensions = imageDimensions();

        outputContext.fillStyle = '#ffffff';
        outputContext.fillRect(0, 0, 512, 512);

        outputContext.drawImage(
            image,
            offsetX * ratio,
            offsetY * ratio,
            dimensions.width * ratio,
            dimensions.height * ratio
        );

        output.toBlob(function (blob) {
            if (!blob) {
                showCropError('Could not create the cropped image. Please try again.');
                applyButton.disabled = false;
                applyButton.textContent = 'Apply Crop';
                return;
            }

            const croppedFile = new File(
                [blob],
                'profile-picture.jpg',
                {
                    type: 'image/jpeg',
                    lastModified: Date.now()
                }
            );

            const transfer = new DataTransfer();
            transfer.items.add(croppedFile);
            fileInput.files = transfer.files;

            const previewUrl = URL.createObjectURL(blob);

            preview.onload = function () {
                URL.revokeObjectURL(previewUrl);
                preview.onload = null;
            };

            preview.src = previewUrl;
            preview.hidden = false;
            fallback.hidden = true;

            removeField.value = '0';
            removeButton.hidden = false;

            closeModal();

            applyButton.disabled = false;
            applyButton.textContent = 'Apply Crop';
        }, 'image/jpeg', 0.90);
    });

    removeButton.addEventListener('click', function () {
        fileInput.value = '';
        removeField.value = '1';

        preview.removeAttribute('src');
        preview.hidden = true;
        fallback.hidden = false;
        removeButton.hidden = true;
    });

    form.addEventListener('reset', function () {
        window.setTimeout(function () {
            fileInput.value = '';
            removeField.value = '0';

            preview.src = @json($profilePictureUrl ?? '');
            preview.hidden = !@json((bool) $profilePictureUrl);
            fallback.hidden = @json((bool) $profilePictureUrl);
            removeButton.hidden = !@json((bool) $profilePictureUrl);

            closeModal();
        }, 0);
    });

    form.addEventListener('submit', function (event) {
        if (!fileInput.files || fileInput.files.length === 0) {
            return;
        }

        const file = fileInput.files[0];

        if (file.size > MAX_FILE_SIZE) {
            event.preventDefault();
            alert('The profile picture must be 2 MB or smaller.');
        }
    });
})();
</script>
@endsection