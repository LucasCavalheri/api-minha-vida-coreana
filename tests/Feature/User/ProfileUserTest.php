<?php

namespace Tests\Feature\User;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class ProfileUserTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->has(Post::factory()->count(2))->create([
            'id' => '99920c15-8e8f-41b0-b882-b569f626400a',
            'email' => 'lucas@email.com',
            'password' => bcrypt('senha'),
            'username' => 'lucas_cavalheri',
            'name' => 'Lucas',
            'avatar' => 'teste.jpg',
            'is_admin' => 0,
        ]);

        $this->authenticate();
        $this->refreshTestDatabase();
    }

    public function authenticate()
    {
        $loginResponse = $this->postJson('/api/auth/login', [
            'email' => 'lucas@email.com',
            'password' => 'senha'
        ]);

        return $loginResponse->json('data.token');
    }

    public function test_se_retorna_o_usuario_com_suas_informacoes()
    {
        $token = $this->authenticate();

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->getJson('/api/users/me');

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'id' => $this->user->id,
            'email' => $this->user->email,
            'username' => $this->user->username,
            'name' => $this->user->name,
            'avatar' => $this->user->avatar,
            'is_admin' => $this->user->is_admin,
        ]);
        $response->assertJsonCount(2, 'posts');
        $response->assertJsonStructure([
            'id',
            'email',
            'username',
            'name',
            'avatar',
            'is_admin',
            'created_at',
            'updated_at',
            'posts' => [
                '*' => [
                    'id',
                    'title',
                    'slug',
                    'content',
                    'image',
                    'user_id',
                    'created_at',
                    'updated_at',
                    'category_id',
                ],
            ],
        ]);
    }
}
