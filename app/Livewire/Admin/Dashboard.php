<?php

namespace App\Livewire\Admin;

use App\Models\GalleryImage;
use App\Models\News;
use App\Models\School;
use App\Models\SiteSetting;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Dashboard Admin')]
class Dashboard extends Component
{
    #[Computed]
    public function stats(): array
    {
        return [
            'schools' => School::count(),
            'active_schools' => School::active()->count(),
            'news' => News::count(),
            'published_news' => News::published()->count(),
            'gallery' => GalleryImage::count(),
        ];
    }

    #[Computed]
    public function recentNews()
    {
        return News::with('school')->latest()->limit(5)->get();
    }

    #[Computed]
    public function settings()
    {
        return SiteSetting::current();
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}
