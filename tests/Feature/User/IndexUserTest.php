<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class IndexUserTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $email;
    private $username;

    public function setUp(): void
    {
        parent::setUp();

        $this->email = $this->faker->unique()->safeEmail();
        $this->username = $this->faker->unique()->userName();

        User::factory()->create([
            'email' => $this->email,
            'password' => bcrypt('senha'),
            'username' => $this->username,
            'name' => $this->faker->name(),
            'avatar' => 'teste.jpg',
        ]);

        $this->authenticate();

        $this->refreshTestDatabase();
    }

    public function authenticate()
    {
        $loginResponse = $this->postJson('/api/auth/login', [
            'email' => $this->email,
            'password' => 'senha'
        ]);

        return $loginResponse->json('data.token');
    }

    public function test_listagem_de_usuarios_com_sucesso()
    {
        $token = $this->authenticate();

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->getJson('/api/users');

        $response->assertStatus(Response::HTTP_OK);
    }
}
