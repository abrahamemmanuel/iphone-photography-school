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
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $comment = Comment::factory()->create([
            'user_id' => $user->id,
            'body' => 'This is first comment'
        ]);
        event(new \App\Events\CommentWritten($comment));
        $response = $this->get("/users/{$user->id}/achievements");
        
        $response->assertStatus(200);
    }
}
