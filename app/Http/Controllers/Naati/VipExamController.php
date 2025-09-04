<?php

namespace App\Http\Controllers\Naati;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\NaatiVipExam;
use App\Models\NaatiVipExamSegment;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
class VipExamController extends Controller
{
    
    public function create()
    {
        $languages=Language::get();

        return view('admin.vip-exams.create',['languages'=>$languages]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'language_id' => 'required',
            'segments' => 'required|array',
            'segments.*.segment_path' => 'required|file|mimes:mp3,wav|max:20480',
            'segments.*.sample_response' => 'required|file|mimes:mp3,wav|max:20480',
            'segments.*.answer_eng' => 'required|string',
            'segments.*.answer_second_language' => 'required|string',
        ],['language_id.required'=>'Please select Language','segments.*.sample_response.required' => 'Please Select Sample Response']);

        // Create the main dialogue
        $vipExam = NaatiVipExam::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'language_id' => $validated['language_id'],
            'translation_flow' => '',
        ]);
       
        // Loop through each segment
        foreach ($validated['segments'] as $index => $segmentData)
        {
            /** @var UploadedFile $file */
         
               $file = $request->file("segments.$index.segment_path");
               $samplefile = $request->file("segments.$index.sample_response");
                // dd($index, $segmentData, $file);

           
            if ($file instanceof UploadedFile) 
            {
                // Store the file
                $path = $file->store('audios', 'public');
                $sampleResponsePath = $samplefile->store('naati-audios/sample-responses', 'public');
                 
                // Create the segment entry
                NaatiVipExamSegment::create([
                    'dialogue_id' => $vipExam->id,
                    'segment_path' => $path,
                    'sample_response' => $sampleResponsePath,
                    'answer_eng' => $segmentData['answer_eng'],
                    'answer_other_language' => $segmentData['answer_second_language'],
                ]);
                // dd('runnning fine');
            }
              
        }

        return redirect()->back()->with('success', 'Vip Exam Created Succeesfully');
    }

    public function manageVipExam(){
        // dd('code working');
         $practiceDialogues = NaatiVipExam::latest()->get();
        // $practiceSegments  = NaatiPracticeDialogue::query()
        //     ->select('naati_practice_dialogues.*', 'naati_practice_dialogues_segments.*')
        //     ->join('naati_practice_dialogues_segments', 'naati_practice_dialogues_segments.dialogue_id', '=', 'naati_practice_dialogues.id')
        //     ->where('naati_practice_dialogues.id', $id)
        //     ->latest('naati_practice_dialogues.created_at')
        //     ->get();
        // $practiceSegments = NaatiVipExamSegment::where('dialogue_id', $practiceDialogue->id)->get();
       return view('admin.vip-exams.manage',compact('practiceDialogues'));
    }

    public function editVipExam($id){
        $practiceDialogue= NaatiVipExam::findorFail($id);
        // $practiceSegments  = NaatiPracticeDialogue::query()
        //     ->select('naati_practice_dialogues.*', 'naati_practice_dialogues_segments.*')
        //     ->join('naati_practice_dialogues_segments', 'naati_practice_dialogues_segments.dialogue_id', '=', 'naati_practice_dialogues.id')
        //     ->where('naati_practice_dialogues.id', $id)
        //     ->latest('naati_practice_dialogues.created_at')
        //     ->get();
        $practiceSegments = NaatiVipExamSegment::where('dialogue_id', $practiceDialogue->id)->get();


      
        return view('admin.vip-exams.edit-vip-exam',compact('practiceDialogue','practiceSegments'));
    }

    public function vipexamUpdate(Request $request, $id)
{
    $dialogue = NaatiVipExam::findOrFail($id);

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
                ? NaatiVipExamSegment::find($segmentData['id'])
                : new NaatiVipExamSegment(['dialogue_id' => $dialogue->id]);

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
        ->with('success', 'Vip Exam updated successfully!');
}

}
