<?php

use Site\Service\Integration\Linkedin;

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
            $url = 'https://api.github.com/users/lleitep3/repos?type=owner&sort=pushed';
            $file = PATH_CACHE . DIRECTORY_SEPARATOR . 'gitHubRepos.json';
            $curl = new Site\Service\CurlService();
            $json = $curl->get($url)->fetch();

            try {
                if (!file_exists($file)) {
                    touch($file);
                }
                chmod($file, 0666);

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
