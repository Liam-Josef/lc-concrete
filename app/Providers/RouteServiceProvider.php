<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "home" route for your application.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
            Route::middleware('web')
                ->group(base_path('routes/assessment.php'));
            Route::middleware('web')
                ->group(base_path('routes/student.php'));
            Route::middleware('web')
                ->group(base_path('routes/user.php'));
            Route::middleware('web')
                ->group(base_path('routes/instructor.php'));
            Route::middleware('web')
                ->group(base_path('routes/certificate.php'));

            Route::middleware('web')
                ->group(base_path('routes/payment.php'));

            Route::middleware(['web', 'admin'])
                ->group(base_path('routes/mex-admin-video.php'));

            Route::middleware(['web', 'admin'])
                ->group(base_path('routes/mex-admin-section.php'));

            Route::middleware(['web', 'admin'])
                ->group(base_path('routes/mex-admin-organization.php'));

            Route::middleware(['web', 'admin'])
                ->group(base_path('routes/mex-admin-contact.php'));

            Route::middleware(['web', 'admin'])
                ->group(base_path('routes/mex-admin-student.php'));
            Route::middleware(['web', 'admin'])
                ->group(base_path('routes/mex-admin-app-info.php'));

            Route::middleware(['web', 'admin'])
                ->group(base_path('routes/mex-admin-lesson.php'));
            Route::middleware(['web', 'admin'])
                ->group(base_path('routes/utilities.php'));
            Route::middleware(['web', 'admin'])
                ->group(base_path('routes/mex-admin-course.php'));

        });
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
