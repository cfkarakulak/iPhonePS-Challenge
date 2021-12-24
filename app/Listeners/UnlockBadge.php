<?php

namespace App\Listeners;

use App\Events\BadgeUnlocked;

class UnlockBadge
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\BadgeUnlocked  $event
     * @return void
     */
    public function handle(BadgeUnlocked $event)
    {
        return info('A new badge is unlocked: ' . $event->badge_name);
    }
}
