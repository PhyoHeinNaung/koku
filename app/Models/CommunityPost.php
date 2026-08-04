<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class CommunityPost extends Model {
    use SoftDeletes;
    protected $fillable=['user_id','product_id','order_item_id','caption','location','status','visibility','is_featured','likes_count','comments_count','published_at'];
    protected $casts=['is_featured'=>'boolean','published_at'=>'datetime'];
    public function user(){return $this->belongsTo(User::class);}
    public function product(){return $this->belongsTo(Product::class);}
    public function orderItem(){return $this->belongsTo(OrderItem::class);}
    public function media(){return $this->hasMany(CommunityPostMedia::class,'post_id')->orderBy('sort_order');}
    public function likes(){return $this->hasMany(CommunityPostLike::class,'post_id');}
    public function comments(){return $this->hasMany(CommunityComment::class,'post_id');}
}
