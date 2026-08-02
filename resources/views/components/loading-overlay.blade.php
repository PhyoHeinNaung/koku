<div x-data="{
        active: false,
        showTimer: null,
        hideTimer: null,
        start() {
            clearTimeout(this.hideTimer);
            // Small delay before showing, so fast requests don't flash the overlay.
            this.showTimer = setTimeout(() => { this.active = true; }, 150);
        },
        stop() {
            clearTimeout(this.showTimer);
            this.hideTimer = setTimeout(() => { this.active = false; }, 150);
        },
    }" @loading-bar-start.window="start()" @loading-bar-stop.window="stop()" x-cloak x-show="active"
    x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-[9999] flex items-center justify-center bg-white/70 backdrop-blur-sm">
    <div class="flex flex-col items-center gap-4">
        <div class="relative h-12 w-12">
            <div class="absolute inset-0 rounded-full border-2 border-gray-200"></div>
            <div class="absolute inset-0 rounded-full border-2 border-gray-900 border-t-transparent animate-spin"></div>
        </div>
        <x-brand-logo class="h-4 w-20 text-gray-900" />
    </div>
</div>
