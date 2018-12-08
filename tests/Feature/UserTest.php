<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetAllUsers()
    {
        $users = factory(User::class, 5)->create();

        $response = $this->get('/api/v1/users');
        $response->assertStatus(200)
            ->assertJson([
                'data' => $users->toArray(),
                'links' => [
                    'self' => '',
                    'meta' => ''
                ]
            ]);
    }

    public function testGetSingleUser()
    {
        $user = factory(User::class)->create();

        $response = $this->get('/api/v1/users/' . $user->id);
        $response->assertStatus(200)
            ->assertJson($user->toArray());
    }
}
