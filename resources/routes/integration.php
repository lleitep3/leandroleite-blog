<?php

use Site\Service\Integration\Linkedin;
use Site\Service\Integration\GitHubPublicClient;
use Site\Service\CurlService;
use Leviathan\Service\Locator;

$router->get('/linkedin', function() {
            $cacheManager = new \Site\Service\FileCacheManager(PATH_CACHE);
            $apiKey = 'vj3oxvlgfpni';
            $apiSecret = 'zVpY54LtLH0cTuYr';
            $userToken = '9bc87eee-b75c-4dd2-ac49-433fb8672bce';
            $userSecret = '55b2f2a0-2719-4b6b-aa44-ad7700be0e6e';
            $resources = array(
                'email-address'
                , 'skills'
                , 'publications'
                , 'three-current-positions'
                , 'three-past-positions'
                , 'specialties'
                , 'interests'
                , 'certifications'
                , 'educations'
                , 'recommendations-received'
            );
            $linkedin = new Linkedin($apiKey, $apiSecret);
            $linkedin->setUserToken($userToken, $userSecret);
            $linkedin->setResources($resources);
            $json = $linkedin->get();

            $jsonObject = json_decode($json);
            if (isset($jsonObject->status) && $jsonObject->status != 200) {
                echo $cacheManager->fetchFileCache('linkedIn.json');
                return;
            }

            $cacheManager->updateFileCache('linkedIn.json', $json);
            echo $json;
            return;
        });

$router->get('/githubrepos', function() {
            $gitHubClient = new GitHubPublicClient(new CurlService(), 'lleitep3');
            $file = PATH_CACHE . DIRECTORY_SEPARATOR . 'gitHubRepos.json';
            if (!file_exists($file)) {
                touch($file);
                chmod($file, 0666);
            }

            try {
                $json = $gitHubClient->listRepos(array('type' => 'owner', 'sort' => 'pushed'));
                file_put_contents($file, $json);
                $array = json_decode($json);
                echo json_encode(
                        array_values(
                                array_filter($array, function($item) {
                                            return !$item->fork;
                                        })
                        )
                );
            } catch (Exception $e) {
                error_log('erro ao consultar githubRepos :' . $e->getMessage());
                echo file_get_contents($file);
            }
        });

$router->get('/githubrepo/articles', function() {
            $gitHubClient = new GitHubPublicClient(new CurlService(), 'lleitep3');
            $file = PATH_CACHE . DIRECTORY_SEPARATOR . 'gitHubRepo_Articles.json';
            if (!file_exists($file)) {
                touch($file);
                chmod($file, 0666);
            }

            try {
                $json = $gitHubClient->getRepoContents('Artigos');
                file_put_contents($file, $json);
                echo $json;
            } catch (Exception $e) {
                error_log('erro ao consultar githubRepo/articles :' . $e->getMessage());
                echo file_get_contents($file);
            }
        });

$router->get('/githubrepo/article/*', function($articleName) {
            $gitHubClient = new GitHubPublicClient(new CurlService(), 'lleitep3');
            $file = PATH_CACHE . DIRECTORY_SEPARATOR . "gitHubRepo_Article_{$articleName}.json";
            if (!file_exists($file)) {
                touch($file);
                chmod($file, 0666);
            }

            try {
                $obj = json_decode($gitHubClient->getRepoContent('Artigos', $articleName));
                $obj->content = \ElephantMarkdown\Markdown::parse(str_replace("\n", "<br>", base64_decode($obj->content)));
                $json = json_encode($obj);
                file_put_contents($file, $json);
                echo $json;
            } catch (Exception $e) {
                error_log('erro ao consultar githubRepo/articles :' . $e->getMessage());
                echo file_get_contents($file);
            }
        });

$router->get('/googleDrive', function() {
            @session_start();
            $info = Locator::get(':integrations:google:info');
            $googleClient = new \GoogleAPIClient\GoogleClient($info->client_id, $info->client_secret);
            $googleClient->setRedirectUri($info->redirect_uri);

            if (!isset($_GET['code'])) {
                $uriAuth = $info->auth_uri;
                echo $googleClient->getRedirectLink($uriAuth);
                exit;
            }

            $tokenUri = $info->token_uri;
            $result = $googleClient->getAccessToken($tokenUri, $_GET['code']);

            var_dump($result);








//            $driver = GoogleAPIClient\BuildDriveService::buildService();
//            var_dump($driver->files->listFiles());
            exit;
            // retrieving Json Wev Token parameters
            $header = (array) Locator::get(':integrations:google:jwt:header');
            $claims = (array) Locator::get(':integrations:google:jwt:claims');
            $now = time();
            $claims['iat'] = $now;
            $claims['exp'] = $now + 3600;

            // retrieving privateKey
            $file = realpath(Locator::get(':integrations:google:privateConf')->filePath);
            $pass = Locator::get(':integrations:google:privateConf')->pass;
            $certs = array();
            if (!openssl_pkcs12_read(file_get_contents($file), $certs, $pass)) {
                throw new Exception('not possible to autenticate with google');
                exit;
            }
            $pkeyid = openssl_get_privatekey($certs['pkey']);

            // creating Json Web Token
            $jwt = new GoogleAPIClient\GoogleJWT($header, $claims);
            $jwt->generateSignature($pkeyid);

            $google = new GoogleAPIClient\GoogleServerRequest($jwt);
            var_dump($google->getAccessTokenData());
            exit;
        });


$router->get('/googlee46ebe5824247571.html', function() {
            echo 'google-site-verification: googlee46ebe5824247571.html';
        });