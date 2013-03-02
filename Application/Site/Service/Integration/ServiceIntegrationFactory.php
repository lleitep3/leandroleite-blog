<?php

namespace Service\Integration;

use Service\Integration\Google\GoogleClient;
use Service\Integration\LinkedIn\Linkedin;
use Service\Integration\GitHub\GitHubClient as GitHub;
use Service\CurlService;

/**
 * Description of ServiceIntegrationFactory
 *
 * @author leandro
 */
class ServiceIntegrationFactory {

    /**
     * 
     * @param string $IntegrationClassType
     * @param \stdClass $info
     * @return \Service\Integration\Integretable|false
     */
    public function makeService($IntegrationClassType, $info) {
        switch ($IntegrationClassType) {
            case 'google':
                return new GoogleClient(new CurlService(), $info);
            case 'linkedin':
                return new Linkedin($info);
            case 'github':
                return new GitHub(new CurlService(), $info);
        }

        return false;
    }

}