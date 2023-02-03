<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteCourseRequest;
use App\Http\Requests\FilterRequest;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Http\Resources\CourseCollection;
use App\Http\Resources\CourseResource;
use App\Models\Area;
use App\Models\Course;
use Illuminate\Http\Response;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $orderedByRating = Course::query()->orderBy('rating', 'desc')->get();

        return response(new CourseCollection($orderedByRating->groupBy('area_id')), 200);
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

        return \response(new CourseCollection($filtered), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCourseRequest $request): Response
    {
        $request->validated();

        $this->checkIfAreaExist($request['area_id']);

        $user = request()->user();

        $course = $user->courses()->firstOrCreate($request->all());

        $course->available_places = $course->max_students;

        $course->save();

        return response(new CourseResource($course), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course): Response
    {
        return \response(new CourseResource($course->load(['ratings', 'user'])), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCourseRequest $request, Course $course): Response
    {
        $request->validated();

        $user = request()->user();

        if ($user->id !== $course->user_id) {
            abort(403, 'Only course owners can edit');
        }

        $this->checkIfAreaExist($request['area_id']);

        $course->update($request->all());

        return response(new CourseResource($course), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course, DeleteCourseRequest $request): Response
    {
        $user = request()->user();

        if ($user->id !== $course->user_id) {
            abort(403, 'Only course owners can delete');
        }

        $course->delete();

        return \response(null, 204);
    }

    public function checkIfAreaExist($areaId)
    {
        Area::query()->findOrFail($areaId);
    }
}
