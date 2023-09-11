<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Services\Achievement as AchievementService;

class BadgeUnlocked
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $badge_name;
    /**
     * Create a new event instance.
     */
    public function __construct($user, $badge_name)
    {
        $this->user = $user;
        $this->badge_name = $badge_name;
    }
}
