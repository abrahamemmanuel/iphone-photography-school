<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    const FIRST_LESSON_WATCHED_ACHIEVEMENT = 'First Lesson Watched';
    const FIVE_LESSONS_WATCHED_ACHIEVEMENT = '5 Lessons Watched';
    const TEN_LESSONS_WATCHED_ACHIEVEMENT = '10 Lessons Watched';
    const TWENTY_FIVE_LESSONS_WATCHED_ACHIEVEMENT = '25 Lessons Watched';
    const FIFTY_LESSONS_WATCHED_ACHIEVEMENT = '50 Lessons Watched';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get all of the comments for the user.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function watched()
    {
        return $this->hasMany(Lesson::class, 'lesson_user', 'user_id', 'lesson_id');
    }
}
