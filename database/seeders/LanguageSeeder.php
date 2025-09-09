<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Language;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages=['Hindi','Punjabi','Tamil'];
         foreach ($languages as $data) {
           Language::create(['second_language'=>$data]);
        }
    }

}
