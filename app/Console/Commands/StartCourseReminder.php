<?php

namespace App\Console\Commands;

use App\Jobs\SendEmailJob;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class StartCourseReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminder:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder a few days before course starts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $enrollments = Enrollment::query()->whereHas('course', function ($course) {
            $course->where('init_date', '=', Carbon::now()->addDays(5)->format('y-m-d'));
        })->get();

        foreach ($enrollments as $enrollment) {
            $email = $enrollment->user->email;
            $details['email'] = $email;
            $details['userName'] = $enrollment->user->name;
            $details['course'] = $enrollment->course;
            dispatch(new SendEmailJob($details));
        }

        $this->info('Succesfully sent starting course reminder');
    }
}
