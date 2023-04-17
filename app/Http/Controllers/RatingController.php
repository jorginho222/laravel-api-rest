<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRatingRequest;
use App\Http\Resources\RatingResource;
use App\Models\Course;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseStatus;

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

        return \response(new RatingResource($rating->load(['course', 'user'])), ResponseStatus::HTTP_CREATED);
    }
}
