<?php namespace App\Console\Commands\google;

use Illuminate\Console\Command;
use Google_Client;
use App\FS\FileSystem;

class GetClient extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'google:getclient';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Google Client credentials';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {   
        /* Create google client. Was uable to use DP injector
        in constructor since each registered command is created
        on application initiation and therefore run the constructor
        of  \App\Google\Client::class. Which will fail if json-files
        are missing. Which they do when running automated testing*/
        $client = new \App\Google\Client(new Google_Client(), new FileSystem(), "./credentials.json", "./token.json");
        $client->auth(function(string $authUrl) {
            $this->info("Go to url \"{$authUrl}\" for authentication");
            $code = $this->ask("Paste auth code here:");
            return $code;
        });
        $this->info("Client was succesfully authenticated!");
    }
}
