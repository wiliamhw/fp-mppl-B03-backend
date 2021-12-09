<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Webinar;
use Faker\Generator;
use Illuminate\Database\Seeder;

class WebinarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categoryIds = Category::pluck('id')->take(8)->toArray();
        $faker = new Generator();

        foreach ($categoryIds as $categoryId) {
            $webinars = Webinar::factory(2)->create([
                'category_id' => $categoryId,
            ]);
            foreach ($webinars as $webinar) {
                $webinar->addMediaFromUrl($faker->imageUrl())
                    ->toMediaCollection(Webinar::IMAGE_COLLECTION);
            }
        }
    }
}
