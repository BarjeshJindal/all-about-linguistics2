<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NaatiPracticeDialoguesSegment extends Model
{
    use HasFactory;

    protected $table = 'naati_practice_dialogues_segments';

    protected $fillable = [
        'segment_path',
        'answer_eng',
        'answer_other_language',
        'dialogue_id',
        'sample_response',
    ];
}
