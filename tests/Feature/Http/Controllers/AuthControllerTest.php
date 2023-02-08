<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{

    // TODO: finish test_login assertions
    public function test_login()
    {
        $userFact = User::factory(1)->create()->first();

        $userArr = $userFact->toArray();

        $response = $this->post('/api/login', $userArr);
    }
}
