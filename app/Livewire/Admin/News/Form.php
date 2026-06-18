<?php

namespace App\Livewire\Admin\News;

use App\Models\News;
use App\Models\School;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.app')]
#[Title('Form Berita')]
class Form extends Component
{
    use WithFileUploads;

    public ?News $news = null;

    public string $title = '';

    public ?string $excerpt = null;

    public ?string $body = null;

    public string $category = 'yayasan';

    public ?int $school_id = null;

    public ?string $published_at = null;

    public bool $is_published = false;

    public $cover_image = null;

    public function mount(?News $news = null): void
    {
        if ($news && $news->exists) {
            $this->news = $news;
            $this->fill($news->only(['title', 'excerpt', 'body', 'category', 'school_id', 'is_published']));
            $this->published_at = $news->published_at?->format('Y-m-d\TH:i');
        }
    }

    #[Computed]
    public function schools()
    {
        return School::active()->ordered()->get(['id', 'name']);
    }

    public function save(): void
    {
        $validated = $this->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'body' => 'nullable|string',
            'category' => 'required|in:yayasan,sekolah',
            'school_id' => 'nullable|exists:schools,id',
            'published_at' => 'nullable|date',
            'is_published' => 'boolean',
            'cover_image' => 'nullable|image|max:4096',
        ]);

        if ($this->cover_image) {
            $validated['cover_image'] = $this->cover_image->store('news', 'public');
        }
        $validated['published_at'] = $validated['published_at'] ?: null;

        if ($this->news && $this->news->exists) {
            $this->news->update($validated);
            session()->flash('status', 'Berita diperbarui.');
        } else {
            $validated['author_id'] = auth()->id();
            News::create($validated);
            session()->flash('status', 'Berita dibuat.');
        }

        $this->redirect(route('admin.news.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.news.form');
    }
}
