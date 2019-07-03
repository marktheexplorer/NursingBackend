<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class QualificationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('qualifications')->delete();

        DB::table('qualifications')->insert([[
            	'name' => 'Associate of Science in Nursing',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
        	],[
        		'name' => 'Bachelor of Science in Nursing',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
        	]
    	]); 
    }
}
