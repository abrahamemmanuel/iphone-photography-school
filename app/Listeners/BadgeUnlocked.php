<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\BadgeUnlocked as BadgeUnlockedEvent;
use App\Services\Achievement as AchievementService;

class BadgeUnlocked
{

    /**
     * Handle the event.
     */
    public function handle(BadgeUnlockedEvent $event): void
    {
        AchievementService::setUnlockedBadges($event);
        dump('BadgeUnlocked listener fired');
    }
}
