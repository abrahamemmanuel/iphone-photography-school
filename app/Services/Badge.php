<?php
declare(strict_types=1);

namespace App\Services;

use App\Interfaces\Badge;

class BadgeService implements Badge
{

    public int $achievements;

    public function __construct($achievements)
    {
        $this->achievements = $achievements;
    }
    
    public function beginner(): string
    {
        if($this->achievements === 0) {
            return "Beginner";
        }
    }

    public function intermediate(): string
    {
        if($this->achievements === 4) {
            return "Intermediate";
        }
    }

    public function advanced(): string
    {
        if($this->achievements === 8) {
            return "Advanced";
        }
    }

    public function master(): string
    {
        if($this->achievements === 10) {
            return "Master";
        }
    }
}