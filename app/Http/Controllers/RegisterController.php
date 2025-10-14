<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => [
                'required',
                'string',
                'min:8', // at least 8 characters
                'regex:/[0-9]/', // must contain a number
                'regex:/[!@#$%^&*(),.?":{}|<>]/', // must contain a special character
                'confirmed', // checks if password_confirmation matches
            ],
            'password_confirmation' => 'required',
            'phone' => 'required|string|max:20',
            'position' => 'required|string|max:100',
            'account_type' => 'required|in:1,2',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ], [
            'password.min' => 'Password must be at least 8 characters long.',
            'password.regex' => 'Password must contain at least one number and one special character.',
            'password.confirmed' => 'Password confirmation does not match.',
            'photo.image' => 'The uploaded file must be an image.',
            'photo.max' => 'The image must not exceed 5MB.',
        ]);

        try {
            // ✅ Handle optional photo
            $photoPath = null;
            if ($request->hasFile('photo') && $request->file('photo')->isValid()) {
                $photoPath = $request->file('photo')->store('photos', 'public');
            }

            // ✅ Create the user
            User::create([
                'name' => $validatedData['name'],
                'lastname' => $validatedData['lastname'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'photo' => $photoPath,
                'phone' => $validatedData['phone'],
                'position' => $validatedData['position'],
                'account_type' => $validatedData['account_type'],
            ]);

            // ✅ Redirect on success
            return redirect()->route('auth.success')->with('success', 'Account created successfully.');

        } catch (\Exception $e) {
            // ❌ Log and show error
            logger()->error('Registration failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Registration failed. Please try again.']);
        }
    }
}
