<?php

use Site\Service\Integration\Linkedin;
use Site\Service\Integration\GitHubPublicClient;
use Site\Service\CurlService;

$router->get('/linkedin', function() {
            $file = PATH_CACHE . DIRECTORY_SEPARATOR . 'linkedIn.json';
            $apiKey = 'vj3oxvlgfpni';
            $apiSecret = 'zVpY54LtLH0cTuYr';
            $userToken = 'ca62e630-5165-4a47-b93d-002bad5f1394';
            $userSecret = '261e79e0-c301-42e5-a806-ed8e64f4a099';
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
            $file = PATH_CACHE . DIRECTORY_SEPARATOR . 'gitHubRepo_Articles.json';
            if (!file_exists($file)) {
                touch($file);
                chmod($file, 0666);
            }

            try {
                $json = $gitHubClient->getRepoContent('Artigos', $articleName);
                file_put_contents($file, $json);
                echo $json;
            } catch (Exception $e) {
                error_log('erro ao consultar githubRepo/articles :' . $e->getMessage());
                echo file_get_contents($file);
            }
        });
