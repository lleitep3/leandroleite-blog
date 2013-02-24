<?php

namespace GoogleAPIClient;

use Leviathan\Service\Locator;

require_once "google-api-php-client/src/Google_Client.php";
require_once "google-api-php-client/src/contrib/Google_DriveService.php";
require_once "google-api-php-client/src/contrib/Google_Oauth2Service.php";

/**
 * Description of BuildDriveService
 *
 * @author leandro
 */
class BuildDriveService {

    /**
     * Build and returns a Drive service object authorized with the service accounts.
     *
     * @return \Google_DriveService service object.
     */
    public static function buildService() {
        $DRIVE_SCOPE = array('https://www.googleapis.com/auth/drive','https://www.googleapis.com/auth/drive.readonly');
        $SERVICE_ACCOUNT_EMAIL = '861685171956-bso5e477bs1l2bpg363v5s20jdcvp1fd@developer.gserviceaccount.com';
        $SERVICE_ACCOUNT_PKCS12_FILE_PATH = Locator::get(':integrations:google:privateConf')->filePath;
        
        $key = file_get_contents($SERVICE_ACCOUNT_PKCS12_FILE_PATH);
        $auth = new \Google_AssertionCredentials(
                $SERVICE_ACCOUNT_EMAIL, $DRIVE_SCOPE, $key,'notasecret', 
                'http://oauth.net/grant_type/jwt/1.0/bearer','leandro@leandroleite.info');
        $client = new \Google_Client();
        $client->setUseObjects(true);
        $client->setAssertionCredentials($auth);
        return new \Google_DriveService($client);
    }

}