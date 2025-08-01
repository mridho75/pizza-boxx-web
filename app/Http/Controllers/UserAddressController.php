<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Address;

class UserAddressController extends Controller
{
    public function create()
    {
        return view('user.address-create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'label' => 'required|string|max:50',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'phone' => 'required|string|max:20',
        ]);
        Auth::user()->addresses()->create($request->only('label', 'address', 'city', 'province', 'phone'));
        return redirect()->route('user.profile')->with('success', 'Alamat berhasil ditambahkan.');
    }

    public function delete(Address $address)
    {
        if ($address->user_id == Auth::id()) {
            $address->delete();
        }
        return redirect()->route('user.profile')->with('success', 'Alamat berhasil dihapus.');
    }
}
