<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Show the login page
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Show the main dashboard after successful OTP verification
     */
    public function showMainPage()
    {
        return view('dashboard');
    }

    /**
     * Step 1: Handle login + send OTP
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email is not registered!'])->withInput();
        }

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Incorrect password!'])->withInput();
        }

        // ✅ Generate 6-digit OTP
        $otp = rand(100000, 999999);

        // ✅ Store OTP + expiration + email in session
        session([
            'otp' => $otp,
            'otp_email' => $user->email,
            'otp_expires_at' => now()->addMinutes(5),
        ]);

        // ✅ Send OTP via email
        try {
            Mail::raw("Your CaliCrane verification code is: {$otp}", function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('CaliCrane OTP Verification Code');
            });
        } catch (\Exception $e) {
            return back()->withErrors([
                'email' => 'Failed to send OTP. Please check mail configuration.'
            ]);
        }

        // ✅ Temporarily save user details before OTP verification
        session([
            'temp_user_id' => $user->id,
            'temp_name' => $user->name,
            'temp_lastname' => $user->lastname,
            'temp_photo' => $user->photo,
            'temp_account_type' => $user->account_type,
        ]);

        // ✅ Make sure no active auth session
        Auth::logout();

        // ✅ Redirect to OTP verification page
        return redirect()->route('otp.verify.form')->with('success', 'An OTP has been sent to your email.');
    }

    /**
     * Step 2: Complete login after OTP verification
     */
    public function completeLogin()
    {
        $user = User::find(session('temp_user_id'));

        if (!$user) {
            return redirect()->route('login')->withErrors([
                'msg' => 'Session expired. Please log in again.'
            ]);
        }

        // ✅ Log in the verified user
        Auth::login($user);

        // ✅ Clear temp + OTP session
        session()->forget([
            'otp', 'otp_email', 'otp_expires_at',
            'temp_user_id', 'temp_name', 'temp_lastname', 'temp_photo', 'temp_account_type'
        ]);

        return redirect()->route('dashboard')->with('success', 'Login successful!');
    }

    /**
     * Logout function
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
