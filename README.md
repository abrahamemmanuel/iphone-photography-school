# iphone-photography-school

### Installation

-   clone the code repo https://github.com/abrahamemmanuel/iphone-photography-school.git
-   From the root directory run composer install
-   You must have a MySQL database running locally
-   Update the database details in .env to match your local setup
-   Run php artisan migrate to setup the database tables

### Guide to Tests Coverage

-   run `php artisan test`
    There are 31 tests with 354 assertions that cover all possible scenarios

    ✓ should assert that user has no comment written achievements and has 4 achievements to unlock intermediate badge

    ✓ should assert that user achieves first comment written achievement and has 3 achievements to unlock intermediate badge

    ✓ should assert that user achieves three comment written achievement and has 2 achievements to unlock intermediate badge

    ✓ should assert that user achieves five comment written achievement and has 1 achievement to unlock intermediate badge

    ✓ should assert that user achieves ten comment written achievement and has 4 achievement to unlocked intermediate badge

    ✓ should assert that user achieves twenty comment written achievement and has 4 achievement to unlock advanced badge

    ✓ should assert that user has no lesson watched achievements and has 4 achievements to unlock intermediate badge

    ✓ should assert that user has achieve first lesson watched achievements and has 3 achievements to unlock intermediate badge

    ✓ should assert that user has achieve five lesson watched achievements and has 3 achievements to unlock intermediate badge

    ✓ should assert that user has achieve ten lesson watched achievements and has 1 achievements to unlock intermediate badge

    ✓ should assert that user has achieve twenty five lesson watched achievements and has 4 achievements to unlock advanced badge

    ✓ should assert that user has achieve fifty lesson watched achievements and has 3 achievements to unlock advanced badge

    ✓ should assert that user has achieve first lesson and first comment achievements and has 2 achievements to unlock intermediate badge

    ✓ should assert that user has achieve first lesson and three comment achievements and has 1 achievements to unlock intermediate badge

    ✓ should assert that user has achieve first lesson and five comment achievements and has 4 achievements to unlock advanced badge

    ✓ should assert that user has achieve first lesson and ten comment achievements and has 3 achievements to unlock advanced badge

    ✓ should assert that user has achieve first lesson and twenty comment achievements and has 2 achievements to unlock advanced badge

    ✓ should assert that user has achieve five lesson and twenty comment achievements and has 1 achievements to unlock advanced badge

    ✓ should assert that user has achieve ten lesson and twenty comment achievements and has 2 achievements to unlock master badge

    ✓ should assert that user has achieve twenty five lesson and twenty comment achievements and has 1 achievements to unlock master badge

    ✓ should assert that user has achieve fifty lesson and twenty comment achievements and has master badge

    ✓ should assert that user has not achieve first lesson and first comment achievements and has 4 achievements to unlock intermediate badge

    ✓ should assert that user has achieve five lesson and first comment achievements and has 1 achievements to unlock intermediate badge

    ✓ should assert that user has achieve five lesson and three comment achievements and has 4 achievements to unlock advanced badge

    ✓ should assert that user has achieve ten lesson and three comment achievements and has 3 achievements to unlock advanced badge

    ✓ should assert that user has achieve ten lesson and five comment achievements and has 2 achievements to unlock advanced badge

    ✓ should assert that user has achieve twenty five lesson and five comment achievements and has 2 achievements to unlock advanced badge

    ✓ should assert that user has achieve twenty five lesson and ten comment achievements and has unlocked advanced badge

    ✓ should assert that user has achieve fifty lesson and ten comment achievements and has 1 achievements to unlocked master badge

    ✓ should assert that user has achieve fifty lesson and twenty comment achievements and has unlocked master badge

> note that all the tests are sitting in the root/tests/Feature/ExampleTest.php

Let's Write Some Tests

**Example:**
If the user has unlocked the “5 Lessons Watched” and “First Comment Written” achievements only the “10 Lessons Watched” and “3 Comments Written“ achievements should be returned.

```sh
    public function test_should_assert_that_user_has_achieve_five_lesson_and_first_comment_achievements_and_has_1_achievements_to_unlock_intermediate_badge(): void
    {
        $this->withoutExceptionHandling();
        $user = User::factory()->create();
        $this->lessonWatcher(5, $user);
        $this->commentWriter(1, $user);
        $response = $this->get("/users/{$user->id}/achievements");
        $response->assertJsonFragment([
            'unlocked_achievements' => [
                'First Comment Written',
                'First Lesson Watched',
                '5 Lessons Watched'
            ],
            'next_available_achievements' => [
                '3 Comments Written',
                '10 Lessons Watched'
            ],
            'current_badge' => 'Beginner',
            'next_badge' => 'Intermediate',
            'remaining_to_unlock_next_badge' => 1,
        ]);
        $this->assertCount(3, $response['unlocked_achievements']);
        $this->assertCount(2, $response['next_available_achievements']);
        $this->assertIsArray($response['next_available_achievements']);
        $this->assertIsArray($response['unlocked_achievements']);
        $this->assertIsString($response['current_badge']);
        $this->assertIsString($response['next_badge']);
        $this->assertIsInt($response['remaining_to_unlock_next_badge']);
        $this->resetAllAchievementValues();
    }
```

$this->commentWriter(1, $user) is responsible for performing comment writing. It takes two arguments, an integer and user object. Pass in the number of comment to be written.

```sh
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
```

$this->lessonWatcher(5, $user) is responsible for performing watch lessons. It takes two arguments, an integer and user object. Pass in the number of lessons to be watched.

```sh
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
```

$this->resetAllAchievementValues() is responsible for reseting all achievements values. This must always be invoke after every single tests.

```sh
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
```

> note that The following relationships were not readily available on the user model:

watched
This will return an eloquent relationship for lessons watched by a user.

comments
This will return an eloquent relationship for comments written by a user.

**you might consider updating the codebase** https://drive.google.com/file/d/1B7su9bgAUhDHdhA7SA1q64-L6bq1JboO/view?usp=sharing

_Happy Testing!_
