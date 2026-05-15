<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user();

        // Stats ringkasan untuk ditampilkan di profil
        $txCount     = $user->transactions()->count();
        $wallet      = $user->wallet()->firstOrCreate(['user_id' => $user->id], ['balance' => 0]);
        $reviewCount = \App\Models\Review::where('user_id', $user->id)->count();

        return view('profile.edit', compact('user', 'txCount', 'wallet', 'reviewCount'));
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if it was a file path
            if ($user->avatar && !str_starts_with($user->avatar, 'data:image')) {
                Storage::disk('public')->delete($user->avatar);
            }
            
            $file = $request->file('avatar');
            $mimeType = $file->getMimeType();
            $base64 = base64_encode(file_get_contents($file->getRealPath()));
            $data['avatar'] = 'data:' . $mimeType . ';base64,' . $base64;
        }

        if (isset($data['email']) && $data['email'] !== $user->email) {
            $user->email_verified_at = null;
        }

        $user->fill($data)->save();

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'min:8', 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password berhasil diubah!');
    }

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

        return redirect()->to('/');
    }
}
