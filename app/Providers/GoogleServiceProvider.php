<?php namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Google_Client;
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
        $this->app->bind(Google_Client::class, function($app) {
            return new Google_Client();
        });

        // Singleton binding for google client
        $this->app->singleton(\App\Google\Client::class, function($app) {
            return new \App\Google\Client($this->app->make(Google_Client::class), $this->app->make(FileSystem::class), "./credentials.json", "./token.json");
        });

        // Singleton binding for google claendar
        $this->app->singleton(\App\Google\Calendar::class, function($app) {
            return new \App\Google\Calendar($this->app->make(Google\Client::class)->client);
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
