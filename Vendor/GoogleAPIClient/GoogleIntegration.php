<?php

require_once 'google-api-php-client/src/Google_Client.php';
require_once 'google-api-php-client/src/contrib/Google_DriveService.php';

namespace GoogleAPIClient;

/**
 *
 * @author leandro <leandro@leandroleite.info>
 */
class GoogleIntegration {

    public function __construct($clientId, $clientSecret) {
        $client = new Google_Client();
        // Get your credentials from the APIs Console
        $client->setClientId($clientId);
        $client->setClientSecret($clientSecret);
        $client->setRedirectUri('urn:ietf:wg:oauth:2.0:oob');
        $client->setScopes(array('https://www.googleapis.com/auth/drive'));
        $service = new Google_DriveService($client);
        echo $authUrl = $client->createAuthUrl();
    }

}