<?php

namespace App\Providers;

use Anhskohbo\NoCaptcha\Facades\NoCaptcha;
use App\Models\AppSetting;
use App\Models\Page;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        // Keep existing default length
        Schema::defaultStringLength(191);

        // Your captcha rule (uses the NoCaptcha alias from config/app.php)
        Validator::extend('captcha', function ($attribute, $value, $parameters, $validator) {
            return NoCaptcha::verifyResponse($value, request()->ip());
        }, 'reCAPTCHA verification failed.');

        // Share $page only if the table exists (prevents errors before migrations)
        View::composer('*', function ($view) {
            if (Schema::hasTable('pages')) {
                $view->with('page', Page::forCurrentRoute());
            }
        });

        // Share $settings globally (cached)
        if (Schema::hasTable('apps')) {
            View::share('settings', AppSetting::current());
        }
    }

}
