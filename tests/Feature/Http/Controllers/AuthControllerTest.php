<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
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
