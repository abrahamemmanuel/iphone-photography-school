<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\Achievement;

class AchievementsController extends Controller
{
    public function index($user)
    {
        Achievement::setUser($user);
        return response()->json([
            'unlocked_achievements' => Achievement::getUnlockedAchievements(),
            'next_available_achievements' => Achievement::getNextAvailableAchievements(),
            'current_badge' => Achievement::getCurrentBadge(),
            'next_badge' => Achievement::getNextBadge(),
            'remaing_to_unlock_next_badge' => Achievement::getRemainingToUnlockNextBadge(),
        ]);
    }
}
