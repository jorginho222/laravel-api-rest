<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterByAreaRequest;
use App\Http\Requests\FilterRequest;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Models\Course;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return response(Course::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCourseRequest $request): Response
    {
        $request->validated();
        $course = Course::query()->firstOrCreate($request->all());
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
            ->where('area', '=', $criterias['area'])
            ->where('price', '<=', $criterias['price'])->get();

        return \response($filtered, 200);
    }

    /**
     * Filter courses by area
     */
    public function filterByArea($areaCriteria): Collection
    {
        return Course::query()->where('area', '=', $areaCriteria)->get();
    }

}
