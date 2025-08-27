<?php

namespace App\Http\Controllers\Naati;

use App\Models\UserRecording;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 

class TeacherController extends Controller
{
   public function index()
{
    $recordings = UserRecording::with('user', 'dialogue')->whereNull('score')->get();
    return view('teacher.reviews', compact('recordings'));
}

public function review(Request $request, $id)
{
     $request->validate([
        'score' => 'required|integer|min:0|max:100',
        'feedback' => 'nullable|string',
    ]);

    $recording = UserRecording::findOrFail($id);
    $recording->update([
        'score' => $request->score,
        'feedback' => $request->feedback,
        'reviewed_at' => now(),
    ]);

    return ('reviewed done');
}
}