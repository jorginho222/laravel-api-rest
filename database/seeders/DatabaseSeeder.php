<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Course;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Database\Seeder;
use Silber\Bouncer\BouncerFacade as Bouncer;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // roles
        $student = Bouncer::role()->firstOrCreate([
            'name' => 'student',
            'title' => 'Student',
        ]);
        $enroll = Bouncer::ability()->firstOrCreate([
            'name' => 'enroll',
            'title' => 'Sign up in a course',
        ]);
        $rate = Bouncer::ability()->firstOrCreate([
            'name' => 'rate',
            'title' => 'Rate a course'
        ]);
        Bouncer::allow($student)->to($enroll);
        Bouncer::allow($student)->to($rate);

        $instructor = Bouncer::role()->firstOrCreate([
            'name' => 'instructor',
            'title' => 'Instructor',
        ]);
        $manageCourses = Bouncer::ability()->firstOrCreate([
            'name' => 'manage-courses',
            'title' => 'Manage own courses',
        ]);
        Bouncer::allow($instructor)->to($manageCourses);

        $admin = Bouncer::role()->firstOrCreate([
            'name' => 'administrator',
            'title' => 'Administrator',
        ]);
        $manageAreas = Bouncer::ability()->firstOrCreate([
            'name' => 'manage-areas',
            'title' => 'Manage the areas',
        ]);
        Bouncer::allow($admin)->to($manageAreas);

        // resources
        $students = User::factory(470)
            ->create()
            ->each(function ($user) {
                $user->assign('student');
            });
        $instructors = User::factory(27)
            ->create()
            ->each(function ($user) {
                $user->assign('instructor');
            });
        $admins = User::factory(3)
            ->create()
            ->each(function ($user) {
                $user->assign('administrator');
            });
        $areas = Area::factory(5)
            ->make()
            ->each(function ($area) use ($admins) {
               $randAdmin = $admins->random();
               $area->user_id = $randAdmin->id;
               $area->save();
            });
        $courses = Course::factory(150)
            ->make()
            ->each(function ($course) use ($areas, $instructors) {
                $course->area_id = $areas->random()->id;
                $course->user_id = $instructors->random()->id;
                $course->save();
            });
        $ratings = Rating::factory(500)
            ->make()
            ->each(function ($rating) use ($students, $courses) {
               $rating->course_id = $courses->random()->id;
               $rating->user_id = $students->random()->id;
               $rating->save();
            });
        foreach ($students as $student) {
            $student->enrollments()->create(['course_id' => $courses->random()->id]);
        }
    }
}
