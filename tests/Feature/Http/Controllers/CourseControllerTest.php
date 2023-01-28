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

        $response = $this->post('/api/course', $course->toArray());
        $createdCourse = $response->original;

        $response->assertStatus(201);
        $this->assertEquals($course->id, $createdCourse->id);
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
        $updatedCourse = $response->original;

        $response->assertStatus(200);
        $this->assertEquals($course->name, $updatedCourse->name);
    }

    public function test_course_destroy()
    {
        $course = Course::query()->inRandomOrder()->first();

        $response = $this->delete("/api/course/{$course->id}");
        $response->assertStatus(204);
    }

    public function test_course_filter()
    {
        $randomAreaId = Course::query()->inRandomOrder()->first()->area_id;

        $minPrice = Course::query()->orderBy('price')->first()->price;

        $maxPrice = Course::query()->orderBy('price', 'desc')->first()->price;

        $criterias = [
          'areaId' => $randomAreaId,
          'minPrice' => $minPrice,
          'maxPrice' => $maxPrice,
        ];

        $response = $this->post('/api/course/filter', $criterias);

        $filtered = $response->original;

        $response->assertStatus(200);

        // TODO: Fix fail: assert object is not empty
        $this->assertNotEmpty($filtered);
    }

    public function test_course_enroll()
    {
        $randomUserId = User::query()->inRandomOrder()->first()->id;
        $enrollmentData = [
            'userId' => $randomUserId,
        ];

        $randomCourse = Course::query()->inRandomOrder()->first();
        $randomCourse->available_places = 1;
        $randomCourse->is_full = false;
        $randomCourse->save();

        $response = $this->put("/api/course/{$randomCourse->id}/enroll", $enrollmentData);

        $updatedCourse = $response->original;

        $response->assertStatus(200);
        $this->assertEquals(true, $updatedCourse->is_full);
    }

    public function test_fully_course_enroll()
    {
        $randomUserId = User::query()->inRandomOrder()->first()->id;
        $enrollmentData = [
            'userId' => $randomUserId,
        ];

        $randomCourse = Course::query()->inRandomOrder()->first();
        $randomCourse->is_full = true;
        $randomCourse->save();

        $response = $this->put("/api/course/{$randomCourse->id}/enroll", $enrollmentData);

        $this->assertEquals(400, $response->getStatusCode());
    }

    public function test_course_rate()
    {
        $randomCourse = Course::query()->inRandomOrder()->first();
        $randomUser = User::query()->inRandomOrder()->first();

        $rating = new Rating();
        $rating->id = (string) Str::orderedUuid();
        $rating->value = 5;
        $rating->comment = 'Awesome: ' . fake()->text(30);
        $rating->user_id = $randomUser->id;

        $response = $this->put("/api/course/{$randomCourse->id}/rate", $rating->toArray());

        $createdRating = $response->original->ratings->last();

        $response->assertStatus(200);
        $this->assertEquals($rating->id, $createdRating->id);
    }

    public function test_course_invalid_rate()
    {
        $randomCourse = Course::query()->inRandomOrder()->first();

        $rating = new Rating();
        $rating->id = (string) Str::orderedUuid();
        $rating->value = 0;
        $rating->comment = 'Awesome: ' . fake()->text(30);

        $response = $this->put("/api/course/{$randomCourse->id}/rate", $rating->toArray());

        $response->assertStatus(302);
    }

    public function test_course_get_ratings()
    {
        $randomCourse = Course::query()->inRandomOrder()->first();

        $rating = new Rating();
        $rating->id = (string) Str::orderedUuid();
        $rating->value = 5;
        $rating->comment = 'Awesome: ' . fake()->text(30);

        $this->put("/api/course/{$randomCourse->id}/rate", $rating->toArray());

        $response = $this->get("/api/course/{$randomCourse->id}/ratings");
        $ratings = $response->original;

        $response->assertStatus(200);
        $this->assertNotEmpty($ratings);
    }
}
