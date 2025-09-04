<?php

namespace App\Http\Controllers\Naati;

use App\Models\Practice;
use App\Models\Language;
use Illuminate\Http\Request;
use App\Models\UserRecording;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller; 
use App\Models\NaatiPracticeDialogue;
use App\Models\NaatiPracticeDialoguesSegment;
use Illuminate\Http\UploadedFile;
use App\Models\NaatiCategory;
use Illuminate\Support\Facades\Storage;

class PracticeDialogueController extends Controller
{
    // public function index()
    // {
    //     $practices = Practice::all();
    //     $userId = Auth::id();
    //     $userRecordings = UserRecording::where('user_id', $userId)->get();
      
    //     $awaitingReviews = $userRecordings->filter(function ($recording) {
    //         return is_null($recording->feedback) && is_null($recording->score);
    //     });
    //     $reviewed = $userRecordings->filter(function ($recording) {
    //         return !is_null($recording->feedback) || !is_null($recording->score);
    //     });
    //     // dd($userRecordings);
    //     return view('admin.practices.index', [
    //         'practices' => $practices,
            
    //         'awaitingReviews' => $awaitingReviews,
    //         'reviewed' => $reviewed,
    //     ]);
    // }


    public function create()
    {
        $languages=Language::get();
        $categories = NaatiCategory::get();

        return view('admin.practice-dialogues.create',['languages'=>$languages,'categories'=>$categories]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'language_id' => 'required|integer',
            'category_id' => 'required|integer',
            'segments' => 'required|array',
            'segments.*.segment_path' => 'required|file|mimes:mp3,wav|max:20480',
            'segments.*.sample_response' => 'required|file|mimes:mp3,wav|max:20480',            
            'segments.*.answer_eng' => 'required|string',
            'segments.*.answer_second_language' => 'required|string',
            ],['category_id.required'=>'Please Select Category',
            'language_id.required'=>'Please Select Language',
            'category_id.integer' => 'Please Select Category',
            'segments.*.sample_response.required' => 'Please Select Sample Response']);

        // Create the main dialogue
        $practiceDialogue = NaatiPracticeDialogue::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'language_id' => $validated['language_id'],
            'translation_flow' => '',
            'category_id'=>$validated['category_id'],
        ]);

        // Loop through each segment
        foreach ($validated['segments'] as $index => $segmentData) {
            /** @var UploadedFile $file */
            $file = $request->file("segments.$index.segment_path");
            $samplefile = $request->file("segments.$index.sample_response");

            if ($file instanceof UploadedFile) {
                // Store the file
                $path = $file->store('audios', 'public');
                $sampleResponsePath = $samplefile->store('naati-audios/sample-responses', 'public');

                // Create the segment entry
                NaatiPracticeDialoguesSegment::create([
                    'dialogue_id' => $practiceDialogue->id,
                    'segment_path' => $path,
                    'sample_response' => $sampleResponsePath,
                    'answer_eng' => $segmentData['answer_eng'],
                    'answer_other_language' => $segmentData['answer_second_language'],
                ]);
            }
        }

        return back()->with('success', 'Practice Dialogue Added');
    }


    // public static function saveSegments(array $segments, int $practiceId, int $segmentTypeId)
    // {

        
    // }
    public function manageView(){
        $practiceDialogues = NaatiPracticeDialogue::latest()->get();
        // dd('code running');
        return view('admin.practice-dialogues.list',compact('practiceDialogues'));
    }
   
    public function practiceDialogueEdit($id){
        $practiceDialogue= NaatiPracticeDialogue::findorFail($id);
        // $practiceSegments  = NaatiPracticeDialogue::query()
        //     ->select('naati_practice_dialogues.*', 'naati_practice_dialogues_segments.*')
        //     ->join('naati_practice_dialogues_segments', 'naati_practice_dialogues_segments.dialogue_id', '=', 'naati_practice_dialogues.id')
        //     ->where('naati_practice_dialogues.id', $id)
        //     ->latest('naati_practice_dialogues.created_at')
        //     ->get();
        $practiceSegments = NaatiPracticeDialoguesSegment::where('dialogue_id', $practiceDialogue->id)->get();


      
        return view('admin.practice-dialogues.edit-practice-dialogue',compact('practiceDialogue','practiceSegments'));

    }
   


    public function practiceDialogueUpdate(Request $request, $id)
{
    $dialogue = NaatiPracticeDialogue::findOrFail($id);

    // ✅ Update main dialogue
    $dialogue->update([
        'title'       => $request->title,
        'description' => $request->description,
    ]);

    // ✅ Handle segments
    if ($request->has('segments')) {
        foreach ($request->segments as $index => $segmentData) {

            // If `id` exists → update, otherwise → create
            $segment = !empty($segmentData['id'])
                ? NaatiPracticeDialoguesSegment::find($segmentData['id'])
                : new NaatiPracticeDialoguesSegment(['dialogue_id' => $dialogue->id]);

            if (!$segment) {
                continue; // skip invalid
            }

            // Handle audio file (segment_path)
            if (isset($segmentData['segment_path']) && $segmentData['segment_path'] instanceof \Illuminate\Http\UploadedFile) {
                if ($segment->segment_path && Storage::disk('public')->exists($segment->segment_path)) {
                    Storage::disk('public')->delete($segment->segment_path);
                }
                $segment->segment_path = $segmentData['segment_path']->store('audios', 'public');
            }

            // Handle sample_response file
            if (isset($segmentData['sample_response']) && $segmentData['sample_response'] instanceof \Illuminate\Http\UploadedFile) {
                if ($segment->sample_response && Storage::disk('public')->exists($segment->sample_response)) {
                    Storage::disk('public')->delete($segment->sample_response);
                }
                $segment->sample_response = $segmentData['sample_response']->store('naati-audios/sample-responses', 'public');
            }

            // ✅ Update texts
            $segment->answer_eng            = $segmentData['answer_eng'] ?? $segment->answer_eng;
            $segment->answer_other_language = $segmentData['answer_second_language'] ?? $segment->answer_other_language;

            $segment->save();
        }
    }

    return redirect()
        ->back()
        ->with('success', 'Dialogue updated successfully!');
}





   
}
