<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class NaatiVipExam extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'language_id',
        'translation_flow',
    ];
}
