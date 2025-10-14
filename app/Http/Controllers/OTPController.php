<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\User;

class OTPController extends Controller
{
    public function showVerifyForm()
    {
        if (!session('otp_email')) {
            return redirect()->route('login')->withErrors(['msg' => 'Please login first.']);
        }

        return view('auth.verify-otp');
    }

    public function verify(Request $request)
    {
        $request->validate(['otp' => 'required']);

        $sessionOtp = session('otp');
        $expiresAt = session('otp_expires_at');

        if (!$sessionOtp || now()->greaterThan($expiresAt)) {
            return back()->with('error', 'OTP expired. Please resend.');
        }

        if ($request->otp != $sessionOtp) {
            return back()->with('error', 'Invalid OTP.');
        }

        $email = session('otp_email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('login')->withErrors(['msg' => 'User not found.']);
        }

        Auth::login($user);

        session()->forget(['otp', 'otp_email', 'otp_expires_at']);

        return redirect()->route('dashboard')->with('success', 'Login successful!');
    }

    public function resend()
    {
        $email = session('otp_email');

        if (!$email) {
            return response()->json(['error' => 'Session expired. Please login again.'], 403);
        }

        $otp = rand(100000, 999999);
        session(['otp' => $otp, 'otp_expires_at' => now()->addMinutes(5)]);

        Mail::raw("Your new CaliCrane OTP is: {$otp}", function ($message) use ($email) {
            $message->to($email)
                    ->subject('CaliCrane New OTP Code');
        });

        return response()->json(['success' => true]);
    }
}
