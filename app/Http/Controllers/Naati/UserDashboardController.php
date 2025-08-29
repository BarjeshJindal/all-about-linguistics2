<?php

namespace App\Http\Controllers\Naati;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NaatiPracticeDialogue;
use App\Models\NaatiCategory;
use App\Models\User;
use DB;
use App\Models\NaatiVipExam;
use App\Models\NaatiMockTest;
use App\Models\NaatiUserPracticeDialogue;
use App\Models\NaatiUserVipExam;
use App\Models\NaatiVocabularyWord;
use App\Models\NaatiVocabularyUserOpenedWord;
use App\Models\NaatiUserMockTestDialogue;
class UserDashboardController extends Controller
{
    public function dashboard(){
            
            
            $user = Auth::user();
            $userId = $user->id;
            $categories = NaatiCategory::select(
                                'naati_categories.id',
                                'naati_categories.name',
                                DB::raw('COUNT(DISTINCT naati_practice_dialogues.id) as dialogues_count'),
                                DB::raw('COUNT(DISTINCT CASE WHEN naati_user_practice_dialogues.user_id = ' . $userId . ' THEN naati_user_practice_dialogues.dialogue_id END) as completed_dialogues_count'),
                                DB::raw('ROUND(
                                    (COUNT(DISTINCT CASE WHEN naati_user_practice_dialogues.user_id = ' . $userId . ' THEN naati_user_practice_dialogues.dialogue_id END) 
                                    / NULLIF(COUNT(DISTINCT naati_practice_dialogues.id), 0)) * 100, 2
                                ) as completion_percentage')
                            )
                            ->leftJoin('naati_practice_dialogues', 'naati_categories.id', '=', 'naati_practice_dialogues.category_id')
                            ->leftJoin('naati_user_practice_dialogues', 'naati_user_practice_dialogues.dialogue_id', '=', 'naati_practice_dialogues.id')
                            ->groupBy('naati_categories.id', 'naati_categories.name')
                            ->get();

            $practicedialogueCount =NaatiPracticeDialogue::select('id')->count();
            $vipexamCount =NaatiVipExam::select('id')->count();  
            $mocktestCount =NaatiMockTest::select('id')->count();
            $completedvipexam=NaatiUserVipExam::distinct('dialogue_id')->count('dialogue_id');
            
            $completedPracticeDialogue =NaatiUserPracticeDialogue::distinct('dialogue_id')->count('dialogue_id');
            $completedMockTest =NaatiUserMockTestDialogue::distinct('dialogue_id')->count('dialogue_id');
             // CCL words card 
            $total_words =  NaatiVocabularyWord::where('language_id',$user->language_id)->count(); 
            $total_words_opened = NaatiVocabularyUserOpenedWord::where('user_id',$user->id)->count(); 
            // $practicecount =NaatiPracticeDialogue::select('id')->count();
           
       return view('users.index',compact('categories','completedMockTest','practicedialogueCount','vipexamCount','mocktestCount','completedPracticeDialogue','completedvipexam','total_words','total_words_opened'));
    }
}
