<div>
    <section class="relative text-white py-16 overflow-hidden" style="background: linear-gradient(135deg, var(--brand-900), var(--brand-700));">
        <div class="pattern-stars absolute inset-0 opacity-60" aria-hidden="true"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <p class="eyebrow rise rise-1" style="color: var(--color-gold-500);">Jenjang Pendidikan</p>
            <h1 class="font-display text-4xl font-extrabold mt-3 rise rise-2">Sekolah Binaan</h1>
            <p class="text-white/85 mt-2 rise rise-3">Pendidikan Islam berkualitas di setiap jenjang.</p>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        {{-- Filter chips --}}
        <div class="flex flex-wrap gap-2 mb-8">
            @foreach ($this->levels as $level)
                @php $active = ($level === 'all') ? is_null($this->level) : ($this->level === $level); @endphp
                <button type="button" wire:click="setLevel('{{ $level }}')"
                        class="px-4 py-2 rounded-full text-sm font-semibold transition-colors"
                        style="{{ $active ? 'background-color: var(--brand-700); color: white;' : 'background-color: white; color: var(--ink-600); border: 1px solid var(--ink-200);' }}">
                    {{ $level === 'all' ? 'Semua' : $level }}
                </button>
            @endforeach
        </div>

        @if ($this->schools->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($this->schools as $school)
                    <x-public.school-card :school="$school" />
                @endforeach
            </div>
        @else
            <p class="text-center text-ink-500 py-12">Belum ada sekolah pada jenjang ini.</p>
        @endif
    </div>
</div>
