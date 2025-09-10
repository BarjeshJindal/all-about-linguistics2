<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NaatiNote extends Model
{
    protected $fillable = ['user_id', 'dialogue_id', 'type_id', 'note'];

}
