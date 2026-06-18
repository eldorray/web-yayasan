<?php

namespace App\Livewire\Public\Schools;

use App\Models\School;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.public')]
class Show extends Component
{
    public School $school;

    public string $tab = 'about';

    public function mount(School $school): void
    {
        abort_unless($school->is_active, 404);
        $this->school = $school;
    }

    public function setTab(string $tab): void
    {
        if (in_array($tab, ['about', 'ppdb', 'gallery'], true)) {
            $this->tab = $tab;
        }
    }

    #[Computed]
    public function ppdb()
    {
        return $this->school->activePpdb();
    }

    #[Computed]
    public function gallery()
    {
        return $this->school->galleryImages()->ordered()->get();
    }

    public function render()
    {
        return view('livewire.public.schools.show', ['title' => $this->school->name]);
    }
}
