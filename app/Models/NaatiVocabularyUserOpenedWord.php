<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NaatiVocabularyUserOpenedWord extends Model
{
    protected $fillable = ['user_id', 'word_id', 'open_count'];
}
