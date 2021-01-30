<?php

use App\Credentials;
use Illuminate\Database\Seeder;

class CredentialsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Credentials::create([
	        'id' => 1,
	        'title' => 'First Aid & CPR',
	        'sign_date' => date('y-m-d h:m:s') 
      	]);

      	Credentials::create([
	        'id' => 2,
	        'title' => 'Food Handing  and Safety',
	        'sign_date' => date('y-m-d h:m:s') 
      	]);

      	Credentials::create([
	        'id' => 3,
	        'title' => 'Orientation & Safety',
	        'sign_date' => date('y-m-d h:m:s') 
      	]);

      	Credentials::create([
	        'id' => 4,
	        'title' => 'HIV/AIDS',
	        'sign_date' => date('y-m-d h:m:s') 
      	]);

      	Credentials::create([
	        'id' => 5,
	        'title' => '75 Hours Long Term Care Worker Basic Training',
	        'sign_date' => date('y-m-d h:m:s') 
      	]);

      	Credentials::create([
	        'id' => 6,
	        'title' => 'Mental Health',
	        'sign_date' => date('y-m-d h:m:s') 
      	]);

      	Credentials::create([
	        'id' => 7,
	        'title' => 'Dementia',
	        'sign_date' => date('y-m-d h:m:s') 
      	]);

      	Credentials::create([
	        'id' => 8,
	        'title' => 'Nurse Deligation Core',
	        'sign_date' => date('y-m-d h:m:s') 
      	]);

      	Credentials::create([
	        'id' => 9,
	        'title' => 'Nurse Delegation Diabetes',
	        'sign_date' => date('y-m-d h:m:s') 
      	]);

      	Credentials::create([
	        'id' => 10,
	        'title' => 'AFH Administrator Training',
	        'sign_date' => date('y-m-d h:m:s') 
      	]);

      	Credentials::create([
	        'id' => 11,
	        'title' => 'AFH Orientation Class Prospective Provider',
	        'sign_date' => date('y-m-d h:m:s') 
      	]);

      	Credentials::create([
	        'id' => 12,
	        'title' => 'Continuing Education (CE)',
	        'sign_date' => date('y-m-d h:m:s') 
      	]);
    }
}
