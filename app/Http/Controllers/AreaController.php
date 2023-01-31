<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeleteAreaRequest;
use App\Http\Requests\StoreAreaRequest;
use App\Http\Requests\UpdateAreaRequest;
use App\Models\Area;
use App\Models\User;
use Illuminate\Http\Response;
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
        $data = $request->validated();

        $user = $this->checkUserRole($data);

        $area = $user->areas()->firstOrCreate($request->all());

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
    public function update(UpdateAreaRequest $request): Response
    {
        $data = $request->validated();

        $user = $this->checkUserRole($data);

        $area = $user->areas()->update($request->all());

        return \response($area, 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Area $area, DeleteAreaRequest $request): Response
    {
        $data = $request->validated();

        $this->checkUserRole($data);

        $area->delete();

        return \response(null, 204);
    }

    private function checkUserRole($data)
    {
        $user = User::query()->findOr($data['user_id'], function () {
            abort(400, 'No se encuentra el usuario');
        });

        if (Bouncer::is($user)->notAn('administrator')) {
            abort(400, 'Solo los administradores pueden gestionar las áreas');
        }

        return $user;
    }
}
