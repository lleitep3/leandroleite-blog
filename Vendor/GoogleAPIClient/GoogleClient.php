<?php

namespace GoogleAPIClient;

use Site\Service\CurlService;

/**
 * Description of GoogleServerRuequest
 *
 * @author leandro <leandro@leandroleite.info>
 */
class GoogleClient {

    protected $curl;
    protected $clientId;
    protected $secret;
    protected $redirectUri;
    protected $accessToken;
    protected $scopes;
    private $urlAuth = 'https://accounts.google.com/o/oauth2/auth';
    private $urlToken = 'https://accounts.google.com/o/oauth2/token';

    public function __construct($clientId, $secret) {
        $this->clientId = $clientId;
        $this->secret = $secret;
        $this->curl = new CurlService();
    }

    public function setRedirectUri($uri) {
        $this->redirectUri = $uri;
        return $this;
    }

    public function setAccessToken($accessToken) {
        $this->accessToken = $accessToken;
        return $this;
    }

    public function setScopes(array $scopes) {
        $this->scopes = $scopes;
    }

    public function getRedirectLink() {
        $data = http_build_query(array(
            'scope' => implode(' ', $this->scopes),
            'state' => 'drive_access',
            'redirect_uri' => 'http://leandroleite.info/googleDrive',
            'response_type' => 'code',
            'client_id' => $this->clientId,
            'access_type' => 'offline',
            'approval_prompt' => 'force'
        ));
        $curl = new CurlService();
        return $curl->get("{$this->urlAuth}?{$data}")->fetch();
    }

    public function getAccessToken($refreshToken) {
        $data = array(
            'client_id' => $this->clientId,
            'client_secret' => $this->secret,
            'refresh_token' => $refreshToken,
            'grant_type' => 'refresh_token'
        );
        $curl = new CurlService();
        return json_decode($curl->formPost($this->urlToken, $data)->fetch());
    }

    public function getRefreshToken($code) {
        $data = array(
            'code' => $code,
            'client_id' => $this->clientId,
            'client_secret' => $this->secret,
            'redirect_uri' => $this->redirectUri,
            'grant_type' => 'authorization_code'
        );
        $curl = new CurlService();
        return json_decode($curl->formPost($this->urlToken, $data)->fetch());
    }

    public function searchFiles(array $parameters) {
        $url = "https://www.googleapis.com/drive/v2/files/";
        if (count($parameters)) {
            $query = urlencode(implode('&', $parameters));
            $url .= "?q={$query}";
        }
        $curl = new CurlService();
        $curl->setHeaders(array('Authorization' => "Bearer {$this->accessToken}"));
        return json_decode($curl->get($url)->fetch());
    }

    public function get($uri) {
        $url = "https://www.googleapis.com/drive/v2/{$uri}?access_token={$this->accessToken}";
       
        $curl = new CurlService();
        $curl->setHeaders(array('Authorization' => "Bearer {$this->accessToken}"));
        return json_decode($curl->get($url)->fetch());
    }

}