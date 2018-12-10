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

    public function testCreateUser()
    {
        $validUser = [
            'name' => 'Usuário',
            'email' => 'usuario@teste.com',
            'country' => 'Brasil',
            'state' => 'SP',
            'city' => 'São Paulo',
            'address' => 'Rua Jump',
            'number' => 990,
            'zipcode' => '09090-990'
        ];

        $invalidStateUser = [
            'name' => 'Usuário Inválido',
            'email' => 'usuario@teste.com',
            'country' => 'Brasil',
            'state' => 'São Paulo',
            'city' => 'São Paulo',
            'address' => 'Rua Jump',
            'number' => 990,
            'zipcode' => '09090-990'
        ];

        $invalidArgumentUser = [
            'name' => 'Usuário Inválido',
            'city' => 'São Paulo',
            'address' => 'Rua Jump',
        ];

        $response = $this->post('/api/v1/users/', $invalidStateUser);
        $response->assertStatus(400)
            ->assertJson([
                'error' => [
                    'status' => 400
                ]
            ]);

        $response = $this->post('/api/v1/users/', $invalidArgumentUser);
        $response->assertStatus(400)
            ->assertJson([
                'error' => [
                    'status' => 400
                ]
            ]);

        $response = $this->post('/api/v1/users/', $validUser);
        $response->assertStatus(201)
            ->assertJson([
                'data' => [
                    'name' => $validUser['name'],
                    'email' => $validUser['email'],
                    'country' => $validUser['country'],
                    'state' => $validUser['state'],
                    'city' => $validUser['city'],
                    'address' => $validUser['address'],
                    'number' => $validUser['number'],
                    'zipcode' => $validUser['zipcode'],
                    'updated_at' => [],
                    'created_at' => []
                ]
            ]);
    }

    public function testUpdateUser()
    {
        $user = factory(User::class)->create();
        $invalidUserUpdate = [
            'state' => 'São Paulo',
        ];
        $validUserUpdate = [
            'number' => 10,
            'country' => 'Brasil'
        ];

        $response = $this->put('/api/v1/users/' . $user->id, $invalidUserUpdate);
        $response->assertStatus(400)
            ->assertJson([
                'error' => []
            ]);

        $response = $this->put('/api/v1/users/' . ($user->id + 1), $validUserUpdate); // <= Resource doesn't exist.
        $response->assertStatus(404)
            ->assertJson([
                'error' => []
            ]);

        $response = $this->put('/api/v1/users/' . $user->id, $validUserUpdate);
        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'country' => $validUserUpdate['country'],
                    'state' => $user->state,
                    'city' => $user->city,
                    'address' => $user->address,
                    'number' => (string) $validUserUpdate['number'],
                    'zipcode' => $user->zipcode,
                    'updated_at' => [],
                    'created_at' => []
                ]
            ]);
    }

    public function testDestroyUser()
    {
        $user = factory(User::class)->create();

        $response = $this->delete('/api/v1/users/' . ($user->id + 1)); // <= Resource doesn't exists.
        $response->assertStatus(404)
            ->assertJson([
                'error' => []
            ]);

        $response = $this->delete('/api/v1/users/' . $user->id);
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
