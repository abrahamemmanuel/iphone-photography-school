<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Models\Comment;
use App\Services\Badge;
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
        self::unlockBadges($payload);
    }

    public static function setUnlockedBadges($payload): void
    {
        if(self::$user->id === $payload->user->id){
            self::$current_badge = $payload->badge_name;
            if(self::$current_badge == Badge::BEGINNER){
                self::$next_badge = Badge::INTERMEDIATE;
                self::$remaing_to_unlock_next_badge = 4 - count(self::$unlocked_achievements);
            }

            if(self::$current_badge == Badge::INTERMEDIATE){
                self::$next_badge = Badge::ADVANCED;
                self::$remaing_to_unlock_next_badge = 8 - count(self::$unlocked_achievements);
            }

            if(self::$current_badge == Badge::ADVANCED){
                self::$next_badge = Badge::MASTER;
                self::$remaing_to_unlock_next_badge = 10 - count(self::$unlocked_achievements);
            }
        }
       
    }

    public static function unlockCommentAchievements(): void
    {
        if(self::$user->id == self::$comment->user_id){
            if(self::$user->comments->count() == 1) {
                self::fireAchievementUnlockedEvent(self::$user, Comment::FIRST_COMMENT_ACHIEVEMENT);
                array_push(self::$unlocked_achievements, Comment::FIRST_COMMENT_ACHIEVEMENT);
                array_push(self::$next_available_achievements, Comment::THREE_COMMENTS_ACHIEVEMENT);
            }

            if(self::$user->comments->count() == 3) {
                self::fireAchievementUnlockedEvent(self::$user, Comment::THREE_COMMENTS_ACHIEVEMENT);
                array_push(self::$unlocked_achievements, Comment::THREE_COMMENTS_ACHIEVEMENT);
                array_push(self::$next_available_achievements, Comment::FIVE_COMMENTS_ACHIEVEMENT);
            }

            if(self::$user->comments->count() == 5) {
                self::fireAchievementUnlockedEvent(self::$user, Comment::FIVE_COMMENTS_ACHIEVEMENT);
                array_push(self::$unlocked_achievements, Comment::FIVE_COMMENTS_ACHIEVEMENT);
                array_push(self::$next_available_achievements, Comment::TEN_COMMENTS_ACHIEVEMENT);
            }

            if(self::$user->comments->count() == 10) {
                self::fireAchievementUnlockedEvent(self::$user, Comment::TEN_COMMENTS_ACHIEVEMENT);
                array_push(self::$unlocked_achievements, Comment::TEN_COMMENTS_ACHIEVEMENT);
                array_push(self::$next_available_achievements, Comment::TWENTY_COMMENTS_ACHIEVEMENT);
            }
        }
    }

    public static function unlockBadges($payload): void
    {
        if($payload->user->id === self::$user->id)
        {
            $achievements = count(self::$unlocked_achievements);
            if($achievements == 4){
                self::fireBadgeUnlockedEvent(self::$user, Badge::INTERMEDIATE);
            }

            if($achievements == 8){
                self::fireBadgeUnlockedEvent(self::$user, Badge::ADVANCED);
            }

            if($achievements == 10){
                self::fireBadgeUnlockedEvent(self::$user, Badge::MASTER);
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
        return self::$current_badge;
    }

    public static function getNextBadge(): string
    {
        return self::$next_badge;
    }

    public static function getRemainingToUnlockNextBadge(): int
    {
        return self::$remaing_to_unlock_next_badge;
    }

    public static function fireAchievementUnlockedEvent($user, $achievement_name): void
    {
        event(new AchievementUnlocked($user, $achievement_name));
    }

    public static function fireBadgeUnlockedEvent($user, $badge_name): void
    {
        event(new AchievementUnlocked($user, $badge_name));
    }

    public static function setUser(int $user_id): void
    {
        self::$user = self::$user ?? User::find($user_id);
    }
}