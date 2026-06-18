<?php

namespace App\Livewire\Public;

use App\Models\News;
use App\Models\School;
use App\Models\SiteSetting;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.public')]
#[Title('Beranda')]
class Home extends Component
{
    public function render()
    {
        return view('livewire.public.home', [
            'settings' => SiteSetting::current(),
            'schools' => School::active()->ordered()->limit(3)->get(),
            'news' => News::published()->latest()->limit(3)->get(),
        ]);
    }
}
