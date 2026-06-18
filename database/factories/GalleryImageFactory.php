<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GalleryImageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'image' => 'gallery/sample.jpg',
            'caption' => $this->faker->sentence(),
            'school_id' => null,
            'sort_order' => 0,
        ];
    }
}
