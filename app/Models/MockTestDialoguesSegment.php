<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MockTestDialoguesSegment extends Model
{
    use HasFactory;

    protected $fillable = [
        'segment_path',
        'answer_eng',
        'answer_other',
        'dialogue_id',
    ];
}
