<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\LessonWatched as LessionWatchedEvent;
use App\Services\Achievement as LessionAchievement;

class LessonWatched
{

    /**
     * Handle the event.
     */
    public function handle(LessionWatchedEvent $event): void
    {
        LessionAchievement::setUnlockedLessonAchievements($event);
    }
}
