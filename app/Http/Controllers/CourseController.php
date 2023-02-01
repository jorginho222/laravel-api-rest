<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterRequest;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Models\Area;
use App\Models\Course;
use Illuminate\Http\Response;
use Silber\Bouncer\BouncerFacade as Bouncer;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $orderedByRating = Course::query()->orderBy('rating', 'desc')->get();

        return response($orderedByRating->groupBy('area_id'), 200);
    }

    /**
     * Apply all the filters
     */
    public function filter(FilterRequest $request): Response
    {
        $request->validated();

        $this->checkIfAreaExist($request['area_id']);

        $filtered = Course::query()
            ->where('area_id', '=', $request['area_id'])
            ->where('price', '>=', $request['minPrice'])
            ->where('price', '<=', $request['maxPrice'])
            ->orderBy('price')->get();

        return \response($filtered, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCourseRequest $request): Response
    {
        $request->validated();

        $user = $this->checkInstructorRole();

        $this->checkIfAreaExist($request['area_id']);

        $course = $user->courses()->firstOrCreate($request->all());

        $course->available_places = $course->max_students;

        $course->save();

        return response($course, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course): Response
    {
        return \response($course->load(['ratings', 'user']), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCourseRequest $request, Course $course): Response
    {
        $request->validated();

        $user = $this->checkInstructorRole();

        if ($user->id !== $course->user_id) {
            abort(403, 'Solo los propietarios del curso pueden editarlo');
        }

        $this->checkIfAreaExist($request['area_id']);

        $course->update($request->all());

        return \response($course, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course): Response
    {
        $user = $this->checkInstructorRole();

        if ($user->id !== $course->user_id) {
            abort(403, 'Solo los propietarios del curso pueden eliminarlo');
        }

        $course->delete();

        return \response(null, 204);
    }

    public function checkInstructorRole()
    {
        $user = request()->user();

        if (Bouncer::is($user)->notAn('instructor')) {
            abort(403, 'Solo instructores pueden gestionar los cursos');
        }

        return $user;
    }

    public function checkIfAreaExist($areaId)
    {
        Area::query()->findOrFail($areaId);
    }
}
