<?php

namespace Service\Integration;

use Service\Integration\Integrable;
use Service\Integration\CacheMaker;
use Service\Integration\Exceptions\IntegrationException;

/**
 * Description of HandlerIntegrationService
 *
 * @author leandro <leandro@leandroleite.info>
 */
class IntegrationServiceHandler {

    protected $serviceIntegration;
    protected $cacher;

    public function __construct(Integrable $serviceIntegration, CacheMaker $cacher) {
        $this->serviceIntegration = $serviceIntegration;
        $this->cacher = $cacher;
    }

    public function request($args) {
        $request = $_SERVER['REQUEST_URI'];
        try {
            $result = $this->serviceIntegration->callService($args);
            $this->cacher->makeCache($request, $result);
        } catch (IntegrationException $exc) {
            error_log($exc->getTraceAsString());
            $result = $this->cacher->getCache($request);
        }
        return $result;
    }

}