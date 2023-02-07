<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Area;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class AreaControllerTest extends TestCase
{
    public function test_area_index()
    {
        $response = $this->get('/api/area');

        $response->assertStatus(200);
    }

    public function test_area_store()
    {
        $user = User::query()->whereHas('roles', function ($role) {
            $role->where('name', '=', 'administrator');
        })->first();

        $area = new Area();
        $area->id = (string) Str::orderedUuid();
        $area->description = fake('pt_ES')->text(60);

        $response = $this->actingAs($user)
                         ->post('/api/area', $area->toArray());
        $createdArea = $response->original;

        $response->assertStatus(201);
        $this->assertEquals($area->id, $createdArea->id);
    }

    public function test_area_store_unauthorized()
    {
        $user = User::query()->whereHas('roles', function ($role) {
            $role->where('name', '=', 'instructor');
        })->first();

        $area = new Area();
        $area->id = (string) Str::orderedUuid();
        $area->description = fake('pt_ES')->text(60);

        $response = $this->actingAs($user)
                         ->post('/api/area', $area->toArray());

        $response->assertStatus(403);
    }

    public function test_area_show()
    {
        $randomArea = Area::query()->inRandomOrder()->first();

        $response = $this->get("/api/area/{$randomArea->id}");

        $response->assertStatus(200);
    }

    public function test_area_update()
    {
        $user = User::query()->whereHas('roles', function ($role) {
            $role->where('name', '=', 'administrator');
        })->first();

        $randomArea = Area::query()->inRandomOrder()->first();

        $randomArea->description = fake('pt_ES')->text(60);

        $response = $this->actingAs($user)
                         ->put("/api/area/{$randomArea->id}", $randomArea->toArray());

        $updatedArea = $response->original;

        $response->assertStatus(200);
        $this->assertEquals($randomArea->description, $updatedArea->description);
    }

    public function test_area_update_unauthorized()
    {
        $user = User::query()->whereHas('roles', function ($role) {
            $role->where('name', '=', 'student');
        })->first();

        $randomArea = Area::query()->inRandomOrder()->first();

        $randomArea->description = fake('pt_ES')->text(60);

        $response = $this->actingAs($user)
                         ->put("/api/area/{$randomArea->id}", $randomArea->toArray());

        $response->assertStatus(403);
    }

    public function test_area_destroy()
    {
        $user = User::query()->whereHas('roles', function ($role) {
            $role->where('name', '=', 'administrator');
        })->first();

        $randomArea = Area::query()->inRandomOrder()->first();

        $response = $this->actingAs($user)
                         ->delete("/api/area/{$randomArea->id}");

        $response->assertStatus(204);
    }

    public function test_area_destroy_unauthorized()
    {
        $user = User::query()->whereHas('roles', function ($role) {
            $role->where('name', '=', 'instructor');
        })->first();

        $randomArea = Area::query()->inRandomOrder()->first();

        $response = $this->actingAs($user)
                         ->delete("/api/area/{$randomArea->id}");

        $response->assertStatus(403);
    }
}
