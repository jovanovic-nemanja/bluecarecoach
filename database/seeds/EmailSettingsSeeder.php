<?php

use App\EmailSettings;
use Illuminate\Database\Seeder;

class EmailSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        EmailSettings::create([
	        'type' => 1,
	        'from_address' => 'core.solutions06@gmail.com',
            'from_title' => 'Bluely Credentials',
            'subject' => 'Verify your email for Bluely Credentials',
            'content_name' => 'Welcome User,',
            'content_body' => '',
            'pre_footer' => '',
            'footer' => '',
	        'sign_date' => date('y-m-d h:m:s') 
      	]);

        EmailSettings::create([
	        'type' => 2,
	        'from_address' => 'core.solutions06@gmail.com',
            'from_title' => 'Bluely Credentials',
            'subject' => 'Bluely Credentials : Reset Password',
            'content_name' => 'User!',
            'content_body' => 'You are receiving this email because we received a password reset request for your account.',
            'pre_footer' => 'If you did not request a password reset, no further action is required.<br>Best Regards.<br>BLUELY.',
            'footer' => '',
	        'sign_date' => date('y-m-d h:m:s') 
      	]);

        EmailSettings::create([
	        'type' => 3,
	        'from_address' => 'core.solutions06@gmail.com',
            'from_title' => 'Bluely Credentials',
            'subject' => 'Bluely Credentials : Actived the status of looking for job.',
            'content_name' => '',
            'content_body' => '',
            'pre_footer' => '',
            'footer' => '',
	        'sign_date' => date('y-m-d h:m:s') 
      	]);

        EmailSettings::create([
	        'type' => 4,
	        'from_address' => 'core.solutions06@gmail.com',
            'from_title' => 'Bluely Credentials',
            'subject' => 'Please check and update your credential document. It can be expire in 1 month.',
            'content_name' => '',
            'content_body' => '',
            'pre_footer' => '',
            'footer' => '',
	        'sign_date' => date('y-m-d h:m:s') 
      	]);

        EmailSettings::create([
	        'type' => 5,
	        'from_address' => 'core.solutions06@gmail.com',
            'from_title' => 'Bluely Credentials',
            'subject' => 'Please check and update your credential document. It can be expire in 1 month.',
            'content_name' => '',
            'content_body' => '',
            'pre_footer' => '',
            'footer' => '',
	        'sign_date' => date('y-m-d h:m:s') 
      	]);
    }
}
