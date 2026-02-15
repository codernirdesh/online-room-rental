<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\RoomController as AdminRoomController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Owner\RoomController as OwnerRoomController;
use App\Http\Controllers\Owner\BookingController as OwnerBookingController;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/rooms', [RoomController::class, 'index'])->name('rooms.index');
Route::get('/rooms/{room}', [RoomController::class, 'show'])->name('rooms.show');

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('my-bookings');
    
    // Renter routes
    Route::middleware('role:renter')->group(function () {
        Route::get('/checkout/{room}', [BookingController::class, 'checkout'])->name('bookings.checkout');
        Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    });
    
    // Owner routes
    Route::middleware('role:owner')->prefix('owner')->name('owner.')->group(function () {
        Route::resource('rooms', OwnerRoomController::class);
        Route::get('/bookings', [OwnerBookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/{booking}', [OwnerBookingController::class, 'show'])->name('bookings.show');
        Route::patch('/bookings/{booking}/approve', [OwnerBookingController::class, 'approve'])->name('bookings.approve');
        Route::patch('/bookings/{booking}/reject', [OwnerBookingController::class, 'reject'])->name('bookings.reject');
    });
    
    // Admin routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::resource('users', AdminUserController::class)->except(['create', 'store', 'show']);
        Route::resource('rooms', AdminRoomController::class);
        Route::patch('/rooms/{room}/deactivate', [AdminRoomController::class, 'deactivate'])->name('rooms.deactivate');
        Route::patch('/rooms/{room}/activate', [AdminRoomController::class, 'activate'])->name('rooms.activate');
        Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
        Route::get('/bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
        Route::patch('/bookings/{booking}/approve', [AdminBookingController::class, 'approve'])->name('bookings.approve');
        Route::patch('/bookings/{booking}/reject', [AdminBookingController::class, 'reject'])->name('bookings.reject');
        Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
        Route::post('/settings/payment-qr', [AdminSettingController::class, 'updatePaymentQr'])->name('settings.update-payment-qr');
    });
});

require __DIR__.'/auth.php';
