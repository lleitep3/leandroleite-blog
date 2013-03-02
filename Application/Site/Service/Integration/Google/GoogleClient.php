<?php

namespace Service\Integration\Google;

use Service\CurlService;
use Service\Integration\Integrable;

/**
 * Description of GoogleServerRuequest
 *
 * @author leandro <leandro@leandroleite.info>
 */
class GoogleClient implements Integrable {

    protected $curl;
    protected $clientId;
    protected $secret;
    protected $redirectUri;
    protected $accessToken;
    protected $refreshToken;
    protected $scopes;
    protected $urlAuth;
    protected $urlToken;
    protected $urlTokenInfo;

    public function __construct(CurlService $curl, $info) {
        @session_start();
        $this->clientId = $info->client_id;
        $this->secret = $info->client_secret;
        $this->refreshToken = $info->refresh_token;
        $this->redirectUri = $info->redirect_uri;
        $this->urlAuth = $info->auth_uri;
        $this->urlToken = $info->token_uri;
        $this->urlTokenInfo = $info->token_info_uri;
        $this->setScopes((array) $info->scopes);
        $this->curl = $curl;
        $this->services = (array) $info->services;
    }

    public function setRedirectUri($uri) {
        $this->redirectUri = $uri;
        return $this;
    }

    public function setAccessToken($accessToken) {
        $_SESSION['accessToken'] = $accessToken;
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
        return $this->curl->get("{$this->urlAuth}?{$data}")->fetch();
    }

    public function getAccessToken() {
        $accessToken = $_SESSION['accessToken'];
        if ($this->accessTokenIsValid($accessToken)) {
            return $this->accessToken = $accessToken;
        }
        $data = array(
            'client_id' => $this->clientId,
            'client_secret' => $this->secret,
            'refresh_token' => $this->refreshToken,
            'grant_type' => 'refresh_token'
        );
        $return = json_decode($this->curl->formPost($this->urlToken, $data)->fetch());

        if (isset($return->error)) {
            error_log(print_r($return, 3));
            return false;
        }

        $this->setAccessToken($return->access_token);
        return $return->access_token;
    }

    public function getRefreshToken($code) {
        $data = array(
            'code' => $code,
            'client_id' => $this->clientId,
            'client_secret' => $this->secret,
            'redirect_uri' => $this->redirectUri,
            'grant_type' => 'authorization_code'
        );
        return json_decode($this->curl->formPost($this->urlToken, $data)->fetch());
    }

    protected function accessTokenIsValid($accessToken) {
        $url = "{$this->urlTokenInfo}?access_token={$accessToken}";
        $return = json_decode($this->curl->get($url)->fetch());
        return (isset($return->error)) ? false : true;
    }

    protected function getService($service) {
        if (!array_key_exists($service, $this->services)) {
            return false;
        }
        $api = $this->services[$service]->service;
        $param = (array) $this->services[$service]->filter;
        $param = array_merge($param, array('access_token' => $this->accessToken));
        $query = http_build_query($param);
        return "{$api}?{$query}";
    }

    public function callService(array $args) {
        $service = (count($args)) ? current($args) : 'articles';
        $this->getAccessToken();
        $url = $this->getService($service);
        $return = json_decode($this->curl->get($url)->fetch());

        if (isset($return->error)) {
            error_log(print_r($return, 3));
            return false;
        }
        return $return->items;
    }

}