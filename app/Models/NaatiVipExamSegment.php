<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class NaatiVipExamSegment extends Model
{
    use HasFactory;

    

    protected $fillable = [
        'segment_path',
        'answer_eng',
        'answer_other_language',
        'dialogue_id',
        'sample_response',
    ];
}
