<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class TecksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('tecks')->delete();

        DB::table('tecks')->insert([[
        		'user_id' => 2,
                'type' => 'my',
                'title' => Str::random(10),
                'mark_trip' => 'Home',
                'start_location' => 'Sector 45, Gurugram',
                'end_location' => 'Saket metro',
                'start_date' => '06-05-2019',
                'end_date' => '06-05-2019',
                'start_time' => '4:00 PM',
                'end_time' => '8:00 PM',
                'eta' => '1:00',
                'start_lat_lng' => '28.587713,77.0416592',
                'end_lat_lng' => '28.5205465,77.1992641',
                'is_active' => '1',
                'created_at' => Carbon::now(),
            ],[
            	'user_id' => 2,
                'type' => 'my',
                'title' => Str::random(10),
                'mark_trip' => 'Home',
                'start_location' => 'Sector 45, Gurugram',
                'end_location' => 'Saket metro',
                'start_date' => '06-05-2019',
                'end_date' => '06-05-2019',
                'start_time' => '4:00 PM',
                'end_time' => '8:00 PM',
                'eta' => '1:00',
                'start_lat_lng' => '28.587713,77.0416592',
                'end_lat_lng' => '28.5205465,77.1992641',
                'is_active' => '1',
                'created_at' => Carbon::now(),
            ],[
            	'user_id' => 3,
                'type' => 'my',
                'title' => Str::random(10),
                'mark_trip' => 'Work',
                'start_location' => 'Sector 11, Rohini',
                'end_location' => 'Saket metro',
                'start_date' => '06-05-2019',
                'end_date' => '06-05-2019',
                'start_time' => '5:00 PM',
                'end_time' => '10:00 PM',
                'eta' => '00:30',
                'start_lat_lng' => '28.587713,77.0416592',
                'end_lat_lng' => '28.5205465,77.1992641',
                'is_active' => '1',
                'created_at' => Carbon::now(),
            ],[
            	'user_id' => 4,
                'type' => 'my',
                'title' => Str::random(10),
                'mark_trip' => 'Other',
                'start_location' => 'Sector 23, Dwarka',
                'end_location' => 'Ritala metro',
                'start_date' => '06-05-2019',
                'end_date' => '06-05-2019',
                'start_time' => '8:00 AM',
                'end_time' => '10:00 AM',
                'eta' => '00:45',
                'start_lat_lng' => '28.587713,77.0416592',
                'end_lat_lng' => '28.5205465,77.1992641',
                'is_active' => '1',
                'created_at' => Carbon::now(),
            ],[
            	'user_id' => 11,
                'type' => 'Event',
                'title' => Str::random(10),
                'mark_trip' => 'Home',
                'start_location' => 'Sector 45, Gurugram',
                'end_location' => 'Saket metro',
                'start_date' => '06-05-2019',
                'end_date' => '06-05-2019',
                'start_time' => '4:00 PM',
                'end_time' => '8:00 PM',
                'eta' => '1:00',
                'start_lat_lng' => '28.587713,77.0416592',
                'end_lat_lng' => '28.5205465,77.1992641',
                'is_active' => '1',
                'created_at' => Carbon::now(),
            ],[
            	'user_id' => 12,
                'type' => 'my',
                'title' => Str::random(10),
                'mark_trip' => 'Home',
                'start_location' => 'Sector 45, Gurugram',
                'end_location' => 'Saket metro',
                'start_date' => '06-05-2019',
                'end_date' => '06-05-2019',
                'start_time' => '4:00 PM',
                'end_time' => '8:00 PM',
                'eta' => '1:00',
                'start_lat_lng' => '28.587713,77.0416592',
                'end_lat_lng' => '28.5205465,77.1992641',
                'is_active' => '0',
                'created_at' => Carbon::now(),
            ],[
            	'user_id' => 13,
                'type' => 'my',
                'title' => Str::random(10),
                'mark_trip' => 'Home',
                'start_location' => 'Sector 45, Gurugram',
                'end_location' => 'Saket metro',
                'start_date' => '06-05-2019',
                'end_date' => '06-05-2019',
                'start_time' => '4:00 PM',
                'end_time' => '8:00 PM',
                'eta' => '1:00',
                'start_lat_lng' => '28.587713,77.0416592',
                'end_lat_lng' => '28.5205465,77.1992641',
                'is_active' => '0',
                'created_at' => Carbon::now(),
            ]
        ]);
    }
}
