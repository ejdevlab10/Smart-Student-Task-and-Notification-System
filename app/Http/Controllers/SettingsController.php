<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    /**
     * Display the account settings page.
     */
    public function index()
    {
        $user = auth()->user();

        return view('settings.index', compact('user'));
    }

    /**
     * Update the user's profile information.
     */
    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'middle_name' => ['nullable', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],

            'profile_picture' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
                'dimensions:width=512,height=512',
            ],

            'remove_profile_picture' => [
                'nullable',
                'boolean',
            ],
        ]);

        $oldProfilePicture = $user->profile_picture;
        $newProfilePicture = null;

        // Store the new cropped picture before removing the old one.
        if ($request->hasFile('profile_picture')) {
            $newProfilePicture = $request
                ->file('profile_picture')
                ->store('profile-pictures', 'public');

            if (!$newProfilePicture) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'profile_picture' => 'Unable to save your profile picture. Please try again.',
                    ]);
            }

            $user->profile_picture = $newProfilePicture;
        } elseif (
            $request->boolean('remove_profile_picture')
            && $oldProfilePicture
        ) {
            $user->profile_picture = null;
        }

        // Update personal information.
        $user->first_name = $validated['first_name'];
        $user->middle_name = $validated['middle_name'] ?? null;
        $user->last_name = $validated['last_name'];

        $user->name = trim(
            $validated['first_name'] . ' ' .
            (!empty($validated['middle_name'])
                ? $validated['middle_name'] . ' '
                : '') .
            $validated['last_name']
        );

        $user->email = $validated['email'];

        try {
            $user->save();
        } catch (\Throwable $exception) {
            // If saving the user fails, remove the newly uploaded file.
            if ($newProfilePicture) {
                Storage::disk('public')->delete($newProfilePicture);
            }

            throw $exception;
        }

        // Delete the previous image only after the database update succeeds.
        if (
            $oldProfilePicture
            && $oldProfilePicture !== $user->profile_picture
        ) {
            Storage::disk('public')->delete($oldProfilePicture);
        }

        return redirect()
            ->route('settings.index')
            ->with('success', 'Your profile has been updated successfully.');
    }
}