<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class EnrollmentControllerTest extends TestCase
{
    public function test_enroll_course()
    {
        $user = User::query()->whereHas('roles', function ($role) {
            $role->where('name', '=', 'student');
        })->first();

        $randomCourse = Course::query()->inRandomOrder()->first();
        $randomCourse->is_full = false;
        $randomCourse->save();

        $enrollmentData = [
            'course_id' => $randomCourse->id,
        ];

        $response = $this->actingAs($user)->post("/api/enrollment", $enrollmentData);

        $response->assertStatus(200);
    }

    public function test_enroll_fully_course()
    {
        $user = User::query()->whereHas('roles', function ($role) {
            $role->where('name', '=', 'student');
        })->first();

        $randomCourse = Course::query()->inRandomOrder()->first();
        $randomCourse->is_full = true;
        $randomCourse->save();

        $enrollmentData = [
            'course_id' => $randomCourse->id,
        ];

        $response = $this->actingAs($user)->post("/api/enrollment", $enrollmentData);

        $response->assertStatus(400);
    }
}
