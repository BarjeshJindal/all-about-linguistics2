<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder

{
    public function run(): void
    {
       User::updateOrCreate(
            ['email' => 'student@gmail.com'],
            [
                'name' => 'student',
                'password' => Hash::make('password'), // Change to a secure password
                'language_id'=>'1',
                'key'=>'password',
                'phone'=>'9876543210'
            ],
           
        );
    }
}

