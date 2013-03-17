<?php

namespace Service\Integration\Google;

use Service\CurlService;
use Service\Integration\Integrable;
use Service\Integration\Exceptions\IntegrationException;

/**
 * Description of GoogleServerRuequest
 *
 * @author leandro <leandro@leandroleite.info>
 */
class GoogleClient implements Integrable {

    protected $curl;
    protected $clientId;
    protected $secret;
    protected $services;
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
        if (isset($_SESSION['accessToken']))
            $this->accessToken = $_SESSION['accessToken'];

        if ($this->accessTokenIsValid($this->accessToken))
            return $this->accessToken;

        $data = array(
            'client_id' => $this->clientId,
            'client_secret' => $this->secret,
            'refresh_token' => $this->refreshToken,
            'grant_type' => 'refresh_token'
        );
        $return = json_decode($this->curl->formPost($this->urlToken, $data)->fetch());

        if (isset($return->error)) {
            error_log(print_r($return, 3));
            throw new IntegrationException(print_r($return, 3));
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

    protected function getService($service, $args) {
        if (!array_key_exists($service, $this->services))
            return false;

        array_shift($args);
        $mapperArgs = array();

        foreach ($args as $key => $arg)
            $mapperArgs["#arg{$key}"] = $arg;

        $api = str_replace(
                array_keys($mapperArgs)
                , array_values($mapperArgs)
                , $this->services[$service]->service
        );
        $param = (array) $this->services[$service]->filter;
        $param['access_token'] = $this->accessToken;
        $query = http_build_query($param);
        return "{$api}?{$query}";
    }

    public function callService(array $args) {
        $service = (count($args)) ? current($args) : 'articles';
        $this->getAccessToken();
        $url = $this->getService($service, $args);
        $return = json_decode($this->curl->get($url)->fetch());

        if (isset($return->error)) {
            error_log(print_r($return, 3));
            throw new IntegrationException(print_r($return, 3));
        }
        return $this->formatBeforeSend($service, $return);
    }

    protected function formatBeforeSend($service, $return) {
        $data = new \stdClass();
        switch ($service) {
            case 'articles':
                $items = array();
                foreach ($return->items as $item) {
                    $obj = new \stdClass;
                    $obj->id = $item->id;
                    $obj->title = trim(str_replace('#publish', '', $item->title));
                    $obj->modifiedDate = $item->modifiedDate;
                    $obj->createdDate = $item->createdDate;
                    $obj->owners = $item->owners;
                    $items[] = $obj;
                }
                $data = $items;
                break;
            case 'article':
                $return = (array) $return;
                $data->title = trim(str_replace('#publish', '', $return['title']));
                $data->createdDate = $return['createdDate'];
                $data->modifiedDate = $return['modifiedDate'];
                $data->iconLink = $return['iconLink'];
                $data->owners = $return['owners'];
                $arr = (array) $return['exportLinks'];
                $url = $arr['text/html'] . '&access_token=' . $this->accessToken;
                $data->content = file_get_contents($url);
                break;
            default :
                $data = $return;
        }
        return $data;
    }

}