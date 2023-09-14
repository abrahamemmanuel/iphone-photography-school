<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use App\Models\Comment;
use App\Models\Lesson;
use App\Services\Badge;
use App\Repositories\CommentAchievement;
use App\Repositories\LessonWatchedAchievement;
use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use Illuminate\Support\Facades\DB;

class Achievement
{
    public static ?object $comment = null;
    public static ?object $lesson = null;
    public static ?object $user = null;
    public static ?object $lesson_user = null;
    public static array $unlocked_achievements = [];
    public static string $current_badge = Badge::BEGINNER;
    public static string $next_badge = Badge::INTERMEDIATE;
    public static int $remaining_to_unlock_next_badge = 4;
    public static string $next_available_comment_achievement = Comment::FIRST_COMMENT_ACHIEVEMENT;
    public static string $last_comment_achievement = '';
    public static string $last_watched_achievement = '';
    public static string $next_available_watched_achievement = Lesson::FIRST_LESSON_WATCHED_ACHIEVEMENT;

    public static function setUnlockedCommentAchievements($payload): void
    {
        self::$comment = Comment::find($payload->id) ?? $payload;
        self::$user = User::find(self::$comment->user_id);
        self::unlockCommentAchievements();
    }

    public static function setUnlockedLessonAchievements($payload): void
    {
        self::$lesson = Lesson::find($payload->lesson->id) ?? $payload->lesson;
        self::$user = User::find($payload->user->id) ?? $payload->user;
        self::$lesson_user = self::setLessonUser(self::$user->id, self::$lesson->id);
        self::unlockLessonAchievements();
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

    public static function unlockLessonAchievements(): void
    {
        if(self::$user->id == self::$lesson_user->user_id){
            self::lessonAchievementUnlocker();
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

        if(count(self::$unlocked_achievements) == 10){
            return 'You have unlocked all achievements';
        }

        if(self::$last_comment_achievement != '' && self::$last_watched_achievement == ''){
            return [
                self::$next_available_watched_achievement
            ];
        }

        if(self::$last_comment_achievement == '' && self::$last_watched_achievement != ''){
            return [
                self::$next_available_comment_achievement
            ];
        }

        return [
            self::$next_available_comment_achievement,
            self::$next_available_watched_achievement
        ];
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

    public static function setLessonUser(int $user_id, int $lesson_id): object | null
    {
        return DB::table('lesson_user')->where('user_id', self::$user->id)->where('lesson_id', self::$lesson->id)->first();
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
            self::$last_comment_achievement = Comment::TWENTY_COMMENTS_ACHIEVEMENT;
        }
    }

    public static function lessonAchievementUnlocker()
    {
        if(self::$user->watched->count() == 1){
            self::fireAchievementUnlockedEvent(self::$user, Lesson::FIRST_LESSON_WATCHED_ACHIEVEMENT);
            self::$next_available_watched_achievement = Lesson::FIVE_LESSONS_WATCHED_ACHIEVEMENT;
        }

        if(self::$user->watched->count() == 5){
            self::fireAchievementUnlockedEvent(self::$user, Lesson::FIVE_LESSONS_WATCHED_ACHIEVEMENT);
            self::$next_available_watched_achievement = Lesson::TEN_LESSONS_WATCHED_ACHIEVEMENT;
        }

        if(self::$user->watched->count() == 10){
            self::fireAchievementUnlockedEvent(self::$user, Lesson::TEN_LESSONS_WATCHED_ACHIEVEMENT);
            self::$next_available_watched_achievement = Lesson::TWENTY_FIVE_LESSONS_WATCHED_ACHIEVEMENT;
        }

        if(self::$user->watched->count() == 25){
            self::fireAchievementUnlockedEvent(self::$user, Lesson::TWENTY_FIVE_LESSONS_WATCHED_ACHIEVEMENT);
            self::$next_available_watched_achievement = Lesson::FIFTY_LESSONS_WATCHED_ACHIEVEMENT;
        }

        if(self::$user->watched->count() == 50){
            self::fireAchievementUnlockedEvent(self::$user, Lesson::FIFTY_LESSONS_WATCHED_ACHIEVEMENT);
            self::$last_watched_achievement = Lesson::FIFTY_LESSONS_WATCHED_ACHIEVEMENT;
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
            self::$remaining_to_unlock_next_badge = 0;
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