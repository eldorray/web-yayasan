<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('Dashboard')]
class Dashboard extends Component
{
    public string $range = 'Weekly';

    public array $ranges = ['Weekly', 'Monthly', 'Yearly'];

    public function setRange(string $range): void
    {
        if (in_array($range, $this->ranges, true)) {
            $this->range = $range;
        }
    }

    #[Computed]
    public function summary(): array
    {
        return [
            'total_income' => '12.135,00',
            'total_paid' => '8.873,00',
            'bars' => [40, 60, 80, 50, 70, 90, 45, 65, 30, 80],
        ];
    }

    #[Computed]
    public function averages(): array
    {
        return [
            [
                'label' => 'Weekly average',
                'value' => '25.460,00',
                'change' => '-11.5%',
                'trend' => 'down',
                'variant' => 'plain',
            ],
            [
                'label' => 'Annual average',
                'value' => '25.460,00',
                'change' => '+11.5%',
                'trend' => 'up',
                'variant' => 'muted',
            ],
        ];
    }

    #[Computed]
    public function activityCards(): array
    {
        return [
            [
                'label' => 'Receipts',
                'value' => '120.560,00',
                'change' => '+11.5%',
                'trend' => 'up',
                'icon' => 'file',
                'path' => 'M0 30L20 20L40 25L60 10L80 35L100 15',
                'dark' => false,
            ],
            [
                'label' => 'Contributions',
                'value' => '37.272,00',
                'change' => '+4.5%',
                'trend' => 'up',
                'icon' => 'clock',
                'path' => 'M0 35L20 15L40 25L60 20L80 10L100 30',
                'dark' => false,
            ],
            [
                'label' => 'Owes',
                'value' => '9.230,00',
                'change' => '-20.5%',
                'trend' => 'down',
                'icon' => 'dollar',
                'path' => 'M0 20L20 30L40 10L60 25L80 15L100 35',
                'dark' => true,
            ],
        ];
    }

    #[Computed]
    public function transactions(): array
    {
        return [
            ['name' => 'Guy Hawkins', 'id' => '#1298970', 'status' => 'Completed', 'date' => 'May 29, 2024', 'amount' => '-$15.30'],
            ['name' => 'Robert Fox', 'id' => '#1848230', 'status' => 'Pending', 'date' => 'May 11, 2024', 'amount' => '-$23.00'],
            ['name' => 'Jenny Wilson', 'id' => '#1872021', 'status' => 'Completed', 'date' => 'May 05, 2024', 'amount' => '+$128.00'],
            ['name' => 'Cody Fisher', 'id' => '#1923145', 'status' => 'Failed', 'date' => 'Apr 30, 2024', 'amount' => '-$42.70'],
            ['name' => 'Leslie Alexander', 'id' => '#1948312', 'status' => 'Completed', 'date' => 'Apr 28, 2024', 'amount' => '+$310.00'],
        ];
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}
