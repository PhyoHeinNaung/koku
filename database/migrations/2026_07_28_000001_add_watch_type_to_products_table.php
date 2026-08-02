<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('watch_type')->default('traditional')->after('gender')->index();
        });

        DB::table('products')
            ->where('movement', 'smart')
            ->update(['watch_type' => 'smart']);
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['watch_type']);
            $table->dropColumn('watch_type');
        });
    }
};
