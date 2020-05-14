<?php namespace App\Console\Commands\google;

use Illuminate\Console\Command;

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
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(\App\Google\Client $client)
    {
        $this->client = $client;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->client->auth(function(string $authUrl) {
            $this->info("Go to url \"{$authUrl}\" for authentication");
            $code = $this->ask("Paste auth code here:");
            return $code;
        });
        $this->info("Client was succesfully authenticated!");
    }
}
