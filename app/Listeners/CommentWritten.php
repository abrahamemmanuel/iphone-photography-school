<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\CommentWritten as CommentWrittenEvent;
use App\Services\Achievement as CommentAchievement;

class CommentWritten
{
    /**
     * Create the event listener.
     */

    /**
     * Handle the event.
     */
    public function handle(CommentWrittenEvent $event): void
    {
        CommentAchievement::setUnlockedCommentAchievements($event->comment);
    }
}
