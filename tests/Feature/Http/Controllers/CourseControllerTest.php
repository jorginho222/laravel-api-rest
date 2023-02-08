<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Area;
use App\Models\Course;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Support\Str;
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
        $user = User::query()->whereHas('roles', function ($role) {
            $role->where('name', '=', 'instructor');
        })->first();

        $area = new Area();
        $area->description = fake('pt_ES')->text(60);

        $this->post('/api/area', $area->toArray());

        $randomArea = Area::query()->inRandomOrder()->first();

        $course = new Course();
        $course->id = (string) Str::orderedUuid();
        $course->name = fake('pt_ES')->name();
        $course->description = fake('pt_ES')->text(255);
        $course->area_id = $randomArea->id;
        $course->price = fake()->randomFloat(2, 0, 10000);
        $course->max_students = rand(0, 100);

        $response = $this->actingAs($user)->post('/api/course', $course->toArray());

        $createdCourse = $response->original;

        $response->assertStatus(201);
        $this->assertEquals($course->id, $createdCourse->id);
    }

    public function test_course_store_unauthorized()
    {
        $user = User::query()->whereHas('roles', function ($role) {
            $role->where('name', '=', 'student');
        })->first();

        $area = new Area();
        $area->description = fake('pt_ES')->text(60);

        $this->post('/api/area', $area->toArray());

        $randomArea = Area::query()->inRandomOrder()->first();

        $course = new Course();
        $course->id = (string) Str::orderedUuid();
        $course->name = fake('pt_ES')->name();
        $course->description = fake('pt_ES')->text(255);
        $course->area_id = $randomArea->id;
        $course->price = fake()->randomFloat(2, 0, 10000);
        $course->max_students = rand(0, 100);

        $response = $this->actingAs($user)->post('/api/course', $course->toArray());

        $response->assertStatus(403);
    }

    public function test_course_show()
    {
        $course = Course::query()->inRandomOrder()->first();

        $response = $this->get("/api/course/{$course->id}");
        $response->assertStatus(200);
    }

    public function test_course_update()
    {
        $user = User::query()->whereHas('roles', function ($role) {
            $role->where('name', '=', 'instructor');
        })->first();

        $area = Area::query()->inRandomOrder()->first();

        $course = Course::query()->inRandomOrder()->first();
        $course->name = fake('pt_ES')->name();
        $course->area_id = $area->id;
        $course->price = fake()->randomFloat(2, 0, 10000);
        $course->user_id = $user->id;

        $course->save();

        $response = $this->actingAs($user)->put("/api/course/{$course->id}", $course->toArray());

        $updatedCourse = $response->original;

        $response->assertStatus(200);
        $this->assertEquals($course->name, $updatedCourse->name);
    }

    public function test_course_destroy()
    {
        $user = User::query()->whereHas('roles', function ($role) {
            $role->where('name', '=', 'instructor');
        })->first();

        $course = Course::query()->inRandomOrder()->first();

        $course->user_id = $user->id;

        $course->save();

        $response = $this->actingAs($user)->delete("/api/course/{$course->id}");

        $response->assertStatus(204);
    }

    public function test_course_filter()
    {
        $randomAreaId = Course::query()->inRandomOrder()->first()->area_id;

        $minPrice = Course::query()->orderBy('price')->first()->price;

        $maxPrice = Course::query()->orderBy('price', 'desc')->first()->price;

        $criterias = [
          'area_id' => $randomAreaId,
          'minPrice' => $minPrice,
          'maxPrice' => $maxPrice,
        ];

        $response = $this->post('/api/course/filter', $criterias);

        $filtered = $response->original;

        $response->assertStatus(200);

        $this->assertNotEmpty($filtered);
    }
}
