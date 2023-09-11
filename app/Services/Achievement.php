<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Models\Comment;
use App\Services\BadgeService;
use App\Repositories\CommentAchievement;
use App\Repositories\LessonWatchedAchievement;
use App\Events\AchievementUnlocked;

class Achievement
{
    public static $comment;
    public static ?object $user = null;
    public static array $unlocked_achievements = [];
    public static array $next_available_achievements = [];
    public static string $current_badge = 'Beginner';
    public static string $next_badge = '';
    public static int $remaing_to_unlock_next_badge = 0;

    public static function setUnlockedCommentAchievements($payload): void
    {
        self::$comment = $payload;
        self::$user = User::find(self::$comment->user_id);
        self::unlockCommentAchievements();
    }

    public static function setUnlockedAchievements($payload): void
    {
        self::$unlocked_achievements[] = $payload->achievement_name;
        self::unlockBadges($payload);
    }

    public static function setUnlockedBadges($payload): void
    {
        self::$current_badge = $payload->badge_name;
    }

    public static function setUser(int $user_id): void
    {
        self::$user = self::$user ?? User::find($user_id);
    }

    public static function unlockCommentAchievements()
    {
        if(self::$user->id == self::$comment->user_id){
            if(self::$user->comments->count() == 1) {
                self::fireAchievementUnlockedEvent(self::$user, Comment::FIRST_COMMENT_ACHIEVEMENT);
                self::$next_available_achievements[] = Comment::THREE_COMMENTS_ACHIEVEMENT;
            }
        }
    }

    public static function unlockBadges($payload)
    {
        $badge = new BadgeService();
        if($payload->user->id === self::$user->id)
        {
            $achievements = count(self::$unlocked_achievements);
            if($achievements == 4){
                self::fireBadgeUnlockedEvent(self::$user, $badge->intermediate());
            }

            if($achievements == 8){
                self::fireBadgeUnlockedEvent(self::$user, $badge->advanced());
            }

            if($achievements == 10){
                self::fireBadgeUnlockedEvent(self::$user, $badge->master());
            }
        }
    }

    public static function getUnlockedAchievements(): array
    {
        return self::$unlockedAchievements;
    }

    public static function getNextAvailableAchievements(): array
    {
        return self::$next_available_achievements;
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

    public static function fireAchievementUnlockedEvent($user, $achievement_name): void
    {
        event(new AchievementUnlocked($user, $achievement_name));
    }

    public static function fireBadgeUnlockedEvent($user, $badge_name): void
    {
        event(new AchievementUnlocked($user, $badge_name));
    }
}