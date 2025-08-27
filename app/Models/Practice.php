<?php

namespace App\Models;

use App\Models\Segment;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Practice extends Model
{
   protected $fillable = ['title', 'description','second_language'];
   /**
    * Get all of the comments for the Practice
    *
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
   public function segments():HasMany
   {
      return $this->hasMany(Segment::class,'segment_parent_id');
   }
}
