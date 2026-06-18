<?php

namespace App\Livewire\Public;

use App\Models\SiteSetting;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.public')]
#[Title('Tentang Kami')]
class About extends Component
{
    public function render()
    {
        return view('livewire.public.about', [
            'settings' => SiteSetting::current(),
        ]);
    }
}
