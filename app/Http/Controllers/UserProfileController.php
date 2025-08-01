<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Address;

class UserProfileController extends Controller
{
    public function show()
    {
        $addresses = Auth::user()->addresses ?? collect();
        return view('user.profile', compact('addresses'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name' => 'required|string|max:255',
        ]);
        $user->name = $request->name;
        $user->save();
        return back()->with('success', 'Profile berhasil diperbarui.');
    }
}
