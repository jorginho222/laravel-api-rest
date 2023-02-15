<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteAreaRequest;
use App\Http\Requests\StoreAreaRequest;
use App\Http\Requests\UpdateAreaRequest;
use App\Http\Resources\AreaResource;
use App\Models\Area;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Silber\Bouncer\BouncerFacade as Bouncer;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return \response(AreaResource::collection(Area::all()), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAreaRequest $request): Response
    {
        $request->validated();

        $user = request()->user();

        $area = $user->areas()->create($request->all());

        return \response(new AreaResource($area), 201);
    }

    /**
     * Display the specified resource
     */
    public function show(Area $area): Area
    {
        return $area->load(['courses']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Area $area, UpdateAreaRequest $request): Response
    {
        $request->validated();

        $area->update($request->all());

        return \response(new AreaResource($area), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Area $area, DeleteAreaRequest $request): Response
    {
        $area->delete();

        return \response(null, 204);
    }
}
