<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('students.profile.edit');
    }

    public function update(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'password' => 'nullable|confirmed|min:8'
        ]);

        $user = auth()->user();
        
        // Update email if changed
        if ($user->email !== $request->email) {
            $user->update(['email' => $request->email]);
        }

        // Student full name is read-only, so we do not update it.

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('student.dashboard')->with('success', 'Profile updated successfully!');
    }
}
