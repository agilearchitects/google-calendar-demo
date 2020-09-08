<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Google_Client;
use Google_Service_Calendar;
use App\FS\FileSystem;

class GoogleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Simple binding for Google_Client lib
        $this->app->bind(Google_Client::class, function() {
            return new Google_Client();
        });

        $this->app->bind(Google_Service_Calendar::class, function($app, $parameters) {
            return new Google_Service_Calendar(...$parameters);
        });

        // Singleton binding for google client
        $this->app->singleton(\App\Google\Client::class, function($app) {
            return new \App\Google\Client($this->app->make(Google_Client::class), $this->app->make(FileSystem::class), base_path(). "/credentials.json", base_path(). "/token.json");
        });

        // Singleton binding for google calendar
        $this->app->singleton(\App\Google\Calendar::class, function($app) {
            // Create calendar service using singleton client
            $nativeClient = $this->app->make(\App\Google\Client::class)->nativeClient;
            $service = $this->app->make(Google_Service_Calendar::class, [$nativeClient]);
            return new \App\Google\Calendar($service);
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
