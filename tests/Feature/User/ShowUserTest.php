<?php

namespace Tests\Feature\User;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;
use Illuminate\Support\Str;

class ShowUserTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $email;
    private $username;
    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->email = $this->faker->unique()->safeEmail();
        $this->username = $this->faker->unique()->userName();

        $this->user = User::factory()->create([
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

    public function test_exibicao_de_um_usuario()
    {
        $user = $this->user;

        $token = $this->authenticate();

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->getJson("/api/users/$user->id");

        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_se_o_usuario_nao_existir_retorna_404()
    {
        $token = $this->authenticate();

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->getJson('/api/users/'. Str::uuid());

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
