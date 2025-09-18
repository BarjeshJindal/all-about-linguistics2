<?php

namespace App\Http\Controllers\Naati\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MockTest;
use App\Models\MockTestDialogue;
use App\Models\Language;
use App\Http\Controllers\Naati\SegmentController;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;
use App\Models\User;

use App\Models\NaatiMockTest;
use App\Models\NaatiMockTestDialogue;
use App\Models\NaatiMockTestDialogueSegment;
use App\Models\NaatiUserMockTest;
use Illuminate\Support\Facades\Storage;



class AdminMockTestController extends Controller
{
    public function create()
    {
        $languages = Language::all(); 
        return view('admin.mock-tests.addMockTest', compact('languages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'duration' => 'required',
            'language_id' => 'required|integer|exists:languages,id',
            'dialogues' => 'required|array|min:2',
            'dialogues.*.title' => 'required|string|max:255',
            'dialogues.*.description' => 'nullable|string',
            'dialogues.*.translation_flow' => 'required|string|in:english_to_other,other_to_english',
            'dialogues.*.segments' => 'required|array|min:1',
            'dialogues.*.segments.*.segment_path' => 'required|file|mimes:mp3|max:20480',
            'dialogues.*.segments.*.sample_response' => 'nullable|file|mimes:mp3|max:20480', 
            'dialogues.*.segments.*.answer_eng' => 'required|string',
            'dialogues.*.segments.*.answer_second_language' => 'required|string',
        ]);

        // Create the mock test
        $mockTest = NaatiMockTest::create([
            'title' => $validated['title'],
            'language_id' => $validated['language_id'],
            'duration' => $validated['duration'],            
        ]);


        foreach ($validated['dialogues'] as $index => $dialogueData) {

            $mockDialogue = NaatiMockTestDialogue::create([
                'title' => $dialogueData['title'],
                'description' => $dialogueData['description'] ?? null,
                'translation_flow' => $dialogueData['translation_flow'],
            ]);

            // Update mock test with dialogue reference
            if ($index === 0) {
                $mockTest->update(['dialogue_one_id' => $mockDialogue->id]);
            } elseif ($index === 1) {
                $mockTest->update(['dialogue_two_id' => $mockDialogue->id]);
            }

            // 3️⃣ Save segments
            $uploadedFiles = $request->file("dialogues.$index.segments");

            foreach ($dialogueData['segments'] as $i => $segment) {
                $file = $uploadedFiles[$i]['segment_path'];

                $path = $file->store('naati-audios', 'public');
                
                // ✅ Check if sample_response is uploaded
                $sampleResponsePath = null;
                if (isset($uploadedFiles[$i]['sample_response'])) {
                    $sampleFile = $uploadedFiles[$i]['sample_response'];
                    $sampleResponsePath = $sampleFile->store('naati-audios/sample-responses', 'public');
                }

                NaatiMockTestDialogueSegment::create([
                    'dialogue_id' => $mockDialogue->id,
                    'segment_path' => $path,
                    'sample_response' => $sampleResponsePath, 
                    'answer_eng' => $segment['answer_eng'],
                    'answer_other_language' => $segment['answer_second_language'],
                ]);
            }
        }

        return redirect()->route('admin.mock-tests.addMockTest')->with('success', 'Mock Test created successfully.');
     
    }


    public function reviewsIndex()
    {

        $pending = DB::select("
                    SELECT 
                        ut.id, 
                        u.name AS user_name, 
                        mt.title AS mock_test_name, 
                        ut.score
                    FROM naati_user_mock_tests AS ut
                    INNER JOIN users AS u ON u.id = ut.user_id
                    INNER JOIN naati_mock_tests AS mt ON mt.id = ut.mock_test_id
                    WHERE ut.score IS NULL
                ");

        $reviewed = DB::select("
                    SELECT 
                        ut.id, 
                        u.name AS user_name, 
                        mt.title AS mock_test_name, 
                        ut.score
                    FROM naati_user_mock_tests AS ut
                    INNER JOIN users AS u ON u.id = ut.user_id
                    INNER JOIN naati_mock_tests AS mt ON mt.id = ut.mock_test_id
                    WHERE ut.score IS NOT NULL
                "); 

        return view('admin.mock-tests.reviews-index', compact('pending', 'reviewed'));

    }
    

    public function getUserMockTestDetails($id)
    {
        // 1) Header: the user’s attempt + mock test title + user name
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
        ", [$id]);

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
        return view('admin.mock-tests.reviews-show', [
            'mockTest'  => $mockTest,   // has: user_name, mock_test_title, score, feedback, etc.
            'dialogues' => $dialogues,  // each has: title, description, admin_segments[], user_segments[]
        ]);
    }


    public function updateFeedback(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:naati_user_mock_tests,id',
            'score' => 'required|numeric',
            'feedback' => 'required|string',
        ]);

        $mockTest = \App\Models\NaatiUserMockTest::findOrFail($request->id);

        $mockTest->score = $request->score;
        $mockTest->feedback = $request->feedback;
        $mockTest->save();

        return back()->with('success', 'Feedback updated successfully!');
    }

   public function manage(Request $request)
    {
        $languages = DB::table('languages')->get();

        $query = DB::table('naati_mock_tests')
            ->join('languages', 'naati_mock_tests.language_id', '=', 'languages.id')
            ->select('naati_mock_tests.*', 'languages.second_language as language_name')
            ->orderByDesc('naati_mock_tests.created_at');

        // ✅ Handle filter (GET or POST)
        $languageId = $request->input('language_id');

        if (!empty($languageId)) {
            $query->where('naati_mock_tests.language_id', $languageId);
        }

        $practiceDialogues = $query->get();

        return view('admin.mock-tests.manage-mocktest', compact('practiceDialogues', 'languages', 'languageId'));
    }


   public function edit($id){
         $mocktest = NaatiMockTest::findOrFail($id);

        // Get dialogues
        $dialogues = NaatiMockTestDialogue::whereIn('id', [
            $mocktest->dialogue_one_id,
            $mocktest->dialogue_two_id
        ])->get();

        // Get all segments for both dialogues
        $segments = NaatiMockTestDialogueSegment::whereIn('dialogue_id', [
            $mocktest->dialogue_one_id,
            $mocktest->dialogue_two_id
        ])->get();

        return view('admin.mock-tests.edit-mocktest', compact('mocktest', 'dialogues', 'segments'));
    }


public function updateMockTest(Request $request, $id)
{
    // dd($request->all());
    // ✅ Base validation
    $request->validate([
        'title'    => 'required|string|max:255',
        'duration' => 'required|integer|min:1',

        // Dialogue one
        'dialogue_one_title'       => 'required|string|max:255',
        'dialogue_one_description' => 'nullable|string',
        'dialogue_one_flow'        => 'required|in:english_to_other,other_to_english',

        // Dialogue two
        'dialogue_two_title'       => 'required|string|max:255',
        'dialogue_two_description' => 'nullable|string',
        'dialogue_two_flow'        => 'required|in:english_to_other,other_to_english',

        // Segments
        'segments.*.id'  => 'nullable|integer|exists:naati_mock_test_dialogue_segments,id',

        // Deleted segments
        'deleted_segments'   => 'nullable|array',
        'deleted_segments.*' => 'integer|exists:naati_mock_test_dialogue_segments,id',
    ]);

    // ✅ Extra validation: new vs existing segments
    if ($request->has('segments')) {
        foreach ($request->segments as $index => $segment) {
            // Skip deleted
            if ($request->filled('deleted_segments') &&
                in_array($segment['id'] ?? null, $request->deleted_segments)) {
                continue;
            }

            // New segment → all fields required
            if (empty($segment['id'])) {
                $segmentValidator = \Validator::make($segment, [
                    'dialogue_id'            => 'required|integer',
                    'segment_path'           => 'required|file|mimes:mp3,wav|max:10240',
                    'sample_response'        => 'required|file|mimes:mp3,wav|max:10240',
                    'answer_eng'             => 'required|string|max:1000',
                    'answer_second_language' => 'required|string|max:1000',
                ]);
            } else {
                // Existing segment → relaxed rules
                $segmentValidator = \Validator::make($segment, [
                    'dialogue_id'            => 'required|integer',
                    'segment_path'           => 'nullable|file|mimes:mp3,wav|max:10240',
                    'sample_response'        => 'nullable|file|mimes:mp3,wav|max:10240',
                    'answer_eng'             => 'nullable|string|max:1000',
                    'answer_second_language' => 'nullable|string|max:1000',
                ]);
            }

            if ($segmentValidator->fails()) {
                return redirect()->back()->withErrors(
                    $segmentValidator->errors()->mapWithKeys(function ($error, $key) use ($index) {
                        return ["segments.$index.$key" => $error];
                    })
                )->withInput();
            }
        }
    }

    $mocktest = NaatiMockTest::findOrFail($id);

    // ✅ Update mocktest
    $mocktest->update([
        'title'    => $request->title,
        'duration' => $request->duration,
    ]);

    // ✅ Update dialogues
    NaatiMockTestDialogue::where('id', $mocktest->dialogue_one_id)
        ->update([
            'title'            => $request->dialogue_one_title,
            'description'      => $request->dialogue_one_description,
            'translation_flow' => $request->dialogue_one_flow,
        ]);

    NaatiMockTestDialogue::where('id', $mocktest->dialogue_two_id)
        ->update([
            'title'            => $request->dialogue_two_title,
            'description'      => $request->dialogue_two_description,
            'translation_flow' => $request->dialogue_two_flow,
        ]);

    // ✅ Handle segments (save / update)
    if ($request->has('segments')) {
        foreach ($request->segments as $segmentData) {
            // Skip deleted
            if ($request->filled('deleted_segments') &&
                in_array($segmentData['id'] ?? null, $request->deleted_segments)) {
                continue;
            }

            $segment = !empty($segmentData['id'])
                ? NaatiMockTestDialogueSegment::find($segmentData['id'])
                : new NaatiMockTestDialogueSegment();

            if (!$segment) continue;

            $segment->dialogue_id = $segmentData['dialogue_id'];

            // Segment audio
            if (!empty($segmentData['segment_path']) &&
                $segmentData['segment_path'] instanceof \Illuminate\Http\UploadedFile) {
                if ($segment->segment_path && Storage::disk('public')->exists($segment->segment_path)) {
                    Storage::disk('public')->delete($segment->segment_path);
                }
                $segment->segment_path = $segmentData['segment_path']->store('mocktest-audios', 'public');
            }

            // Sample response
            if (!empty($segmentData['sample_response']) &&
                $segmentData['sample_response'] instanceof \Illuminate\Http\UploadedFile) {
                if ($segment->sample_response && Storage::disk('public')->exists($segment->sample_response)) {
                    Storage::disk('public')->delete($segment->sample_response);
                }
                $segment->sample_response = $segmentData['sample_response']->store('mocktest-audios/sample-responses', 'public');
            }

            // Answers
            $segment->answer_eng = $segmentData['answer_eng'] ?? null;
            $segment->answer_other_language = $segmentData['answer_second_language'] ?? null;

            $segment->save();
        }
    }

    // ✅ Handle deleted segments
    if ($request->has('deleted_segments')) {
    foreach ($request->deleted_segments as $deletedId) {
        $segment = NaatiMockTestDialogueSegment::find($deletedId);
        if ($segment) {
            // delete files if exist
            if ($segment->segment_path) Storage::delete('public/'.$segment->segment_path);
            if ($segment->sample_response) Storage::delete('public/'.$segment->sample_response);
            $segment->delete();
                }
            }
        }

    return redirect()->back()->with('success', 'Mock Test updated successfully!');
}









}
