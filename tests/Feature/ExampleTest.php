<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use App\Models\Lesson;
use App\Models\Comment;
use App\Services\Achievement;
use App\Repositories\CommentAchievement;
use App\Repositories\LessonWatchedAchievement;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * test
     * A basic test example.
     */
    public function test_should_assert_that_user_has_no_comment_written_achievements_and_has_4_achievements_to_unlock_intermediate_badge(): void
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $response = $this->get("/users/{$user->id}/achievements");
        $response->assertJsonFragment([
            'unlocked_achievements' => 'No unlocked achievements yet',
            'next_available_achievements' => [
                'First Comment Written',
                'First Lesson Watched'
            ],
            'current_badge' => 'Beginner',
            'next_badge' => 'Intermediate',
            'remaining_to_unlock_next_badge' => 4,
        ]);
        $this->assertCount(2, $response['next_available_achievements']);
        $this->assertIsString($response['unlocked_achievements']);
        $this->assertIsArray($response['next_available_achievements']);
        $this->assertIsString($response['current_badge']);
        $this->assertIsString($response['next_badge']);
        $this->assertIsInt($response['remaining_to_unlock_next_badge']);
        $this->resetAllAchievementValues();
    }

    /**
     * test
     * A basic test example.
     */
    public function test_should_assert_that_user_achieves_first_comment_written_achievement_and_has_3_achievements_to_unlock_intermediate_badge(): void
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $this->commentWriter(1, $user);
        $response = $this->get("/users/{$user->id}/achievements");
        $response->assertJsonFragment([
            'unlocked_achievements' => [
                'First Comment Written'
            ],
            'next_available_achievements' => [
                '3 Comments Written',
                'First Lesson Watched'
            ],
            'current_badge' => 'Beginner',
            'next_badge' => 'Intermediate',
            'remaining_to_unlock_next_badge' => 3,
        ]);
        $this->assertCount(1, $response['unlocked_achievements']);
        $this->assertCount(2, $response['next_available_achievements']);
        $this->assertIsArray($response['unlocked_achievements']);
        $this->assertIsArray($response['next_available_achievements']);
        $this->assertIsString($response['current_badge']);
        $this->assertIsString($response['next_badge']);
        $this->assertIsInt($response['remaining_to_unlock_next_badge']);
        $this->resetAllAchievementValues();
    }

     /**
     * test
     * A basic test example.
     */
    public function test_should_assert_that_user_achieves_three_comment_written_achievement_and_has_2_achievements_to_unlock_intermediate_badge(): void
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $this->commentWriter(3, $user);
        $response = $this->get("/users/{$user->id}/achievements");
        // dd(Achievement::$unlocked_achievements);
        $response->assertJsonFragment([
            'unlocked_achievements' => [
                'First Comment Written',
                '3 Comments Written',
            ],
            'next_available_achievements' => [
                '5 Comments Written',
                'First Lesson Watched'
            ],
            'current_badge' => 'Beginner',
            'next_badge' => 'Intermediate',
            'remaining_to_unlock_next_badge' => 2,
        ]);
        $this->assertCount(2, $response['unlocked_achievements']);
        $this->assertCount(2, $response['next_available_achievements']);
        $this->assertIsArray($response['unlocked_achievements']);
        $this->assertIsArray($response['next_available_achievements']);
        $this->assertIsString($response['current_badge']);
        $this->assertIsString($response['next_badge']);
        $this->assertIsInt($response['remaining_to_unlock_next_badge']);
        $this->resetAllAchievementValues();
    }

    /**
    * test
    * A basic test example.
    */
    public function test_should_assert_that_user_achieves_five_comment_written_achievement_and_has_1_achievement_to_unlock_intermediate_badge(): void
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $this->commentWriter(5, $user);
        $response = $this->get("/users/{$user->id}/achievements");
        $response->assertJsonFragment([
            'unlocked_achievements' => [
                'First Comment Written',
                '3 Comments Written',
                '5 Comments Written',
            ],
            'next_available_achievements' => [
                '10 Comments Written',
                'First Lesson Watched'
            ],
            'current_badge' => 'Beginner',
            'next_badge' => 'Intermediate',
            'remaining_to_unlock_next_badge' => 1,
        ]);
        $this->assertCount(3, $response['unlocked_achievements']);
        $this->assertCount(2, $response['next_available_achievements']);
        $this->assertIsArray($response['unlocked_achievements']);
        $this->assertIsArray($response['next_available_achievements']);
        $this->assertIsString($response['current_badge']);
        $this->assertIsString($response['next_badge']);
        $this->assertIsInt($response['remaining_to_unlock_next_badge']);
        $this->resetAllAchievementValues();
    }

    /**
     * test
     * A basic test example.
     */
    public function test_should_assert_that_user_achieves_ten_comment_written_achievement_and_has_4_achievement_to_unlocked_intermediate_badge(): void
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $this->commentWriter(10, $user);
        $response = $this->get("/users/{$user->id}/achievements");
        $response->assertJsonFragment([
            'unlocked_achievements' => [
                'First Comment Written',
                '3 Comments Written',
                '5 Comments Written',
                '10 Comments Written',
            ],
            'next_available_achievements' => [
                '20 Comments Written',
                'First Lesson Watched'
            ],
            'current_badge' => 'Intermediate',
            'next_badge' => 'Advanced',
            'remaining_to_unlock_next_badge' => 4,
        ]);
        $this->assertCount(4, $response['unlocked_achievements']);
        $this->assertCount(2, $response['next_available_achievements']);
        $this->assertIsArray($response['unlocked_achievements']);
        $this->assertIsArray($response['next_available_achievements']);
        $this->assertIsString($response['current_badge']);
        $this->assertIsString($response['next_badge']);
        $this->assertIsInt($response['remaining_to_unlock_next_badge']);
        $this->resetAllAchievementValues();
    }

    /**
     * test
     * A basic test example.
     */
    public function test_should_assert_that_user_achieves_twenty_comment_written_achievement_and_has_4_achievement_to_unlock_advanced_badge(): void
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $this->commentWriter(20, $user);
        $response = $this->get("/users/{$user->id}/achievements");
        $response->assertJsonFragment([
            'unlocked_achievements' => [
                'First Comment Written',
                '3 Comments Written',
                '5 Comments Written',
                '10 Comments Written',
                '20 Comments Written',
            ],
            'next_available_achievements' => [
                'First Lesson Watched'
            ],
            'current_badge' => 'Intermediate',
            'next_badge' => 'Advanced',
            'remaining_to_unlock_next_badge' => 3,
        ]);
        $this->assertCount(5, $response['unlocked_achievements']);
        $this->assertCount(1, $response['next_available_achievements']);
        $this->assertIsString($response['current_badge']);
        $this->assertIsString($response['next_badge']);
        $this->assertIsInt($response['remaining_to_unlock_next_badge']);
        $this->resetAllAchievementValues();
    }

    /**
     * test
     * A basic test example.
     */
    public function test_should_assert_that_user_has_no_lesson_watched_achievements_and_has_4_achievements_to_unlock_intermediate_badge(): void
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $response = $this->get("/users/{$user->id}/achievements");
        $response->assertJsonFragment([
            'unlocked_achievements' => 'No unlocked achievements yet',
            'next_available_achievements' => [
                'First Comment Written',
                'First Lesson Watched'
            ],
            'current_badge' => 'Beginner',
            'next_badge' => 'Intermediate',
            'remaining_to_unlock_next_badge' => 4,
        ]);
        $this->assertCount(2, $response['next_available_achievements']);
        $this->assertIsString($response['unlocked_achievements']);
        $this->assertIsArray($response['next_available_achievements']);
        $this->assertIsString($response['current_badge']);
        $this->assertIsString($response['next_badge']);
        $this->assertIsInt($response['remaining_to_unlock_next_badge']);
        $this->resetAllAchievementValues();
    }

    /**
     * test
     * A basic test example.
     */
    public function test_should_assert_that_user_has_achieve_first_lesson_watched_achievements_and_has_3_achievements_to_unlock_intermediate_badge(): void
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $this->lessonWatcher(1, $user);
        $response = $this->get("/users/{$user->id}/achievements");
        $response->assertJsonFragment([
            'unlocked_achievements' => [
                'First Lesson Watched'
            ],
            'next_available_achievements' => [
                'First Comment Written',
                '5 Lessons Watched'
            ],
            'current_badge' => 'Beginner',
            'next_badge' => 'Intermediate',
            'remaining_to_unlock_next_badge' => 3,
        ]);
        $this->assertCount(1, $response['unlocked_achievements']);
        $this->assertCount(2, $response['next_available_achievements']);
        $this->assertIsArray($response['unlocked_achievements']);
        $this->assertIsArray($response['next_available_achievements']);
        $this->assertIsString($response['current_badge']);
        $this->assertIsString($response['next_badge']);
        $this->assertIsInt($response['remaining_to_unlock_next_badge']);
        $this->resetAllAchievementValues();
    }

    /**
     * test
     * A basic test example.
     */
    public function test_should_assert_that_user_has_achieve_five_lesson_watched_achievements_and_has_3_achievements_to_unlock_intermediate_badge(): void
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $this->lessonWatcher(5, $user);
        $response = $this->get("/users/{$user->id}/achievements");
        $response->assertJsonFragment([
            'unlocked_achievements' => [
                'First Lesson Watched',
                '5 Lessons Watched',
            ],
            'next_available_achievements' => [
                'First Comment Written',
                '10 Lessons Watched'
            ],
            'current_badge' => 'Beginner',
            'next_badge' => 'Intermediate',
            'remaining_to_unlock_next_badge' => 2,
        ]);
        $this->assertCount(2, $response['unlocked_achievements']);
        $this->assertCount(2, $response['next_available_achievements']);
        $this->assertIsArray($response['unlocked_achievements']);
        $this->assertIsArray($response['next_available_achievements']);
        $this->assertIsString($response['current_badge']);
        $this->assertIsString($response['next_badge']);
        $this->assertIsInt($response['remaining_to_unlock_next_badge']);
        $this->resetAllAchievementValues();
    }

    /**
     * test
     * A basic test example.
     */
    public function test_should_assert_that_user_has_achieve_ten_lesson_watched_achievements_and_has_1_achievements_to_unlock_intermediate_badge(): void
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $this->lessonWatcher(10, $user);
        $response = $this->get("/users/{$user->id}/achievements");
        $response->assertJsonFragment([
            'unlocked_achievements' => [
                'First Lesson Watched',
                '5 Lessons Watched',
                '10 Lessons Watched',
            ],
            'next_available_achievements' => [
                'First Comment Written',
                '25 Lessons Watched'
            ],
            'current_badge' => 'Beginner',
            'next_badge' => 'Intermediate',
            'remaining_to_unlock_next_badge' => 1,
        ]);
        $this->assertCount(3, $response['unlocked_achievements']);
        $this->assertCount(2, $response['next_available_achievements']);
        $this->assertIsArray($response['unlocked_achievements']);
        $this->assertIsArray($response['next_available_achievements']);
        $this->assertIsString($response['current_badge']);
        $this->assertIsString($response['next_badge']);
        $this->assertIsInt($response['remaining_to_unlock_next_badge']);
        $this->resetAllAchievementValues();
    }

    /**
     * test
     * A basic test example.
     */
    public function test_should_assert_that_user_has_achieve_twenty_five_lesson_watched_achievements_and_has_4_achievements_to_unlock_advanced_badge(): void
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $this->lessonWatcher(25, $user);
        $response = $this->get("/users/{$user->id}/achievements");
        $response->assertJsonFragment([
            'unlocked_achievements' => [
                'First Lesson Watched',
                '5 Lessons Watched',
                '10 Lessons Watched',
                '25 Lessons Watched',
            ],
            'next_available_achievements' => [
                'First Comment Written',
                '50 Lessons Watched'
            ],
            'current_badge' => 'Intermediate',
            'next_badge' => 'Advanced',
            'remaining_to_unlock_next_badge' => 4,
        ]);
        $this->assertCount(4, $response['unlocked_achievements']);
        $this->assertCount(2, $response['next_available_achievements']);
        $this->assertIsArray($response['unlocked_achievements']);
        $this->assertIsArray($response['next_available_achievements']);
        $this->assertIsString($response['current_badge']);
        $this->assertIsString($response['next_badge']);
        $this->assertIsInt($response['remaining_to_unlock_next_badge']);
        $this->resetAllAchievementValues();
    }

    /**
     * test
     * A basic test example.
     */
    public function test_should_assert_that_user_has_achieve_fifty_lesson_watched_achievements_and_has_3_achievements_to_unlock_advanced_badge(): void
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $this->lessonWatcher(50, $user);
        $response = $this->get("/users/{$user->id}/achievements");
        $response->assertJsonFragment([
            'unlocked_achievements' => [
                'First Lesson Watched',
                '5 Lessons Watched',
                '10 Lessons Watched',
                '25 Lessons Watched',
                '50 Lessons Watched',
            ],
            'next_available_achievements' => [
                'First Comment Written'
            ],
            'current_badge' => 'Intermediate',
            'next_badge' => 'Advanced',
            'remaining_to_unlock_next_badge' => 3,
        ]);
        $this->assertCount(5, $response['unlocked_achievements']);
        $this->assertCount(1, $response['next_available_achievements']);
        $this->assertIsArray($response['unlocked_achievements']);
        $this->assertIsArray($response['next_available_achievements']);
        $this->assertIsString($response['current_badge']);
        $this->assertIsString($response['next_badge']);
        $this->assertIsInt($response['remaining_to_unlock_next_badge']);
        $this->resetAllAchievementValues();
    }

    /**
     * test
     * A basic test example.
     */
    public function test_should_assert_that_user_has_achieve_first_lesson_and_first_comment_achievements_and_has_2_achievements_to_unlock_intermediate_badge(): void
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $this->lessonWatcher(1, $user);
        $this->commentWriter(1, $user);
        $response = $this->get("/users/{$user->id}/achievements");
        $response->assertJsonFragment([
            'unlocked_achievements' => [
                'First Comment Written',
                'First Lesson Watched'
            ],
            'next_available_achievements' => [
                '3 Comments Written',
                '5 Lessons Watched'
            ],
            'current_badge' => 'Beginner',
            'next_badge' => 'Intermediate',
            'remaining_to_unlock_next_badge' => 2,
        ]);
        $this->assertCount(2, $response['unlocked_achievements']);
        $this->assertCount(2, $response['next_available_achievements']);
        $this->assertIsArray($response['unlocked_achievements']);
        $this->assertIsArray($response['next_available_achievements']);
        $this->assertIsString($response['current_badge']);
        $this->assertIsString($response['next_badge']);
        $this->assertIsInt($response['remaining_to_unlock_next_badge']);
        $this->resetAllAchievementValues();
    }

    /**
     * test
     * A basic test example.
     */
    public function test_should_assert_that_user_has_achieve_first_lesson_and_three_comment_achievements_and_has_1_achievements_to_unlock_intermediate_badge(): void
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $this->lessonWatcher(1, $user);
        $this->commentWriter(3, $user);
        $response = $this->get("/users/{$user->id}/achievements");
        $response->assertJsonFragment([
            'unlocked_achievements' => [
                'First Comment Written',
                'First Lesson Watched',
                '3 Comments Written',
            ],
            'next_available_achievements' => [
                '5 Comments Written',
                '5 Lessons Watched'
            ],
            'current_badge' => 'Beginner',
            'next_badge' => 'Intermediate',
            'remaining_to_unlock_next_badge' => 1,
        ]);
        $this->assertCount(3, $response['unlocked_achievements']);
        $this->assertCount(2, $response['next_available_achievements']);
        $this->assertIsArray($response['unlocked_achievements']);
        $this->assertIsArray($response['next_available_achievements']);
        $this->assertIsString($response['current_badge']);
        $this->assertIsString($response['next_badge']);
        $this->assertIsInt($response['remaining_to_unlock_next_badge']);
        $this->resetAllAchievementValues();
    }

    /**
     * test
     * A basic test example.
     */
    public function test_should_assert_that_user_has_achieve_first_lesson_and_five_comment_achievements_and_has_4_achievements_to_unlock_advanced_badge(): void
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $this->lessonWatcher(1, $user);
        $this->commentWriter(5, $user);
        $response = $this->get("/users/{$user->id}/achievements");
        $response->assertJsonFragment([
            'unlocked_achievements' => [
                'First Comment Written',
                'First Lesson Watched',
                '3 Comments Written',
                '5 Comments Written',
            ],
            'next_available_achievements' => [
                '10 Comments Written',
                '5 Lessons Watched'
            ],
            'current_badge' => 'Intermediate',
            'next_badge' => 'Advanced',
            'remaining_to_unlock_next_badge' => 4,
        ]);
        $this->assertCount(4, $response['unlocked_achievements']);
        $this->assertCount(2, $response['next_available_achievements']);
        $this->assertIsArray($response['unlocked_achievements']);
        $this->assertIsArray($response['next_available_achievements']);
        $this->assertIsString($response['current_badge']);
        $this->assertIsString($response['next_badge']);
        $this->assertIsInt($response['remaining_to_unlock_next_badge']);
        $this->resetAllAchievementValues();
    }

        /**
     * test
     * A basic test example.
     */
    public function test_should_assert_that_user_has_achieve_first_lesson_and_ten_comment_achievements_and_has_3_achievements_to_unlock_advanced_badge(): void
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $this->lessonWatcher(1, $user);
        $this->commentWriter(10, $user);
        $response = $this->get("/users/{$user->id}/achievements");
        $response->assertJsonFragment([
            'unlocked_achievements' => [
                'First Comment Written',
                'First Lesson Watched',
                '3 Comments Written',
                '5 Comments Written',
                '10 Comments Written',
            ],
            'next_available_achievements' => [
                '20 Comments Written',
                '5 Lessons Watched'
            ],
            'current_badge' => 'Intermediate',
            'next_badge' => 'Advanced',
            'remaining_to_unlock_next_badge' => 3,
        ]);
        $this->assertCount(5, $response['unlocked_achievements']);
        $this->assertCount(2, $response['next_available_achievements']);
        $this->assertIsArray($response['unlocked_achievements']);
        $this->assertIsArray($response['next_available_achievements']);
        $this->assertIsString($response['current_badge']);
        $this->assertIsString($response['next_badge']);
        $this->assertIsInt($response['remaining_to_unlock_next_badge']);
        $this->resetAllAchievementValues();
    }

            /**
     * test
     * A basic test example.
     */
    public function test_should_assert_that_user_has_achieve_first_lesson_and_twenty_comment_achievements_and_has_2_achievements_to_unlock_advanced_badge(): void
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $this->lessonWatcher(1, $user);
        $this->commentWriter(20, $user);
        $response = $this->get("/users/{$user->id}/achievements");
        $response->assertJsonFragment([
            'unlocked_achievements' => [
                'First Comment Written',
                'First Lesson Watched',
                '3 Comments Written',
                '5 Comments Written',
                '10 Comments Written',
                '20 Comments Written',
            ],
            'next_available_achievements' => [
                '5 Lessons Watched'
            ],
            'current_badge' => 'Intermediate',
            'next_badge' => 'Advanced',
            'remaining_to_unlock_next_badge' => 2,
        ]);
        $this->assertCount(6, $response['unlocked_achievements']);
        $this->assertCount(1, $response['next_available_achievements']);
        $this->assertIsArray($response['unlocked_achievements']);
        $this->assertIsArray($response['next_available_achievements']);
        $this->assertIsString($response['current_badge']);
        $this->assertIsString($response['next_badge']);
        $this->assertIsInt($response['remaining_to_unlock_next_badge']);
        $this->resetAllAchievementValues();
    }

    /**
     * test
     * A basic test example.
     */
    public function test_should_assert_that_user_has_achieve_five_lesson_and_twenty_comment_achievements_and_has_1_achievements_to_unlock_advanced_badge(): void
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $this->lessonWatcher(5, $user);
        $this->commentWriter(20, $user);
        $response = $this->get("/users/{$user->id}/achievements");
        $response->assertJsonFragment([
            'unlocked_achievements' => [
                'First Comment Written',
                'First Lesson Watched',
                '3 Comments Written',
                '5 Comments Written',
                '10 Comments Written',
                '20 Comments Written',
                '5 Lessons Watched',
            ],
            'next_available_achievements' => [
                '10 Lessons Watched'
            ],
            'current_badge' => 'Intermediate',
            'next_badge' => 'Advanced',
            'remaining_to_unlock_next_badge' => 1,
        ]);
        $this->assertCount(7, $response['unlocked_achievements']);
        $this->assertCount(1, $response['next_available_achievements']);
        $this->assertIsArray($response['unlocked_achievements']);
        $this->assertIsArray($response['next_available_achievements']);
        $this->assertIsString($response['current_badge']);
        $this->assertIsString($response['next_badge']);
        $this->assertIsInt($response['remaining_to_unlock_next_badge']);
        $this->resetAllAchievementValues();
    }

    /**
     * test
     * A basic test example.
     */
    public function test_should_assert_that_user_has_achieve_ten_lesson_and_twenty_comment_achievements_and_has_2_achievements_to_unlock_master_badge(): void
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $this->lessonWatcher(10, $user);
        $this->commentWriter(20, $user);
        $response = $this->get("/users/{$user->id}/achievements");
        $response->assertJsonFragment([
            'unlocked_achievements' => [
                'First Comment Written',
                'First Lesson Watched',
                '3 Comments Written',
                '5 Comments Written',
                '10 Comments Written',
                '20 Comments Written',
                '5 Lessons Watched',
                '10 Lessons Watched',
            ],
            'next_available_achievements' => [
                '25 Lessons Watched'
            ],
            'current_badge' => 'Advanced',
            'next_badge' => 'Master',
            'remaining_to_unlock_next_badge' => 2,
        ]);
        $this->assertCount(8, $response['unlocked_achievements']);
        $this->assertCount(1, $response['next_available_achievements']);
        $this->assertIsArray($response['unlocked_achievements']);
        $this->assertIsArray($response['next_available_achievements']);
        $this->assertIsString($response['current_badge']);
        $this->assertIsString($response['next_badge']);
        $this->assertIsInt($response['remaining_to_unlock_next_badge']);
        $this->resetAllAchievementValues();
    }

    /**
     * test
     * A basic test example.
     */
    public function test_should_assert_that_user_has_achieve_twenty_five_lesson_and_twenty_comment_achievements_and_has_1_achievements_to_unlock_master_badge(): void
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $this->lessonWatcher(25, $user);
        $this->commentWriter(20, $user);
        $response = $this->get("/users/{$user->id}/achievements");
        $response->assertJsonFragment([
            'unlocked_achievements' => [
                'First Comment Written',
                'First Lesson Watched',
                '3 Comments Written',
                '5 Comments Written',
                '10 Comments Written',
                '20 Comments Written',
                '5 Lessons Watched',
                '10 Lessons Watched',
                '25 Lessons Watched',
            ],
            'next_available_achievements' => [
                '50 Lessons Watched'
            ],
            'current_badge' => 'Advanced',
            'next_badge' => 'Master',
            'remaining_to_unlock_next_badge' => 1,
        ]);
        $this->assertCount(9, $response['unlocked_achievements']);
        $this->assertCount(1, $response['next_available_achievements']);
        $this->assertIsArray($response['unlocked_achievements']);
        $this->assertIsArray($response['next_available_achievements']);
        $this->assertIsString($response['current_badge']);
        $this->assertIsString($response['next_badge']);
        $this->assertIsInt($response['remaining_to_unlock_next_badge']);
        $this->resetAllAchievementValues();
    }

    /**
     * test
     * A basic test example.
     */
    public function test_should_assert_that_user_has_achieve_fifty_lesson_and_twenty_comment_achievements_and_has_master_badge(): void
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $this->lessonWatcher(50, $user);
        $this->commentWriter(20, $user);
        $response = $this->get("/users/{$user->id}/achievements");
        $response->assertJsonFragment([
            'unlocked_achievements' => [
                'First Comment Written',
                'First Lesson Watched',
                '3 Comments Written',
                '5 Comments Written',
                '10 Comments Written',
                '20 Comments Written',
                '5 Lessons Watched',
                '10 Lessons Watched',
                '25 Lessons Watched',
                '50 Lessons Watched',
            ],
            'next_available_achievements' => 'You have unlocked all achievements',
            'current_badge' => 'Master',
            'next_badge' => 'You have unlocked all achievements, no next badge available',
            'remaining_to_unlock_next_badge' => 0,
        ]);
        $this->assertCount(10, $response['unlocked_achievements']);
        $this->assertIsArray($response['unlocked_achievements']);
        $this->assertIsString($response['next_available_achievements']);
        $this->assertIsString($response['current_badge']);
        $this->assertIsString($response['next_badge']);
        $this->assertIsInt($response['remaining_to_unlock_next_badge']);
        $this->resetAllAchievementValues();
    }

    public function commentWriter(int $number, User $user): void
    {
        for($i = 0; $i < $number; $i++) {
            $comment = Comment::factory()->create([
                'user_id' => $user->id,
                'body' => 'This is '.$i.' comment'
            ]);
            event(new \App\Events\CommentWritten($comment));
        }
    }

    public function lessonWatcher(int $number, User $user): void
    {
        $lessons = Lesson::factory()->count($number)->create();
        foreach($lessons as $lesson) {
            DB::table('lesson_user')->insert([
                'user_id' => $user->id,
                'lesson_id' => $lesson->id,
                'watched' => 1,
            ]);
            event(new \App\Events\LessonWatched($lesson, $user));
        }
    }

    public function resetAllAchievementValues(): void
    {
        Achievement::$comment = null;
        Achievement::$user = null;
        Achievement::$unlocked_achievements = [];
        Achievement::$current_badge = 'Beginner';
        Achievement::$next_badge = 'Intermediate';
        Achievement::$remaining_to_unlock_next_badge = 4;
        Achievement::$next_available_comment_achievement = Comment::FIRST_COMMENT_ACHIEVEMENT;
        Achievement::$next_available_watched_achievement = Lesson::FIRST_LESSON_WATCHED_ACHIEVEMENT;
        Achievement::$last_comment_achievement = '';
        Achievement::$last_watched_achievement = '';
    }
}
