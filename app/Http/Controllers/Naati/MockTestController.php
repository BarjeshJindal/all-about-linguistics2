<?php

namespace App\Http\Controllers\Naati;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\MockTest;
use App\Models\MockTestDialogue;
use Illuminate\Support\Facades\DB;
use App\Models\NaatiMockTest;
use App\Models\NaatiUserMockTest;


class MockTestController extends Controller
{
    
    public function showMockTestsList()
    {
        $user = auth()->user();
        $userId = $user->id;

        // Get the user's preferred second language
        $secondLanguage = $user->language_id;

        // User plan
        $planId = $user->subscription_id ?? 1;

        // Dialogues assigned by admin for this plan
        $allowedDialogues = DB::table('naati_plan_dialogue')
            ->where('plan_id', $planId)
            ->where('type_id', 3)
            ->pluck('dialogue_id')
            ->toArray();

        // All Mock Tests for user's language
        $mockTests = NaatiMockTest::where('language_id', $secondLanguage)->get();

        // Separate unlocked and locked
        $unlocked = $mockTests->whereIn('id', $allowedDialogues);
        $locked   = $mockTests->whereNotIn('id', $allowedDialogues);

        // Awaiting feedback
        $awaitingFeedback = NaatiUserMockTest::select(
            'naati_user_mock_tests.*',
            'naati_mock_tests.title'
        )
            ->join('naati_mock_tests', 'naati_mock_tests.id', '=', 'naati_user_mock_tests.mock_test_id')
            ->where('naati_user_mock_tests.user_id', $userId)
            ->whereNull('naati_user_mock_tests.score')
            ->get();

        // Completed feedback
        $completedFeedback = NaatiUserMockTest::select(
            'naati_user_mock_tests.*',
            'naati_mock_tests.title'
        )
            ->join('naati_mock_tests', 'naati_mock_tests.id', '=', 'naati_user_mock_tests.mock_test_id')
            ->where('naati_user_mock_tests.user_id', $userId)
            ->whereNotNull('naati_user_mock_tests.score')
            ->get();

        return view('naati.users.mock-tests.showAllMockTests', compact(
            'mockTests',
            'unlocked',
            'locked',
            'awaitingFeedback',
            'completedFeedback',
            'allowedDialogues'
        ));
    }



    public function viewMockTest($mockTestId)
    {   
        $user = auth()->user();
        // Fetch the mock test
        $practice = DB::table('naati_mock_tests')->where('id', $mockTestId)->first();
          
        if (!$practice) {
        return redirect()->back()->with('error', 'Dialogue not found.');
        }
        $planId = $user->subscription_id ?? 1;
        $allowedDialogues = DB::table('naati_plan_dialogue')
            ->where('plan_id', $planId)
            ->where('type_id', 3) // type 3 = Mock Test
            ->pluck('dialogue_id')
            ->toArray();

          // --- Check access ---
        if (!in_array($mockTestId, $allowedDialogues)) {
            return redirect()->back()
                ->with('error', 'You need an active subscription to access this Mock Test.');
        }
    
        // Fetch dialogues related to the mock test
        $dialogues = DB::select("
            SELECT d.id, d.title, d.description
            FROM naati_mock_test_dialogues d
            WHERE d.id IN (
                SELECT dialogue_one_id FROM naati_mock_tests WHERE id = ?
                UNION
                SELECT dialogue_two_id FROM naati_mock_tests WHERE id = ?
            )
        ", [$mockTestId, $mockTestId]);



        // Attach segments to each dialogue
        foreach ($dialogues as $dialogue) {
            $dialogue->segments = DB::table('naati_mock_test_dialogue_segments')
                ->where('dialogue_id', $dialogue->id)
                ->get();
        }


        return view('naati.users.mock-tests.mockTestView', compact('dialogues', 'practice'));
    }


    public function viewMockTestfeedback($mockTestId)
    {

        $mockTest = DB::selectOne("
            SELECT 
                ut.id AS user_mock_test_id,
                ut.user_id,
                ut.mock_test_id,
                ut.user_dialogue_one_id,
                ut.user_dialogue_two_id,
                ut.score,
                ut.feedback,
                u.name AS user_name,
                mt.title AS mock_test_title
            FROM naati_user_mock_tests ut
            INNER JOIN users u ON u.id = ut.user_id
            INNER JOIN naati_mock_tests mt ON mt.id = ut.mock_test_id
            WHERE ut.id = ?
        ", [$mockTestId]);

        if (!$mockTest) {
            abort(404, 'Mock test not found.');
        }

        // 2) Collect the user_dialogue IDs (can be null)
        $userDialogueIds = array_values(array_filter([
            $mockTest->user_dialogue_one_id ?? null,
            $mockTest->user_dialogue_two_id ?? null,
        ], fn($v) => !is_null($v)));

        // If no user dialogues, render with empty dialogues
        if (empty($userDialogueIds)) {
            return view('admin.mock-tests.reviews-show', [
                'mockTest'  => $mockTest,
                'dialogues' => [],
            ]);
        }

        // 3) Map user_dialogue_id -> dialogue_id (from naati_user_mock_test_dialogues)
        $placeholders = implode(',', array_fill(0, count($userDialogueIds), '?'));
        $userDialogueRows = DB::select("
            SELECT id AS user_dialogue_id, dialogue_id
            FROM naati_user_mock_test_dialogues
            WHERE id IN ($placeholders)
        ", $userDialogueIds);

        $userDialogueMap = [];   // [user_dialogue_id => dialogue_id]
        $dialogueIds      = [];  // collect dialogue_ids
        foreach ($userDialogueRows as $row) {
            $userDialogueMap[$row->user_dialogue_id] = $row->dialogue_id;
            $dialogueIds[] = $row->dialogue_id;
        }

        if (empty($dialogueIds)) {
            return view('admin.mock-tests.reviews-show', [
                'mockTest'  => $mockTest,
                'dialogues' => [],
            ]);
        }

        // 4) Dialogue meta: title/description (from naati_mock_test_dialogues)
        $dlgPlaceholders = implode(',', array_fill(0, count($dialogueIds), '?'));
        $dialogueMetaRows = DB::select("
            SELECT id, title, description
            FROM naati_mock_test_dialogues
            WHERE id IN ($dlgPlaceholders)
        ", $dialogueIds);

        $dialogueMeta = []; // [dialogue_id => {id,title,description}]
        foreach ($dialogueMetaRows as $row) {
            $dialogueMeta[$row->id] = $row;
        }

        // 5) Admin segments for each dialogue (from naati_mock_test_dialogue_segments)
        $adminSegRows = DB::select("
            SELECT id, dialogue_id, segment_path, sample_response, answer_eng, answer_other_language
            FROM naati_mock_test_dialogue_segments
            WHERE dialogue_id IN ($dlgPlaceholders)
            ORDER BY id
        ", $dialogueIds);

        $adminSegmentsByDialogueId = []; // [dialogue_id => [rows]]
        foreach ($adminSegRows as $row) {
            $adminSegmentsByDialogueId[$row->dialogue_id][] = $row;
        }

        // 6) User segments for each user_dialogue (from naati_user_mock_test_dialogue_segments)
        $usrSegRows = DB::select("
            SELECT id, user_dialogue_id, segment_path, segment_number
            FROM naati_user_mock_test_dialogue_segments
            WHERE user_dialogue_id IN ($placeholders)
            ORDER BY segment_number
        ", $userDialogueIds);

        $userSegmentsByUserDialogueId = []; // [user_dialogue_id => [segment_number => row]]
        foreach ($usrSegRows as $row) {
            $userSegmentsByUserDialogueId[$row->user_dialogue_id][$row->segment_number] = $row;
        }

        // 7) Build a tidy dialogues array in the order: dialogue_one then dialogue_two
        $orderedUserDialogueIds = [];
        if (!empty($mockTest->user_dialogue_one_id)) $orderedUserDialogueIds[] = $mockTest->user_dialogue_one_id;
        if (!empty($mockTest->user_dialogue_two_id)) $orderedUserDialogueIds[] = $mockTest->user_dialogue_two_id;

        $dialogues = [];
        foreach ($orderedUserDialogueIds as $udid) {
            $dId  = $userDialogueMap[$udid] ?? null;
            $meta = $dId ? ($dialogueMeta[$dId] ?? null) : null;

            $dialogues[] = (object) [
                'user_dialogue_id' => $udid,
                'dialogue_id'      => $dId,
                'title'            => $meta->title ?? null,
                'description'      => $meta->description ?? null,
                'admin_segments'   => $adminSegmentsByDialogueId[$dId] ?? [],
                'user_segments'    => $userSegmentsByUserDialogueId[$udid] ?? [],
            ];
        }

        // dd($mockTest);
        return view('naati.users.mock-tests.mockTestFeedbackView', [
            'mockTest'  => $mockTest,   // has: user_name, mock_test_title, score, feedback, etc.
            'dialogues' => $dialogues,  // each has: title, description, admin_segments[], user_segments[]
        ]);

    }

}
