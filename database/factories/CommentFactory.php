<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'content'       => $this->faker->paragraph(),
            'user_id'       => User::pluck('id')->random(),
            'post_id'       => Post::pluck('id')->random(),
            // 'parent_id'     => Comment::pluck('id')->random(),
        ];
    }
}
