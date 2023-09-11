<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\AchievementUnlocked as AchievementUnlockedEvent;
use App\Services\Achievement as AchievementService;

class AchievementUnlocked
{

    /**
     * Handle the event.
     */
    public function handle(AchievementUnlockedEvent $event): void
    {
        AchievementService::setUnlockedAchievements($event);
    }
}
