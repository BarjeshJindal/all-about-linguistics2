<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
 
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
        return view('admin.dashboard');
    }
 
    // Logout admin
    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect('/admin/login');
    }
}