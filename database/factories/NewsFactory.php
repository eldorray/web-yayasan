<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NewsFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(6),
            'excerpt' => $this->faker->paragraph(2),
            'body' => '<p>'.$this->faker->paragraph(5).'</p>',
            'category' => $this->faker->randomElement(['yayasan', 'sekolah']),
            'school_id' => null,
            'author_id' => User::factory(),
            'published_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'is_published' => true,
        ];
    }
}
