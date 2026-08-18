<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'product_id', 'order_item_id', 'rating', 'comment', 'status', 'verified_purchase'];

    protected $casts = ['rating' => 'integer', 'verified_purchase' => 'boolean'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
    public function orderItem(): BelongsTo { return $this->belongsTo(OrderItem::class); }
    public function images(): HasMany { return $this->hasMany(ReviewImage::class)->orderBy('sort_order'); }
}
