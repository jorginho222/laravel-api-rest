<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRatingRequest;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Silber\Bouncer\BouncerFacade as Bouncer;

class RatingController extends Controller
{
    /**
     *  Rates a specified course
     */
    public function rate(StoreRatingRequest $request): Response
    {
        $request->validated();

        $course = Course::query()->findOr($request['course_id'], function () {
            abort(400, 'No se encuentra el curso');
        });

        $user = $this->checkStudentRole();

        foreach ($user->ratings as $userRating) {
            if ($userRating->course_id === $course->id) {
                $userRating->update($request->all());
            }
        }

        $rating = $course->ratings()->firstOrCreate($request->all());

        $count = $course->ratings->count();
        $sum = $course->ratings->pluck('value')->sum();

        $course->rating = $sum / $count;

        $course->save();

        return \response($rating, 200);
    }

    public function checkStudentRole()
    {
        $user = \request()->user();

        if (Bouncer::is($user)->notA('student')) {
            abort(403, 'Solo los usuarios registrados como estudiantes pueden inscribirse');
        }

        return $user;
    }

}
