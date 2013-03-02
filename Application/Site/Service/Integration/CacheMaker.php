<?php

namespace Service\Integration;

/**
 *
 * @author leandro <leandro@leandroleite.info>
 */
interface CacheMaker {

    /**
     * 
     * @param string $key
     */
    public function getCache($key);

    /**
     * 
     * @param string $key
     * @param string $content
     * @return boolean
     */
    public function makeCache($key, $content);
}