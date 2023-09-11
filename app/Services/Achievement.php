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

    public static function setComment($payload): void
    {
        self::$comment = $payload;
    }

    public static function getComment(): Comment
    {
        return self::$comment;
    }

    public static function getUser($user_id): void
    {
        return User::find($user_id);
    }

    public static function unLockAchievements($user_id)
    {
        $user = self::getUser($user_id);
        $comment = self::getComment();
        self::unLockCommentAchievements($user, $comment);
        self::$badge = new BadgeService(self::$achievements);
    }

    public static function unLockCommentAchievements($user, $comment)
    {
        //unlock comment achievements
        if($user->id === $comment->user_id) {
            if($user->comments->count() === 1) {
                self::$achievements++;
            }

            if($user->comments->count() === 3) {
                self::$achievements++;
            }

            if($user->comments->count() === 5) {
                self::$achievements++;
            }

            if($user->comments->count() === 10) {
                self::$achievements++;
            }

            if($user->comments->count() === 20) {
                self::$achievements++;
            }
            return self::$achievements;
        }
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