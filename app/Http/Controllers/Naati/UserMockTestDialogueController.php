<?php

namespace App\Http\Controllers\Naati;

use Illuminate\Http\Request;
use App\Models\UserMockTestDialogue;
use App\Models\UserRecording;
use App\Http\Controllers\Controller; 

use App\Models\NaatiUserMockTest;
use App\Models\NaatiUserMockTestDialogue;
use App\Models\NaatiUserMockTestDialogueSegment;


class UserMockTestDialogueController extends Controller
{


    public function submitResponses(Request $request)
    {
        $userId     = auth()->id();
        $mockTestId = $request->input('mock_test_id');
        $responses  = $request->file('responses');

        if (!$responses || count($responses) === 0) {
            return response()->json(['message' => 'No recordings found'], 422);
        }

        // 1. Save or fetch NaatiUserMockTest
        $userMockTest = new NaatiUserMockTest();
        $userMockTest->user_id      = $userId;
        $userMockTest->mock_test_id = $mockTestId;
        $userMockTest->save();

        // 2. Save dialogues (extract dialogueIds from keys)
        $dialogueIds = [];
        foreach (array_keys($responses) as $key) {
            [$dialogueId, $segmentNumber] = explode('_', $key);
            $dialogueIds[] = $dialogueId;
        }
        $dialogueIds = array_unique($dialogueIds);

        $userMockTestDialogues = [];
        foreach ($dialogueIds as $dialogueId) {
            $dialogue = new NaatiUserMockTestDialogue();
            $dialogue->user_id     = $userId;
            $dialogue->dialogue_id = $dialogueId;
            $dialogue->save();

            $userMockTestDialogues[$dialogueId] = $dialogue;
        }

        // Update reference ids
        $dialogueIdsUnique = array_values($dialogueIds);
        if (isset($dialogueIdsUnique[0])) {
            $userMockTest->user_dialogue_one_id = $userMockTestDialogues[$dialogueIdsUnique[0]]->id;
        }
        if (isset($dialogueIdsUnique[1])) {
            $userMockTest->user_dialogue_two_id = $userMockTestDialogues[$dialogueIdsUnique[1]]->id;
        }
        $userMockTest->save();

        // 3. Save segments
        foreach ($responses as $key => $audioFile) {
            [$dialogueId, $segmentNumber] = explode('_', $key);

            $path = $audioFile->store("user-responses/{$userId}/mock-test-audios", 'public');

            $segment = new NaatiUserMockTestDialogueSegment();
            $segment->user_dialogue_id = $userMockTestDialogues[$dialogueId]->id;
            $segment->segment_number   = $segmentNumber;
            $segment->segment_path     = $path;
            $segment->save();
        }

        return response()->json(['message' => 'Mock test responses saved successfully.']);
    }




}
