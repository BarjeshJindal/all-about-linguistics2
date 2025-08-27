<?php

namespace Database\Seeders;

use App\Models\Practice;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class PracticeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker=Faker::create();
        foreach(range(1,20) as $index){
            Practice::create([
                'title'=>$faker->sentence(3),
                'description'=>$faker->paragraph(4)
            ]);
        }

    }
}
