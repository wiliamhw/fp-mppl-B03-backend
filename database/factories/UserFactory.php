<?php

namespace Database\Factories;

use App\Models\User;
use Hash;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'email' => $this->faker->safeEmail,
            'password' => Hash::make('password'), // password
            'name' => $this->faker->text(rand(5, 32)),
            'phone_number' => $this->faker->e164PhoneNumber,
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (User $user) {
            $user->addMediaFromUrl($this->faker->imageUrl())
                ->toMediaCollection(User::IMAGE_COLLECTION);
        });
    }
}
