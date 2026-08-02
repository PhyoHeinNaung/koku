<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_variant_specifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_variant_id')
                ->unique()
                ->constrained('product_variants')
                ->cascadeOnDelete();
            $table->json('overrides')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_variant_specifications');
    }
};
