<?php

namespace Digitalcake\Scheduling\Providers;

use Digitalcake\Scheduling\Contracts\UserBirthdayContract;
use Digitalcake\Scheduling\Events\BirthdaySendEmailEvent;
use Digitalcake\Scheduling\Models\EmailSendSettings;
use Digitalcake\Scheduling\Models\SendedBirthdayEmail;
use Exception;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class ScheduleServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->register(ScheduleEventServiceProvider::class);
    }

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

        if (!$this->app->runningInConsole()) {
            if (!is_file(__DIR__ . '/../../controllers/PackageBaseController.php')) {
                throw new Exception('PackageBaseController.php dosyası bulunamadı. Yayınlamak için php artisan vendor:publish --tag=schedule-base-controller');
            }
        }

        $this->commands([
            \Digitalcake\Scheduling\Commands\SendNewsletter::class,
        ]);

        $this->app->booted(function (Application $app) {

            /**
             * @var Schedule $schedule
             */
            $schedule = $app->make(Schedule::class);

            $this->scheduleBirthday($schedule);
            $this->scheduleNewsletter($schedule);
            $this->schedulePrune($schedule);
        });

        $this->loadRoutes();
        $this->publishConfig();
        $this->publishController();
        $this->publishViews();
    }

    public function loadRoutes(): void
    {
        $routesCache = Cache::get('routes');

        if ($routesCache !== null && $routesCache->search(dirname(__DIR__, 2) . '/routes.web.php') === false) {
            $routesCache->push(
                dirname(__DIR__, 2) . '/routes.web.php'
            );
            Cache::put('routes', $routesCache);
        } else {
            $this->loadRoutesFrom(__DIR__ . '/../../routes.web.php');
        }
    }

    public function publishConfig()
    {
        $this->publishes([
            __DIR__ . '/../../config/extensions.php'
            => config_path('extensions.php'),
            __DIR__ . '/../../config/schedule.php' => config_path('schedule.php'),
        ], 'schedule-config');
    }

    public function publishController()
    {
        $this->publishes([
            __DIR__ . '/../../controllers/PackageBaseController.php'
            => app_path('Http/Controllers/PackageBaseController.php'),
        ], 'schedule-base-controller');
    }

    public function publishViews()
    {
        $this->publishes([
            __DIR__ . '/../../Views' => resource_path('views/vendor/schedule')
        ], 'scheduling-views');
    }

    public function scheduleNewsletter(Schedule $schedule)
    {
        $schedule->command('newsletter:send')
            ->everyMinute()
            ->withoutOverlapping()
            ->appendOutputTo(storage_path('logs/schedule.log'));
    }

    public function scheduleBirthday(Schedule $schedule)
    {
        $schedule->call(function () {

            $model = config('schedule.birthday_model');
            if ($model === null) {
                return;
            }
            /**
             * @var \Illuminate\Database\Eloquent\Model $model
             */
            $model = new $model;
            if (!$model instanceof UserBirthdayContract) {
                throw new Exception('config/schedule.php içerisinde birthday_model değeri UserContract sınıfından türetilmiş bir sınıf olmalıdır.');
            }

            $settings = EmailSendSettings::first();

            $date = now();

            if ($settings->email_send_day > 0) {
                $date->addDays($settings->email_send_day);
            }
            if ($settings->email_send_day < 0) {
                $date->subDays(abs($settings->email_send_day));
            }

            $model->whereMonth($model->getBirthdateColumn(), $date->format('m'))
                ->whereDay($model->getBirthdateColumn(), $date->format('d'))
                ->get()
                ->map(function ($user) {
                    event(new BirthdaySendEmailEvent($user));
                });
        })
            ->twiceDaily(1, 13)
            ->appendOutputTo(storage_path('logs/schedule.log'))
            ->skip(function () {
                return !config('schedule.birthday_email_enabled');
            });
    }

    public function schedulePrune(Schedule $schedule)
    {
        $schedule->command('model:prune', [
            '--model' => [SendedBirthdayEmail::class,]
        ])
            ->monthly()
            ->appendOutputTo(storage_path('logs/schedule.log'));
    }
}
