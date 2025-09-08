<?php

namespace App\Http\Controllers\Naati;

use App\Models\User;
use App\Models\Practice;
use Illuminate\Http\Request;
use App\Models\UserRecording;
use App\Models\Label;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller; 
use DB;
use App\Models\NaatiPracticeDialogue;
use App\Models\NaatiPracticeDialoguesSegment;
use App\Models\NaatiUserPracticeDialogue;


class UserController extends Controller
{

    public function practiceDialogue()
{
    $user = auth()->user();
    $labels = Label::all();
    $languageId = $user->language_id;

    // user plan
    $planId = $user->subscription_id ?? 1;

    // dialogues assigned by admin
    $allowedDialogues = DB::table('naati_plan_dialogue')
        ->where('plan_id', $planId)
        ->pluck('dialogue_id')
        ->toArray();

    // all dialogues in user's language
    $dialoguesQuery = NaatiPracticeDialogue::where('language_id', $languageId);

    // separate allowed and locked
    $unlocked = (clone $dialoguesQuery)->whereIn('id', $allowedDialogues)->get();
    $locked   = (clone $dialoguesQuery)->whereNotIn('id', $allowedDialogues)->get();

    // merge unlocked first, locked later
    $dialogues = $unlocked->merge($locked);

    // completed ones
    $completedDialogues = NaatiUserPracticeDialogue::select(
        'naati_user_practice_dialogues.*',
        'naati_practice_dialogues.title'
    )
    ->join('naati_practice_dialogues', 'naati_practice_dialogues.id', '=', 'naati_user_practice_dialogues.dialogue_id')
    ->where('naati_user_practice_dialogues.user_id', $user->id)
    ->get();

    return view('naati.users.practice-dialogues.index', [
        'dialogues' => $dialogues,
        'labels' => $labels,
        'completedDialogues' => $completedDialogues,
        'allowedDialogues' => $allowedDialogues,
    ]);
}

    // public function resultsPracticeDialogue($userPracticeDialogueId)
    // {
    //     // 1) Get the user's practice dialogue attempt
    //     $userPractice = DB::selectOne("
    //         SELECT upd.id AS user_practice_dialogue_id,
    //             upd.user_id,
    //             upd.dialogue_id,
    //             upd.category_id,
    //             u.name AS user_name,
    //             pd.title AS dialogue_title,
    //             pd.description AS dialogue_description
    //         FROM naati_user_practice_dialogues upd
    //         INNER JOIN users u ON u.id = upd.user_id
    //         INNER JOIN naati_practice_dialogues pd ON pd.id = upd.dialogue_id
    //         WHERE upd.id = ?
    //     ", [$userPracticeDialogueId]);

    //     if (!$userPractice) {
    //         abort(404, 'Practice dialogue not found.');
    //     }

    //     // 2) Fetch admin segments (original dialogue parts)
    //     $adminSegments = DB::select("
    //         SELECT id, dialogue_id, segment_path, sample_response, answer_eng, answer_other_language
    //         FROM naati_practice_dialogues_segments
    //         WHERE dialogue_id = ?
    //         ORDER BY id
    //     ", [$userPractice->dialogue_id]);

    //     // 3) Fetch user’s recorded responses
    //     $userSegments = DB::select("
    //         SELECT id, user_dialogue_id, segment_path, created_at
    //         FROM naati_user_practice_dialogue_segments
    //         WHERE user_dialogue_id = ?
    //         ORDER BY id
    //     ", [$userPractice->user_practice_dialogue_id]);

    //     // 4) Structure everything
    //     $dialogue = (object) [
    //         'user_practice_dialogue_id' => $userPractice->user_practice_dialogue_id,
    //         'dialogue_id'               => $userPractice->dialogue_id,
    //         'title'                     => $userPractice->dialogue_title,
    //         'description'               => $userPractice->dialogue_description,
    //         'admin_segments'            => $adminSegments,
    //         'user_segments'             => $userSegments, // no numbering, just parallel by id
    //     ];

    //     // 5) Return view
    //     return view('naati.users.practice-dialogues.results', [
    //         'practiceDialogue' => $userPractice, // has: user_name, title, description, etc.
    //         'dialogue'         => $dialogue,     // includes both admin + user segments
    //     ]);
    // }

   public function resultsPracticeDialogue($userPracticeDialogueId)
    {
            // 1) Fetch user attempt with dialogue meta
            $userPractice = DB::selectOne("
            SELECT 
            upd.id AS user_practice_dialogue_id,
            upd.user_id,
            upd.dialogue_id,
            u.name AS user_name,
            pd.title AS dialogue_title,
            pd.description AS dialogue_description
            FROM naati_user_practice_dialogues upd
            INNER JOIN users u ON u.id = upd.user_id
            INNER JOIN naati_practice_dialogues pd ON pd.id = upd.dialogue_id
            WHERE upd.id = ?
            ", [$userPracticeDialogueId]);

            if (!$userPractice) {
            abort(404, 'Practice dialogue not found.');
            }

            $dialogueId = $userPractice->dialogue_id;

            // 2) Get admin segments for this dialogue
            $adminSegments = DB::select("
            SELECT id, dialogue_id, segment_path, sample_response, answer_eng, answer_other_language
            FROM naati_practice_dialogues_segments
            WHERE dialogue_id = ?
            ORDER BY id
            ", [$dialogueId]);

            // 3) Get user segments for this attempt
            $userSegments = DB::select("
            SELECT id, user_dialogue_id, segment_path, segment_number, created_at
            FROM naati_user_practice_dialogue_segments
            WHERE user_dialogue_id = ?
            ORDER BY id
            ", [$userPracticeDialogueId]);

            // 4) Prepare structured data
            $dialogue = (object) [
            'user_dialogue_id' => $userPractice->user_practice_dialogue_id,
            'dialogue_id'      => $dialogueId,
            'title'            => $userPractice->dialogue_title,
            'description'      => $userPractice->dialogue_description,
            'admin_segments'   => collect($adminSegments),
            'user_segments'    => collect($userSegments),
            ];

            // dd($dialogue);

            // 5) Return view
            return view('naati.users.practice-dialogues.results', [
            'practiceDialogue' => $userPractice, // meta info
            'dialogue'         => $dialogue
            ]);
    }








public function pendingFeedback(practice $practice)
{
    $userId = Auth::id();
        $user_recordings = UserRecording::where('user_id', $userId)
    ->whereHas('segment', function ($query) use ($practice) {
        $query->where('segment_parent_id', $practice->id);
    })
    ->with(['segment.practice'])
    ->get();

    return view('naati.users.practice-dialogues.results', compact('user_recordings'));
}
}
