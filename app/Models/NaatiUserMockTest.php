<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NaatiUserMockTest extends Model
{
    use HasFactory;

    protected $table = 'naati_user_mock_tests';

    protected $fillable = [
        'user_id',
        'mock_test_id',
        'user_dialogue_one_id',
        'user_dialogue_two_id',
        'score',
        'feedback',
    ];
}
