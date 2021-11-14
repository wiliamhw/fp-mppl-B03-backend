<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Webinar;
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

        foreach ($categoryIds as $categoryId) {
            Webinar::factory(2)->create([
                'category_id' => $categoryId,
            ]);
        }
    }
}
