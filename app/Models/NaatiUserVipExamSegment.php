<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NaatiUserVipExamSegment extends Model
{
      protected $fillable = [
        'segment_path',
        'user_dialogue_id',
    ];
}
