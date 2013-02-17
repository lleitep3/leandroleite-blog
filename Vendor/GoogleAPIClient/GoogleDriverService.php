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
        
        $client->setRedirectUri('http://leandroleite.info/gDriveCallback');
        $client->setScopes(
                array(
                    'https://www.googleapis.com/auth/drive.apps.readonly'
                    , 'https://www.googleapis.com/auth/drive.readonly'
                    , 'https://www.googleapis.com/auth/drive.readonly.metadata'
                )
        );
        $this->service = new \Google_DriveService($client);
        
        echo $authUrl = $client->createAuthUrl();
//        $curl = new \Site\Service\CurlService();
//        $curl->get($authUrl)->fetch();
//        $return = $curl->get($authUrl)->getResponse();
//        $returnHeaders = $curl->getResponseHeader();
//        echo $return . $returnHeaders;
//        exit;
        $authCode = '4/KxgFbpLSUd_bRpMtVxIA-buoURNl.orQJ3u4XVtYXshQV0ieZDAraWmTQeQI';

        // Exchange authorization code for access token
        $accessToken = $client->authenticate($authCode);
        
        $client->setAccessToken($accessToken);
        var_dump($this->service->files->listFiles(array()));

    }

}