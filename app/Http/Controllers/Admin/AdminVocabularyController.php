<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NaatiVocabularyCategory;
use App\Models\NaatiVocabularyWord;
use App\Models\NaatiCategory;
use App\Models\Language;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AdminVocabularyController extends Controller
{
    
    public function addVocabulary(){
        $categories = NaatiCategory::select(
                'naati_categories.id',
                'naati_categories.name',
                'naati_vocabulary_categories.words_count'
            )
            ->leftJoin(
                'naati_vocabulary_categories',
                'naati_categories.id',
                '=',
                'naati_vocabulary_categories.category_id'
            )
            ->orderBy('naati_categories.name')
            ->get();
        return view('admin.vocabulary.category',compact('categories'));
    }

    public function storeVocabulary(Request $request){
        
        $data=$request->validate
                ([
                'name'=>'required|string|regex:/^[A-Za-z\s]+$/',

                ],
                ['name.regex'=>'Category must be alphabets only']
                    );

        NaatiVocabularyCategory::create($data);

        return  redirect()->back()->with('success','New Category Created');
    
    }

    public function addWords(){
        //   $words = Word::where('category_id',$category->id)->get();
            $categories = NaatiVocabularyCategory::select(
                            'naati_vocabulary_categories.*',
                            'naati_categories.name as name'
                        )
                        ->leftJoin('naati_categories', 'naati_vocabulary_categories.category_id', '=', 'naati_categories.id')
                        ->get();
            $languages = Language::all();
        return view('admin.vocabulary.words', compact('categories','languages'));
    }

    public function storeWord(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:naati_vocabulary_categories,id',
            'language_id' => 'required|exists:languages,id',
            'file' => 'required|mimes:xlsx,xls'
        ]);

        $categoryId = $request->category_id;
        $languageId = $request->language_id;
        $file = $request->file('file');

        $insertedCount = 0;
        $skippedWords = [];
        $invalidRows = [];

        // read all sheets into array
        $sheets = Excel::toArray([], $file);

        // take first sheet
        $rows = $sheets[0] ?? [];

        if (!empty($rows)) {
            // first row as header
            $header = array_map('strtolower', array_map('trim', $rows[0]));

            foreach (array_slice($rows, 1) as $row) {
                if (count(array_filter($row, fn($val) => trim($val) !== '')) === 0) {
                    continue; // empty row
                }

                if (count($row) !== count($header)) {
                    $invalidRows[] = implode(' | ', $row);
                    continue;
                }

                $data = array_combine($header, $row);

                $wordText = isset($data['word']) ? trim($data['word']) : null;
                $meaningText = isset($data['meaning']) ? trim($data['meaning']) : null;

                if (!$wordText || !$meaningText) {
                    $invalidRows[] = implode(' | ', $row);
                    continue;
                }

                $exists = NaatiVocabularyWord::where('category_id', $categoryId)
                    ->where('language_id', $languageId)
                    ->whereRaw('LOWER(word) = ?', [strtolower($wordText)])
                    ->exists();

                if ($exists) {
                    $skippedWords[] = $wordText;
                    continue;
                }

                NaatiVocabularyWord::create([
                    'word'        => $wordText,
                    'meaning'     => $meaningText,
                    'category_id' => $categoryId,
                    'language_id' => $languageId,
                ]);

                $insertedCount++;
            }

            if ($insertedCount > 0) {
                NaatiVocabularyCategory::where('id', $categoryId)
                    ->increment('words_count', $insertedCount);
            }
        }

        $summary = "$insertedCount new words imported successfully!";
        if (!empty($skippedWords)) {
            $summary .= ' | Skipped duplicates: ' . count($skippedWords);
        }
        if (!empty($invalidRows)) {
            $summary .= ' | Invalid rows: ' . count($invalidRows);
        }

        $alertType = 'success';
        if ($insertedCount === 0 && (!empty($skippedWords) || !empty($invalidRows))) {
            $alertType = 'warning';
        }
        if ($insertedCount === 0 && empty($skippedWords) && empty($invalidRows)) {
            $alertType = 'danger';
        }

        return redirect()->back()->with([
            'flash_type' => $alertType,
            'flash_message' => $summary,
            'skipped_words' => $skippedWords,
            'invalid_rows' => $invalidRows,
        ]);
    }



    public function storesingleWord(Request $request)
    { 
        $data = $request->validate([
                'word' => 'required|string',
                'meaning' => 'required|string',
                'category_id'=>'required',
                'language_id'=>'required'
                    ]);
            
        // Check if the word already exists in the same category & language
        $exists = NaatiVocabularyWord::where('category_id', $data['category_id'])
                    ->where('language_id', $data['language_id'])
                    ->whereRaw('LOWER(word) = ?', [strtolower(trim($data['word']))])
                    ->exists();

        if ($exists) {
            return redirect()->back()->withErrors(['word' => 'This word already exists in this selected category']);
        }
                    


        // $data['category_id'] = $category->id;
        $newWord= NaatiVocabularyWord::create([
                    'word'        => trim($data['word']),
                    'meaning'     => trim($data['meaning']),
                    'category_id' => $data['category_id'],
                    'language_id' => $data['language_id']
                    ]);
        if ($newWord) {
            $category = NaatiVocabularyCategory::findorFail($newWord['category_id']);
            $category->increment('words_count');
        }
        // Increment word count
        

        return redirect()->back()->with([
                                            'flash_type' => 'success',
                                            'flash_message' => 'New word created successfully!',
                                        ]);
                        
    }


    public function wordsList(NaatiVocabularyCategory $category){
        $words = NaatiVocabularyWord::where('category_id',$category->id)->get();
        return view('admin.vocabulary.words-list', compact('words'));
    }

}
