<?php

namespace App\Observers;

use App\Models\UserWebinar;

class UserWebinarObserver
{
    /**
     * Handle the UserWebinar "created" event.
     *
     * @param  \App\Models\UserWebinar  $userWebinar
     * @return void
     */
    public function created(UserWebinar $userWebinar)
    {
        $userWebinar->webinar?->increment('participants');
    }

    /**
     * Handle the UserWebinar "deleted" event.
     *
     * @param  \App\Models\UserWebinar  $userWebinar
     * @return void
     */
    public function deleted(UserWebinar $userWebinar)
    {
        $userWebinar->webinar?->decrement('participants');
    }
}
