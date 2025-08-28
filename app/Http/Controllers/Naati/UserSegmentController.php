<?php

namespace App\Http\Controllers\Naati;

use App\Models\Practice;
use App\Models\Notes;
use Illuminate\Http\Request;
use App\Models\UserRecording;
use Illuminate\Support\Facades\Auth;
use App\Models\Label;
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\DB;
use App\Models\NaatiPracticeDialogue;
use App\Models\NaatiUserPracticeDialogue;
use App\Models\NaatiUserPracticeDialogueSegment;

class UserSegmentController extends Controller
{
    public function index($practiceId)
    {
        // Get practice
        $practice = DB::table('naati_practice_dialogues')
            ->where('id', $practiceId)
            ->first();

            // dd($practice);
        // Get related segments manually (manual foreign key: dialogue_id)
        $segments = DB::table('naati_practice_dialogues_segments')
            ->where('dialogue_id', $practiceId)
            ->get();

        // Get labels
        $labels = DB::table('labels')->get();

        return view('naati.users.segments.index', [
            'practice' => $practice,
            'segments' => $segments,
            'labels'   => $labels
        ]);
    }


    public function submitResponses(Request $request)
    {
        $userId = auth()->id();

        $request->validate([
            'dialogue_id' => 'required|integer',
            'responses.*' => 'file|mimes:webm,wav,mp3,ogg|max:10240',
        ]);

        try {
            $responses  = $request->file('responses');
            $dialogueId = $request->input('dialogue_id');

            // 🚨 Check if no responses were uploaded
            if (empty($responses)) {
                return response()->json([
                    'message' => 'No recordings found',
                    'redirect'=> route('practiceDialogue')
                ], 200);
            }

            $categoryId = NaatiPracticeDialogue::where('id', $dialogueId)->value('category_id');
        
            // Save user dialogue
            $userDialogue = new NaatiUserPracticeDialogue();
            $userDialogue->user_id     = $userId;
            $userDialogue->dialogue_id = $dialogueId;
            $userDialogue->category_id = $categoryId;
            $userDialogue->save(); 

            // Save user segments with segment_number
            foreach ($responses as $segmentId => $audioFile) {
                $path = $audioFile->store("user-responses/{$userId}/practice-dialogue-audios", 'public');

                $userSegment = new NaatiUserPracticeDialogueSegment();
                $userSegment->segment_path     = $path;
                $userSegment->user_dialogue_id = $userDialogue->id;
                $userSegment->segment_number   = $segmentId; // 👈 use original segmentId
                $userSegment->save();
            }

            return response()->json([
                'message'  => 'Responses saved.',
                'redirect' => route('practiceDialogue')
            ]);

        } catch (\Exception $e) {
            \Log::error('Submit Responses Error', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            return response()->json([
                'message' => $e->getMessage(),
                'error'   => $e->getMessage()
            ], 500);
        }
    }



    public function note(Request $request, $practiceId)
    {
        $note = DB::table('notes')
            ->updateOrInsert(
                [
                    'user_id' => auth()->id(),
                    'practice_id' => $practiceId,
                ],
                [
                    'note'       => $request['note'],
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );

        return response()->json([
            'success' => true,
            'message' => 'Note saved successfully.',
        ]);
    }

    public function getNote($practiceId)
    {
        $note = DB::table('notes')
            ->where('user_id', auth()->id())
            ->where('practice_id', $practiceId)
            ->value('note');

        return response()->json([
            'success' => true,
            'note' => $note
        ]);
    }

    public function updateLabel(Request $request, $practiceId)
    {
        // Get label template (shared label: user_id is null)
        $labelTemplate = DB::table('labels')
            ->whereNull('user_id')
            ->where('id', $request->id)
            ->first();

        if (!$labelTemplate) {
            return response()->json(['success' => false, 'message' => 'Label not found.']);
        }

        // Save or update user's label for the practice
        DB::table('labels')->updateOrInsert(
            [
                'user_id'     => auth()->id(),
                'practice_id' => $practiceId,
            ],
            [
                'name'       => $labelTemplate->name,
                'color'      => $labelTemplate->color,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );

        return response()->json(['success' => true]);
    }
}
