<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NaatiUserVipExam extends Model
{
   protected $fillable = [
        'user_id',
        'dialogue_id',
    ];
}
