<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NaatiPracticeDialogue;
use App\Models\NaatiVipExam;
use App\Models\NaatiMockTest;
use App\Models\NaatiSubscriptionPlan;
use App\Models\Language;
use DB;

class AdminSubcriptionPlanController extends Controller
{
    public function selectPracticeDialogue($id)
    {
        $languages = Language::get()->keyBy('id'); // 🔑 makes lookup easier

        $plan = DB::table('naati_subscription_plans')->find($id);

        // Group practice by language
        $practiceDialoguesByLang = NaatiPracticeDialogue::get()
            ->groupBy('language_id');

        // Group VIP by language
        $vipDialoguesByLang = NaatiVipExam::get()
            ->groupBy('language_id');

        // Group Mock Tests by language
        $mockTestsByLang = NaatiMockTest::get()
            ->groupBy('language_id');

        // Already assigned dialogues
        $assigned = DB::table('naati_plan_dialogue')
            ->where('plan_id', $plan->id)
            ->selectRaw("CONCAT(type_id, '-', dialogue_id) as combo")
            ->pluck('combo')
            ->toArray();

        return view('admin.practice-dialogues.select-dialogues', compact(
            'practiceDialoguesByLang',
            'vipDialoguesByLang',
            'mockTestsByLang',
            'assigned',
            'plan',
            'languages'
        ));
    }

    public function updateSelectedDialogues(Request $request, $planId = '1')
    {
        $plan = DB::table('naati_subscription_plans')->where('id', $planId)->first();
        if (!$plan) {
            return back()->with('error', "Plan not found.");
        }

        $dialogues = $request->input('dialogues', []);
        $dialogues = collect($dialogues)
            ->filter(fn($d) => isset($d['selected']) && in_array($d['type_id'], [1, 2, 3]))
            ->mapWithKeys(function ($data, $key) {
                [$typeId, $dialogueId] = explode('-', $key);
                return ["$typeId-$dialogueId" => [
                    'dialogue_id' => (int) $dialogueId,
                    'language_id' => $data['language_id'],
                    'type_id'     => (int) $typeId,
                ]];
            })
            ->toArray();

        if (empty($dialogues)) {
            DB::table('naati_plan_dialogue')->where('plan_id', $planId)->delete();
            return back()->with('success', "All dialogues removed successfully.");
        }

        $byLanguage = collect($dialogues)->groupBy('language_id');
        $languages = DB::table('languages')->pluck('second_language', 'id');

        foreach ($byLanguage as $languageId => $group) {
            $practiceCount = collect($group)->where('type_id', 1)->count();
            $vipCount      = collect($group)->where('type_id', 2)->count();
            $mockCount     = collect($group)->where('type_id', 3)->count();

            // Validate practice dialogues
            if ($practiceCount > $plan->practice_dialogues_limit) {
                $languageName = $languages[$languageId] ?? "Language {$languageId}";
                return back()->with(
                    'error',
                    "You can only assign up to {$plan->practice_dialogues_limit} practice dialogues for {$languageName}."
                )->withInput();
            }

            // Validate VIP dialogues
            if ($vipCount > $plan->vip_exams_limit) {
                $languageName = $languages[$languageId] ?? "Language {$languageId}";
                return back()->with(
                    'error',
                    "You can only assign up to {$plan->vip_exams_limit} VIP dialogues for {$languageName}."
                )->withInput();
            }

            // Validate Mock tests
            if ($mockCount > $plan->mock_tests_limit) {
                $languageName = $languages[$languageId] ?? "Language {$languageId}";
                return back()->with(
                    'error',
                    "You can only assign up to {$plan->mock_tests_limit} mock tests for {$languageName}."
                )->withInput();
            }
        }

        DB::transaction(function () use ($planId, $dialogues) {
            DB::table('naati_plan_dialogue')->where('plan_id', $planId)->delete();

            $now = now();
            $rows = [];
            foreach ($dialogues as $combo => $data) {
                $rows[] = [
                    'plan_id'     => $planId,
                    'dialogue_id' => $data['dialogue_id'],
                    'language_id' => $data['language_id'],
                    'type_id'     => $data['type_id'],
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ];
            }

            if (!empty($rows)) {
                DB::table('naati_plan_dialogue')->insert($rows);
            }
        });

        return back()->with('success', 'Dialogues updated successfully!');
    }

    public function manageSubcription()
    {
        $plans = NaatiSubscriptionPlan::get();
        return view('admin.subscriptions.manage-subscription', compact('plans'));
    }
}
