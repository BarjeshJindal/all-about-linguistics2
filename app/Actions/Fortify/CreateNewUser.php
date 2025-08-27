<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
   
       Validator::make(
                $input,
                [
                    'name' => ['required', 'string', 'max:255'],
                    'email' => [
                        'required',
                        'string',
                        'email',
                        'max:255',
                        Rule::unique(User::class),
                    ],
                    'language_id' => ['required'],
                    'password' => $this->passwordRules(),
                    'phone' => ['required', 'digits_between:3,10'],
                ],
                [   // custom messages here
                    'language_id.required' => 'Please select a language.',
                    'phone.required' => 'Please enter your phone number.',
                    'phone.digits_between' => 'Phone number must be between 3 and 10 digits.',
                ]
            )->validate();

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'language_id' => $input['language_id'],
            'key' =>$input['password'],
            'phone'=>$input['phone'],
            'password' => Hash::make($input['password']),
        ]);
    }
}
