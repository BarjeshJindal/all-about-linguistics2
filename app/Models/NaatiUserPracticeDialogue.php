<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NaatiUserPracticeDialogue extends Model
{
    use HasFactory;

    protected $table = 'naati_user_practice_dialogues';

    protected $fillable = [
        'user_id',
        'dialogue_id',
    ];
}
