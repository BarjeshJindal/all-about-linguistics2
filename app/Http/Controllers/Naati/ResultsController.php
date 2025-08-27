<?php

namespace App\Http\Controllers\Naati;

use Illuminate\Http\Request;
use App\Models\UserRecording;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller; 

class ResultsController extends Controller
{
   public function index()
{
    $userId = Auth::id(); // Correct variable assignment
    $user_recordings = UserRecording::where('user_id', $userId)->get();

    return view('naati.users.results.index', compact('user_recordings'));
}


}
