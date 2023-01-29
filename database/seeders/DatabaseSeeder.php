<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        $users = User::factory(20)
            ->create()
            ->each(function ($user) {
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

                $user->assign('student');
            });

        $instructors = User::factory(10)
            ->create()
            ->each(function ($user) {
                $instructor = Bouncer::role()->firstOrCreate([
                    'name' => 'instructor',
                    'title' => 'Instructor',
                ]);

                $manageCourses = Bouncer::ability()->firstOrCreate([
                    'name' => 'manage-courses',
                    'title' => 'Manage own courses',
                ]);

                Bouncer::allow($instructor)->to($manageCourses);

                $user->assign('instructor');
            });

        $admins = User::factory(3)
            ->create()
            ->each(function ($user) {
                $admin = Bouncer::role()->firstOrCreate([
                    'name' => 'administrator',
                    'title' => 'Administrator',
                ]);

                $manageAreas = Bouncer::ability()->firstOrCreate([
                    'name' => 'manage-areas',
                    'title' => 'Manage the areas',
                ]);

                Bouncer::allow($admin)->to($manageAreas);

                $user->assign('administrator');
            });

        $areas = Area::factory(5)->create();

        $courses = Course::factory(10)
            ->make()
            ->each(function ($course) use ($areas, $instructors) {
                $randArea = $areas->random();
                $randInstruc = $instructors->random();
                $course->area_id = $randArea->id;
                $course->user_id = $randInstruc->id;
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
