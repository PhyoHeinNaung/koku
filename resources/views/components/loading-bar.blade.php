<div x-data="{
        active: false,
        width: 0,
        timer: null,
        start() {
            clearTimeout(this.timer);
            this.active = true;
            this.width = 0;
            // Animate up to 90% and stall there — the real network response
            // decides when we jump to 100% and fade out, same idea as
            // YouTube/NProgress: never let the fake progress finish on its own.
            requestAnimationFrame(() => {
                this.width = 20;
                setTimeout(() => { if (this.active) this.width = 45; }, 150);
                setTimeout(() => { if (this.active) this.width = 65; }, 400);
                setTimeout(() => { if (this.active) this.width = 80; }, 900);
                setTimeout(() => { if (this.active) this.width = 90; }, 1800);
            });
        },
        stop() {
            this.width = 100;
            clearTimeout(this.timer);
            this.timer = setTimeout(() => {
                this.active = false;
                this.width = 0;
            }, 250);
        },
    }" @loading-bar-start.window="start()" @loading-bar-stop.window="stop()" x-cloak x-show="active"
    class="fixed top-0 inset-x-0 z-[9999] h-[3px] bg-transparent pointer-events-none">
    <div class="h-full bg-gray-800 transition-all ease-out" :class="width === 100 ? 'duration-200' : 'duration-500'"
        :style="`width: ${width}%`"></div>
</div>