<?php

namespace App\Http\Controllers\Naati;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Auth;
use App\Models\Language;
use App\Models\NaatiVocabularyCategory;
use App\Models\NaatiVocabularyWord;
use App\Models\NaatiVocabularyUserOpenedWord;
use App\Models\NaatiVocabularyUserWord;
use DB;

class VocabularyController extends Controller
{
    public function vocabularyView()
    {
        $user = Auth::user();
         
       
        $languageId = Language::where('id', $user->language_id)->value('id');

        $categories = DB::table('naati_vocabulary_categories')
                            ->leftJoin('naati_categories',function($join){
                                $join->on('naati_vocabulary_categories.category_id','=','naati_categories.id');
                            })
                            
                            ->leftJoin('naati_vocabulary_words', function ($join) use ($languageId) {
                                $join->on('naati_vocabulary_categories.id', '=', 'naati_vocabulary_words.category_id')
                                    ->where('naati_vocabulary_words.language_id', '=', $languageId);
                            })
                            ->leftJoin('naati_vocabulary_user_opened_words as v',function($join) {
                                $join->on('naati_vocabulary_words.id','=','v.word_id')
                                ->where('v.user_id',auth()->id());
                            })
                           ->select(
                                'naati_vocabulary_categories.id',
                                'naati_categories.name as name',
                                DB::raw('COUNT(DISTINCT naati_vocabulary_words.id) as words_count'),
                                DB::raw('COUNT(DISTINCT v.word_id) as viewed_count')
                            )
                            ->groupBy('naati_vocabulary_categories.id', 'naati_categories.name')
                            ->get();
        // CCL words card 
        $ccl_words =  NaatiVocabularyWord::where('language_id',$user->language_id)->count(); 
        $ccl_words_opened = NaatiVocabularyUserOpenedWord::where('user_id',$user->id)->count(); 
        //My words
        $myWords =  NaatiVocabularyUserWord::where('user_id',$user->id)->count(); 
        $myWord_memorized =  NaatiVocabularyUserWord::where('user_id',$user->id)
                                ->where('memorized_count','>',0)
                                ->count(); 



        return view('naati.users.vocabulary.vocabulary', compact('categories','ccl_words','ccl_words_opened','myWords','myWord_memorized'));
    }

    public function wordsView(NaatiVocabularyCategory $category, Request $request)
    {
        $user = Auth::user();

        // Get language id for the user's second language
        $languageId = Language::where('id', $user->language_id)->value('id');

        $categoryName = DB::table('naati_categories')
            ->where('id', $category->category_id)
            ->value('name');


        // Fetch words for this category & language
        $words = DB::table('naati_vocabulary_words as w')
            ->leftJoin('naati_vocabulary_user_opened_words as v', function ($join) {
                $join->on('w.id', '=', 'v.word_id')
                    ->where('v.user_id', auth()->id());
            })
            ->where('w.category_id', $category->id)
            ->where('w.language_id', $languageId)
            ->orderBy('w.id')
            ->select(
                'w.id',
                'w.word',
                'w.meaning',
                DB::raw('COALESCE(v.open_count, 0) as open_count')
            )
            ->get();

        // ✅ Handle translation direction (Native ⇄ English)
        $direction = $request->get('direction', 'native_to_english');

        $words = $words->map(function ($item) use ($direction) {
            if ($direction === 'english_to_native') {
                $temp = $item->word;
                $item->word = $item->meaning;
                $item->meaning = $temp;
            }
            return $item;
        });
        
       return view('naati.users.vocabulary.words', compact('words', 'direction', 'category', 'categoryName'));
    }

  public function incrementView(Request $request)
    {
        $exists = DB::table('naati_vocabulary_user_opened_words')
            ->where('user_id', auth()->id())
            ->where('word_id', $request->word_id)
            ->exists();

        if ($exists) {
            DB::table('naati_vocabulary_user_opened_words')
                ->where('user_id', auth()->id())
                ->where('word_id', $request->word_id)
                ->increment('open_count');
        } else {
            DB::table('naati_vocabulary_user_opened_words')->insert([
                'user_id'   => auth()->id(),
                'word_id'   => $request->word_id,
                'open_count'=> 1,
                'created_at'=> now(),
                'updated_at'=> now(),
            ]);
        }

        // Get the new count
        $count = DB::table('naati_vocabulary_user_opened_words')
            ->where('user_id', auth()->id())
            ->where('word_id', $request->word_id)
            ->value('open_count');

        return response()->json(['count' => $count]);
    }

   public function cclWordsview(Request $request)
    {
        $userId = Auth::id();

        $words = DB::table('naati_vocabulary_words')
            ->leftJoin('naati_vocabulary_user_opened_words', function ($join) use ($userId) {
                $join->on('naati_vocabulary_words.id', '=', 'naati_vocabulary_user_opened_words.word_id')
                    ->where('naati_vocabulary_user_opened_words.user_id', '=', $userId);
            })
            ->select(
                'naati_vocabulary_words.id as word_id',
                'naati_vocabulary_words.word',
                'naati_vocabulary_words.meaning',
                DB::raw('COALESCE(naati_vocabulary_user_opened_words.open_count, 0) as open_count')
            )
            ->get();

        // ✅ Handle translation direction (default = native_to_english)
        $direction = $request->get('direction', 'native_to_english');

        $words = $words->map(function ($item) use ($direction) {
            if ($direction === 'english_to_native') {
                $temp = $item->word;
                $item->word = $item->meaning;
                $item->meaning = $temp;
            }
            return $item;
        });

        return view('naati.users.vocabulary.ccl-words', compact('words', 'direction'));
    }

    
    public function addMyWord(Request $request)
    {
        $user = Auth::user();
        $exists = NaatiVocabularyUserWord::where('user_id',$user->id)
                          ->where('word_id',$request->word_id)
                          ->exists();
        
        if ($exists) 
        {
            return response()->json([
                'success' => false,
                'message' => 'This word is already present in your My Words '
            ]);
        }   
        NaatiVocabularyUserWord::firstOrCreate([
                    'user_id' => $user->id,
                    'word_id' => $request->word_id,
                ]);                       
       
        return response()->json(['success' => true, 'message' => 'Word added successfully!']);                     
    }
    
    public function myWords(Request $request)
    {
        $direction = $request->get('direction', 'native_to_english'); // default

        $words = NaatiVocabularyUserWord::where('user_id', auth()->id())
            ->leftJoin('naati_vocabulary_words', 'naati_vocabulary_user_words.word_id', '=', 'naati_vocabulary_words.id')
            ->select('naati_vocabulary_user_words.*', 'naati_vocabulary_words.word', 'naati_vocabulary_words.meaning')
            ->get()
            ->map(function ($item) use ($direction) {
                if ($direction === 'english_to_native') {
                    // swap word ⇄ meaning
                    $temp = $item->word;
                    $item->word = $item->meaning;
                    $item->meaning = $temp;
                }
                return $item;
            });

        return view('naati.users.vocabulary.my-words', compact('words'));
    }

   public function incrementMemorized(Request $request)
    {
        $request->validate([
            'word_id' => 'required|exists:naati_vocabulary_words,id',
        ]);

        $userWord = NaatiVocabularyUserWord::firstOrCreate(
            [
                'user_id' => auth()->id(),
                'word_id' => $request->word_id,
            ],
            ['memorized_count' => 0]
        );

        $userWord->increment('memorized_count');

        return response()->json(['count' => $userWord->memorized_count]);
    }

    
}
