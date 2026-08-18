@php($tabs = ['posts' => 'Gallery posts', 'comments' => 'Comments', 'reports' => 'Reports'])

<div class="admin-page admin-moderation-page">
    <main class="admin-moderation-shell">
        <header class="admin-moderation-head">
            <div>
                <p class="admin-moderation-eyebrow">Community safety</p>
                <h1 class="admin-page-title">Wrist Stories</h1>
                <p class="admin-moderation-intro">Posts go live immediately. Feature good stories and act on content that breaks community rules.</p>
            </div>
            <div class="admin-moderation-total">
                <strong>{{ $tab === 'posts' ? $posts->count() : ($tab === 'comments' ? $comments->count() : $reports->count()) }}</strong>
                <span>{{ $tab === 'posts' ? 'recent posts' : ($tab === 'comments' ? 'recent comments' : 'open reports') }}</span>
            </div>
        </header>

        <nav class="admin-segmented" aria-label="Community moderation sections">
            @foreach($tabs as $value => $label)<button wire:click="$set('tab','{{ $value }}')" class="{{ $tab === $value ? 'is-active' : '' }}">{{ $label }}</button>@endforeach
        </nav>

        @if(session('success'))<div class="admin-moderation-notice">{{ session('success') }}</div>@endif

        <section class="admin-card-list" aria-label="Community moderation queue">
            @if($tab === 'posts')
                @forelse($posts as $post)
                    <article class="admin-moderation-card" wire:key="post-{{ $post->id }}">
                        <div class="admin-card-meta">
                            <div><span class="admin-status {{ $post->visibility === 'public' ? 'admin-status-success' : 'admin-status-warning' }}">{{ $post->visibility === 'public' ? 'Live' : 'Hidden' }}</span>@if($post->is_featured)<span class="admin-featured-label">Featured</span>@endif</div>
                            <time datetime="{{ ($post->published_at ?? $post->created_at)->toDateString() }}">{{ ($post->published_at ?? $post->created_at)->format('M j, Y · g:i A') }}</time>
                        </div>
                        <div class="admin-card-body">
                            <div class="admin-review-source"><p>{{ $post->product->name }}</p><strong>{{ $post->user->name }}</strong><span>Verified owner story</span></div>
                            <blockquote>{{ $post->caption ?: 'This story was published without a caption.' }}</blockquote>
                        </div>
                        <footer class="admin-card-actions">
                            <span>{{ $post->likes_count }} likes · {{ $post->comments_count }} comments</span>
                            <div>
                                @if($post->visibility === 'public')
                                    <a href="{{ route('community.show', $post) }}" target="_blank" rel="noopener">Open story</a>
                                    <button wire:click="feature({{ $post->id }})">{{ $post->is_featured ? 'Unfeature' : 'Feature' }}</button>
                                    <button wire:click="removePost({{ $post->id }})" wire:confirm="Remove this post from the public gallery?" class="is-danger">Remove</button>
                                @else
                                    <button wire:click="restorePost({{ $post->id }})" class="is-primary">Restore post</button>
                                @endif
                            </div>
                        </footer>
                    </article>
                @empty<div class="admin-moderation-empty"><strong>No community posts yet</strong><p>Published stories will appear here.</p></div>@endforelse
            @elseif($tab === 'comments')
                @forelse($comments as $comment)
                    <article class="admin-moderation-card is-compact" wire:key="comment-{{ $comment->id }}">
                        <div class="admin-card-meta"><div><span class="admin-status admin-status-success">Published</span><strong>{{ $comment->user->name }}</strong></div><time>{{ $comment->created_at->format('M j, Y · g:i A') }}</time></div>
                        <div class="admin-card-body"><div class="admin-review-source"><p>Story #{{ $comment->post_id }}</p><span>Community comment</span></div><blockquote>{{ $comment->body }}</blockquote></div>
                        <footer class="admin-card-actions"><span>Visible to the community</span><button wire:click="hideComment({{ $comment->id }})" wire:confirm="Hide this comment?" class="is-danger">Hide comment</button></footer>
                    </article>
                @empty<div class="admin-moderation-empty"><strong>No published comments</strong><p>Community conversations will appear here.</p></div>@endforelse
            @else
                @forelse($reports as $report)
                    <article class="admin-moderation-card is-report" wire:key="report-{{ $report->id }}">
                        <div class="admin-card-meta"><div><span class="admin-status admin-status-warning">{{ str($report->reason)->title() }}</span><strong>{{ $report->reporter->name }}</strong></div><time>{{ $report->created_at->diffForHumans() }}</time></div>
                        <div class="admin-card-body"><div class="admin-review-source"><p>{{ str($report->reportable_type)->title() }} #{{ $report->reportable_id }}</p><span>Reported content</span></div><blockquote>{{ $report->details ?: 'The reporter did not provide additional details.' }}</blockquote></div>
                        <footer class="admin-card-actions"><span>Open report</span><button wire:click="resolveReport({{ $report->id }})" class="is-primary">Mark resolved</button></footer>
                    </article>
                @empty<div class="admin-moderation-empty"><strong>No open reports</strong><p>The community queue is clear.</p></div>@endforelse
            @endif
        </section>
    </main>
</div>
