<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Mockery;

use Google_Client;
use Google_Service_Calendar;
use App\FS\FileSystem;

class CalendarTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testIndex()
    {   
        // Set up google client mock
        $googleClientMock = Mockery::mock(Google_Client::class);
        $googleClientMock->shouldReceive(
            "setApplicationName",
            "setScopes",
            "setAuthConfig",
            "setAccessType",
            "setPrompt",
            "setAccessToken",
        )->once();

        $googleClientMock->shouldReceive("getLogger")->andReturn(new class() {
            public function info() {}
        });

        // Set up file system mock
        $fileSystemMock = Mockery::mock(FileSystem::class);
        $fileSystemMock->shouldReceive("exists")->andReturnTrue();
        $fileSystemMock->shouldReceive("getContent")->andReturn("{}");

        // Replace container instances
        $this->app->instance(Google_Client::class, $googleClientMock);
        $this->app->instance(FileSystem::class, $fileSystemMock);
        $this->app->bind(Google_Service_Calendar::class, function() {
            return new class() {
                public $events;
                public function __construct() { $this->events = new class {
                    public function listEvents() { return []; }
                }; }
            };
        });
        
        // Testing calendar endpoint
        $response = $this->get('/api/calendar?year=2020&week=37');
        $response->assertStatus(200);
    }

    public function testStore() {
        // Set up google client mock
        $googleClientMock = Mockery::mock(Google_Client::class);
        $googleClientMock->shouldReceive(
            "setApplicationName",
            "setScopes",
            "setAuthConfig",
            "setAccessType",
            "setPrompt",
            "setAccessToken",
        )->once();

        $googleClientMock->shouldReceive("getLogger")->andReturn(new class() {
            public function info() {}
        });

        // Set up file system mock
        $fileSystemMock = Mockery::mock(FileSystem::class);
        $fileSystemMock->shouldReceive("exists")->andReturnTrue();
        $fileSystemMock->shouldReceive("getContent")->andReturn("{}");

        // Replace container instances
        $this->app->instance(Google_Client::class, $googleClientMock);
        $this->app->instance(FileSystem::class, $fileSystemMock);
        $this->app->bind(Google_Service_Calendar::class, function() {
            return new class() {
                public $events;
                public function __construct() { $this->events = new class {
                    public function insert() { return []; }
                }; }
            };
        });
        
        // Testing calendar endpoint
        $response = $this->postJson('/api/calendar', [
            "title" => "Test Title",
            "when" => "2010-01-01 10:00:00"
        ]);
        $response->assertStatus(200);

    }
}
