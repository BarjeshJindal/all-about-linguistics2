<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\AdminSeeder;
use Database\Seeders\SegmentType;
use Database\Seeders\PracticeSeeder;
use Database\Seeders\LanguageSeeder;
use Database\Seeders\LabelSeeder;
use Database\Seeders\RolePermissionSeeder;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

    //      $this->call([
    //     TestsSeeder::class,
    // ])
    $this->call([
            AdminSeeder::class,
            UserSeeder::class,
            SegmentType::class, 
            LanguageSeeder::class,
            LabelSeeder::class,
            RolePermissionSeeder::class
            
        ]);

    }
}
