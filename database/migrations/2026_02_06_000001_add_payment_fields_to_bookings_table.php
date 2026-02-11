<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('payment_screenshot')->nullable()->after('status');
            $table->timestamp('paid_at')->nullable()->after('payment_screenshot');
        });

        // Update the status enum to include new payment statuses
        // We'll use DB::statement for MySQL enum modification
        \DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending', 'paid', 'approved', 'rejected', 'cancelled') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending', 'approved', 'rejected', 'cancelled') DEFAULT 'pending'");

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['payment_screenshot', 'paid_at']);
        });
    }
};
