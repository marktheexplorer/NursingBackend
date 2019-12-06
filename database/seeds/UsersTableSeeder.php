<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();

        DB::table('users')->insert([[
                'role_id' => '1',
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'country_code' => '+91',
                'mobile_number' => '999999909',
                'password' => bcrypt('secret'),
                'created_at' => Carbon::now(),
            ]              
        ]);
    }
}
