<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Models\Comment;
use App\Services\Badge;
use App\Repositories\CommentAchievement;
use App\Repositories\LessonWatchedAchievement;
use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;

class Achievement
{
    public static $comment;
    public static ?object $user = null;
    public static array $unlocked_achievements = [];
    public static string $current_badge = Badge::BEGINNER;
    public static string $next_badge = Badge::INTERMEDIATE;
    public static int $remaining_to_unlock_next_badge = 4;
    public static string $next_available_comment_achievement = 'You are yet to unlock any achievement';
    public static string $next_available_watched_achievement = '';

    public static function setUnlockedCommentAchievements($payload): void
    {
        self::$comment = $payload;
        self::$user = User::find(self::$comment->user_id);
        self::unlockCommentAchievements();
    }

    public static function setUnlockedAchievements($payload): void
    {
        array_push(self::$unlocked_achievements, $payload->achievement_name);
        self::unlockBadges($payload);
    }

    public static function setUnlockedBadges($payload): void
    {
        if(self::$user->id === $payload->user->id){
            self::setBadge($payload);
        }
       
    }

    public static function unlockCommentAchievements(): void
    {
        if(self::$user->id == self::$comment->user_id){
            self::commentAchievementUnlocker();
        }
    }

    public static function unlockBadges($payload): void
    {
        if($payload->user->id === self::$user->id){
            self::badgeUnlocker();
        }
    }

    public static function getUnlockedAchievements(): array | string
    {
        return count(self::$unlocked_achievements) > 0 ? self::$unlocked_achievements : 'No unlocked achievements yet';
    }

    public static function getNextAvailableAchievements(): array | string
    {
        if(self::$next_available_comment_achievement == 'You are yet to unlock any achievement' ){
            return self::$next_available_comment_achievement;
        }elseif(self::$next_available_comment_achievement == 'You have unlocked all comment achievements')
        {
            return self::$next_available_comment_achievement;
        }else{
            return [self::$next_available_comment_achievement];
        }
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
        return self::$remaining_to_unlock_next_badge;
    }

    public static function fireAchievementUnlockedEvent($user, $achievement_name): void
    {
        event(new AchievementUnlocked($user, $achievement_name));
    }

    public static function fireBadgeUnlockedEvent($user, $badge_name): void
    {
        event(new BadgeUnlocked($user, $badge_name));
    }

    public static function setUser(int $user_id): void
    {
        self::$user = self::$user ?? User::find($user_id);
    }

    public static function commentAchievementUnlocker(): void
    {
        if(self::$user->comments->count() == 1){
            self::fireAchievementUnlockedEvent(self::$user, Comment::FIRST_COMMENT_ACHIEVEMENT);
            self::$next_available_comment_achievement = Comment::THREE_COMMENTS_ACHIEVEMENT;
        }

        if(self::$user->comments->count() == 3){
            self::fireAchievementUnlockedEvent(self::$user, Comment::THREE_COMMENTS_ACHIEVEMENT);
            self::$next_available_comment_achievement = Comment::FIVE_COMMENTS_ACHIEVEMENT;
        }

        if(self::$user->comments->count() == 5){
            self::fireAchievementUnlockedEvent(self::$user, Comment::FIVE_COMMENTS_ACHIEVEMENT);
            self::$next_available_comment_achievement = Comment::TEN_COMMENTS_ACHIEVEMENT;
        }

        if(self::$user->comments->count() == 10){
            self::fireAchievementUnlockedEvent(self::$user, Comment::TEN_COMMENTS_ACHIEVEMENT);
            self::$next_available_comment_achievement = Comment::TWENTY_COMMENTS_ACHIEVEMENT;
        }

        if(self::$user->comments->count() == 20){
            self::fireAchievementUnlockedEvent(self::$user, Comment::TWENTY_COMMENTS_ACHIEVEMENT);
            self::$next_available_comment_achievement = 'You have unlocked all comment achievements';
        }
    }

    public static function badgeUnlocker(): void
    {
        $achievements = count(self::$unlocked_achievements);
        if($achievements < 4){
            self::$remaining_to_unlock_next_badge = 4 - $achievements;
        }

        if($achievements >= 4){
            self::fireBadgeUnlockedEvent(self::$user, Badge::INTERMEDIATE);
            self::$remaining_to_unlock_next_badge = 8 - $achievements;
        }

        if($achievements >= 8){
            self::fireBadgeUnlockedEvent(self::$user, Badge::ADVANCED);
            self::$remaining_to_unlock_next_badge = 10 - $achievements;
        }

        if($achievements >= 10){
            self::fireBadgeUnlockedEvent(self::$user, Badge::MASTER);
            self::$remaing_to_unlock_next_badge = 0;
        }
    }

    public static function setBadge($payload): void
    {
        self::$current_badge = $payload->badge_name;

        if(self::$current_badge == Badge::INTERMEDIATE){
            self::$next_badge = Badge::ADVANCED;
        }

        if(self::$current_badge == Badge::ADVANCED){
            self::$next_badge = Badge::MASTER;
        }
    }
}