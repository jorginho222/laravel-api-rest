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
        $user = User::query()->whereHas('roles', function ($role) {
            $role->where('name', '=', 'student');
        })->first();

        $credentials = [
            'email' => $user->email,
            'password' => 'password',
        ];

        $response = $this->post('/api/login', $credentials);

        $response->assertJsonStructure(['access_token']);
    }
}
