<?php

namespace Database\Factories;

use App\Models\School;
use Illuminate\Database\Eloquent\Factories\Factory;

class SchoolFactory extends Factory
{
    public function definition(): array
    {
        $levels = ['TK', 'SD', 'SMP', 'SMA', 'SMK'];
        $name = $this->faker->company().' Islam';

        return [
            'name' => $name,
            'level' => $this->faker->randomElement($levels),
            'description' => $this->faker->paragraph(3),
            'address' => $this->faker->address(),
            'phone' => $this->faker->phoneNumber(),
            'email' => $this->faker->safeEmail(),
            'website_url' => $this->faker->optional()->url(),
            'established_year' => $this->faker->year(),
            'is_active' => true,
            'sort_order' => 0,
        ];
    }
}
