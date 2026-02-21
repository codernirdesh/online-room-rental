<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Services\EsewaPaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class EsewaPaymentController extends Controller
{
    protected EsewaPaymentService $esewaService;

    public function __construct(EsewaPaymentService $esewaService)
    {
        $this->esewaService = $esewaService;
    }

    /**
     * Initiate eSewa payment - create pending booking and redirect to eSewa.
     */
    public function initiate(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'message' => 'nullable|string|max:1000',
        ]);

        if (!EsewaPaymentService::isEnabled()) {
            return back()->with('error', 'eSewa payment is currently disabled.');
        }

        $room = Room::findOrFail($request->room_id);

        if (!$room->isBookable()) {
            return back()->with('error', 'This room is no longer available for booking.');
        }

        // Generate unique transaction ID
        $transactionUuid = 'TXN-' . $room->id . '-' . auth()->id() . '-' . time();

        // Create a pending booking to track the payment
        $booking = Booking::create([
            'room_id' => $room->id,
            'renter_id' => auth()->id(),
            'message' => $request->message,
            'status' => 'pending',
            'payment_method' => 'esewa',
            'esewa_transaction_id' => $transactionUuid,
            'requested_at' => now(),
        ]);

        // Store booking ID in session for verification
        session(['esewa_booking_id' => $booking->id]);

        // Build eSewa payment form data
        $paymentData = $this->esewaService->buildPaymentData(
            (float) $room->rent_price,
            $transactionUuid
        );

        $paymentUrl = $this->esewaService->getPaymentUrl();

        return view('bookings.esewa-redirect', compact('paymentUrl', 'paymentData', 'room'));
    }

    /**
     * Handle eSewa success callback.
     */
    public function success(Request $request)
    {
        $encodedData = $request->query('data');

        if (!$encodedData) {
            Log::warning('eSewa success callback: No data received');
            return redirect()->route('my-bookings')
                ->with('error', 'Payment verification failed. No data received from eSewa.');
        }

        $bookingId = session('esewa_booking_id');
        session()->forget('esewa_booking_id');

        if (!$bookingId) {
            Log::warning('eSewa success callback: No booking ID in session');
            return redirect()->route('my-bookings')
                ->with('error', 'Payment session expired. Please contact support if payment was deducted.');
        }

        $booking = Booking::find($bookingId);

        if (!$booking) {
            Log::error('eSewa success callback: Booking not found', ['booking_id' => $bookingId]);
            return redirect()->route('my-bookings')
                ->with('error', 'Booking not found. Please contact support.');
        }

        // Verify with eSewa
        $verifiedData = $this->esewaService->verifyPayment($encodedData);

        if (!$verifiedData) {
            Log::error('eSewa: Payment verification failed for booking', ['booking_id' => $bookingId]);
            $booking->update(['status' => 'cancelled']);
            return redirect()->route('my-bookings')
                ->with('error', 'Payment verification failed. Please try again or contact support.');
        }

        // Verify transaction UUID matches
        if ($verifiedData['transaction_uuid'] !== $booking->esewa_transaction_id) {
            Log::error('eSewa: Transaction UUID mismatch', [
                'expected' => $booking->esewa_transaction_id,
                'received' => $verifiedData['transaction_uuid'],
            ]);
            $booking->update(['status' => 'cancelled']);
            return redirect()->route('my-bookings')
                ->with('error', 'Payment verification failed. Transaction mismatch.');
        }

        // Update booking with payment details
        $booking->update([
            'status' => 'paid',
            'paid_at' => now(),
            'esewa_amount' => $verifiedData['total_amount'],
            'esewa_ref_id' => $verifiedData['ref_id'],
        ]);

        // Mark room as booked
        $booking->room->update(['status' => 'booked']);

        Log::info('eSewa: Payment successful', [
            'booking_id' => $booking->id,
            'transaction_uuid' => $verifiedData['transaction_uuid'],
            'amount' => $verifiedData['total_amount'],
        ]);

        return redirect()->route('my-bookings')
            ->with('success', 'Payment successful! Your booking has been confirmed via eSewa.');
    }

    /**
     * Handle eSewa failure callback.
     */
    public function failure(Request $request)
    {
        $bookingId = session('esewa_booking_id');
        session()->forget('esewa_booking_id');

        if ($bookingId) {
            $booking = Booking::find($bookingId);
            if ($booking && $booking->status === 'pending') {
                $booking->update(['status' => 'cancelled']);
            }
        }

        Log::info('eSewa: Payment failed/cancelled by user', ['booking_id' => $bookingId]);

        return redirect()->route('my-bookings')
            ->with('error', 'Payment was cancelled or failed. Please try again.');
    }
}
