<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NaatiPracticeDialogue;
use DB;

class AdminSubcriptionPlanController extends Controller
{
    public function selectPracticeDialogue($planId = 1)
    {
        $practiceDialogues = NaatiPracticeDialogue::latest()->get();

        $assigned = DB::table('naati_plan_dialogue')
            ->where('plan_id', $planId)
            ->pluck('dialogue_id')
            ->toArray();

        $plan = DB::table('naati_subscription_plans')->find($planId);

        return view('admin.practice-dialogues.select-dialogues', compact('practiceDialogues', 'assigned', 'plan'));
    }

    public function updateSelectedDialogues(Request $request, $planId='1')
    {
        $plan = DB::table('naati_subscription_plans')->where('id', $planId)->first();

        $dialogues = $request->input('dialogues', []);

        // Validate limit
        if (count($dialogues) > $plan->practice_dialogues_limit) {
            return back()->with('error', "You can only assign up to {$plan->practice_dialogues_limit} dialogues for {$plan->plan_type} plan.");
        }

        // Clear existing links
        DB::table('naati_plan_dialogue')->where('plan_id', $planId)->delete();

        // Insert new ones
        foreach ($dialogues as $dialogueId) {
            DB::table('naati_plan_dialogue')->insert([
                'plan_id'     => $planId,
                'dialogue_id' => $dialogueId,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]);
        }

        return back()->with('success', 'Dialogues updated successfully!');
    }
}
