<div>
    <x-public.page-hero
        eyebrow="Jenjang Pendidikan"
        title="Sekolah Binaan"
        description="Pendidikan Islam berkualitas di setiap jenjang."
    />

    <div class="public-container public-section">
        <div class="flex flex-wrap gap-2 mb-8" role="group" aria-label="Filter jenjang">
            @foreach ($this->levels as $level)
                @php $active = ($level === 'all') ? is_null($this->level) : ($this->level === $level); @endphp
                <x-public.filter-chip wire:click="setLevel('{{ $level }}')" :active="$active">
                    {{ $level === 'all' ? 'Semua' : $level }}
                </x-public.filter-chip>
            @endforeach
        </div>

        @if ($this->schools->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($this->schools as $school)
                    <x-public.school-card :school="$school" />
                @endforeach
            </div>
        @else
            <div class="public-empty public-card">
                <x-public.icon name="school" class="w-10 h-10 mx-auto mb-3 text-ink-400" />
                <p>Belum ada sekolah pada jenjang ini.</p>
            </div>
        @endif
    </div>
</div>
