<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Models\Comment;
use App\Services\BadgeService;
use App\Repositories\CommentAchievement;
use App\Repositories\LessonWatchedAchievement;

class Achievement
{
    public static $comment;
    public static int $achievements = 0;
    public static string $badge;

    public static function setCommentAndIncrementAchievements($payload): void
    {
        self::$comment = $payload;
        self::$achievements++;
    }

    public static function getComment(): Comment
    {
        return self::$comment;
    }

    public static function getUser($user_id): User
    {
        return User::find($user_id);
    }

    public static function unLockAchievements($user_id): void
    {
      
    }

    public static function unLockCommentAchievements($user, $comment): int
    {
       
    }

    public static function getUnlockedAchievements(): array
    {
        return [];
    }

    public static function getNextAvailableAchievements(): array
    {
        return [];
    }

    public static function getCurrentBadge(): string
    {
        return '';
    }

    public static function getNextBadge(): string
    {
        return '';
    }

    public static function getRemainingToUnlockNextBadge(): int
    {
        return 0;
    }
}