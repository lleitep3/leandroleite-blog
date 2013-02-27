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
            $googleClient->setScopes((array) $info->scopes);

            $refreshToken = Locator::get(':integrations:google')->refresh_token;

            if (!$refreshToken) {
                if (!isset($_GET['code'])) {
                    echo $googleClient->getRedirectLink();
                    exit;
                }
                $return = $googleClient->getRefreshToken($_GET['code']);
                $_SESSION['accessToken'] = $return->access_token;
                $_SESSION['tokenType'] = $return->token_type;
                error_log("segue ai o refresh_token={$return->refresh_token} ");
                var_dump($return);
                exit;
            }

            if (isset($_SESSION['accessToken'])) {
                echo 'you is already authenticated! try to access ' .
                '<a href="/articles/find/">Here</a>';
                exit;
            }

            $return = $googleClient->getAccessToken($refreshToken);
            if (!isset($return->access_token)) {
                var_dump($return);
                exit;
            }
            $_SESSION['accessToken'] = $return->access_token;
            $_SESSION['tokenType'] = $return->token_type;

            $uri = (isset($_GET['sendBack'])) ? $_GET['sendBack'] : $_SERVER['PHP_SELF'];
            header("Location= '{$uri}'");
        });

$router->get('/articles/find/*', function() {
            @session_start();

            $accessToken = $_SESSION['accessToken'];
            $info = Locator::get(':integrations:google:info');
            $googleClient = new \GoogleAPIClient\GoogleClient($info->client_id, $info->client_secret);
            $googleClient->setAccessToken($accessToken);

            $parameters = array('title contains publish');
            $result = $googleClient->searchFiles($parameters);
            if ($result->error->code == 403) {
                $_SESSION['accessToken'] = null;
                unset($_SESSION['accessToken']);
                session_destroy();
                //header('Location:/googleGetRefreshToken?sendBack=/articles/find/');
            }
            var_dump($result, $_SESSION);
        });