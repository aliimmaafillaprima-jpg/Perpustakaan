<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'password' => 'nullable|min:6|confirmed'
        ]);

        $user->name = $validated['name'];
        $user->phone = $validated['phone'];

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    // FUNGSI BARU UNTUK MENGHAPUS FOTO
    public function deleteAvatar()
    {
        $user = auth()->user();

        if ($user->avatar) {
            // Hapus file dari folder storage
            Storage::disk('public')->delete($user->avatar);
            
            // Set kolom avatar di database menjadi NULL
            $user->avatar = null;
            $user->save();
        }

        return back()->with('success', 'Foto profil berhasil dihapus!');
    }
}