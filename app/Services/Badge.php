<?php
declare(strict_types=1);

namespace App\Services;

use App\Interfaces\Badge;

class BadgeService implements Badge
{
    public $badge int

    public function __construct()
    {
        // 
    }
    
    public function beginner(): int
    {
        return $badge;
    }

    public function intermediate(): int
    {
        return $badge;
    }

    public function advanced(): int
    {
        return $badge;
    }

    public function master(): int
    {
        return $badge;
    }
}