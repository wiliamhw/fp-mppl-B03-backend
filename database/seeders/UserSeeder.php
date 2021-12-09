<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::factory(5)->create();
        $faker = Factory::create();

        foreach ($users as $user) {
            $user->addMediaFromUrl($faker->imageUrl())
                ->toMediaCollection(User::IMAGE_COLLECTION);
        }
    }
}
