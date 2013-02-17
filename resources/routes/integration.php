<?php

use Site\Service\Integration\Linkedin;
use Site\Service\Integration\GitHubPublicClient;
use Site\Service\CurlService;

$router->get('/linkedin', function() {
            $file = PATH_CACHE . DIRECTORY_SEPARATOR . 'linkedIn.json';
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

            try {
                $linkedin = new Linkedin($apiKey, $apiSecret);
                $linkedin->setUserToken($userToken, $userSecret);
                $linkedin->setResources($resources);
                $json = $linkedin->get();
                if (!file_exists($file)) {
                    touch($file);
                }
                chmod($file, 0666);

                file_put_contents($file, $json);
                echo $json;
            } catch (\Exception $e) {
                error_log('erro ao consultar linkedIn :' . $e->getMessage());
                echo file_get_contents($file);
            }
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
            $clientId = '921417781880-q55apggio21ecctui2456069c05l9tcq.apps.googleusercontent.com';
            $clientSecret = 'INp5v7dtGFWWA9r7Sp4sHHp6';
            $redirectUri = 'http://leandroleite.info/googleDrive';
            $authEndPoint = 'https://accounts.google.com/o/oauth2/auth';
            $tokenEndPoint = 'https://www.googleapis.com/oauth2/v1/tokeninfo?access_token=';
            $parameters = array('scope' => 'https://www.googleapis.com/auth/drive');
            $client = new \OAuth2\Client($clientId, $clientSecret);

            if (!isset($_GET['code'])) {
                $auth_url = $client->getAuthenticationUrl($authEndPoint, $redirectUri, $parameters);
                var_dump($auth_url);
                exit;
                header('Location: ' . $auth_url);
                die('Redirect');
            } else {
                $params = array('code' => $_GET['code'], 'redirect_uri' => $redirectUri);
                $response = $client->getAccessToken($tokenEndPoint, 'authorization_code', $params);
                parse_str($response['result'], $info);
                $client->setAccessToken($info['access_token']);
                $response = $client->fetch('https://www.googleapis.com/drive/v2/files');
                var_dump($response, $response['result']);
            }




            if (isset($_GET['code'])) {
                $client->authenticate();
                $_SESSION['token'] = $client->getAccessToken();
                header('Location: http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF']);
            }

//            $client = new \GoogleAPIClient\GoogleClient();
//            $client->setClientId($clientId);
//            $client->setClientSecret($clientSecret);
//            $client->setApplicationName('leandroleite.info');
//            $client->setDeveloperKey('921417781880-q55apggio21ecctui2456069c05l9tcq@developer.gserviceaccount.com');
//            $client->setRedirectUri('http://leandroleite.info/gDriveCallback');
//            $client->setScopes(
//                    array(
//                        'https://www.googleapis.com/auth/drive.apps.readonly'
//                        , 'https://www.googleapis.com/auth/drive.readonly'
//                        , 'https://www.googleapis.com/auth/drive.readonly.metadata'
//                    )
//            );
//
//            $drive = new GoogleAPIClient\GoogleDriverService($client);
//            $auth = new \GoogleAPIClient\GoogleOAuth2Service($client);
//            if (isset($_SESSION['code']))
//                $authCode = $_SESSION['code'];
//            try {
//                $client->authenticate($authCode);
//            } catch (\Exception $e) {
//                var_dump($client->authenticate());
//                exit;
//            }
//            // Exchange authorization code for access token
//            $client->getAccessToken();
//
//            $results = $drive->searchFiles();
//
//            var_dump($results);
        });

$router->get('/gDriveCallback', function() {
            @session_start();
            $_SESSION['code'] = $_GET['code'];
            header('Location:/googleDrive');
        });

