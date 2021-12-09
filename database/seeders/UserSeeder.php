<?php

namespace Database\Seeders;

use App\Models\User;
use Faker\Generator;
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
        $faker = new Generator();

        foreach ($users as $user) {
            $user->addMediaFromUrl($faker->imageUrl())
                ->toMediaCollection(User::IMAGE_COLLECTION);
        }
    }
}
