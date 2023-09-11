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
            'unlocked_achievements' => Achievement::$unlocked_achievements,
            'next_available_achievements' => Achievement::$next_available_achievements,
            'current_badge' => Achievement::$current_badge,
            'next_badge' => Achievement::$next_badge,
            'remaing_to_unlock_next_badge' => Achievement::$remaing_to_unlock_next_badge,
        ]);
    }
}
