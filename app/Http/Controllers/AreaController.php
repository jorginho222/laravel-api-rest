<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAreaRequest;
use App\Http\Requests\UpdateAreaRequest;
use App\Models\Area;
use Illuminate\Http\Response;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return \response(Area::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAreaRequest $request): Response
    {
        $request->validated();
        $area = Area::query()->firstOrCreate($request->all());

        return \response($area, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Area $area): Area
    {
        return $area;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAreaRequest $request, Area $area): Response
    {
        $request->validated();
        return $area->update($request->all()) ? \response($area, 200) : \response(null, 400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Area $area): Response
    {
        $area->delete();
        return \response(null, 204);
    }
}
