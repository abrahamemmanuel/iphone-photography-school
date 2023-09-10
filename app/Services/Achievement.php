<?php
declare(strict_types=1);

namespace App\Services;

class Achievement extends AchievementAbstract
{
    public function __construct()
    {
        // 
    }
    
    public function getUnlockedAchievements(): array
    {
        return [];
    }

    public function getNextAvailableAchievements(): array
    {
        return [];
    }

    public function getCurrentBadge(): string
    {
        return '';
    }

    public function getNextBadge(): string
    {
        return '';
    }

    public function getRemainingToUnlockNextBadge(): int
    {
        return 0;
    }
}