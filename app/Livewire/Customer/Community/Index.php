<?php
namespace App\Livewire\Customer\Community;
use App\Models\CommunityPost;
use App\Models\CommunityPostLike;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
class Index extends Component {
    use WithPagination;
    public string $sort='latest';
    public function toggleLike(int $postId): void {
        if(!Auth::check()){ $this->redirectRoute('login'); return; }
        $post=CommunityPost::where('status','published')->findOrFail($postId);
        $like=CommunityPostLike::where('post_id',$post->id)->where('user_id',Auth::id())->first();
        $like ? $like->delete() : CommunityPostLike::create(['post_id'=>$post->id,'user_id'=>Auth::id()]);
        $post->update(['likes_count'=>$post->likes()->count()]);
    }
    public function render(){
        $posts=CommunityPost::with(['user','product.brand','media'=>fn($q)=>$q->where('status','published')])->where('status','published')->where('visibility','public')->when($this->sort==='popular',fn($q)=>$q->orderByDesc('likes_count'))->when($this->sort==='latest',fn($q)=>$q->latest('published_at'))->paginate(18);
        $liked=Auth::check()?CommunityPostLike::where('user_id',Auth::id())->pluck('post_id')->all():[];
        return view('livewire.customer.community.index',compact('posts','liked'))->layout('layouts.app',['overlay'=>false]);
    }
}
