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

    public string $ppdb_academic_year = '2026/2027';

    public ?string $ppdb_open_date = null;

    public ?string $ppdb_close_date = null;

    public ?string $ppdb_requirements = null;

    public ?string $ppdb_fees = null;

    public ?string $ppdb_registration_url = null;

    public bool $ppdb_is_open = true;

    public function mount(?School $school = null): void
    {
        if ($school && $school->exists) {
            $this->school = $school;
            $this->fill($school->only(['name', 'level', 'description', 'address', 'phone', 'email', 'website_url', 'established_year', 'is_active', 'sort_order']));

            $ppdb = $school->ppdbInfos()->latest('academic_year')->first();
            if ($ppdb) {
                $this->ppdb_academic_year = $ppdb->academic_year;
                $this->ppdb_open_date = $ppdb->open_date?->format('Y-m-d');
                $this->ppdb_close_date = $ppdb->close_date?->format('Y-m-d');
                $this->ppdb_requirements = strip_tags($ppdb->requirements ?? '', '<p><br><ul><ol><li><strong><em>');
                $this->ppdb_fees = $ppdb->fees;
                $this->ppdb_registration_url = $ppdb->registration_url;
                $this->ppdb_is_open = $ppdb->is_open;
            }
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
            'ppdb_academic_year' => 'required|string|max:16',
            'ppdb_open_date' => 'nullable|date',
            'ppdb_close_date' => 'nullable|date|after_or_equal:ppdb_open_date',
            'ppdb_requirements' => 'nullable|string',
            'ppdb_fees' => 'nullable|string|max:255',
            'ppdb_registration_url' => 'nullable|url|max:255',
            'ppdb_is_open' => 'boolean',
        ]);

        $schoolData = collect($validated)->only([
            'name', 'level', 'description', 'address', 'phone', 'email',
            'website_url', 'established_year', 'is_active', 'sort_order',
        ])->toArray();

        if ($this->logo) {
            $schoolData['logo'] = $this->logo->store('schools', 'public');
        }
        if ($this->cover_image) {
            $schoolData['cover_image'] = $this->cover_image->store('schools/covers', 'public');
        }

        $ppdbData = [
            'open_date' => $this->ppdb_open_date,
            'close_date' => $this->ppdb_close_date,
            'requirements' => $this->ppdb_requirements,
            'fees' => $this->ppdb_fees,
            'registration_url' => $this->ppdb_registration_url,
            'is_open' => $this->ppdb_is_open,
        ];

        if ($this->school && $this->school->exists) {
            $this->school->update($schoolData);
            $this->school->ppdbInfos()->updateOrCreate(
                ['academic_year' => $this->ppdb_academic_year],
                $ppdbData,
            );
            session()->flash('status', 'Sekolah diperbarui.');
        } else {
            $school = School::create($schoolData);
            $school->ppdbInfos()->create(array_merge($ppdbData, [
                'academic_year' => $this->ppdb_academic_year,
            ]));
            session()->flash('status', 'Sekolah dibuat.');
        }

        $this->redirect(route('admin.schools.index'), navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.schools.form');
    }
}
