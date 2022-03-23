<?php

namespace Digitalcake\Scheduling\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class ScheduleServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../../Views', 'schedule');
        $this->loadTranslationsFrom(__DIR__ . '/../../lang', 'schedule');

        if (!is_dir(app_path('Extensions/Scheduling'))) {
            mkdir(app_path('Extensions/Scheduling'));
        }

        $this->publishes([
            __DIR__ . '/../../routes.web.php'
            => app_path('Extensions/Scheduling/routes.web.php'),
        ], 'schedule-router');

        if (!is_file(config_path('extensions.php'))) {
            $this->publishes([
                __DIR__ . '/../../config/extensions.php'
                => config_path('extensions.php'),
            ], 'schedule-config');
        }

        $this->publishes([
            __DIR__ . '/../../public' => public_path('vendor/schedule'),
        ]);

        $this->commands([
            \Digitalcake\Scheduling\Commands\SendNewsletter::class,
        ]);

        $this->app->booted(function (Application $app) {

            $schedule = $app->make(Schedule::class);

            $schedule->command('newsletter:send')
                ->everyMinute()
                ->withoutOverlapping()
                ->appendOutputTo(storage_path('logs/schedule.log'));
        });
    }
}
