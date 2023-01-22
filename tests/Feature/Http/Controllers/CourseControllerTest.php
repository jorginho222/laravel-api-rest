<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Course;
use Tests\TestCase;

class CourseControllerTest extends TestCase
{
    public function test_index()
    {
        $response = $this->get('/api/course');

        $response->assertStatus(200);
    }

    public function test_store()
    {
        $course = new Course();
        $course->name = fake('pt_ES')->name();
        $course->description = fake('pt_ES')->text(255);
        $course->area = fake('pt_ES')->text(60);
        $course->price = fake()->randomFloat(2, 0, 10000);
        $course->max_students = rand(0, 100);

        $response = $this->post('/api/course', $course->toArray());
        $response->assertStatus(201);
    }

    public function test_show()
    {
        $course = Course::query()->inRandomOrder()->first();

        $response = $this->get("/api/course/{$course->id}");
        $response->assertStatus(200);
    }

    public function test_update()
    {
        $course = Course::query()->inRandomOrder()->first();
        $course->name = fake('pt_ES')->name();
        $course->area = fake('pt_ES')->text(60);
        $course->price = fake()->randomFloat(2, 0, 10000);

        $response = $this->put("/api/course/{$course->id}", $course->toArray());
        $response->assertStatus(200);
    }

    public function test_destroy()
    {
        $course = Course::query()->inRandomOrder()->first();

        $response = $this->delete("/api/course/{$course->id}");
        $response->assertStatus(204);
    }
}
