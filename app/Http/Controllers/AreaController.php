<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteAreaRequest;
use App\Http\Requests\StoreAreaRequest;
use App\Http\Requests\UpdateAreaRequest;
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
        return \response(Area::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAreaRequest $request): Response
    {
        $request->validated();

        $user = $this->checkAdminRole();

        $area = $user->areas()->create($request->all());

        return \response($area, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Area $area): Area
    {
        return $area->load('courses');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Area $area, UpdateAreaRequest $request): Response
    {
        $request->validated();

        $this->checkAdminRole();

        $area->update($request->all());

        return \response($area, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Area $area): Response
    {
        $this->checkAdminRole();

        $area->delete();

        return \response(null, 204);
    }

    private function checkAdminRole()
    {
        $user = request()->user();

        if (Bouncer::is($user)->notAn('administrator')) {
            abort(403, 'Solo los administradores pueden gestionar las áreas');
        }

        return $user;
    }
}
