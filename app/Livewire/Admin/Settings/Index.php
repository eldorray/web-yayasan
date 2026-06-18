<?php

namespace App\Livewire\Admin\Settings;

use App\Models\SiteSetting;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.app')]
#[Title('Pengaturan Yayasan')]
class Index extends Component
{
    use WithFileUploads;

    public string $name = '';

    public ?string $tagline = null;

    public ?string $vision = null;

    public ?string $mission = null;

    public ?string $history = null;

    public ?string $address = null;

    public ?string $phone = null;

    public ?string $email = null;

    public ?int $established_year = null;

    public ?int $students_count = null;

    public ?string $facebook = null;

    public ?string $instagram = null;

    public ?string $youtube = null;

    public $logo = null;

    public function mount(): void
    {
        $settings = SiteSetting::current();
        $this->fill($settings->only(['name', 'tagline', 'vision', 'mission', 'history', 'address', 'phone', 'email', 'established_year', 'students_count']));
        $this->facebook = $settings->socials['facebook'] ?? null;
        $this->instagram = $settings->socials['instagram'] ?? null;
        $this->youtube = $settings->socials['youtube'] ?? null;
    }

    public function save(): void
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'tagline' => 'nullable|string|max:255',
            'vision' => 'nullable|string',
            'mission' => 'nullable|string',
            'history' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:32',
            'email' => 'nullable|email|max:255',
            'established_year' => 'nullable|integer|min:1900|max:'.(date('Y') + 1),
            'students_count' => 'nullable|integer|min:0',
            'facebook' => 'nullable|url|max:255',
            'instagram' => 'nullable|url|max:255',
            'youtube' => 'nullable|url|max:255',
            'logo' => 'nullable|image|max:2048',
        ]);

        $socials = array_filter([
            'facebook' => $validated['facebook'] ?? null,
            'instagram' => $validated['instagram'] ?? null,
            'youtube' => $validated['youtube'] ?? null,
        ]);

        if ($this->logo) {
            $validated['logo'] = $this->logo->store('yayasan', 'public');
        }

        $settings = SiteSetting::current();
        $settings->update(
            collect($validated)
                ->except(['facebook', 'instagram', 'youtube'])
                ->merge(['socials' => $socials])
                ->toArray()
        );

        session()->flash('status', 'Pengaturan disimpan.');
    }

    public function render()
    {
        return view('livewire.admin.settings.index');
    }
}
