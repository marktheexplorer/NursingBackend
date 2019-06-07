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
                'name' => Str::random(10),
                'email' => 'admin@gmail.com',
                'country_code' => '+91',
                'mobile_number' => '999999909',
                'password' => bcrypt('secret'),
                'created_at' => Carbon::now(),
            ],[
                'role_id' => '2',
                'name' => Str::random(10),
                'email' => Str::random(5).'@gmail.com',
                'country_code' => '+91',
                'mobile_number' => '999999808',
                'password' => bcrypt('secret'),
                'created_at' => Carbon::now(),
            ],[
                'role_id' => '2',
                'name' => Str::random(10),
                'email' => Str::random(5).'@gmail.com',
                'country_code' => '+91',
                'mobile_number' => '999999907',
                'password' => bcrypt('secret'),
                'created_at' => Carbon::now(),
            ],[
                'role_id' => '2',
                'name' => Str::random(10),
                'email' => Str::random(5).'@gmail.com',
                'country_code' => '+91',
                'mobile_number' => '999999906',
                'password' => bcrypt('secret'),
                'created_at' => Carbon::now()->subDays(1),
            ],[
                'role_id' => '2',
                'name' => Str::random(10),
                'email' => Str::random(5).'@gmail.com',
                'country_code' => '+91',
                'mobile_number' => '999999905',
                'password' => bcrypt('secret'),
                'created_at' => Carbon::now()->subDays(1),
            ],[
                'role_id' => '2',
                'name' => Str::random(10),
                'email' => Str::random(5).'@gmail.com',
                'country_code' => '+91',
                'mobile_number' => '999999804',
                'password' => bcrypt('secret'),
                'created_at' => Carbon::now()->subDays(1),
            ],[
                'role_id' => '2',
                'name' => Str::random(10),
                'email' => Str::random(5).'@gmail.com',
                'country_code' => '+91',
                'mobile_number' => '999999903',
                'password' => bcrypt('secret'),
                'created_at' => Carbon::now()->subDays(2),
            ],[
                'role_id' => '2',
                'name' => Str::random(10),
                'email' => Str::random(5).'@gmail.com',
                'country_code' => '+91',
                'mobile_number' => '999999902',
                'password' => bcrypt('secret'),
                'created_at' => Carbon::now()->subDays(1),
            ],[
                'role_id' => '2',
                'name' => Str::random(10),
                'email' => Str::random(5).'@gmail.com',
                'country_code' => '+91',
                'mobile_number' => '999999901',
                'password' => bcrypt('secret'),
                'created_at' => Carbon::now()->subDays(1),
            ],[
                'role_id' => '2',
                'name' => Str::random(10),
                'email' => Str::random(5).'@gmail.com',
                'country_code' => '+91',
                'mobile_number' => '999999810',
                'password' => bcrypt('secret'),
                'created_at' => Carbon::now()->subDays(3),
            ],[
                'role_id' => '2',
                'name' => Str::random(10),
                'email' => Str::random(5).'@gmail.com',
                'country_code' => '+91',
                'mobile_number' => '999999977',
                'password' => bcrypt('secret'),
                'created_at' => Carbon::now(),
            ],[
                'role_id' => '2',
                'name' => Str::random(10),
                'email' => Str::random(5).'@gmail.com',
                'country_code' => '+91',
                'mobile_number' => '999999920',
                'password' => bcrypt('secret'),
                'created_at' => Carbon::now()->subDays(3),
            ],[
                'role_id' => '2',
                'name' => Str::random(10),
                'email' => Str::random(5).'@gmail.com',
                'country_code' => '+91',
                'mobile_number' => '9999999033',
                'password' => bcrypt('secret'),
                'created_at' => Carbon::now()->subDays(3),
            ],[
                'role_id' => '2',
                'name' => Str::random(10),
                'email' => Str::random(5).'@gmail.com',
                'country_code' => '+91',
                'mobile_number' => '999999840',
                'password' => bcrypt('secret'),
                'created_at' => Carbon::now()->subDays(3),
            ],[
                'role_id' => '2',
                'name' => Str::random(10),
                'email' => Str::random(5).'@gmail.com',
                'country_code' => '+91',
                'mobile_number' => '99999997712',
                'password' => bcrypt('secret'),
                'created_at' => Carbon::now()->subDays(4),
            ],[
                'role_id' => '2',
                'name' => Str::random(10),
                'email' => Str::random(5).'@gmail.com',
                'country_code' => '+91',
                'mobile_number' => '9999999840',
                'password' => bcrypt('secret'),
                'created_at' => Carbon::now()->subDays(5),
            ],[
                'role_id' => '2',
                'name' => Str::random(10),
                'email' => Str::random(5).'@gmail.com',
                'country_code' => '+91',
                'mobile_number' => '9999999550',
                'password' => bcrypt('secret'),
                'created_at' => Carbon::now()->subDays(6),
            ],
        ]);
    }
}
