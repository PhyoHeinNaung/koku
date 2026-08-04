<?php
namespace App\Livewire\Customer\Community;
use App\Models\CommunityComment;
use App\Models\CommunityPost;
use App\Models\CommunityPostLike;
use App\Models\CommunityReport;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
class Show extends Component {
    public CommunityPost $post; public string $comment=''; public string $reportReason='spam'; public string $reportDetails='';
    public function mount(CommunityPost $post):void{abort_unless($post->status==='published'&&$post->visibility==='public',404);$this->post=$post;}
    public function toggleLike():void{if(!Auth::check()){$this->redirectRoute('login');return;}$like=CommunityPostLike::where('post_id',$this->post->id)->where('user_id',Auth::id())->first();$like?$like->delete():CommunityPostLike::create(['post_id'=>$this->post->id,'user_id'=>Auth::id()]);$this->post->update(['likes_count'=>$this->post->likes()->count()]);}
    public function addComment():void{abort_unless(Auth::check(),403);$data=$this->validate(['comment'=>['required','string','max:1000']]);CommunityComment::create(['post_id'=>$this->post->id,'user_id'=>Auth::id(),'body'=>trim($data['comment']),'status'=>'published']);$this->comment='';$this->post->update(['comments_count'=>$this->post->comments()->where('status','published')->count()]);}
    public function deleteComment(int $id):void{$comment=CommunityComment::where('post_id',$this->post->id)->findOrFail($id);abort_unless(Auth::id()===$comment->user_id||Auth::user()?->role==='admin',403);$comment->delete();$this->post->update(['comments_count'=>$this->post->comments()->where('status','published')->count()]);}
    public function report(string $type,int $id):void{abort_unless(Auth::check(),403);abort_unless(in_array($type,['post','comment'],true),422);if($type==='post')abort_unless($id===$this->post->id,404);else CommunityComment::where('post_id',$this->post->id)->findOrFail($id);$data=$this->validate(['reportReason'=>['required','in:spam,harassment,inappropriate,copyright,misleading,other'],'reportDetails'=>['nullable','string','max:1000']]);CommunityReport::updateOrCreate(['reporter_id'=>Auth::id(),'reportable_type'=>$type,'reportable_id'=>$id],['reason'=>$data['reportReason'],'details'=>trim($data['reportDetails'])?:null,'status'=>'open']);session()->flash('report-success','Thank you. The report was sent to our moderation team.');}
    public function render(){$this->post->load(['user','product.brand','product.variants.images','media'=>fn($q)=>$q->where('status','published')]);$comments=CommunityComment::with('user')->where('post_id',$this->post->id)->whereNull('parent_id')->where('status','published')->oldest()->get();$liked=Auth::check()&&CommunityPostLike::where('post_id',$this->post->id)->where('user_id',Auth::id())->exists();return view('livewire.customer.community.show',compact('comments','liked'))->layout('layouts.app',['overlay'=>false]);}
}
