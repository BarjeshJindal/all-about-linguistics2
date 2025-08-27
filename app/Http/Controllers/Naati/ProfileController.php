<?php

namespace App\Http\Controllers\Naati;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Language;
use Auth;
class ProfileController extends Controller
{
    public function editProfile()
    {
      $userId = Auth::id(); // get logged in user's id
      $user = User::findOrFail($userId); // fetch user by id
     
      $language= Language::where('id',$user->language_id)->value('second_language');
    
      return view('naati.users.profile.edit', compact('user','language'));
    }

    public function updateProfile(Request $request)
    {
      $userId = Auth::id(); // get logged in user's id
      $user = User::findOrFail($userId); // fetch user by id
       
      $data=  $request->validate([
            'name'     => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $userId,
            'phone' => ['required', 'digits_between:3,10'],
            // 'password' => 'required|string|min:6|confirmed',
            // 'role'     => 'required'
             ],
            [
              'phone.required' => 'Please enter your phone number.',
              'phone.digits_between' => 'Phone number must be between 3 and 10 digits.',
             ]);
      
      $user['name'] = $data['name']; 
      $user['email'] =$data['email'];
      $user['phone'] =$data['phone'];
      $user->save();

      return redirect()->back()->with('success',"User Updated Successfully");
              
        
      
    }

}
