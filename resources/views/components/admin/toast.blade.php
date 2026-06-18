<div
    x-data="{
        toasts: [],
        add(message, type = 'success') {
            if (! message) return;
            const id = Date.now() + Math.random();
            this.toasts.push({ id, message, type });
            setTimeout(() => this.remove(id), 3500);
        },
        remove(id) { this.toasts = this.toasts.filter((t) => t.id !== id); },
    }"
    x-init="@if (session('status')) add(@js(session('status'))) @endif"
    @notify.window="add($event.detail?.message, $event.detail?.type ?? 'success')"
    class="fixed top-6 right-6 z-[60] flex flex-col gap-2 items-end pointer-events-none"
>
    <template x-for="toast in toasts" :key="toast.id">
        <div
            x-show="true"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-x-4"
            x-transition:enter-end="opacity-100 translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="pointer-events-auto flex items-center gap-3 rounded-2xl px-4 py-3 shadow-soft text-sm font-medium min-w-[240px] max-w-sm"
            :class="toast.type === 'error'
                ? 'bg-red-50 text-red-700 border border-red-100'
                : 'bg-emerald-50 text-emerald-700 border border-emerald-100'"
        >
            <span x-text="toast.message" class="flex-1"></span>
            <button type="button" @click="remove(toast.id)" class="opacity-60 hover:opacity-100 text-base leading-none">&times;</button>
        </div>
    </template>
</div>
