<?php
namespace App\Livewire\Admin\Community;
use App\Models\CommunityComment; use App\Models\CommunityPost; use App\Models\CommunityReport; use Illuminate\Support\Facades\Auth; use Livewire\Component;
class Index extends Component {
 public string $tab='pending';
 public function approve(CommunityPost $post):void{$post->update(['status'=>'published','published_at'=>now()]);$post->media()->update(['status'=>'published']);session()->flash('success','Community post published.');}
 public function reject(CommunityPost $post):void{$post->update(['status'=>'rejected']);$post->media()->update(['status'=>'rejected']);session()->flash('success','Community post rejected.');}
 public function feature(CommunityPost $post):void{$post->update(['is_featured'=>!$post->is_featured]);}
 public function hideComment(CommunityComment $comment):void{$comment->update(['status'=>'hidden']);$comment->post->update(['comments_count'=>$comment->post->comments()->where('status','published')->count()]);}
 public function resolveReport(CommunityReport $report):void{$report->update(['status'=>'resolved','reviewed_by'=>Auth::id(),'reviewed_at'=>now()]);}
 public function render(){ $posts=CommunityPost::with(['user','product','media'])->when($this->tab==='pending',fn($q)=>$q->where('status','pending'))->when($this->tab==='published',fn($q)=>$q->where('status','published'))->latest()->take(30)->get();$reports=CommunityReport::with('reporter')->where('status','open')->latest()->get();$comments=CommunityComment::with(['user','post'])->where('status','published')->latest()->take(30)->get();return view('livewire.admin.community.index',compact('posts','reports','comments'))->layout('layouts.admin'); }
}
