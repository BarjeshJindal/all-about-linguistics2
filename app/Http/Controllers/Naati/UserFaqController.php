<?php

namespace App\Http\Controllers\Naati;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NaatiFaq;
class UserFaqController extends Controller
{
    public function showFaqs(){
        $faqs = NaatiFaq::all();

        // Split into two columns
        $half = ceil($faqs->count() / 2);
        $leftFaqs = $faqs->slice(0, $half);
        $rightFaqs = $faqs->slice($half);
      return view('naati.users.faq' ,compact('leftFaqs', 'rightFaqs','faqs'));
    }
}
