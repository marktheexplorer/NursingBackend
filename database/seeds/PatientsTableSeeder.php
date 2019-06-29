<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PatientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('patients')->delete();

        DB::table('patients')->insert([[
                'role_id' => '2',
                'name' => Str::random(10),
                'email' => Str::random(5).'@gmail.com',
                'mobile_number' => '999999909',
                'location' => 'Saket',
                'country' => 'India',
                'zip_code' => '110030',
                'password' => bcrypt('123456'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],[
                'role_id' => '2',
                'name' => Str::random(10),
                'email' => Str::random(5).'@gmail.com',
                'mobile_number' => '999999900',
                'location' => 'Saket',
                'country' => 'India',
                'zip_code' => '110030',
                'password' => bcrypt('123456'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],[
                'role_id' => '2',
                'name' => Str::random(10),
                'email' => Str::random(5).'@gmail.com',
                'mobile_number' => '999999908',
                'location' => 'Saket',
                'country' => 'India',
                'zip_code' => '110030',
                'password' => bcrypt('123456'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],[
                'role_id' => '2',
                'name' => Str::random(10),
                'email' => Str::random(5).'@gmail.com',
                'mobile_number' => '999999907',
                'location' => 'Saket',
                'country' => 'India',
                'zip_code' => '110030',
                'password' => bcrypt('123456'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);
    }
}
