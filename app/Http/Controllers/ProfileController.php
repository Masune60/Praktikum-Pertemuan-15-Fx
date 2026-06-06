<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();

        // Memastikan model profile sudah terbuat (One-to-One)
        if (!$user->profile) {
            $user->profile()->create();
            $user->refresh();
        }

        return view('admin.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:1000',
        ]);

        // Perbarui data Akun User
        $user->update([
            'name' => $request->name,
        ]);

        // Perbarui data Profil Penulis (One-to-One)
        $user->profile->update([
            'phone' => $request->phone,
            'bio' => $request->bio,
        ]);

        return redirect()->back()->with('success', 'Profil penulis berhasil diperbarui!');
    }
}