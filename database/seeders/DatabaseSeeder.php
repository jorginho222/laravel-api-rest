<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Area;
use App\Models\Course;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $users = User::factory(10)->create();

        $areas = Area::factory(5)->create();

        $courses = Course::factory(15)
            ->make()
            ->each(function ($course) use ($areas) {
                $randArea = $areas->random();
                $course->area_id = $randArea->id;
                $course->save();
            });

        $ratings = Rating::factory(60)
            ->make()
            ->each(function ($rating) use ($users, $courses) {
               $rating->course_id = $courses->random()->id;
               $rating->user_id = $users->random()->id;
               $rating->save();
            });
    }
}
