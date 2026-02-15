<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Room;
use App\Models\Booking;
use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'phone' => '9801234567',
            'role' => 'admin',
        ]);

        // Create owner users
        $owner1 = User::create([
            'name' => 'John Doe',
            'email' => 'owner1@example.com',
            'password' => Hash::make('password'),
            'phone' => '9801234568',
            'role' => 'owner',
        ]);

        $owner2 = User::create([
            'name' => 'Jane Smith',
            'email' => 'owner2@example.com',
            'password' => Hash::make('password'),
            'phone' => '9801234569',
            'role' => 'owner',
        ]);

        // Create renter users
        $renter1 = User::create([
            'name' => 'Alice Johnson',
            'email' => 'renter1@example.com',
            'password' => Hash::make('password'),
            'phone' => '9801234570',
            'role' => 'renter',
        ]);

        $renter2 = User::create([
            'name' => 'Bob Williams',
            'email' => 'renter2@example.com',
            'password' => Hash::make('password'),
            'phone' => '9801234571',
            'role' => 'renter',
        ]);

        $renter3 = User::create([
            'name' => 'Charlie Brown',
            'email' => 'renter3@example.com',
            'password' => Hash::make('password'),
            'phone' => '9801234572',
            'role' => 'renter',
        ]);

        // Create rooms for owner1
        $room1 = Room::create([
            'owner_id' => $owner1->id,
            'title' => 'Cozy Single Room in Kathmandu',
            'description' => 'A comfortable single room perfect for students or working professionals. Close to public transport.',
            'address' => 'Thamel Street 123',
            'city' => 'Kathmandu',
            'province' => 'Bagmati',
            'rent_price' => 8000.00,
            'room_type' => 'single',
            'amenities' => 'WiFi, Parking, 24/7 Water',
            'available_from' => now()->addDays(5),
            'status' => 'available',
        ]);

        $room2 = Room::create([
            'owner_id' => $owner1->id,
            'title' => 'Spacious Double Room with Mountain View',
            'description' => 'Beautiful double room with stunning mountain views. Fully furnished with modern amenities.',
            'address' => 'Lazimpat Road 45',
            'city' => 'Kathmandu',
            'province' => 'Bagmati',
            'rent_price' => 15000.00,
            'room_type' => 'double',
            'amenities' => 'WiFi, Parking, Kitchen, Balcony',
            'available_from' => now()->addDays(10),
            'status' => 'available',
        ]);

        // Create rooms for owner2
        $room3 = Room::create([
            'owner_id' => $owner2->id,
            'title' => 'Modern Flat in Pokhara',
            'description' => 'Newly built flat with all modern facilities. Located in the heart of Pokhara.',
            'address' => 'Lakeside Street 78',
            'city' => 'Pokhara',
            'province' => 'Gandaki',
            'rent_price' => 20000.00,
            'room_type' => 'flat',
            'amenities' => 'WiFi, Parking, Kitchen, Washing Machine, Furnished',
            'available_from' => now()->addDays(15),
            'status' => 'available',
        ]);

        $room4 = Room::create([
            'owner_id' => $owner2->id,
            'title' => 'Luxury Apartment in Patan',
            'description' => 'Premium apartment with 3 bedrooms and 2 bathrooms. Perfect for families.',
            'address' => 'Jawalakhel Road 90',
            'city' => 'Lalitpur',
            'province' => 'Bagmati',
            'rent_price' => 35000.00,
            'room_type' => 'apartment',
            'amenities' => 'WiFi, Parking, Kitchen, Gym, Swimming Pool, Security',
            'available_from' => now()->addDays(20),
            'status' => 'available',
        ]);

        $room5 = Room::create([
            'owner_id' => $owner2->id,
            'title' => 'Budget Single Room in Bhaktapur',
            'description' => 'Affordable single room for students. Near to Bhaktapur Durbar Square.',
            'address' => 'Durbar Square Area',
            'city' => 'Bhaktapur',
            'province' => 'Bagmati',
            'rent_price' => 6000.00,
            'room_type' => 'single',
            'amenities' => 'WiFi, Shared Kitchen',
            'available_from' => now(),
            'status' => 'available',
        ]);

        // Create bookings
        Booking::create([
            'room_id' => $room1->id,
            'renter_id' => $renter1->id,
            'message' => 'I am interested in this room. Can we schedule a visit?',
            'status' => 'paid',
            'payment_screenshot' => 'payments/sample1.png',
            'paid_at' => now(),
            'requested_at' => now(),
        ]);

        Booking::create([
            'room_id' => $room3->id,
            'renter_id' => $renter2->id,
            'message' => 'Looking for a flat in Pokhara. This looks perfect!',
            'status' => 'approved',
            'payment_screenshot' => 'payments/sample2.png',
            'paid_at' => now()->subDays(3),
            'requested_at' => now()->subDays(2),
        ]);

        Booking::create([
            'room_id' => $room4->id,
            'renter_id' => $renter3->id,
            'message' => 'Need a family apartment. When can I move in?',
            'status' => 'paid',
            'payment_screenshot' => 'payments/sample3.png',
            'paid_at' => now()->subDays(1),
            'requested_at' => now()->subDays(1),
        ]);

        Booking::create([
            'room_id' => $room2->id,
            'renter_id' => $renter1->id,
            'message' => 'Interested in the mountain view room.',
            'status' => 'rejected',
            'payment_screenshot' => 'payments/sample4.png',
            'paid_at' => now()->subDays(6),
            'requested_at' => now()->subDays(5),
        ]);

        // Create default settings
        Setting::set('payment_qr', null); // Admin will upload QR from settings page
    }
}
