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
        $rating = $request->validated();

        $user = User::query()->findOr($rating['user_id'], function () {
            abort(400, 'No se encuentra el usuario');
        });
        $course = Course::query()->findOr($rating['course_id'], function () {
            abort(400, 'No se encuentra el curso');
        });

        if (Bouncer::is($user)->notA('student')) {
            abort(403, 'Solo usuarios registrados como estudiantes pueden valorar un curso');
        }

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

}
