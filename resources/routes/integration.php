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
        
        $linkedin = new Linkedin($apiKey, $apiSecret);
        $linkedin->setUserToken($userToken, $userSecret);
        $linkedin->setResources($resources);
        echo $linkedin->get();
        
    });