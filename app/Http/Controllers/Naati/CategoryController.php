<?php

namespace App\Http\Controllers\Naati;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NaatiCategory;
use App\Models\NaatiVocabularyCategory;
class CategoryController extends Controller
{
    public function index(){
        $categories= NaatiCategory::get();
        return view('admin.category.category',compact('categories'));
    }

    public function store(Request $request){
        $data = $request->validate(
                            [
                                'name' => 'required|unique:naati_categories,name',
                            ],
                            [
                                'name.required' => 'Please enter category name',
                                'name.unique'   => 'This category already exists',
                            ]
                        );
        
       $category= NaatiCategory::create($data);

       if($category){
        NaatiVocabularyCategory::create([
                'category_id'=>$category->id,
                'words_count'=>0
                ]);
       }
       return back()->with('success', 'New Category Added Successfully');
    }
}

