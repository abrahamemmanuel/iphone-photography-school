<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\BadgeUnlocked as BadgeUnlockedEvent;

class BadgeUnlocked
{

    /**
     * Handle the event.
     */
    public function handle(BadgeUnlockedEvent $event): void
    {
        //
    }
}
