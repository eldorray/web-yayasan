<?php

namespace App\Livewire\Public\News;

use App\Models\News;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.public')]
#[Title('Berita')]
class Index extends Component
{
    use WithPagination;

    public ?string $category = null;

    public function setCategory(?string $category): void
    {
        $this->category = ($category === '' || $category === 'all') ? null : $category;
        $this->resetPage();
    }

    #[Computed]
    public function categories(): array
    {
        return ['all', 'yayasan', 'sekolah'];
    }

    public function render()
    {
        $news = News::published()
            ->when($this->category, fn ($q) => $q->where('category', $this->category))
            ->latest('published_at')
            ->paginate(9);

        $featured = $this->category ? null : News::published()->latest('published_at')->first();

        return view('livewire.public.news.index', compact('news', 'featured'));
    }
}
