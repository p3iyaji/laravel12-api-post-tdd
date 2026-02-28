<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;


/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence();
        return [
            'id' => $this->faker->unique()->numberBetween(1, 1000),
            'title' => $title,
            'content' => $this->faker->paragraph(),
            'user_id' => User::factory(),
            'status' => $this->faker->randomElement(['published', 'draft']),
            'published_at' => 'status' === 'published' ? $this->faker->dateTimeBetween('-1 month', 'now') : null,
        ];
    }

    public function published()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'published',
                'published_at' => $this->faker->dateTimeBetween('-1 month', 'now'),
            ];
        });
    }

    public function draft()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'draft',
                'published_at' => null,
            ];
        });
    }
}
