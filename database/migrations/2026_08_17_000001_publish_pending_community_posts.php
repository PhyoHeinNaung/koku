<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $publishedAt = now();

        DB::table('community_posts')
            ->where('status', 'pending')
            ->update(['status' => 'published', 'published_at' => $publishedAt]);

        DB::table('community_post_media')
            ->where('status', 'pending')
            ->update(['status' => 'published']);
    }

    public function down(): void
    {
        // Publishing community stories is intentionally not reversed.
    }
};
