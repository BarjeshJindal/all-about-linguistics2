<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMockTestDialogue extends Model
{
    protected $fillable = [
        'user_id',
        'mock_test_id',
        'mock_test_dialogue_id',
        'score',
    ];
}
