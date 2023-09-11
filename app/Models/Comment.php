<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;
    
    const FIRST_COMMENT_ACHIEVEMENT = 'First Comment Written Achievements';
    const THREE_COMMENTS_ACHIEVEMENT = '3 Comments Written Achievements';
    const FIVE_COMMENTS_ACHIEVEMENT = '5 Comments Written Achievements';
    const TEN_COMMENTS_ACHIEVEMENT = '10 Comments Written Achievements';
    const TWENTY_COMMENTS_ACHIEVEMENT = '20 Comments Written Achievements';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'body',
        'user_id'
    ];

    /**
     * Get the user that wrote the comment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
