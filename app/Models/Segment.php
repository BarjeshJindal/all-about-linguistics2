<?php

namespace App\Models;

use App\Models\Practice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Segment extends Model
{
    protected $fillable =['segment_path', 'answer_eng', 'answer_second_language','segment_type_id', 'segment_parent_id'];
    
    /**
     * Get the user associated with the Segment
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
  
    /**
     * The roles that belong to the Segment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */

    public function recordings()
    {
        return $this->hasMany(UserRecording::class);
    }
}
