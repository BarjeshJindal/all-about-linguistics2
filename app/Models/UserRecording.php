<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserRecording extends Model
{   
    protected $table = 'users_recording'; 
    protected $fillable = [
        'user_id',
        'segment_id', 
        'audio_path',
        'score',
        'feedback'
    ];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function segment()
{
    return $this->belongsTo(Segment::class);
}
}
