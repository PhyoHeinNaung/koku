<?php

namespace App\Livewire\Admin\Community;

use App\Models\CommunityComment;
use App\Models\CommunityPost;
use App\Models\CommunityReport;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public string $tab = 'posts';

    public function feature(CommunityPost $post): void
    {
        $post->update(['is_featured' => ! $post->is_featured]);
    }

    public function removePost(CommunityPost $post): void
    {
        $post->update(['visibility' => 'hidden', 'is_featured' => false]);
        session()->flash('success', 'Community post removed from the public gallery.');
    }

    public function restorePost(CommunityPost $post): void
    {
        $post->update(['visibility' => 'public']);
        session()->flash('success', 'Community post restored to the public gallery.');
    }

    public function hideComment(CommunityComment $comment): void
    {
        $comment->update(['status' => 'hidden']);
        $comment->post->update(['comments_count' => $comment->post->comments()->where('status', 'published')->count()]);
        session()->flash('success', 'Comment hidden.');
    }

    public function resolveReport(CommunityReport $report): void
    {
        $report->update(['status' => 'resolved', 'reviewed_by' => Auth::id(), 'reviewed_at' => now()]);
        session()->flash('success', 'Report resolved.');
    }

    public function render()
    {
        $posts = CommunityPost::with(['user', 'product', 'media'])
            ->where('status', 'published')
            ->latest('published_at')
            ->take(30)
            ->get();
        $reports = CommunityReport::with('reporter')->where('status', 'open')->latest()->get();
        $comments = CommunityComment::with(['user', 'post'])->where('status', 'published')->latest()->take(30)->get();

        return view('livewire.admin.community.index', compact('posts', 'reports', 'comments'))->layout('layouts.admin');
    }
}
