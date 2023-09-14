<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    const FIRST_LESSON_WATCHED_ACHIEVEMENT = 'First Lesson Watched';
    const FIVE_LESSONS_WATCHED_ACHIEVEMENT = '5 Lessons Watched';
    const TEN_LESSONS_WATCHED_ACHIEVEMENT = '10 Lessons Watched';
    const TWENTY_FIVE_LESSONS_WATCHED_ACHIEVEMENT = '25 Lessons Watched';
    const FIFTY_LESSONS_WATCHED_ACHIEVEMENT = '50 Lessons Watched';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title'
    ];
}
