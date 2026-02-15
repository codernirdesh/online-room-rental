<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SettingController extends Controller
{
    /**
     * Show the settings page.
     */
    public function index(): View
    {
        $paymentQr = Setting::get('payment_qr');

        return view('admin.settings.index', compact('paymentQr'));
    }

    /**
     * Update the payment QR code.
     */
    public function updatePaymentQr(Request $request): RedirectResponse
    {
        $request->validate([
            'payment_qr' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        // Delete old QR image if exists
        $oldQr = Setting::get('payment_qr');
        if ($oldQr && Storage::disk('public')->exists($oldQr)) {
            Storage::disk('public')->delete($oldQr);
        }

        // Store new QR image
        $path = $request->file('payment_qr')->store('settings', 'public');

        Setting::set('payment_qr', $path);

        return back()->with('success', 'Payment QR code updated successfully.');
    }
}
