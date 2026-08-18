<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class CommunityReport extends Model { protected $fillable=['reporter_id','reportable_type','reportable_id','reason','details','status','reviewed_by','reviewed_at','resolution_notes']; protected $casts=['reviewed_at'=>'datetime']; public function reporter(){return $this->belongsTo(User::class,'reporter_id');} }
