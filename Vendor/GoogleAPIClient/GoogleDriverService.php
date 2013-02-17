<?php

namespace GoogleAPIClient;

require_once realpath(__DIR__ . '/google-api-php-client/src/contrib/Google_DriveService.php');

/**
 *
 * @author leandro <leandro@leandroleite.info>
 */
class GoogleDriverService {

    protected $service;

    public function __construct(GoogleClient $client) {
        $this->service = new \Google_DriveService($client);
    }

    public function searchFiles($field, $operator, $value) {
        $parameters = array(
            'field' => $field,
            'operator' => $operator,
            'value' => $value
        );
        
        return $this->service->files->listFiles($parameters);
    }
}