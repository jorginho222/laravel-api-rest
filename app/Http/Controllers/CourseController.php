<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteCourseRequest;
use App\Http\Requests\FilterRequest;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Models\Area;
use App\Models\Course;
use App\Models\User;
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
     * Store a newly created resource in storage.
     */
    public function store(StoreCourseRequest $request): Response
    {
        $request->validated();

        $area = Area::query()->findOrFail($request['area_id']);

        $user = User::query()->findOrFail($request['user_id']);

        if (Bouncer::is($user)->notAn('instructor')) {
            abort(403, 'EL usuario no esta autorizado a crear un curso');
        }

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

        $area = Area::query()->findOrFail($request['area_id']);

        $user = User::query()->findOrFail($request['user_id']);

        if ($user->id !== $course->user_id) {
            abort(403, 'El usuario no esta autorizado a editar el curso');
        }

        $course->update($request->all());

        return \response($course, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course, DeleteCourseRequest $request): Response
    {
        $request->validated();

        $user = User::query()->findOrFail($request['user_id']);

        if ($user->id !== $course->user_id) {
            abort(403, 'El usuario no esta autorizado a eliminar el curso');
        }

        $course->delete();
        return \response(null, 204);
    }

    /**
     * Apply all the filters
     */
    public function filter(FilterRequest $request): Response
    {
        $criterias = $request->validated();

        Area::query()->findOrFail($criterias['areaId']);

        $filtered = Course::query()
            ->where('area_id', '=', $criterias['areaId'])
            ->where('price', '>=', $criterias['minPrice'])
            ->where('price', '<=', $criterias['maxPrice'])
            ->orderBy('price')->get();

        return \response($filtered, 200);
    }
}
