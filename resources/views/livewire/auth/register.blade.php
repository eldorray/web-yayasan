<div class="w-full max-w-md mx-auto">
    <div class="mb-8">
        <p class="eyebrow text-brand-700 mb-3">Daftar</p>
        <h1 class="font-display text-3xl font-extrabold text-ink-900 mb-2">Buat akun baru</h1>
        <p class="text-sm text-ink-600">Isi data berikut untuk mulai mengelola dashboard.</p>
    </div>

    <form wire:submit="register" class="space-y-5">
        <div>
            <label for="name" class="auth-label">Nama lengkap</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-4 flex items-center text-ink-400 pointer-events-none">
                    <x-admin.icon name="user" class="w-4 h-4" />
                </span>
                <input id="name" type="text" wire:model="name" autocomplete="name" required
                    class="auth-input pl-11" placeholder="Nama Anda">
            </div>
            @error('name')
                <p class="auth-error">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="auth-label">Email</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-4 flex items-center text-ink-400 pointer-events-none">
                    <x-admin.icon name="mail" class="w-4 h-4" />
                </span>
                <input id="email" type="email" wire:model="email" autocomplete="email" required
                    class="auth-input pl-11" placeholder="you@example.com">
            </div>
            @error('email')
                <p class="auth-error">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password" class="auth-label">Password</label>
            <div class="relative" x-data="{ show: false }">
                <span class="absolute inset-y-0 left-4 flex items-center text-ink-400 pointer-events-none">
                    <x-admin.icon name="lock" class="w-4 h-4" />
                </span>
                <input id="password" :type="show ? 'text' : 'password'" wire:model="password"
                    autocomplete="new-password" required class="auth-input pl-11 pr-11"
                    placeholder="Minimal 8 karakter">
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

        <div>
            <label for="password_confirmation" class="auth-label">Konfirmasi password</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-4 flex items-center text-ink-400 pointer-events-none">
                    <x-admin.icon name="lock" class="w-4 h-4" />
                </span>
                <input id="password_confirmation" type="password" wire:model="password_confirmation"
                    autocomplete="new-password" required class="auth-input pl-11" placeholder="Ulangi password">
            </div>
        </div>

        <button type="submit" class="auth-submit motion-tap cursor-pointer" wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="register">Buat akun</span>
            <span wire:loading wire:target="register">Memproses...</span>
        </button>
    </form>

    <p class="mt-8 text-sm text-ink-600 text-center">
        Sudah punya akun?
        <a href="{{ route('login') }}" wire:navigate class="font-semibold cursor-pointer transition-colors duration-200 hover:underline" style="color: var(--brand-700);">Masuk</a>
    </p>
</div>
