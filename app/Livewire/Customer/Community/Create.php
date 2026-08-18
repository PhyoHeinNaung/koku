<?php
namespace App\Livewire\Customer\Community;
use App\Models\CommunityPost;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
class Create extends Component {
    use WithFileUploads;
    public ?int $productId=null; public string $caption=''; public string $location=''; public array $photos=[];
    public function save(): void {
        $data=$this->validate(['productId'=>['required','integer'],'caption'=>['nullable','string','max:2000'],'location'=>['nullable','string','max:150'],'photos'=>['required','array','min:1','max:5'],'photos.*'=>['image','mimes:jpg,jpeg,png,webp','max:5120']]);
        $item=$this->eligibleItems()->first(fn($item)=>$item->variant?->product_id===$data['productId']); abort_unless($item,403);
        $post=CommunityPost::create(['user_id'=>Auth::id(),'product_id'=>$data['productId'],'order_item_id'=>$item->id,'caption'=>trim($data['caption'])?:null,'location'=>trim($data['location'])?:null,'status'=>'published','visibility'=>'public','published_at'=>now()]);
        foreach($this->photos as $i=>$photo){$size=@getimagesize($photo->getRealPath());$post->media()->create(['file_path'=>$photo->store('community','public'),'width'=>$size[0]??null,'height'=>$size[1]??null,'sort_order'=>$i,'status'=>'published']);}
        session()->flash('community-success','Your wrist story is now live.'); $this->redirectRoute('community.index');
    }
    private function eligibleItems(){return OrderItem::with(['variant.images','variant.product.brand','variant.product.variants.images'])->whereHas('order',fn($q)=>$q->when(Auth::user()?->role!=='admin',fn($order)=>$order->where('user_id',Auth::id()))->where('status','delivered')->whereHas('payments',fn($payment)=>$payment->where('status','paid')))->whereHas('variant.product')->get()->unique(fn($i)=>$i->variant->product_id)->values();}
    public function render(){return view('livewire.customer.community.create',['eligibleItems'=>$this->eligibleItems()])->layout('layouts.app',['overlay'=>false]);}
}
