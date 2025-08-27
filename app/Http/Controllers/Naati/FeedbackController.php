<?php

namespace App\Http\Controllers\Naati;

use App\Models\UserRecording;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 

class FeedbackController extends Controller
{
    public function index(UserRecording $user_recording)
    {
       $user_recordings = UserRecording::whereNull('score')
        ->whereNull('feedback')
        ->with(['user', 'segment.practice']) 
        ->get();

        return view('admin.feedback.index', ['user_recordings' => $user_recordings]);
    }

    public function update(Request $request, UserRecording $user_recording)
    {
        $validated = $request->validate([
            'score' => 'required|integer|min:0|max:100',
            'feedback' => 'required|string|max:1000',
        ]);

        $user_recording->update($validated);

        return redirect()->back()->with('success', 'Feedback submitted successfully.');
    }
}