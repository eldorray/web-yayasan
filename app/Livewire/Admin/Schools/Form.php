<?php

namespace App\Livewire\Admin\Schools;

use App\Models\School;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.app')]
#[Title('Form Sekolah')]
class Form extends Component
{
    use WithFileUploads;

    public ?School $school = null;

    public string $name = '';

    public string $level = 'SD';

    public ?string $description = null;

    public ?string $address = null;

    public ?string $phone = null;

    public ?string $email = null;

    public ?string $website_url = null;

    public ?int $established_year = null;

    public bool $is_active = true;

    public $logo = null;

    public $cover_image = null;

    public int $sort_order = 0;

    public function mount(?School $school = null): void
    {
        if ($school && $school->exists) {
            $this->school = $school;
            $this->fill($school->only(['name', 'level', 'description', 'address', 'phone', 'email', 'website_url', 'established_year', 'is_active', 'sort_order']));
        }
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'level' => 'required|in:TK,SD,SMP,SMA,SMK',
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:32',
            'email' => 'nullable|email|max:255',
            'website_url' => 'nullable|url|max:255',
            'established_year' => 'nullable|integer|min:1900|max:'.(date('Y') + 1),
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'logo' => 'nullable|image|max:2048',
            'cover_image' => 'nullable|image|max:4096',
        ]);

        if ($this->logo) {
            $validated['logo'] = $this->logo->store('schools', 'public');
        }
        if ($this->cover_image) {
            $validated['cover_image'] = $this->cover_image->store('schools/covers', 'public');
        }

        if ($this->school && $this->school->exists) {
            $this->school->update($validated);
            session()->flash('status', 'Sekolah diperbarui.');
        } else {
            School::create($validated);
            session()->flash('status', 'Sekolah dibuat.');
        }

        $this->redirect(route('admin.schools.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.schools.form');
    }
}
