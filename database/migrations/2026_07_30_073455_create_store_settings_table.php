<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('store_settings', function (Blueprint $table) {
            $table->id();
            $table->string('store_name')->default('TICKS');
            $table->string('legal_name')->nullable();
            $table->string('support_email')->nullable();
            $table->string('support_phone', 40)->nullable();
            $table->text('business_address')->nullable();
            $table->string('default_country', 100)->default('Myanmar');
            $table->string('order_prefix', 8)->default('TCK');
            $table->unsignedSmallInteger('low_stock_threshold')->default(5);
            $table->boolean('insurance_enabled')->default(true);
            $table->decimal('insurance_rate', 6, 4)->default(0.0200);
            $table->boolean('guest_checkout_enabled')->default(true);
            $table->timestamps();
        });

        DB::table('store_settings')->insert([
            'store_name' => 'TICKS',
            'default_country' => 'Myanmar',
            'order_prefix' => 'TCK',
            'low_stock_threshold' => 5,
            'insurance_enabled' => true,
            'insurance_rate' => 0.0200,
            'guest_checkout_enabled' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_settings');
    }
};
