<?php

namespace GoogleAPIClient;

use Site\Service\CurlService;

/**
 * Description of GoogleServerRuequest
 *
 * @author leandro <leandro@leandroleite.info>
 */
class GoogleServerRequest {

    protected $jwt = false;
    protected $curl;

    public function __construct(GoogleJWT $jwt) {
        $this->setJsonWebToken($jwt);
        $this->curl = new CurlService();
    }

    /**
     * 
     * @param \GoogleAPIClient\GoogleJWT $jwt
     */
    public function setJsonWebToken(GoogleJWT $jwt) {
        $this->jwt = $jwt;
    }

    /**
     * 
     * @return \GoogleAPIClient\GoogleJWT
     */
    public function getJsonWebToken() {
        return $this->jwt;
    }

    public function getDataToRetrieveAccessToken() {
        return array(
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $this->getJsonWebToken()->getString()
        );
    }

    public function getAccessTokenData() {
        $url = 'https://accounts.google.com/o/oauth2/token';
        $data = $this->getDataToRetrieveAccessToken();
        return $this->curl->formPost($url, $data)->fetch();
    }

}