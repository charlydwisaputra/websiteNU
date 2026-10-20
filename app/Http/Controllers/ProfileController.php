<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Display the admin workspace profile management view.
     */
    public function adminEdit(Request $request): View
    {
        $user = $request->user();
        $anggota = $user->getOrBuildProfilAnggota();

        return view('admin.profil', compact('user', 'anggota'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $user->fill($request->safe()->only(['name', 'email']));

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // Sinkronkan ke data profil Anggota terkait
        $anggota = $user->getOrBuildProfilAnggota();
        $anggota->name = $user->name;

        if ($request->hasFile('photo')) {
            if ($anggota->photo && Storage::disk('public')->exists($anggota->photo)) {
                Storage::disk('public')->delete($anggota->photo);
            }
            $anggota->photo = $request->file('photo')->store('photos/anggota', 'public');
        }

        $anggota->save();

        if ($request->routeIs('admin.*') || str_contains($request->header('referer', ''), '/admin/')) {
            return Redirect::route('admin.profil')->with('success', 'Profil admin berhasil diperbarui.');
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
