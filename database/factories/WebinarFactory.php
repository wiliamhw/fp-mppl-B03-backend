<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Webinar;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Carbon\Carbon;

class WebinarFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Webinar::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'category_id' => Category::factory(),
            'title' => $this->faker->text(rand(10, 32)),
            'description' => $this->faker->randomHtml,
            'start_at' => Carbon::now()->subDays(rand(5, 60))->addHours(rand(1, 12))->addMinutes(rand(1, 30))->addSeconds(rand(1, 30)),
            'end_at' => $this->faker->randomElement([
                Carbon::now()->addDays(rand(1, 60)),
                Carbon::now()->subDays()
            ]),
            'price' => $this->faker->numberBetween(0, 99999),
            'type' => $this->faker->randomElement(Webinar::TYPE),
            'zoom_id' => $this->faker->text(rand(6, 11)),
            'max_participants' => $this->faker->numberBetween(0, 99999),
            'published_at' => $this->faker->boolean(90) ?
                Carbon::now()
                    ->subDays(rand(1, 60))->addHours(rand(1, 12))
                    ->addMinutes(rand(1, 30))->addSeconds(rand(1, 30))
                : null
        ];
    }
}
