<?php

namespace App\Http\Controllers;

use App\Http\Requests\EnrollCourseRequest;
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
            abort(400, 'No se encuentra el curso');
        });

        $user = $this->checkStudentRole();

        if ($user->enrollments) {
            foreach ($user->enrollments as $enrollment) {
                if ($enrollment->course_id === $course->id) {
                    abort(400, 'El usuario ya se encuentra inscripto en este curso');
                }
            }
        }

        if ($course->is_full) {
            abort(400, sprintf('No hay cupo disponible para el curso: %s', $course->name));
        }

        $user->enrollments()->firstOrCreate($request->all());

        $course->available_places --;

        if ($course->available_places === 0) {
            $course->is_full = true;
        }

        $course->save();

        return \response($course, 200);
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

    public function checkStudentRole()
    {
        $user = \request()->user();

        if (Bouncer::is($user)->notA('student')) {
            abort(403, 'Solo los usuarios registrados como estudiantes pueden inscribirse');
        }

        return $user;
    }
}
