<div>
    {{-- Page header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h2 class="text-3xl font-bold text-ink-900 mb-1">
                Welcome back, {{ auth()->user()->name }}!
            </h2>
            <p class="text-ink-500 text-sm">Control your investment, income, and expenses.</p>
        </div>
        <div class="flex items-center gap-3 flex-wrap">
            <button type="button" class="btn-ghost">
                <x-admin.icon name="filter" class="w-4 h-4" />
                Filters
            </button>
            <button type="button" class="btn-ghost">
                <x-admin.icon name="download" class="w-4 h-4" />
                Exports
            </button>
            <button type="button" class="btn-primary">
                <x-admin.icon name="plus" class="w-4 h-4" />
                Add card
            </button>
        </div>
    </div>

    {{-- Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {{-- LEFT COLUMN --}}
        <div class="lg:col-span-5 flex flex-col gap-6">
            {{-- Summary card --}}
            <div class="bg-ink-100 rounded-[2rem] p-6 pb-0 flex flex-col relative overflow-hidden h-[340px]">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-ink-900">Summary</h3>
                        <p class="text-sm text-ink-500">Track your performance.</p>
                    </div>
                    <div x-data="{ open: false }" @click.outside="open = false" class="relative">
                        <button @click="open = !open" type="button"
                            class="bg-white rounded-full px-4 py-1.5 text-sm font-medium text-ink-700 flex items-center gap-2 shadow-soft">
                            <span>{{ $this->range }}</span>
                            <x-admin.icon name="chevron-down" class="w-3 h-3" />
                        </button>
                        <div x-show="open" x-transition
                            class="absolute right-0 mt-2 bg-white rounded-2xl shadow-soft border border-ink-100 p-1 z-10 min-w-[130px]">
                            @foreach ($this->ranges as $range)
                                <button type="button" wire:click="setRange('{{ $range }}')"
                                    @click="open = false"
                                    class="w-full text-left px-3 py-1.5 rounded-xl text-sm {{ $range === $this->range ? 'bg-ink-50 text-ink-900 font-medium' : 'text-ink-600 hover:bg-ink-50' }}">
                                    {{ $range }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white rounded-[1.5rem] p-6 mt-auto mx-[-8px] mb-[-8px] shadow-soft flex flex-col justify-end h-[240px]">
                    <div class="flex justify-between border-b border-ink-100 pb-4 mb-4">
                        <div>
                            <p class="text-xs text-ink-500 font-medium flex items-center gap-1 mb-1">
                                <x-admin.icon name="arrow-up" class="w-3 h-3 text-ink-400" />
                                Total income
                            </p>
                            <p class="text-2xl font-bold text-ink-900">{{ $this->summary['total_income'] }}</p>
                        </div>
                        <div class="w-px bg-ink-200"></div>
                        <div>
                            <p class="text-xs text-ink-500 font-medium flex items-center gap-1 mb-1">
                                <x-admin.icon name="arrow-down" class="w-3 h-3 text-ink-400" />
                                Total paid
                            </p>
                            <p class="text-2xl font-bold text-ink-900">{{ $this->summary['total_paid'] }}</p>
                        </div>
                    </div>

                    <div class="flex items-end justify-between h-24 gap-2 pt-2">
                        @foreach ($this->summary['bars'] as $i => $height)
                            @php
                                $isHighlighted = $i < 6;
                                $opacity = match (true) {
                                    $i === 5 => 'opacity-100',
                                    $i === 2 => 'opacity-100',
                                    $i === 4 || $i === 1 => 'opacity-90',
                                    default => 'opacity-70',
                                };
                            @endphp
                            <div class="w-full rounded-t-md {{ $isHighlighted ? 'bg-brand-500 ' . $opacity : 'bg-ink-200' }}"
                                style="height: {{ $height }}%"></div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Averages --}}
            <div class="grid grid-cols-2 gap-4">
                @foreach ($this->averages as $avg)
                    <div
                        class="bg-ink-100 rounded-2xl p-5 flex flex-col justify-between relative overflow-hidden min-h-[140px]">
                        @if ($avg['variant'] === 'muted')
                            <div class="absolute inset-0 bg-white/40"></div>
                        @endif
                        <div class="flex items-center gap-2 mb-4 text-ink-600 relative z-10">
                            <x-admin.icon :name="$loop->first ? 'clock' : 'tag'" class="w-4 h-4" />
                            <span class="text-sm font-medium">{{ $avg['label'] }}</span>
                        </div>
                        <div class="relative z-10">
                            <div
                                class="flex items-center gap-1 text-xs font-medium mb-1 {{ $avg['trend'] === 'up' ? 'text-brand-500' : 'text-red-500' }}">
                                <x-admin.icon :name="$avg['trend'] === 'up' ? 'trend-up' : 'trend-down'" class="w-3 h-3" />
                                {{ $avg['change'] }}
                            </div>
                            <p class="text-2xl font-bold text-ink-900">{{ $avg['value'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Banner --}}
            <div class="bg-ink-100 rounded-2xl p-5 flex items-center justify-between">
                <div>
                    <p class="font-medium text-ink-900">How is your business</p>
                    <p class="font-medium text-ink-900">management going?</p>
                </div>
                <button type="button"
                    class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-ink-500 shadow-soft"
                    aria-label="Dismiss">
                    <x-admin.icon name="close" class="w-3.5 h-3.5" />
                </button>
            </div>
        </div>

        {{-- RIGHT COLUMN --}}
        <div class="lg:col-span-7 flex flex-col gap-6">
            {{-- Activity --}}
            <div class="bg-ink-100 rounded-[2rem] p-6">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-ink-900">Activity</h3>
                        <p class="text-sm text-ink-500">Track your activity.</p>
                    </div>
                    <div class="flex gap-2">
                        <button type="button"
                            class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-ink-600 shadow-soft"
                            aria-label="Chart view">
                            <x-admin.icon name="bars" class="w-4 h-4" />
                        </button>
                        <button type="button"
                            class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-ink-600 shadow-soft"
                            aria-label="Add">
                            <x-admin.icon name="trend-up" class="w-4 h-4" />
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach ($this->activityCards as $card)
                        @php
                            $isDark = $card['dark'];
                            $wrapper = $isDark ? 'bg-[#422a18] text-white' : 'bg-white text-ink-700';
                            $iconWrap = $isDark ? 'bg-white/10 text-white' : 'bg-ink-100 text-ink-600';
                            $valueText = $isDark ? 'text-white' : 'text-ink-900';
                            $trendText =
                                $card['trend'] === 'up'
                                    ? ($isDark
                                        ? 'text-brand-300'
                                        : 'text-brand-500')
                                    : ($isDark
                                        ? 'text-brand-300'
                                        : 'text-red-500');
                        @endphp
                        <div class="{{ $wrapper }} rounded-2xl p-5 shadow-soft">
                            <div class="flex items-center gap-2 mb-4">
                                <div class="w-6 h-6 rounded-md flex items-center justify-center {{ $iconWrap }}">
                                    <x-admin.icon :name="$card['icon']" class="w-3.5 h-3.5" />
                                </div>
                                <span class="font-medium text-sm">{{ $card['label'] }}</span>
                            </div>
                            <div>
                                <div class="flex items-center gap-1 text-xs font-medium mb-1 {{ $trendText }}">
                                    <x-admin.icon :name="$card['trend'] === 'up' ? 'trend-up' : 'trend-down'" class="w-3 h-3" />
                                    {{ $card['change'] }}
                                </div>
                                <p class="text-xl font-bold {{ $valueText }}">{{ $card['value'] }}</p>
                            </div>
                            <div class="mt-5 h-10">
                                <svg viewBox="0 0 100 40" preserveAspectRatio="none" class="w-full h-full">
                                    <path d="{{ $card['path'] }}" stroke="#ff5e1e" stroke-width="2" fill="none"
                                        vector-effect="non-scaling-stroke" />
                                </svg>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Transactions --}}
            <div class="bg-ink-100 rounded-[2rem] p-6 flex-1 flex flex-col">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="text-lg font-bold text-ink-900">Transactions history</h3>
                        <p class="text-sm text-ink-500">Track your history.</p>
                    </div>
                    <button type="button"
                        class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-ink-600 shadow-soft"
                        aria-label="Options">
                        <x-admin.icon name="bars" class="w-4 h-4" />
                    </button>
                </div>

                <div class="bg-white rounded-[1.5rem] p-2 flex-1 mt-2 shadow-soft overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse min-w-[540px]">
                            <thead>
                                <tr class="text-xs text-ink-400 border-b border-ink-100">
                                    <th class="font-medium py-3 px-4">Name</th>
                                    <th class="font-medium py-3 px-4">ID</th>
                                    <th class="font-medium py-3 px-4">Status</th>
                                    <th class="font-medium py-3 px-4">Date</th>
                                    <th class="font-medium py-3 px-4 text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm text-ink-800">
                                @foreach ($this->transactions as $tx)
                                    @php
                                        [$statusText, $statusBg, $statusDot] = match ($tx['status']) {
                                            'Completed' => ['text-emerald-600', 'bg-emerald-50', 'bg-emerald-500'],
                                            'Pending' => ['text-orange-500', 'bg-orange-50', 'bg-orange-400'],
                                            'Failed' => ['text-red-500', 'bg-red-50', 'bg-red-500'],
                                            default => ['text-ink-500', 'bg-ink-50', 'bg-ink-400'],
                                        };
                                        $amountColor = str_starts_with($tx['amount'], '-')
                                            ? 'text-red-500'
                                            : 'text-emerald-600';
                                    @endphp
                                    <tr
                                        class="border-b border-ink-50 last:border-0 hover:bg-ink-50/60 transition-colors">
                                        <td class="py-3 px-4">
                                            <div class="flex items-center gap-3">
                                                <div
                                                    class="w-8 h-8 rounded-full bg-gradient-to-br from-ink-200 to-ink-300 flex items-center justify-center text-ink-700 text-xs font-semibold">
                                                    {{ \Illuminate\Support\Str::of($tx['name'])->explode(' ')->take(2)->map(fn($w) => substr($w, 0, 1))->implode('') }}
                                                </div>
                                                <span class="font-medium">{{ $tx['name'] }}</span>
                                            </div>
                                        </td>
                                        <td class="py-3 px-4 text-ink-500">{{ $tx['id'] }}</td>
                                        <td class="py-3 px-4">
                                            <span
                                                class="inline-flex items-center gap-1.5 text-xs font-medium {{ $statusText }} {{ $statusBg }} px-2 py-1 rounded-full">
                                                <span class="w-1.5 h-1.5 rounded-full {{ $statusDot }}"></span>
                                                {{ $tx['status'] }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 text-ink-500">{{ $tx['date'] }}</td>
                                        <td class="py-3 px-4 text-right font-medium {{ $amountColor }}">
                                            {{ $tx['amount'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
