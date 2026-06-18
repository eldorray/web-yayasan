<?php

namespace App\Livewire\Admin\Gallery;

use App\Models\GalleryImage;
use App\Models\School;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Kelola Galeri')]
class Index extends Component
{
    use WithFileUploads;
    use WithPagination;

    public $photos = [];

    public ?int $school_id = null;

    public ?string $caption = null;

    public ?int $deleteId = null;

    public function save(): void
    {
        $this->validate([
            'photos' => 'required|array|min:1',
            'photos.*' => 'image|max:4096',
            'school_id' => 'nullable|exists:schools,id',
            'caption' => 'nullable|string|max:255',
        ]);

        foreach ($this->photos as $photo) {
            GalleryImage::create([
                'image' => $photo->store('gallery', 'public'),
                'caption' => $this->caption,
                'school_id' => $this->school_id,
            ]);
        }

        $this->reset(['photos', 'caption', 'school_id']);
        $this->dispatch('notify', message: 'Foto ditambahkan.');
    }

    public function confirmDelete(int $id): void
    {
        $this->deleteId = $id;
    }

    public function delete(): void
    {
        GalleryImage::findOrFail($this->deleteId)?->delete();
        $this->deleteId = null;
        $this->dispatch('notify', message: 'Foto dihapus.');
    }

    #[Computed]
    public function schools()
    {
        return School::active()->ordered()->get(['id', 'name']);
    }

    #[Computed]
    public function images()
    {
        return GalleryImage::with('school')->ordered()->paginate(24);
    }

    public function render()
    {
        return view('livewire.admin.gallery.index');
    }
}
