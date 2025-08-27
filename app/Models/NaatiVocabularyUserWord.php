<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NaatiVocabularyUserWord extends Model
{
   protected $fillable =['user_id','word_id','memorized_count'];
}

