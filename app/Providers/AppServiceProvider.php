<?php

namespace App\Providers;

use App;
use Cart;
use App\User;
use App\GeneralSetting;
use App\LocalizationSetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
            
        \View::composer('*', function($view){
            if ($view->getName() != 'admin.category.index') {
 
                $view->with(
                    [
                        'general_setting' => GeneralSetting::first(),
                        'localization_setting' => LocalizationSetting::first(),
                    ]
                );
            }

            $localization_setting = LocalizationSetting::first();
            App::setLocale($localization_setting->language);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
