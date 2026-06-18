<?php

namespace App\Livewire\Public\News;

use App\Models\News;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.public')]
class Show extends Component
{
    public News $news;

    public function mount(News $news): void
    {
        abort_unless($news->is_published && ($news->published_at === null || $news->published_at <= now()), 404);
        $this->news = $news;
    }

    public function render()
    {
        return view('livewire.public.news.show', ['title' => $this->news->title]);
    }
}
