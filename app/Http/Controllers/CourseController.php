<?php

namespace App\Http\Controllers;

use App\Enums\CourseModality;
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

        return response(new CourseCollection($orderedByRating), 200);
    }

    /**
     * Apply all the filters
     */
    public function filter(FilterRequest $request): Response
    {
        $request->validated();

        $this->checkIfAreaExist($request['area_id']);

        $filtered = Course::query()
            ->where([
                ['area_id', '=', $request['area_id']],
                ['modality', '=', $request['modality']],
                ['price', '>=', $request['minPrice']],
                ['price', '<=', $request['maxPrice']],
            ])
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

        $this->checkIfModalityExist($request['modality']);

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

    public function checkIfModalityExist($modality)
    {
        if (!in_array($modality, CourseModality::values())) {
            abort(400, sprintf("Inexistent modality \" %s \"", $modality));
        }
    }
}
