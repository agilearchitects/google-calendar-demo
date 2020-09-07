<?php namespace Tests\Unit;

use Mockery;
use PHPUnit\Framework\TestCase;
use Google_Client;
use App\FS\FileSystem;

class GoogleClientTest extends TestCase
{
    /**
     * Testing Google Client
     *
     * @return void
     */
    public function testCreate()
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

        // Set up file system mock
        $fileSystemMock = Mockery::mock(FileSystem::class);
        $fileSystemMock->shouldReceive("exists")->andReturnTrue();
        $fileSystemMock->shouldReceive("getContent")->andReturn("{}");

        // Create client
        $client = new \App\Google\Client($googleClientMock, $fileSystemMock, "", "");
        $this->assertInstanceOf(\App\Google\Client::class, $client);
    }
    public function testAuth() {
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

        // Set up file system mock
        $fileSystemMock = Mockery::mock(FileSystem::class);
        $fileSystemMock->shouldReceive("exists")->andReturnTrue();
        $fileSystemMock->shouldReceive("getContent")->andReturn("{}");

        // Create client
        $client = new \App\Google\Client($googleClientMock, $fileSystemMock, "", "");
        $this->assertInstanceOf(\App\Google\Client::class, $client);
        
        $googleClientMock->shouldReceive("isAccessTokenExpired")->andReturnFalse();

        $nativeClient = $client->auth();

        $this->assertInstanceOf(Google_Client::class, $nativeClient);
    }
}
