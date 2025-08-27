<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Label;
use Illuminate\Database\Seeder;

class LabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
    {
        $labels = [
            ['name' => 'Cancel', 'color' => 'secondary'],
            ['name' => "Don't know at All", 'color' => 'danger'],
            ['name' => 'Struggled', 'color' => 'warning'],
            ['name' => 'Try My Best', 'color' => 'primary'],
            ['name' => 'Very Confident', 'color' => 'success'],
        ];

        foreach ($labels as $label) {
            Label::firstOrCreate(['name' => $label['name']], ['color' => $label['color']]);
        }
    }
}
