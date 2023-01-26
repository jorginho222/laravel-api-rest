<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterRequest;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Http\Requests\StoreRatingRequest;
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

        return response($orderedByRating->groupBy('area_id'), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCourseRequest $request): Response
    {
        $request->validated();
        $course = Course::query()->firstOrCreate($request->all());

        $course->available_places = $course->max_students;

        $course->save();

        return response($course, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course): Course
    {
        return $course;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCourseRequest $request, Course $course): Response
    {
        return $course->update($request->all()) ? \response($course) : \response(null, 400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course): Response
    {
        $course->delete();
        return \response(null, 204);
    }

    /**
     * Apply all the filters
     */
    public function filter(FilterRequest $request): Response
    {
        $criterias = $request->validated();

        $filtered = Course::query()
            ->where('area_id', '=', $criterias['areaId'])
            ->where('price', '>=', $criterias['minPrice'])
            ->where('price', '<=', $criterias['maxPrice'])->orderBy('price')->get();

        return \response($filtered, 200);
    }

    /**
     *  Effectuates an enrollment
     */
    public function enroll(Course $course): Response
    {
        if ($course->is_full) {
            abort(400, sprintf('No hay cupo disponible para el curso: %s', $course->name));
        }

        $course->available_places --;

        if ($course->available_places === 0) {
            $course->is_full = true;
        }

        $course->save();

        return \response($course, 200);
    }

    public function rate(Course $course, StoreRatingRequest $request): Response
    {
        $request->validated();

        $course->ratings()->firstOrCreate($request->all());

        $count = $course->ratings->count();
        $sum = $course->ratings->pluck('value')->sum();

        $course->rating = $sum / $count;

        $course->save();

        return \response($course, 200);
    }

    public function getRatings(Course $course): Response
    {
        $ratings = $course->ratings;

        return \response($ratings, 200);
    }
}
