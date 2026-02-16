<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\OtpVerificationMail;
use App\Models\EmailVerificationOtp;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class OtpVerificationController extends Controller
{
    /**
     * Show the OTP verification form.
     */
    public function show(Request $request): View|RedirectResponse
    {
        $email = $request->session()->get('otp_verification_email');

        if (!$email) {
            return redirect()->route('register')
                ->with('status', 'Please register first to verify your email.');
        }

        return view('auth.verify-otp', ['email' => $email]);
    }

    /**
     * Verify the submitted OTP.
     */
    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $email = $request->session()->get('otp_verification_email');

        if (!$email) {
            return redirect()->route('register')
                ->with('status', 'Session expired. Please register again.');
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('register')
                ->withErrors(['email' => 'No account found with this email.']);
        }

        $otpRecord = EmailVerificationOtp::where('user_id', $user->id)
            ->latest()
            ->first();

        if (!$otpRecord) {
            return back()->withErrors(['otp' => 'No OTP found. Please request a new one.']);
        }

        if ($otpRecord->isExpired()) {
            return back()->withErrors(['otp' => 'This OTP has expired. Please request a new one.']);
        }

        if ($otpRecord->otp !== $request->otp) {
            return back()->withErrors(['otp' => 'Invalid OTP. Please try again.']);
        }

        // Mark email as verified
        $user->email_verified_at = now();
        $user->save();

        // Clean up OTPs
        EmailVerificationOtp::where('user_id', $user->id)->delete();

        // Clear session
        $request->session()->forget('otp_verification_email');

        // Log the user in
        Auth::login($user);

        return redirect()->route('dashboard')
            ->with('status', 'Email verified successfully! Welcome to ' . config('app.name') . '.');
    }

    /**
     * Resend OTP to the user.
     */
    public function resend(Request $request): RedirectResponse
    {
        $email = $request->session()->get('otp_verification_email');

        if (!$email) {
            return redirect()->route('register')
                ->with('status', 'Please register first.');
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('register')
                ->withErrors(['email' => 'No account found with this email.']);
        }

        // Delete old OTPs
        EmailVerificationOtp::where('user_id', $user->id)->delete();

        // Generate new OTP
        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        EmailVerificationOtp::create([
            'user_id' => $user->id,
            'otp' => $otp,
            'expires_at' => now()->addMinutes(10),
        ]);

        Mail::to($user->email)->send(new OtpVerificationMail($otp, $user->name));

        return back()->with('status', 'A new OTP has been sent to your email.');
    }
}
