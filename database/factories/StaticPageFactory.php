<?php

namespace Database\Factories;

use App\Models\StaticPage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class StaticPageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StaticPage::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->text(rand(30, 160));

        return [
            'name'          => $name,
            'slug'          => Str::slug($name),
            'content'       => $this->faker->randomHtml(),
            'youtube_video' => null,
            'layout'        => 'default',
            'published'     => 'Yes',
        ];
    }
}
