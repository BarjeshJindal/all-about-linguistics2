<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NaatiUserMockTestDialogueSegment extends Model
{
    use HasFactory;

    protected $table = 'naati_user_mock_test_dialogue_segments';

    protected $fillable = [
        'segment_path',
        'user_dialogue_id',
        'segment_number',
    ];
}
