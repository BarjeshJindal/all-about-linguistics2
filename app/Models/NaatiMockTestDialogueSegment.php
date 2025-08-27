<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NaatiMockTestDialogueSegment extends Model
{
    use HasFactory;

    protected $table = 'naati_mock_test_dialogue_segments';

    protected $fillable = [
        'segment_path',
        'sample_response',
        'answer_eng',
        'answer_other_language',
        'dialogue_id',
    ];
}
