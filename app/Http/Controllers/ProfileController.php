<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function updateProfile(Request $request): RedirectResponse
    {
        $authenticatedUser = Auth::user();

        $validatedProfileData = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'nip'           => ['nullable', 'string', 'max:50'],
            'email'         => ['required', 'email', 'max:255', 'unique:users,email,' . $authenticatedUser->id],
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:2048'],
        ]);

        if ($request->hasFile('profile_image')) {
            $uploadedProfileImage = $request->file('profile_image');
            
            try {
                $imageMimeType = $uploadedProfileImage->getMimeType() ?: 'image/jpeg';
                $rawImageData = file_get_contents($uploadedProfileImage->getRealPath());
                $base64EncodedImage = 'data:' . $imageMimeType . ';base64,' . base64_encode($rawImageData);
                $validatedProfileData['photo'] = $base64EncodedImage;
            } catch (\Throwable $imageProcessingException) {
                try {
                    $storedImagePath = $uploadedProfileImage->store('profile_photos', 'public');
                    $validatedProfileData['photo'] = $storedImagePath;
                } catch (\Throwable $storageException) {
                }
            }

            if ($authenticatedUser->photo && !str_starts_with($authenticatedUser->photo, 'data:') && Storage::disk('public')->exists($authenticatedUser->photo)) {
                try {
                    Storage::disk('public')->delete($authenticatedUser->photo);
                } catch (\Throwable $fileDeletionException) {
                }
            }
        }

        unset($validatedProfileData['profile_image']);

        $authenticatedUser->update($validatedProfileData);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validatedPasswordData = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        Auth::user()->update([
            'password' => Hash::make($validatedPasswordData['password']),
        ]);

        return back()->with('password_success', 'Password berhasil diubah.');
    }
}
