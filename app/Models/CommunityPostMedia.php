<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class CommunityPostMedia extends Model { protected $table='community_post_media'; protected $fillable=['post_id','media_type','file_path','thumbnail_path','width','height','alt_text','sort_order','status']; public function post(){return $this->belongsTo(CommunityPost::class,'post_id');} }
