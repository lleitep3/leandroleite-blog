<?php

namespace GoogleAPIClient;

require_once realpath(__DIR__ . '/google-api-php-client/src/Google_Client.php');
require_once realpath(__DIR__ . '/google-api-php-client/src/contrib/Google_DriveService.php');

/**
 *
 * @author leandro <leandro@leandroleite.info>
 */
class GoogleIntegration {

    public function __construct($clientId, $clientSecret) {
        $client = new \Google_Client();
        // Get your credentials from the APIs Console
        $client->setClientId($clientId);
        $client->setClientSecret($clientSecret);
        $client->setRedirectUri('http://leandroleite.info/responseGoogle');
        $client->setScopes(
                array(
                    'https://www.googleapis.com/auth/drive.apps.readonly'
                    , 'https://www.googleapis.com/auth/drive.readonly'
                    , 'https://www.googleapis.com/auth/drive.readonly.metadata'
                )
        );
        $service = new \Google_DriveService($client);
        echo $authUrl = $client->createAuthUrl();
    }

}