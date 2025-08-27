<?php

namespace App\Http\Controllers\Naati;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use App\Models\Language;

class RoutingController extends Controller
{
    public function index(Request $request)
    {
        return redirect('index');
    }

    /**
     * Display a view based on first route param
     *
     * @return \Illuminate\Http\Response
     */
    public function root(Request $request, $first)
    {
        return view($first);
    }
   public function login(){
    return view('auth.login', ['isAdmin' => false]);
   }
     public function register(){
        $languages=Language::get();

        // dd($languages);
    return view('auth.register',['languages'=>$languages]);
   }
    /**
     * second level route
     */
    public function secondLevel(Request $request, $first, $second)
    {
        return view($first . '.' . $second);
    }

    /**
     * third level route
     */
    public function thirdLevel(Request $request, $first, $second, $third)
    {
        return view($first . '.' . $second . '.' . $third);
    }
}
