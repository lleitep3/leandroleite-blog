<?php

use Site\Service\Integration\Linkedin;

$router->get('/linkedin', function() {
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
                file_put_contents(PATH_CACHE . DIRECTORY_SEPARATOR . 'linkedIn.json', $json);
                echo $json;
            } catch (\Exception $e) {
                error_log('erro ao consultar linkedIn :' . $e->getMessage());
                echo file_get_contents(PATH_CACHE . DIRECTORY_SEPARATOR . 'linkedIn.json');
            }
        });

$router->get('/githubrepos', function() {
            $url = 'https://api.github.com/users/lleitep3/repos?type=owner&sort=pushed';
            $curl = new Site\Service\CurlService();
            $array = json_decode($curl->get($url)->fetch());
            
            echo json_encode(array_values(array_filter($array,function($item){
                return !$item->fork;
            })));
        });
