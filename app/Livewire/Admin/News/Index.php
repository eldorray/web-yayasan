<?php

namespace App\Livewire\Admin\News;

use App\Models\News;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Kelola Berita')]
class Index extends Component
{
    use WithPagination;

    public ?string $search = null;

    public ?int $deleteId = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId = $id;
    }

    public function delete(): void
    {
        News::findOrFail($this->deleteId)?->delete();
        $this->deleteId = null;
        $this->dispatch('news-deleted');
    }

    #[Computed]
    public function news()
    {
        return News::with('school')
            ->where('title', 'like', '%'.$this->search.'%')
            ->latest()
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.admin.news.index');
    }
}
