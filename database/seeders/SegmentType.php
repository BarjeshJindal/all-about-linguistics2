<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SegmentType extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $types= [
            ['name'=>'practice_dialogues'],
            ['name'=>'mock_tests']
            

        ];
        foreach($types as $type){
             DB::table('segment_types')->updateOrInsert($type);
        }
    }
}
