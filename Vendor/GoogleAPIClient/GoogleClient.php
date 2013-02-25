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

    public function getRedirectLink($uriAuth) {
        $data = http_build_query(array(
            'scope' => 'https://www.googleapis.com/auth/drive',
            'state' => 'drive_access',
            'redirect_uri' => 'http://leandroleite.info/googleDrive',
            'response_type' => 'code',
            'client_id' => $this->clientId,
            'access_type' => 'offline',
            'approval_prompt' => 'force'
        ));
        $curl = new CurlService();
        return $curl->get("{$uriAuth}?{$data}")->fetch();
    }

    public function getAccessToken($tokenUri, $code) {
        $data = array(
            'code' => $code,
            'client_id' => $this->clientId,
            'client_secret' => $this->secret,
            'redirect_uri' => $this->redirectUri,
            'grant_type' => 'authorization_code'
        );
        $curl = new CurlService();
        return json_decode($curl->formPost($tokenUri, $data)->fetch());
    }

    public function searchFiles(array $parameters) {
        $query = urlencode(implode('&', $parameters));
        $url = "https://www.googleapis.com/drive/v2/files/?q={$query}";
        $curl = new CurlService();
        $curl->setHeaders(array('Authorization' => "Bearer {$this->accessToken}"));
        return $curl->get($url)->fetch();
    }

}