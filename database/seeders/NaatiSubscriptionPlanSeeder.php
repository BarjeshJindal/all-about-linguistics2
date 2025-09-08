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
         // Basic Plan
        NaatiSubscriptionPlan::create([
            'plan_type' => 'basic',
            'practice_dialogues_limit' => 20,
            'mock_tests_limit' => 3,
            'vip_exams_limit' => 0,
            'duration_days' => 30, // 1 month
        ]);

        // Intermediate Plan
        NaatiSubscriptionPlan::create([
            'plan_type' => 'intermediate',
            'practice_dialogues_limit' => 50,
            'mock_tests_limit' => 5,
            'vip_exams_limit' => 1,
            'duration_days' => 60, 
        ]);

        // Advance Plan
        NaatiSubscriptionPlan::create([
            'plan_type' => 'advance',
            'practice_dialogues_limit' => 100,
            'mock_tests_limit' => 10,
            'vip_exams_limit' => 3,
            'duration_days' => 60, 
        ]);

    }
}
