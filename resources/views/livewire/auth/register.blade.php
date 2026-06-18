<div class="w-full max-w-md mx-auto">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-ink-900 mb-2">Create your account</h1>
        <p class="text-sm text-ink-500">Just a few details to get you inside the dashboard.</p>
    </div>

    <form wire:submit="register" class="space-y-5">
        <div>
            <label for="name" class="block text-xs font-medium text-ink-500 mb-2 uppercase tracking-wide">Full
                name</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-4 flex items-center text-ink-400">
                    <x-admin.icon name="user" class="w-4 h-4" />
                </span>
                <input id="name" type="text" wire:model="name" autocomplete="name" required
                    class="chip-input pl-11" placeholder="Uzui Tengen">
            </div>
            @error('name')
                <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email"
                class="block text-xs font-medium text-ink-500 mb-2 uppercase tracking-wide">Email</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-4 flex items-center text-ink-400">
                    <x-admin.icon name="mail" class="w-4 h-4" />
                </span>
                <input id="email" type="email" wire:model="email" autocomplete="email" required
                    class="chip-input pl-11" placeholder="you@example.com">
            </div>
            @error('email')
                <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password"
                class="block text-xs font-medium text-ink-500 mb-2 uppercase tracking-wide">Password</label>
            <div class="relative" x-data="{ show: false }">
                <span class="absolute inset-y-0 left-4 flex items-center text-ink-400">
                    <x-admin.icon name="lock" class="w-4 h-4" />
                </span>
                <input id="password" :type="show ? 'text' : 'password'" wire:model="password"
                    autocomplete="new-password" required class="chip-input pl-11 pr-11"
                    placeholder="At least 8 characters">
                <button type="button" @click="show = !show"
                    class="absolute inset-y-0 right-4 flex items-center text-ink-400 hover:text-ink-600"
                    aria-label="Toggle password">
                    <template x-if="!show"><x-admin.icon name="eye" class="w-4 h-4" /></template>
                    <template x-if="show"><x-admin.icon name="eye-off" class="w-4 h-4" /></template>
                </button>
            </div>
            @error('password')
                <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation"
                class="block text-xs font-medium text-ink-500 mb-2 uppercase tracking-wide">Confirm password</label>
            <div class="relative">
                <span class="absolute inset-y-0 left-4 flex items-center text-ink-400">
                    <x-admin.icon name="lock" class="w-4 h-4" />
                </span>
                <input id="password_confirmation" type="password" wire:model="password_confirmation"
                    autocomplete="new-password" required class="chip-input pl-11" placeholder="Repeat your password">
            </div>
        </div>

        <button type="submit" class="btn-primary w-full py-3" wire:loading.attr="disabled">
            <span wire:loading.remove wire:target="register">Create account</span>
            <span wire:loading wire:target="register">Creating account...</span>
        </button>
    </form>

    <p class="mt-8 text-sm text-ink-500 text-center">
        Already have an account?
        <a href="{{ route('login') }}" wire:navigate class="text-brand-500 hover:text-brand-600 font-medium">Sign in</a>
    </p>
</div>
