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
        $userWebinar->webinar->increment('participants');
    }

    /**
     * Handle the UserWebinar "updated" event.
     *
     * @param  \App\Models\UserWebinar  $userWebinar
     * @return void
     */
    public function updated(UserWebinar $userWebinar)
    {
        //
    }

    /**
     * Handle the UserWebinar "deleted" event.
     *
     * @param  \App\Models\UserWebinar  $userWebinar
     * @return void
     */
    public function deleted(UserWebinar $userWebinar)
    {
        $userWebinar->webinar->decrement('participants');
    }

    /**
     * Handle the UserWebinar "restored" event.
     *
     * @param  \App\Models\UserWebinar  $userWebinar
     * @return void
     */
    public function restored(UserWebinar $userWebinar)
    {
        //
    }

    /**
     * Handle the UserWebinar "force deleted" event.
     *
     * @param  \App\Models\UserWebinar  $userWebinar
     * @return void
     */
    public function forceDeleted(UserWebinar $userWebinar)
    {
        //
    }
}
