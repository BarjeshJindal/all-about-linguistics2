<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NaatiPracticeDialogue extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'language_id',
        'translation_flow',
        'category_id'
    ];
}
