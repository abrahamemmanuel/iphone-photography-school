<?php
declare(strict_types=1);

namespace App\Services;

use App\Interfaces\Badge;

class BadgeService implements Badge
{
    const BEGINNER = 'Beginner';
    const INTERMEDIATE = 'Intermediate';
    const ADVANCED = 'Advanced';
    const MASTER = 'Master';

    public function beginner(): string
    {
        return self::BEGINNER;
    }

    public function intermediate(): string
    {
        return self::INTERMEDIATE;
    }

    public function advanced(): string
    {
        return self::ADVANCED;
    }

    public function master(): string
    {
        return self::MASTER;
    }
}