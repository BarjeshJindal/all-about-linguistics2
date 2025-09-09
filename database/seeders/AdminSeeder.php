<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::updateOrCreate(
            ['email' => 'allaboutlinguistics@gmail.com'],
            [
                'name' => 'admin',
                'password' => Hash::make('allaboutlinguistics@123##'), // Change to a secure password
            ]
        );
    }
}
