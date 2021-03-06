<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserWebinar;
use App\Models\Webinar;
use Illuminate\Database\Seeder;

class UserWebinarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userIds = User::pluck('id')->take(5)->toArray();
        $webinarIds = Webinar::pluck('id')->take(5)->toArray();

        foreach ($userIds as $userId) {
            foreach ($webinarIds as $webinarId) {
                UserWebinar::factory()->create([
                    'user_id' => $userId,
                    'webinar_id' => $webinarId,
                ]);
            }
        }
    }
}
