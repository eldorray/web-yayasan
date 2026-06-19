<div class="w-full max-w-md mx-auto">
    <div class="mb-8">
        <p class="eyebrow text-brand-700 mb-3">Masuk</p>
        <h1 class="font-display text-3xl font-extrabold text-ink-900 mb-2">Selamat datang kembali</h1>
        <p class="text-sm text-ink-600">Masuk ke akun admin untuk mengelola konten yayasan.</p>
    </div>

    <form wire:submit="login" class="space-y-5">
        <div>
            <label for="email" class="auth-label">Email</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-4 flex items-center text-ink-400 pointer-events-none">
                    <x-admin.icon name="mail" class="w-4 h-4" />
                </span>
                <input id="email" type="email" wire:model="email" autocomplete="email" required
                    class="auth-input pl-11" placeholder="admin@example.com">
            </div>
            @error('email')
                <p class="auth-error">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <div class="flex items-center justify-between mb-2">
                <label for="password" class="auth-label mb-0">Password</label>
                <a href="#" class="text-xs font-semibold cursor-pointer transition-colors duration-200 hover:underline" style="color: var(--brand-700);">Lupa password?</a>
            </div>
            <div class="relative" x-data="{ show: false }">
                <span class="absolute inset-y-0 left-4 flex items-center text-ink-400 pointer-events-none">
                    <x-admin.icon name="lock" class="w-4 h-4" />
                </span>
                <input id="password" :type="show ? 'text' : 'password'" wire:model="password"
                    autocomplete="current-password" required class="auth-input pl-11 pr-11" placeholder="••••••••">
                <button type="button" @click="show = !show"
                    class="absolute inset-y-0 right-4 flex items-center text-ink-400 hover:text-ink-700 cursor-pointer transition-colors duration-200"
                    aria-label="Tampilkan password">
                    <template x-if="!show"><x-admin.icon name="eye" class="w-4 h-4" /></template>
                    <template x-if="show"><x-admin.icon name="eye-off" class="w-4 h-4" /></template>
                </button>
            </div>
            @error('password')
                <p class="auth-error">{{ $message }}</p>
            @enderror
        </div>

        <label class="flex items-center gap-2.5 text-sm text-ink-700 select-none cursor-pointer">
            <input type="checkbox" wire:model="remember"
                class="rounded border-ink-300 text-brand-600 focus:ring-brand-500/30 cursor-pointer">
            <span>Tetap masuk di perangkat ini</span>
        </label>

        <button type="submit" class="auth-submit motion-tap cursor-pointer" wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="login">Masuk</span>
            <span wire:loading wire:target="login">Memproses...</span>
        </button>
    </form>

    <p class="mt-8 text-sm text-ink-600 text-center">
        Belum punya akun?
        <a href="{{ route('register') }}" wire:navigate class="font-semibold cursor-pointer transition-colors duration-200 hover:underline" style="color: var(--brand-700);">Daftar</a>
    </p>
</div>
