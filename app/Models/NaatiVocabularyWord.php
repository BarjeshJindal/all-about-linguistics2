<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NaatiVocabularyWord extends Model
{
    protected $fillable =['word','meaning','category_id','language_id'];
}
