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

        foreach ($users as $user) {
            $user->number = (string) $user->number;
        }

        $response = $this->get('/api/v1/users');
        $response->assertStatus(200)
            ->assertJson([
                'data' => $users->toArray(),
                'links' => [],
                'meta' => []
            ]);
    }

    public function testGetSingleUser()
    {
        $user = factory(User::class)->create();
        $user->number = (string) $user->number;

        $response = $this->get('/api/v1/users/' . $user->id);
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'country' => $user->country,
                    'state' => $user->state,
                    'city' => $user->city,
                    'address' => $user->address,
                    'number' => $user->number,
                    'zipcode' => $user->zipcode,
                    'updated_at' => [],
                    'created_at' => []
                ]
            ]);
    }
}
