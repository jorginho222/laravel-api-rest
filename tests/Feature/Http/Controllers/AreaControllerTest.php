<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Area;
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
        $area = new Area();
        $area->id = (string) Str::orderedUuid();
        $area->description = fake('pt_ES')->text(60);

        $response = $this->post('/api/area', $area->toArray());
        $createdArea = $response->original;

        $response->assertStatus(201);
        $this->assertEquals($area->id, $createdArea->id);
    }

    public function test_area_show()
    {
        $randomArea = Area::query()->inRandomOrder()->first();

        $response = $this->get("/api/area/{$randomArea->id}");

        $response->assertStatus(200);
    }

    public function test_area_update()
    {
        $randomArea = Area::query()->inRandomOrder()->first();

        $randomArea->description = fake('pt_ES')->text(60);

        $response = $this->put("/api/area/{$randomArea->id}", $randomArea->toArray());

        $updatedArea = $response->original;

        $response->assertStatus(200);
        $this->assertEquals($randomArea->description, $updatedArea->description);
    }

    public function test_area_destroy()
    {
        $randomArea = Area::query()->inRandomOrder()->first();

        $response = $this->delete("/api/area/{$randomArea->id}");

        $response->assertStatus(204);
    }
}
