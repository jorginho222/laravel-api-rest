<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{

    public function test_login()
    {
        $user = new User();
        $user->name = 'Ramone';
        $user->email = 'test@test.com';
        $user->password = '12345678';

        $registered = User::query()->firstOrCreate($user->toArray());

        $response = $this->post('/api/login', $user->toArray());
    }
}
