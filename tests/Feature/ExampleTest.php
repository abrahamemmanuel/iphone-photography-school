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
    public function test_that_user_achieves_first_comment_written()
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $this->commentWriter(1, $user);
        $response = $this->get("/users/{$user->id}/achievements");
        $response->assertJsonFragment([
            'unlocked_achievements' => [
                'First Comment Written Achievements'
            ],
            'next_available_achievements' => [
                '3 Comments Written Achievements'
            ],
            'current_badge' => 'Beginner',
            'next_badge' => 'Intermediate',
            'remaing_to_unlock_next_badge' => 3,
        ]);
        $this->assertCount(1, $response['unlocked_achievements']);
        $this->assertCount(1, $response['next_available_achievements']);
        $this->assertIsArray($response['unlocked_achievements']);
        $this->assertIsArray($response['next_available_achievements']);
        $this->assertIsString($response['current_badge']);
        $this->assertIsString($response['next_badge']);
        $this->assertIsInt($response['remaing_to_unlock_next_badge']);
    }

    public function commentWriter($number, $user)
    {
        for($i = 0; $i < $number; $i++) {
            $comment = Comment::factory()->create([
                'user_id' => $user->id,
                'body' => 'This is '.$i.' comment'
            ]);
            event(new \App\Events\CommentWritten($comment));
        }
    }
}
