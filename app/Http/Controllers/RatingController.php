<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRatingRequest;
use App\Http\Resources\EnrollmentResource;
use App\Http\Resources\RatingResource;
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

        $course = Course::query()->findOrFail($request['course_id']);

        $user = \request()->user();

        $rating = $user->ratings()->updateOrCreate(
            ['course_id' => $course->id],
            $request->all()
        );

        $course->rating = $course->ratings->avg('value');

        $course->save();

        return \response(new RatingResource($rating->load(['course', 'user'])), 200);
    }
}
