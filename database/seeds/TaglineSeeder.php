<?php

use App\Tagline;
use Illuminate\Database\Seeder;

class TaglineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Tagline::create([
            'id' => 1,
            'descrption' => 'Standing with Frontline Caregivers',
            'type' => 1,    //1: homescreen, 2: profile screen
            'sign_date' => date('y-m-d h:m:s') 
        ]);
    }
}
