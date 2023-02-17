<?php

namespace App\Console\Commands;

use App\Jobs\SendEmailJob;
use App\Models\Course;
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
     *
     * @return int
     */
    public function handle()
    {
        $courses = Course::query()->where('init_date', '=', Carbon::now()->addDays(5));

        foreach ($courses as $course) {
            $course->users->pluck('email');
        }

        foreach ($emails as $email) {
            $details['email'] = $email;
            dispatch(new SendEmailJob($details));
        }

        $this->info('Succesfully sent starting course reminder');
    }
}
