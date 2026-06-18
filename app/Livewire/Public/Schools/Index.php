<?php

namespace App\Livewire\Public\Schools;

use App\Models\School;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.public')]
#[Title('Sekolah Binaan')]
class Index extends Component
{
    public ?string $level = null;

    public function setLevel(?string $level): void
    {
        $this->level = $level === '' || $level === 'all' ? null : $level;
    }

    #[Computed]
    public function levels(): array
    {
        return ['all', 'TK', 'SD', 'SMP', 'SMA', 'SMK'];
    }

    #[Computed]
    public function schools()
    {
        return School::active()
            ->ordered()
            ->when($this->level, fn ($q) => $q->where('level', $this->level))
            ->get();
    }

    public function render()
    {
        return view('livewire.public.schools.index');
    }
}
