<?php

namespace App\Http\Controllers\Naati;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\NaatiFaq;
class FaqController extends Controller
{
    public function addFaqs(){
        $faqs = NaatiFaq::latest()->get();
        return view('admin.faqs.add-faqs',compact('faqs'));
    }
    public function faqsList(){
        $faqs = NaatiFaq::latest()->get();
        return view('admin.faqs.faqs-list',compact('faqs'));
    }

    public function storeFaqs(Request $request){
            $data= $request->validate([
                       'question' => 'required|string|unique:naati_faqs,question',
                      'answer'   => 'required|string',
                        ]);

            $faq=NaatiFaq::create($data);
            if($faq){
               return redirect()->back()->with('success','Faq Create Successfully');
            }
            return redirect()->back()->with('error', 'Something went wrong, please try again.');



    }

    //     Route::get('/edit/{id}/faq',[FaqController::class,'faqEdit'])->name('faqs.edit');
    // Route::post('/update-faqs',[FaqController::class,'faqUpdate'])->name('faqs.update');

    public function faqEdit($id){
        $faq = NaatiFaq::findOrFail($id);
        // dd($faq->question);
        return view('admin.faqs.edit-faq', compact('faq'));
    }

     // Update FAQ
    public function faqUpdate(Request $request, $id)
    {
        $faq = NaatiFaq::findOrFail($id);

        $data = $request->validate([
            'question' => 'required|string|unique:naati_faqs,question,' . $faq->id,
            'answer'   => 'required|string',
        ]);

        $faq->update($data);

        return redirect()->back()->with('success', 'FAQ updated successfully.');
    }

    public function faqDelete($id)
    {
        $faq = NaatiFaq::findOrFail($id);
        $faq->delete();

        return redirect()->back()->with('success', 'FAQ deleted successfully.');
    }

}
