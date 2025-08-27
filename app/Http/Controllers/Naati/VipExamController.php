<?php

namespace App\Http\Controllers\Naati;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\NaatiVipExam;
use App\Models\NaatiVipExamSegment;
use Illuminate\Http\UploadedFile;

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

}
