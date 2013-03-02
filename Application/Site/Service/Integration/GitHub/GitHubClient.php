<?php

namespace Service\Integration\GitHub;

use Service\Integration\Integrable;
use Service\CurlService;
use \Exception;

/**
 * Description of GitHubClient
 *
 * @author leandro <leandro@leandroleite.info>
 */
class GitHubClient implements Integrable {

    protected $curl;
    protected $url;
    protected $services;

    public function __construct(CurlService $curl, $info) {
        $this->curl = $curl;
        $this->url = $info->url;
        $this->services = (array) $info->services;
    }

    protected function getService($service) {
        if (!array_key_exists($service, $this->services)) {
            return false;
        }
        $api = $this->services[$service]->service;
        $query = http_build_query((array) $this->services[$service]->filter);

        return "{$this->url}{$api}?{$query}";
    }

    protected function doRequest($url) {
        try {
            return json_decode($this->curl->get($url)->fetch());
        } catch (Exception $e) {
            error_log('Error on Class{' . __CLASS__ . '} : ' . $e->getMessage());
            return false;
        }
    }

    public function callService(array $args) {
        $service = (count($args)) ? current($args) : 'myprojects';
        return $this->doRequest($this->getService($service));
    }

}