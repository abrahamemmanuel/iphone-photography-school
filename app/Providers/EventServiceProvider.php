<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\CommentWritten as CommentWrittenEvent;
use App\Events\LessonWatched as LessonWatchedEvent;
use App\Listeners\CommentWritten as CommentWrittenListener;
use App\Listeners\LessonWatched as LessonWatchedListener;
use App\Events\AchievementUnlocked as AchievementUnlockedEvent;
use App\Listeners\AchievementUnlocked as AchievementUnlockedListener;
use App\Events\BadgeUnlocked as BadgeUnlockedEvent;
use App\Listeners\BadgeUnlocked as BadgeUnlockedListener;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        CommentWrittenEvent::class => [
            CommentWrittenListener::class,
        ],
        LessonWatchedEvent::class => [
            LessonWatchedListener::class,
        ],
        AchievementUnlockedEvent::class => [
            AchievementUnlockedListener::class,
        ],
        BadgeUnlockedEvent::class => [
            BadgeUnlockedListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
