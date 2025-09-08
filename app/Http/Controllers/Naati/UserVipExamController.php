<?php

namespace App\Http\Controllers\Naati;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NaatiUserVipExam;
use App\Models\NaatiVipExam;
use App\Models\Label;
use App\Models\Notes;
use Illuminate\Support\Facades\Auth;
use App\Models\NaatiUserVipExamSegment;
use Illuminate\Support\Facades\DB;

class UserVipExamController extends Controller
{
    public function vipExam()
{
    $user = auth()->user();
    $labels = Label::all();

    $languageId = $user->language_id;

    // user plan
    $planId = $user->subscription_id ?? 1;

    // dialogues assigned by admin for this plan
    $allowedDialogues = DB::table('naati_plan_dialogue')
        ->where('plan_id', $planId)
        ->where('type_id', 2)
        ->pluck('dialogue_id')
        ->toArray();

    // all Vip Exam dialogues for user's language
    $dialoguesQuery = NaatiVipExam::where('language_id', $languageId);

    // separate unlocked and locked
    $unlocked = (clone $dialoguesQuery)->whereIn('id', $allowedDialogues)->get();
    $locked   = (clone $dialoguesQuery)->whereNotIn('id', $allowedDialogues)->get();

    // merge unlocked first, locked later
    $dialogues = $unlocked->merge($locked);

    // completed ones
    $completedDialogues = NaatiUserVipExam::select(
        'naati_user_vip_exams.*',
        'naati_vip_exams.title'
    )
        ->join('naati_vip_exams', 'naati_vip_exams.id', '=', 'naati_user_vip_exams.dialogue_id')
        ->where('naati_user_vip_exams.user_id', $user->id)
        ->get();

    return view('naati.users.vip-exams.index', [
        'dialogues' => $dialogues,
        'labels' => $labels,
        'completedDialogues' => $completedDialogues,
        'allowedDialogues' => $allowedDialogues,
    ]);
}

    
    

    public function completedVipExam($userPracticeDialogueId)
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
            FROM naati_user_vip_exams upd
            INNER JOIN users u ON u.id = upd.user_id
            INNER JOIN naati_vip_exams pd ON pd.id = upd.dialogue_id
            WHERE upd.id = ?
        ", [$userPracticeDialogueId]);

        if (!$userPractice) {
            abort(404, 'Dialogue not found.');
        }

        $dialogueId = $userPractice->dialogue_id;

        // 2) Get admin segments for this dialogue (order by id ensures sequence)
        $adminSegments = DB::select("
            SELECT id, dialogue_id, segment_path, sample_response, answer_eng, answer_other_language
            FROM naati_vip_exam_segments
            WHERE dialogue_id = ?
            ORDER BY id
        ", [$dialogueId]);

        // 3) Get user segments for this attempt (order by segment_number)
        $userSegments = DB::select("
            SELECT id, user_dialogue_id, segment_path, segment_number, created_at
            FROM naati_user_vip_exam_segments
            WHERE user_dialogue_id = ?
            ORDER BY segment_number
        ", [$userPracticeDialogueId]);

        // 4) Prepare structured data
        $dialogue = (object) [
            'user_dialogue_id' => $userPractice->user_practice_dialogue_id,
            'dialogue_id'      => $dialogueId,
            'title'            => $userPractice->dialogue_title,
            'description'      => $userPractice->dialogue_description,
            'admin_segments'   => $adminSegments,
            'user_segments'    => $userSegments,
        ];

        // 5) Return view
        return view('naati.users.vip-exams.completed-vip-exams', [
            'practiceDialogue' => $userPractice,
            'dialogue'         => $dialogue,
        ]);
    }



    
    public function vipexamSegment($dialogueId)
    {    
        $user = auth()->user();
        // Get practice
        $dialogue = DB::table('naati_vip_exams')
                ->where('id', $dialogueId)
                ->first();

          
        if (!$dialogue) {
        return redirect()->back()->with('error', 'Dialogue not found.');
        }

        $planId = $user->subscription_id ?? 1; // fallback: free plan
        $allowedDialogues = DB::table('naati_plan_dialogue')
            ->where('plan_id', $planId)
            ->where('type_id', 2) // type 2 = VIP exam
            ->pluck('dialogue_id')
            ->toArray();

        // --- Check access ---
        if (!in_array($dialogueId, $allowedDialogues)) {
            return redirect()->back()
                ->with('error', 'You need an active subscription to access this dialogue.');
        }


        // Get related segments manually (manual foreign key: dialogue_id)
        $segments = DB::table('naati_vip_exam_segments')
            ->where('dialogue_id', $dialogue->id)
            ->get();
        //   dd($segments->); 
 
        // Get labels
        $labels = DB::table('labels')->get();

        return view('naati.users.vip-exams.user-vipexam-segments', [
            'dialogue' => $dialogue,
            'segments' => $segments,
            'labels'   => $labels
        ]);
    }

    public function submitVipExamResponses(Request $request)
    {
        $userId = auth()->id();

        $request->validate([
            'dialogue_id' => 'required|integer',
            'responses.*' => 'file|mimes:webm,wav,mp3,ogg|max:10240',
            'segment_ids.*' => 'required|integer',
        ]);

        try {
            $responses  = $request->file('responses');
            $segmentIds  = $request->input('segment_ids', []);
            $dialogueId = $request->input('dialogue_id');

            // 🚨 Check if no responses were uploaded
            if (empty($responses)) {
                    return response()->json([
                        'message' => 'No recordings found','redirect'=>route('vip-exam')
                    ], 200);
                }

            // Save user dialogue
            $userDialogue = new NaatiUserVipExam();
            $userDialogue->user_id     = $userId;
            $userDialogue->dialogue_id = $dialogueId;
         
            $userDialogue->save();

            // Save user segments
           $segmentNumbers  = $request->input('segment_numbers', []);
            foreach ($responses as $key => $audioFile) {
                 if (!isset($segmentNumbers[$key])) {
                        continue; // skip invalid
                    }
                $path = $audioFile->store("user-responses/{$userId}/vip-exams-audios", 'public');

                $userSegment = new NaatiUserVipExamSegment();
                $userSegment->segment_path     = $path;
                $userSegment->user_dialogue_id = $userDialogue->id;
                $userSegment->segment_number   = (int) $segmentNumbers[$key]; // safe cast
                $userSegment->save();
            }
            return response()->json([
                'message'  => 'Responses saved.',
                'redirect' => route('vip-exam')
            ]);

        } catch (\Exception $e) {
            \Log::error('Submit Responses Error', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ], 500);
        }
    }


   }

