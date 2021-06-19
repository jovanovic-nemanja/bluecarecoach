<?php

use Illuminate\Database\Seeder;
use App\GeneralSetting;
use App\LocalizationSetting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        GeneralSetting::create([
        	'id' => 1,
        	'site_name' => 'Bluecarecoach',
        	'site_title' => 'Bluely Credentials Web',
        	'site_subtitle' => 'Bluely Credentials Document',
        	'site_desc' => 'Bluely Credentials Document',
            'site_footer' => '© Copyright 2021 - Bluely Credentials Development Team. All rights reserved.'
        ]);

        LocalizationSetting::create([
            'id' => 1,
            'language' => 'en',
            'currency' => 'USD',
        ]);
    }
}
