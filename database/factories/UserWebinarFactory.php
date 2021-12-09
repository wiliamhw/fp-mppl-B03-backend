<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\UserWebinar;
use App\Models\Webinar;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserWebinarFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserWebinar::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'webinar_id' => Webinar::factory(),
            'payment_status' => $this->faker->randomElement(UserWebinar::PAYMENT_STATUS),
            'payment_method' => $this->faker->text(rand(8, 12)),
            'feedback' => $this->faker->paragraph(5),
            'payment_token' => $this->faker->text(10),

        ];
    }
}
