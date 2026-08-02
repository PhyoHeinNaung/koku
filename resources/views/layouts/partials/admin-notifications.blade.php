@php
    $initialNotification = collect([
        ['type' => 'error', 'message' => session('error')],
        ['type' => 'warning', 'message' => session('warning')],
        ['type' => 'success', 'message' => session('success')],
        ['type' => 'info', 'message' => session('info')],
        ['type' => 'success', 'message' => session('profile-success')],
        ['type' => 'success', 'message' => session('password-success')],
        ['type' => 'success', 'message' => session('verification-success')],
        ['type' => 'success', 'message' => session('session-success')],
    ])->first(fn (array $notification) => filled($notification['message']))
        ?? ['type' => 'info', 'message' => ''];
@endphp

<div
    x-data="{
        visible: false,
        type: 'info',
        message: '',
        dismissTimer: null,
        notify(detail) {
            const payload = Array.isArray(detail) ? detail[0] : detail;

            if (!payload?.message) {
                return;
            }

            window.clearTimeout(this.dismissTimer);
            this.type = ['success', 'error', 'warning', 'info'].includes(payload.type) ? payload.type : 'info';
            this.message = payload.message;
            this.visible = true;

            const duration = {
                success: 4000,
                info: 5000,
                warning: 7000,
                error: 9000,
            }[this.type];

            this.dismissTimer = window.setTimeout(() => this.dismiss(), duration);
        },
        dismiss() {
            window.clearTimeout(this.dismissTimer);
            this.visible = false;
        },
    }"
    x-init="
        const initialNotification = {{ Js::from($initialNotification) }};
        if (initialNotification.message) {
            $nextTick(() => notify(initialNotification));
        }
    "
    @admin-notify.window="notify($event.detail)"
    class="pointer-events-none fixed right-0 top-16 z-[100] transition-[left] duration-200 motion-reduce:transition-none"
    :class="sidebarCollapsed ? 'left-0 lg:!left-[4.75rem]' : 'left-0 lg:!left-[15.5rem]'"
    :style="adminDrawerOpen
        ? {
            left: viewportWidth >= 1536
                ? 'calc(100% - 80rem)'
                : (viewportWidth >= 640 ? '4vw' : '0px')
        }
        : {}"
    aria-live="polite"
    aria-atomic="true"
>
    <div
        x-cloak
        x-show="visible"
        x-transition:enter="transition duration-200 ease-out motion-reduce:transition-none"
        x-transition:enter-start="-translate-y-full opacity-0"
        x-transition:enter-end="translate-y-0 opacity-100"
        x-transition:leave="transition duration-150 ease-in motion-reduce:transition-none"
        x-transition:leave-start="translate-y-0 opacity-100"
        x-transition:leave-end="-translate-y-full opacity-0"
        role="status"
        class="pointer-events-auto relative flex min-h-8 items-center justify-center gap-2 border-y border-white/10 bg-black px-11 py-1.5 text-[10px] text-white shadow-md shadow-black/15 sm:text-[11px]"
    >
        <span
            class="grid size-4 shrink-0 place-items-center rounded-full"
            :class="{
                'bg-success/20 text-success': type === 'success',
                'bg-error/20 text-error': type === 'error',
                'bg-warning/20 text-warning': type === 'warning',
                'bg-info/20 text-info': type === 'info',
            }"
            aria-hidden="true"
        >
            <svg x-show="type === 'success'" class="size-3.5" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2.2">
                <path stroke-linecap="round" stroke-linejoin="round" d="m5 12 4 4L19 6" />
            </svg>
            <svg x-show="type === 'error'" class="size-3.5" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" d="m8 8 8 8m0-8-8 8" />
            </svg>
            <svg x-show="type === 'warning'" class="size-3.5" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 8v5m0 3.5h.01M10.3 4.7 3.5 17a2 2 0 0 0 1.75 3h13.5a2 2 0 0 0 1.75-3L13.7 4.7a2 2 0 0 0-3.4 0Z" />
            </svg>
            <svg x-show="type === 'info'" class="size-3.5" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="9" />
                <path stroke-linecap="round" d="M12 11v5m0-8h.01" />
            </svg>
        </span>

        <p class="min-w-0 truncate leading-4">
            <strong
                class="font-semibold text-white"
                x-text="{
                    success: 'Success!',
                    error: 'Action failed',
                    warning: 'Attention',
                    info: 'Update',
                }[type]"
            ></strong>
            <span class="ml-1 text-white/70" x-text="message"></span>
        </p>

        <button type="button"
            class="btn btn-ghost btn-square absolute right-2 size-6 min-h-6 shrink-0 border-0 text-white opacity-55 hover:bg-white/10 hover:opacity-100"
            @click="dismiss" aria-label="Dismiss notification">
            <svg class="size-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" d="m7 7 10 10m0-10L7 17" />
            </svg>
        </button>
    </div>
</div>
