<?php

namespace Site\Service\Integration;

/**
 * Description of Linkedin
 *
 * @author leandro
 */
class Linkedin {

    protected $conn;
    protected $urlApi;
    protected $applicationKey;
    protected $applicationSecret;
    protected $uriService;
    protected $resources;
    protected $params;

    public function __construct($appKey, $appSecret) {
        $this->setApplication($appKey, $appSecret);
        $this->loadDefaultConfigs();
        $this->loadConfigs();
    }

    protected function loadConfigs() {
        $this->conn->setVersion('1.0');
        $this->conn->setAuthType(Client::AUTH_TYPE_AUTHORIZATION_BASIC);
    }

    protected function loadDefaultConfigs() {
        $this->uriService = 'people/~';
        $this->urlApi = 'http://api.linkedin.com/v1/';
        $this->params = array('format' => 'json');
    }

    public function setApplication($appKey, $appSecret) {
        $this->applicationKey = $appKey;
        $this->applicationSecret = $appSecret;
        $this->conn = new \OAuth($this->applicationKey, $this->applicationSecret);
    }

    public function setUserToken($userToken, $userSecret) {
        $this->conn->setToken($userToken, $userSecret);
    }

    public function addParam($key, $value) {
        $this->params[$key] = $value;
    }

    public function removeParam($key) {
        if (key_exists($key, $this->params))
            unset($this->params[$key]);
        return $this;
    }

    public function setResources(array $resources) {
        $this->resources = $resources;
    }

    public function addResource($resource) {
        if (!in_array($resource, $this->resources))
            $this->resources[] = $resource;
    }

    public function getResources() {
        return ':(' . implode(',', $this->resources) . ')';
    }

    public function getUrlService() {
        return $this->urlApi
                . $this->uriService
                . $this->getResources();
    }

    public function get() {
        $date = new \DateTime();
        $this->conn->setNonce(rand(0, 5));
        $this->conn->setTimestamp($date->getTimestamp());
        $this->conn->setAuthType(\OAUTH_AUTH_TYPE_AUTHORIZATION);

        try {
            $this->conn->fetch($this->getUrlService(), $this->getParams());
        } catch (\OAuthException $E) {
            echo "Response: " . urldecode($E->lastResponse) . "\n";
            exit;
        }
        header('Content-type:application/json');
        return $this->conn->getLastResponse();
    }

    public function getParams() {
        return $this->params;
    }

    public function getQueryParams() {
        return '?' . http_build_query($this->params);
    }

    public function connect() {

        try {
            $date = new \DateTime();
            $apiKey = 'vj3oxvlgfpni';
            $apiSecret = 'zVpY54LtLH0cTuYr';
            $userToken = 'ca62e630-5165-4a47-b93d-002bad5f1394';
            $userSecret = '261e79e0-c301-42e5-a806-ed8e64f4a099';
            $url = "http://api.linkedin.com/v1/people/~{$this->getResources()}";
            $oauth = new \OAuth($apiKey, $apiSecret);
            $oauth->setNonce(rand(0, 5));
            $oauth->setVersion('1.0');
            $oauth->setAuthType(\OAUTH_AUTH_TYPE_AUTHORIZATION);
            $oauth->setTimestamp($date->getTimestamp());
            $oauth->setToken($userToken, $userSecret);
            $params = array('format' => 'json');
            $oauth->fetch($url, $params);

            echo $oauth->getLastResponse();
        } catch (\OAuthException $E) {
            echo "Response: " . urldecode($E->lastResponse) . "\n";
            exit;
        }
    }

}
