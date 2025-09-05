<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NaatiSubscriptionPlan extends Model
{
    protected $fillable =['plan_type','practice_dialogues_limit','mock_tests_limit','vip_exams_limit','duration_days'];
}
