<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NaatiUserPracticeDialogueSegment extends Model
{
    use HasFactory;

    protected $table = 'naati_user_practice_dialogue_segments';

    protected $fillable = [
        'segment_path',
        'user_dialogue_id',
    ];
}
