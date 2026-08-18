@php
    $statusOptions = ['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected', 'all' => 'All reviews'];
@endphp

<div class="admin-page admin-moderation-page">
    <main class="admin-moderation-shell">
        <header class="admin-moderation-head">
            <div>
                <p class="admin-moderation-eyebrow">Customer trust</p>
                <h1 class="admin-page-title">Review moderation</h1>
                <p class="admin-moderation-intro">Read the full customer submission and decide what appears on product pages.</p>
            </div>
            <div class="admin-moderation-total">
                <strong>{{ number_format($reviews->total()) }}</strong>
                <span>{{ $status === 'all' ? 'reviews' : $status.' reviews' }}</span>
            </div>
        </header>

        <nav class="admin-segmented" aria-label="Review status">
            @foreach($statusOptions as $value => $label)
                <button wire:click="$set('status', '{{ $value }}')" class="{{ $status === $value ? 'is-active' : '' }}">{{ $label }}</button>
            @endforeach
        </nav>

        @if(session('success'))
            <div class="admin-moderation-notice">{{ session('success') }}</div>
        @endif

        <section class="admin-card-list" aria-label="Reviews">
            @forelse($reviews as $review)
                <article class="admin-moderation-card" wire:key="review-{{ $review->id }}">
                    <div class="admin-card-meta">
                        <div>
                            <span class="admin-status {{ $review->status === 'approved' ? 'admin-status-success' : ($review->status === 'rejected' ? 'admin-status-warning' : 'admin-status-muted') }}">{{ str($review->status)->title() }}</span>
                            <time datetime="{{ $review->created_at->toDateString() }}">{{ $review->created_at->format('M j, Y') }}</time>
                        </div>
                        <div class="admin-stars" aria-label="{{ $review->rating }} out of 5 stars">{{ str_repeat('★', $review->rating) }}<span>{{ str_repeat('☆', 5 - $review->rating) }}</span></div>
                    </div>

                    <div class="admin-card-body">
                        <div class="admin-review-source">
                            <p>{{ $review->product->name }}</p>
                            <strong>{{ $review->user->name }}</strong>
                            <a href="mailto:{{ $review->user->email }}">{{ $review->user->email }}</a>
                        </div>
                        <blockquote>{{ $review->comment ?: 'This customer submitted a rating without written feedback.' }}</blockquote>
                        @if($review->images->isNotEmpty())
                            <p class="admin-attachment-note">{{ $review->images->count() }} {{ Str::plural('attachment', $review->images->count()) }} included with this review</p>
                        @endif
                    </div>

                    <footer class="admin-card-actions">
                        <a href="mailto:{{ $review->user->email }}?subject={{ rawurlencode('About your review of '.$review->product->name) }}">Contact customer</a>
                        <div>
                            @if($review->status !== 'rejected')<button wire:click="reject({{ $review->id }})" wire:confirm="Reject this review?" class="is-danger">Reject</button>@endif
                            @if($review->status !== 'approved')<button wire:click="approve({{ $review->id }})" class="is-primary">Approve review</button>@endif
                        </div>
                    </footer>
                </article>
            @empty
                <div class="admin-moderation-empty"><strong>Queue clear</strong><p>No {{ $status === 'all' ? '' : $status }} reviews need attention.</p></div>
            @endforelse
        </section>

        <div class="admin-card-pagination">{{ $reviews->links() }}</div>
    </main>
</div>
