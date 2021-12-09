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
        $userIds = User::pluck('id')->toArray();
        $webinarIds = Webinar::pluck('id')->toArray();

        foreach ($userIds as $userId) {
            foreach ($webinarIds as $webinarId) {
                UserWebinar::factory(5)->create([
                    'user_id' => $userId,
                    'webinar_id' => $webinarId,
                ]);
            }
        }
    }
}
