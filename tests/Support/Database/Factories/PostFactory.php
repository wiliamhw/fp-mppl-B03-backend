<?php

namespace Tests\Support\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Tests\Support\Database\Models\Post;

class PostFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Post::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title'     => $this->faker->text(rand(128, 250)),
            'content'   => $this->faker->paragraph(rand(3, 6)),
            'published' => $this->faker->boolean(70),
        ];
    }
}
