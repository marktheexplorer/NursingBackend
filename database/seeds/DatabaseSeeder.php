<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
    	$this->call(RolesTableSeeder::class);
        $this->call(UsersTableSeeder::class);
        $this->call(FaqsTableSeeder::class);
<<<<<<< HEAD
=======
        $this->call(ServiceTableSeeder::class);
        $this->call(PatientsTableSeeder::class);
>>>>>>> 60d78a8db945eda99eff38bc4ebe0291299e0446
    }
}
