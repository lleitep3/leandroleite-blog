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
        $SERVICE_ACCOUNT_EMAIL = '921417781880-5oqtsjlfrptps3d697vtaius63v6ckit@developer.gserviceaccount.com';
        $SERVICE_ACCOUNT_PKCS12_FILE_PATH = Locator::get(':integrations:google:privateConf')->filePath;
        
        $key = file_get_contents($SERVICE_ACCOUNT_PKCS12_FILE_PATH);
        $auth = new \Google_AssertionCredentials(
                $SERVICE_ACCOUNT_EMAIL, $DRIVE_SCOPE, $key,'notasecret', 
                'urn:ietf:params:oauth:grant-type:jwt-bearer','leandro@leandroleite.info');
        $client = new \Google_Client();
        $client->setClientId('921417781880-5oqtsjlfrptps3d697vtaius63v6ckit.apps.googleusercontent.com');
        $client->setUseObjects(true);
        $client->setAssertionCredentials($auth);
        return new \Google_DriveService($client);
    }

}