<?php

namespace Database\Factories;

use App\Enums\CourseModality;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'id' => (string) Str::orderedUuid(),
            'name' => fake('pt_ES')->text(60),
            'description' => fake('pt_ES')->text(255),
            'init_date' => Carbon::now()->addDays(rand(3, 6)),
            'price' => fake()->randomFloat(2, 0, 10000),
            'max_students' => rand(20, 60),
            'available_places' => rand(1, 20),
            'modality' => fake()->randomElement(CourseModality::values()),
            'rating' => rand(1, 5),
        ];
    }
}
