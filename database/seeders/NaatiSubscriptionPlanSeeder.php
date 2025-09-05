<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\NaatiSubscriptionPlan;

class NaatiSubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          // Free Plan
        NaatiSubscriptionPlan::create([
            'plan_type' => 'free',
            // 'name' => 'Free Plan',
            
            'practice_dialogues_limit' => 5,
            'mock_tests_limit' => 0,
            'vip_exams_limit' => 0,
            // 'price' => 0,
            'duration_days' => null,
            // 'is_active' => true,
        ]);

    }
}
