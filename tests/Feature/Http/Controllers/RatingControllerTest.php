<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Course;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class RatingControllerTest extends TestCase
{
    public function test_course_rate()
    {
        $user = User::query()->whereHas('roles', function ($role) {
            $role->where('name', '=', 'student');
        })->first();

        $randomCourse = Course::query()->inRandomOrder()->first();

        $rating = new Rating();
        $rating->id = (string) Str::orderedUuid();
        $rating->value = 5;
        $rating->comment = 'Awesome: ' . fake()->text(30);
        $rating->course_id = $randomCourse->id;

        $response = $this->actingAs($user)->post("/api/rating", $rating->toArray());

        $createdRating = $response->original;

        $response->assertStatus(200);
        $this->assertEquals($rating->id, $createdRating->id);
    }

    public function test_course_invalid_rate()
    {
        $user = User::query()->whereHas('roles', function ($role) {
            $role->where('name', '=', 'student');
        })->first();

        $randomCourse = Course::query()->inRandomOrder()->first();

        $rating = new Rating();
        $rating->id = (string) Str::orderedUuid();
        $rating->value = 0;
        $rating->comment = 'Awesome: ' . fake()->text(30);
        $rating->course_id = $randomCourse->id;

        $response = $this->actingAs($user)->post("/api/rating", $rating->toArray());

        $response->assertStatus(302);
    }
}
