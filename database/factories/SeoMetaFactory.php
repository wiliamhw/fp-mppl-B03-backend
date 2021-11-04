<?php

namespace Database\Factories;

use App\Models\SeoMeta;
use Illuminate\Database\Eloquent\Factories\Factory;

class SeoMetaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SeoMeta::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'attachable_type' => null,
            'attachable_id'   => null,
            'locale'          => null,
            'seo_url'         => '/'.$this->faker->slug(),
            'seo_title'       => $this->faker->text(60),
            'seo_description' => $this->faker->text(150),
            'seo_content'     => null,
            'open_graph_type' => $this->faker->randomElement(['website', 'article']),
        ];
    }
}
