<?php

namespace App\Http\Controllers\Naati;

use App\Models\Dialogue;
use App\Models\UserRecording;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller; 

class UserRecordingController extends Controller
{

public function index()
{
    $dialogues = Dialogue::all();
    return view('naati.users.practice.index', compact('dialogues'));
}


   public function store(Request $request)
{

    
    $request->validate([
        'dialogue_id' => 'required|exists:dialogues,id',
        'audio' => 'required|file|mimes:mp3,wav,webm|max:20480',
    ]);

    $path = $request->file('audio')->store('user_responses', 'public');

    UserRecording::create([
        'user_id' => Auth::id(),
        'dialogue_id' => $request->dialogue_id,
        'audio_path' => $path,
    ]);

    return ('Response uploaded successfully!');
}

    public function reviewList()
    {
        $recordings = UserRecording::whereNull('score')->with(['user', 'dialogue'])->get();
        return view('recordings.review', compact('recordings'));
    }

    public function grade(Request $request, UserRecording $recording)
    {
        $request->validate([
            'score' => 'required|integer|min:0|max:100',
            'feedback' => 'nullable|string'
        ]);

        $recording->update([
            'score' => $request->score,
            'feedback' => $request->feedback,
        ]);

        return redirect()->back()->with('success', 'Score submitted.');
    }
}
