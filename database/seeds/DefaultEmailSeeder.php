<?php

use App\DefaultEmail;
use Illuminate\Database\Seeder;

class DefaultEmailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DefaultEmail::create([
            'address' => 'core.solutions06@gmail.com',
            'sign_date' => date('y-m-d h:m:s')
        ]);
    }
}
