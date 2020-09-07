<?php namespace App\Google;

use Google_Client;
use Google_Service_Calendar;
use App\FS\FileSystem;

class Client {
  private Google_Client $nativeClient;
  private string $tokenPath;

  public function __construct(
    Google_Client $nativeClient,
    FileSystem $fileSystem,
    string $credentialsPath,
    string $tokenPath
  ) {
    $this->tokenPath = $tokenPath;

    // Create client
    $this->nativeClient = $nativeClient;
    $this->nativeClient->setApplicationName('Google Calendar API PHP Quickstart');
    $this->nativeClient->setScopes(Google_Service_Calendar::CALENDAR_EVENTS);
    $this->nativeClient->setAuthConfig($credentialsPath);
    $this->nativeClient->setAccessType('offline');
    $this->nativeClient->setPrompt('select_account consent');
    
    // Check for token file at path and set access token to client
    if ($fileSystem->exists($this->tokenPath)) {
      $accessToken = json_decode($fileSystem->getContent($this->tokenPath), true);
      $this->nativeClient->setAccessToken($accessToken);
    }
  }

  /**
   * Will attempt to authenticate to the google client API. If neccessary a callback parameter will
   * be called if token don't exists, has expired or has expired and was unable to refresh
   * @param Closure $callback Callback for handling oauth2 flow
   * @return Google_Client Native google client instance
   */
  public function auth(\Closure $callback = null): Google_Client {
    // If token has expired (or don't exists)
    if ($this->nativeClient->isAccessTokenExpired()) {
      // Try to refresh token
      if ($this->nativeClient->getRefreshToken()) {
        $this->nativeClient->fetchAccessTokenWithRefreshToken($this->nativeClient->getRefreshToken());
      } else {
        // This state requires the callback parameter to be provided. Will throw exception if not provided
        if($callback === null) { throw new Exception("Callback missing"); }
        // Else generate auth url
        $authUrl = $this->nativeClient->createAuthUrl();
        // Use callback to get auth code
        $authCode = $callback($authUrl);
        // Use auth code to get access token
        $accessToken = $this->nativeClient->fetchAccessTokenWithAuthCode($authCode);
        $this->nativeClient->setAccessToken($accessToken);
        // Check for errors
        if (array_key_exists('error', $accessToken)) {
          throw new Exception(join(', ', $accessToken));
        }
      }
      $fileSystem->putContent($this->tokenPath, json_encode($this->nativeClient->getAccessToken()));
    }
    // Return google client
    return $this->nativeClient;
  }
}