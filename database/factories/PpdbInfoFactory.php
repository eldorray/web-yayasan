<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PpdbInfoFactory extends Factory
{
    public function definition(): array
    {
        $year = now()->year;

        return [
            'academic_year' => $year.'/'.($year + 1),
            'open_date' => now()->startOfYear(),
            'close_date' => now()->startOfYear()->addMonths(3),
            'requirements' => '<p>Fotokopi KK, Akta Kelahiran, Pas Foto</p>',
            'fees' => 'Rp 500.000',
            'registration_url' => $this->faker->url(),
            'is_open' => true,
        ];
    }
}
