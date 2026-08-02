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
        Schema::create('product_specifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->unique()->constrained('products')->cascadeOnDelete();

            // Case
            $table->string('case_size')->nullable();
            $table->string('case_material')->nullable();
            $table->string('case_thickness')->nullable();
            $table->string('water_resistance')->nullable();
            $table->string('glass_type')->nullable(); // treated as the crystal field
            $table->string('weight')->nullable();
            $table->string('dial_color')->nullable();

            // Mechanical / analog movement detail
            $table->string('movement_caliber')->nullable();
            $table->string('power_reserve')->nullable();
            $table->string('frequency')->nullable();
            $table->string('jewels')->nullable();
            $table->string('functions')->nullable();

            // Strap / bracelet
            $table->string('strap_material')->nullable();
            $table->string('clasp_type')->nullable();

            // Smart / sport watches
            $table->string('battery_life')->nullable();
            $table->string('display_type')->nullable();
            $table->string('connectivity')->nullable();
            $table->string('compatibility')->nullable();

            // Origin
            $table->string('country_of_origin')->nullable();

            // Safety net: anything not covered by the fixed columns above,
            // without needing another migration. Rendered as free-form
            // key/value pairs in the admin form.
            $table->json('custom_specifications')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_specifications');
    }
};
