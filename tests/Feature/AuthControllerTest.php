<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        User::factory()->create([
            'email' => 'teste@teste.com',
            'password' => bcrypt('senha'),
            'username' => 'teste',
            'name' => 'Teste',
            'avatar' => 'teste.jpg'
        ]);

        $this->refreshTestDatabase();
    }

    public function test_criacao_de_usuario_com_credenciais_validas()
    {
        $response = $this->postJson('/api/auth/register', [
            'email' => 'usuario01@user.com',
            'password' => bcrypt('usuario'),
            'username' => 'usuario_01',
            'name' => 'Usuário',
            'avatar' => 'user.jpg'
        ]);

        $response->assertStatus(Response::HTTP_CREATED);
    }

    public function test_criacao_de_usuario_com_email_ja_existente()
    {
        $response = $this->postJson('/api/auth/register', [
            'email' => 'teste@teste.com',
            'password' => bcrypt('usuario'),
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function test_criacao_de_usuario_com_username_ja_existente()
    {
        $response = $this->postJson('/api/auth/register', [
            'email' => 'outro@email.com',
            'password' => bcrypt('teste'),
            'username' => 'teste',
            'name' => 'Teste',
            'avatar' => 'teste.jpg'
        ]);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function test_todas_as_regras_de_validacao_para_criar_um_usuario()
    {
        $data = [
            'email' => 'email-invalido',
            'password' => '123',
        ];

        foreach ($data as $field => $value) {
            $response = $this->postJson('/api/auth/register', [
                $field => $value,
            ]);

            $response->assertStatus(Response::HTTP_BAD_REQUEST);
            $response->assertJsonValidationErrors([$field]);
        }
    }

    public function test_todas_as_regras_de_validacao_para_logar_um_usuario()
    {
        $data = [
            'email' => 'email-invalido',
            'password' => '123',
        ];

        foreach ($data as $field => $value) {
            $response = $this->postJson('/api/auth/login', [
                $field => $value,
            ]);

            $response->assertStatus(Response::HTTP_BAD_REQUEST);
            $response->assertJsonValidationErrors([$field]);
        }
    }

    public function test_login_com_credenciais_validas()
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'teste@teste.com',
            'password' => 'senha',
        ]);

        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_login_com_credenciais_invalidas()
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'email-invalido@email.com',
            'password' => 'senha_errada',
        ]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_logout_sem_token()
    {
        $response = $this->postJson('/api/auth/logout');
        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function test_logout_com_token()
    {
        $loginResponse = $this->postJson('/api/auth/login', [
            'email' => 'teste@teste.com',
            'password' => 'senha',
        ]);

        $token = $loginResponse->json('data.token');

        $response = $this->withheaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson('/api/auth/logout');

        $response->assertStatus(Response::HTTP_OK);
    }
}
