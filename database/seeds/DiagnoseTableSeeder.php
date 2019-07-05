<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DiagnoseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('diagnosis')->delete();

        DB::table('diagnosis')->insert([[
            	'title' => 'Diagnosis 1',
            	'is_blocked' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
        	],[
        		'title' => 'Diagnosis 2',
        		'is_blocked' => 0,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
        	]
    	]); 
    }
}
