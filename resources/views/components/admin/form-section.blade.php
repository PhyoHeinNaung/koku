@props([
    'title',
    'description' => null,
    'eyebrow' => null,
])

<section {{ $attributes->class('admin-editor-section') }}>
    <header class="admin-editor-section-head">
        <div class="admin-editor-section-heading">
            @isset($icon)
                <span class="admin-editor-section-icon" aria-hidden="true">{{ $icon }}</span>
            @endisset

            <span class="min-w-0">
                @if ($eyebrow)
                    <small class="admin-editor-section-kicker">{{ $eyebrow }}</small>
                @endif
                <h2>{{ $title }}</h2>
                @if ($description)
                    <p>{{ $description }}</p>
                @endif
            </span>
        </div>

        @isset($actions)
            <div class="admin-editor-section-actions">
                {{ $actions }}
            </div>
        @endisset
    </header>

    <div class="admin-editor-section-body">
        {{ $slot }}
    </div>
</section>
