<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetOtpMail;
use App\Models\PasswordResetOtp;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the forgot password form.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Send a password reset OTP to the user's email.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withInput($request->only('email'))
                ->withErrors(['email' => 'We can\'t find a user with that email address.']);
        }

        // Delete old OTPs for this email
        PasswordResetOtp::where('email', $request->email)->delete();

        // Generate new OTP
        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        PasswordResetOtp::create([
            'email' => $request->email,
            'otp' => $otp,
            'expires_at' => now()->addMinutes(10),
        ]);

        Mail::to($request->email)->send(new PasswordResetOtpMail($otp, $user->name));

        // Store email in session for the OTP verification step
        $request->session()->put('password_reset_email', $request->email);

        return redirect()->route('password.verify-otp')
            ->with('status', 'We have sent an OTP to your email address.');
    }

    /**
     * Show the OTP verification form for password reset.
     */
    public function showVerifyOtp(Request $request): View|RedirectResponse
    {
        $email = $request->session()->get('password_reset_email');

        if (!$email) {
            return redirect()->route('password.request')
                ->with('status', 'Please enter your email first.');
        }

        return view('auth.verify-reset-otp', ['email' => $email]);
    }

    /**
     * Verify the password reset OTP.
     */
    public function verifyOtp(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $email = $request->session()->get('password_reset_email');

        if (!$email) {
            return redirect()->route('password.request')
                ->with('status', 'Session expired. Please try again.');
        }

        $otpRecord = PasswordResetOtp::where('email', $email)
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

        // OTP verified — allow password reset
        $request->session()->put('password_reset_verified', true);

        return redirect()->route('password.reset');
    }

    /**
     * Resend the password reset OTP.
     */
    public function resendOtp(Request $request): RedirectResponse
    {
        $email = $request->session()->get('password_reset_email');

        if (!$email) {
            return redirect()->route('password.request')
                ->with('status', 'Please enter your email first.');
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'No account found with this email.']);
        }

        // Delete old OTPs
        PasswordResetOtp::where('email', $email)->delete();

        // Generate new OTP
        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        PasswordResetOtp::create([
            'email' => $email,
            'otp' => $otp,
            'expires_at' => now()->addMinutes(10),
        ]);

        Mail::to($email)->send(new PasswordResetOtpMail($otp, $user->name));

        return back()->with('status', 'A new OTP has been sent to your email.');
    }
}
