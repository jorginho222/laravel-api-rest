<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Area;
use App\Models\Course;
use Tests\TestCase;

class CourseControllerTest extends TestCase
{
    public function test_course_index()
    {
        $response = $this->get('/api/course');

        $response->assertStatus(200);
    }

    public function test_course_store()
    {
        $randomArea = Area::query()->inRandomOrder()->first();

        $course = new Course();
        $course->name = fake('pt_ES')->name();
        $course->description = fake('pt_ES')->text(255);
        $course->area_id = $randomArea->id;
        $course->price = fake()->randomFloat(2, 0, 10000);
        $course->max_students = rand(0, 100);

        $response = $this->post('/api/course', $course->toArray());
        $response->assertStatus(201);
    }

    public function test_course_show()
    {
        $course = Course::query()->inRandomOrder()->first();

        $response = $this->get("/api/course/{$course->id}");
        $response->assertStatus(200);
    }

    public function test_course_update()
    {
        $course = Course::query()->inRandomOrder()->first();
        $course->name = fake('pt_ES')->name();
        $course->area = fake('pt_ES')->text(60);
        $course->price = fake()->randomFloat(2, 0, 10000);

        $response = $this->put("/api/course/{$course->id}", $course->toArray());
        $response->assertStatus(200);
    }

    public function test_course_destroy()
    {
        $course = Course::query()->inRandomOrder()->first();

        $response = $this->delete("/api/course/{$course->id}");
        $response->assertStatus(204);
    }

    public function test_course_filter()
    {
        $randomArea = Area::query()->inRandomOrder()->first();

        $minPrice = Course::query()->orderBy('price')->first();

        $maxPrice = Course::query()->orderBy('price', 'desc')->first();

        $criterias = [
          'areaId' => $randomArea->id,
          'minPrice' => $minPrice,
          'maxPrice' => $maxPrice,
        ];

        $response = $this->post('/api/course/filter', $criterias);

        $response->assertStatus(200);
    }
}
