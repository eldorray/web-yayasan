<?php

namespace App\Livewire\Admin\Schools;

use App\Models\School;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
#[Title('Kelola Sekolah')]
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
        School::findOrFail($this->deleteId)?->delete();
        $this->deleteId = null;
        $this->dispatch('school-deleted');
    }

    #[Computed]
    public function schools()
    {
        return School::where('name', 'like', '%'.$this->search.'%')
            ->ordered()
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.admin.schools.index');
    }
}
