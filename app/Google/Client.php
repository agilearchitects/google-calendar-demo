<?php namespace App\Google;

use Google_Client;
use Google_Service_Calendar;

class Client {
  public Google_Client $nativeClient;

  public function __construct(string $credentialsPath, string $tokenPath) {
    $this->credentialsPath = $credentialsPath;
    $this->tokenPath = $tokenPath;

    // Create client
    $this->nativeClient = new Google_Client();
    $this->nativeClient->setApplicationName('Google Calendar API PHP Quickstart');
    $this->nativeClient->setScopes(Google_Service_Calendar::CALENDAR_EVENTS);
    $this->nativeClient->setAuthConfig($this->credentialsPath);
    $this->nativeClient->setAccessType('offline');
    $this->nativeClient->setPrompt('select_account consent');
    
    // Check for token file at path and set access token to client
    if (file_exists($this->tokenPath)) {
      $accessToken = json_decode(file_get_contents($tokenPath), true);
      $this->nativeClient->setAccessToken($accessToken);
    }
  }

  public function auth(\Closure $callback): Google_Client {
    // If token has expired (or don't exists)
    if ($this->nativeClient->isAccessTokenExpired()) {
      // Try to refresh token
      if ($this->nativeClient->getRefreshToken()) {
        $this->nativeClient->fetchAccessTokenWithRefreshToken($this->nativeClient->getRefreshToken());
      } else {
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
      file_put_contents($this->tokenPath, json_encode($this->nativeClient->getAccessToken()));
    }

    return $this->nativeClient;
  }
}