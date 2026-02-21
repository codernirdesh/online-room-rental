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
            $table->string('payment_method')->nullable()->after('payment_screenshot'); // 'qr' or 'esewa'
            $table->string('esewa_transaction_id')->nullable()->after('payment_method');
            $table->decimal('esewa_amount', 10, 2)->nullable()->after('esewa_transaction_id');
            $table->string('esewa_ref_id')->nullable()->after('esewa_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'esewa_transaction_id', 'esewa_amount', 'esewa_ref_id']);
        });
    }
};
