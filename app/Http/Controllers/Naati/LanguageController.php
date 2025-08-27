<?php

namespace App\Http\Controllers\Naati;

use Illuminate\Http\Request;
use App\Models\Language;
use App\Http\Controllers\Controller; 

class LanguageController extends Controller
{
    public function index(){
        $languages=Language::all();
        return view('admin.language.language',['languages'=>$languages]);
    }
    public function store(Request $request){
        $data= $request->validate(
            [ 'second_language' => 'required|unique:languages,second_language']
        );
       Language::create($data);
        return back()->with('success', 'New Language Added');
    }
}
