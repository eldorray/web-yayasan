<?php

namespace App\Livewire\Public\Gallery;

use App\Models\GalleryImage;
use App\Models\School;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.public')]
#[Title('Galeri')]
class Index extends Component
{
    public ?int $schoolId = null;

    public function setSchool(?int $schoolId): void
    {
        $this->schoolId = $schoolId ?: null;
    }

    #[Computed]
    public function schoolsWithImages()
    {
        return School::whereHas('galleryImages')->ordered()->get(['id', 'name']);
    }

    #[Computed]
    public function images()
    {
        return GalleryImage::with('school')
            ->when($this->schoolId, fn ($q) => $q->where('school_id', $this->schoolId))
            ->ordered()
            ->get();
    }

    public function render()
    {
        return view('livewire.public.gallery.index');
    }
}
