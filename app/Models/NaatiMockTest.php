<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NaatiMockTest extends Model
{
    use HasFactory;

    protected $table = 'naati_mock_tests';

    protected $fillable = [
        'title',
        'language_id',
        'duration',
        'dialogue_one_id',
        'dialogue_two_id',
    ];
}
