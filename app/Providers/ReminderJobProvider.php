<?php

namespace App\Providers;
use Illuminate\Support\ServiceProvider;

class ReminderJobProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ReminderJob::class.'@handle', function($job, $app) {
            return $job->handle();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
