<?php

namespace Service\Integration;

/**
 *
 * @author leandro
 */
interface Integrable {

    public function callService(array $args);
}