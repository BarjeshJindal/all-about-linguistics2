<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\NaatiPracticeDialogue;
use App\Models\NaatiCategory;
use App\Models\NaatiVipExam;
use App\Models\NaatiMockTest;
use App\Models\NaatiVocabularyWord;
use DB;
class AdminController extends Controller
{
    // Show the admin login page
    public function showLoginForm()
    {
        return view('auth.login', ['isAdmin' => true]);
    }
 
    // Handle admin login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
 
        if (Auth::guard('admin')->attempt($credentials)) {
           
            return redirect()->route('admin.dashboard');
        }
 
        return back()->withErrors(['email' => 'Invalid admin credentials']);
    }
 
    // Admin dashboard
    public function dashboard()
    {
        $practiceDialogues =NaatiPracticeDialogue::get();
           $categories = DB::table('naati_categories as c')
                        ->leftJoin('naati_practice_dialogues as d', 'c.id', '=', 'd.category_id')
                        ->select('c.id', 'c.name', DB::raw('COUNT(d.id) as dialogues_count'))
                        ->groupBy('c.id', 'c.name')
                        ->get();
            $vipexamCount =NaatiVipExam::select('id')->count();  
            $mocktestCount =NaatiMockTest::select('id')->count();            
            $totalPracticeDialogue = $categories->sum('dialogues_count');
            $total_words =  NaatiVocabularyWord::select('id')->count();   
        return view('admin.dashboard',compact('categories','practiceDialogues','totalPracticeDialogue','mocktestCount','vipexamCount','total_words'));
    }
 
    // Logout admin
    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect('/admin/login');
    }
}