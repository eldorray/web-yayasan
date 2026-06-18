<?php

namespace App\Livewire\Public;

use App\Models\PpdbInfo;
use App\Models\SiteSetting;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.public')]
#[Title('PPDB')]
class Ppdb extends Component
{
    #[Computed]
    public function ppdbInfos()
    {
        return PpdbInfo::with('school')
            ->whereHas('school', fn ($q) => $q->where('is_active', true))
            ->latest('academic_year')
            ->get()
            ->groupBy('academic_year');
    }

    public function render()
    {
        return view('livewire.public.ppdb', [
            'settings' => SiteSetting::current(),
        ]);
    }
}
