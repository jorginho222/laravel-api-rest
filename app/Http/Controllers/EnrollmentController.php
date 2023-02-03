<?php

namespace App\Http\Controllers;

use App\Http\Requests\EnrollCourseRequest;
use App\Http\Resources\EnrollmentResource;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Silber\Bouncer\BouncerFacade as Bouncer;

class EnrollmentController extends Controller
{
    public function index()
    {
        //
    }

    /**
     *  Effectuates an enrollment
     */
    public function enroll(EnrollCourseRequest $request): Response
    {
        $request->validated();

        $course = Course::query()->findOr($request['course_id'], function () {
            abort(400, 'Course not found');
        });

        $user = \request()->user();

        if ($user->enrollments) {
            foreach ($user->enrollments as $enrollment) {
                if ($enrollment->course_id === $course->id) {
                    abort(400, 'User has been enrolled on this course');
                }
            }
        }

        if ($course->is_full) {
            abort(400, sprintf("There isn't available places for the course: %s", $course->name));
        }

        $enrollment = $user->enrollments()->firstOrCreate($request->all());

        $course->available_places --;

        if ($course->available_places === 0) {
            $course->is_full = true;
        }

        $course->save();

        return \response(new EnrollmentResource($enrollment->load(['user', 'course'])), 200);
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
