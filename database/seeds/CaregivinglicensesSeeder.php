<?php

use App\Caregivinglicenses;
use Illuminate\Database\Seeder;

class CaregivinglicensesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Caregivinglicenses::create([
	        'id' => 1,
	        'name' => 'Nursing Assistant Registered',
	        'sign_date' => date('y-m-d h:m:s') 
      	]);

      	Caregivinglicenses::create([
	        'id' => 2,
	        'name' => 'Home Care Aid',
	        'sign_date' => date('y-m-d h:m:s') 
      	]);

      	Caregivinglicenses::create([
	        'id' => 3,
	        'name' => 'Nursing Assistance Certified',
	        'sign_date' => date('y-m-d h:m:s') 
      	]);
    }
}
