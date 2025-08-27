<?php

namespace App\Http\Controllers\Naati;

use App\Models\Practice;
use App\Models\Segment;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use App\Http\Controllers\Controller; 

class SegmentController extends Controller
{
   public function index(Practice $practice)
    {
        $practice->load('segments');

        $segmentId = request()->query('segment_id');
        $segment = $practice->segments->firstWhere('id', $segmentId) ?? $practice->segments->first();

        return view('admin.segments.index', [
            'practice' => $practice,
            'segment' => $segment,
        ]);
    }

    public function create(Practice $practice)
    {
        return view('admin.segments.create', ['practice' => $practice]);
    }

    public function store(Request $request, Practice $practice)
    {


        $request->validate([
            'title' => 'required|string|max:255',
            'answer_eng' => 'required|string|max:255',
            'answer_second_language' => 'required|string|max:255',
            'segment_path' => 'required|file|mimes:mp3,wav|max:20480', // up to 20MB
        ]);


        $segmentPath = $request->file('segment_path')->store('audios', 'public');

        Segment::create([
            'title' => $request->title,
            'answer_eng' => $request->answer_eng,
            'answer_second_language' => $request->answer_second_language,
            'practice_id' => $practice->id,
            'segment_parent_id' => $request->segment_parent_id,

            'segment_path' => $segmentPath,
        ]);
        return redirect()->route('admin.segments.index')->with('success', 'Segment Uploaded Successfully ');
        // return redirect()->route('dialogues.index')->with('success', 'Dialogue uploaded successfully.');
    }

    public static function saveSegments(array $segments, int $practiceId, int $segmentTypeId)
    {

        foreach ($segments as $segmentData) {
            // Make sure we actually have an UploadedFile object
            // try {
                if (
                    isset($segmentData['segment_path']) &&
                    $segmentData['segment_path'] instanceof UploadedFile
                ) {
                    // Store the file
                    $path = $segmentData['segment_path']->store('audios', 'public');

                    // Create the segment
                    Segment::create([
                        'segment_parent_id'         => $practiceId,
                        'segment_path'              => $path,
                        'answer_eng'                => $segmentData['answer_eng'],
                        'answer_second_language'    => $segmentData['answer_second_language'],
                        'segment_type_id'           => $segmentTypeId, 
                    ]);



                }
        }
    }


}
