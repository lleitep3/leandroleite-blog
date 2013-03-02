<?php

use Service\Integration\IntegrationServiceHandler;
use Service\Integration\IntegrationException;
use Service\Integration\ServiceIntegrationFactory;
use Service\FileCacheManager;
use Leviathan\Service\Locator;

$accept = array(
    'application/json' => function($data) {
        header('Content-type:application/json');
        echo json_encode($data);
    }
);

$router->get('/api/v1/integration/**', function ($args) {
            $IntegrationClassType = array_shift($args);
            $info = Locator::get(":integrations:{$IntegrationClassType}:info");
            $serviceIntegration = ServiceIntegrationFactory::makeService($IntegrationClassType, $info);

            if (!$serviceIntegration)
                throw new IntegrationException('Service Not Found');

            $cacher = new FileCacheManager(Locator::get(":cache")->path);
            $integrationHandler = new IntegrationServiceHandler($serviceIntegration, $cacher);
            return $integrationHandler->request($args);
        })->accept($accept);            