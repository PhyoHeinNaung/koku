<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('coupon_id')->nullable()->constrained('coupons')->nullOnDelete();
            $table->foreignId('shipping_location_id')->constrained('shipping_locations')->restrictOnDelete();

            $table->string('order_number', 50)->unique();

            $table->string('shipping_full_name');
            $table->string('shipping_phone', 20);
            $table->string('shipping_country');
            $table->string('shipping_state_region')->nullable();
            $table->string('shipping_city');
            $table->string('shipping_district_area')->nullable();
            $table->string('shipping_postal_code', 20)->nullable();
            $table->string('shipping_address_line1');
            $table->string('shipping_address_line2')->nullable();

            $table->string('billing_full_name');
            $table->string('billing_phone', 20);
            $table->string('billing_country');
            $table->string('billing_state_region')->nullable();
            $table->string('billing_city');
            $table->string('billing_district_area')->nullable();
            $table->string('billing_postal_code', 20)->nullable();
            $table->string('billing_address_line1');
            $table->string('billing_address_line2')->nullable();

            $table->foreignId('shipping_address_id')->nullable()->constrained('addresses')->nullOnDelete();
            $table->foreignId('billing_address_id')->nullable()->constrained('addresses')->nullOnDelete();

            $table->decimal('subtotal', 10, 2);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('tax', 10, 2)->default(0);
            $table->decimal('shipping_fee', 10, 2)->default(0);
            $table->decimal('insurance_fee', 10, 2)->default(0);
            $table->decimal('total', 10, 2);

            $table->enum('status', ['pending', 'processing', 'shipped', 'delivered', 'cancelled'])->default('pending');

            $table->string('stripe_payment_intent_id')->nullable()->unique();

            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
