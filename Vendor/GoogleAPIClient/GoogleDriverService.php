<?php

namespace GoogleAPIClient;

require_once realpath(__DIR__ . '/google-api-php-client/src/Google_Client.php');
require_once realpath(__DIR__ . '/google-api-php-client/src/contrib/Google_DriveService.php');

/**
 *
 * @author leandro <leandro@leandroleite.info>
 */
class GoogleDriverService {

    protected $service;

    public function __construct($clientId, $clientSecret) {
        $client = new \Google_Client();
        
        // Get your credentials from the APIs Console
        $client->setClientId($clientId);
        $client->setClientSecret($clientSecret);
        $client->setApplicationName('leandroleite.info');
        
        $client->setRedirectUri('http://leandroleite.info/gDriveCallback');
        $client->setScopes(
                array(
                    'https://www.googleapis.com/auth/drive.file'
                    , 'https://www.googleapis.com/auth/drive.readonly'
                    , 'https://www.googleapis.com/auth/drive.readonly.metadata'
                )
        );
        $this->service = new \Google_DriveService($client);
        
        echo $authUrl = $client->createAuthUrl();
        $curl = new \Site\Service\CurlService();
        $curl->get($authUrl)->fetch();
        $return = $curl->get($authUrl)->getResponse();
        $returnHeaders = $curl->getResponseHeader();
        echo $return . $returnHeaders;
        exit;
        $authCode = '4/lRO9CbZxtln4v2S_-G07vEVjaYe7.slindDUxybYWshQV0ieZDAqsgEvReQI';

        // Exchange authorization code for access token
        $client->authenticate($authCode);
        $opa = $client->getAccessToken();
        var_dump($opa, $this->service->files->listFiles(array()));

    }

}