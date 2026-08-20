<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Update the user's profile information (name & email).
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'nip'           => ['nullable', 'string', 'max:50'],
            'email'         => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'profile_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp,gif', 'max:2048'],
        ]);

        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            
            try {
                // Simpan sebagai Data URI Base64 agar dapat dibaca di Vercel tanpa filesystem lokal
                $mimeType = $file->getMimeType() ?: 'image/jpeg';
                $imageData = file_get_contents($file->getRealPath());
                $base64Image = 'data:' . $mimeType . ';base64,' . base64_encode($imageData);
                $validated['photo'] = $base64Image;
            } catch (\Throwable $e) {
                try {
                    $imagePath = $file->store('profile_photos', 'public');
                    $validated['photo'] = $imagePath;
                } catch (\Throwable $err) {
                    // Abaikan jika serverless read-only
                }
            }

            // Hapus foto lama jika tersimpan di disk lokal
            if ($user->photo && !str_starts_with($user->photo, 'data:') && Storage::disk('public')->exists($user->photo)) {
                try {
                    Storage::disk('public')->delete($user->photo);
                } catch (\Throwable $e) {
                    // Silent fail
                }
            }
        }

        unset($validated['profile_image']);

        $user->update($validated);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Update the user's password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        Auth::user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('password_success', 'Password berhasil diubah.');
    }
}
