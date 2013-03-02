<?php

namespace Service;

use Service\Integration\CacheMaker;

/**
 * Description of FileCacheManager
 *
 * @author leandro <leandro@leandroleite.info>
 */
class FileCacheManager implements CacheMaker {

    protected $folderCache;

    /**
     * 
     * @param string $folderCache based path
     * @throws \InvalidArgumentException
     */
    public function __construct($folderCache) {
        if (!is_dir($folderCache)) {
            $msg = "Folder Path \"{$folderCache}\" not found.";
            throw new \InvalidArgumentException($msg);
        }
        $this->folderCache = $folderCache;
    }

    /**
     * 
     * @param string $filePath file name with extension to save
     * @param string $content
     * @return boolean
     */
    public function updateFileCache($fullPath, $content) {
        return (bool) file_put_contents($fullPath, $content);
    }

    /**
     * return content of fileCache
     * @param string $filePath
     * @return string
     */
    public function fetchFileCache($filePath) {
        return file_get_contents($filePath);
    }

    /**
     * 
     * @param string $filePath
     * @return string full path normalized
     */
    protected function getFullPath($filePath) {
        $path = str_replace(
                DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR
                , DIRECTORY_SEPARATOR
                , $this->folderCache . DIRECTORY_SEPARATOR . $filePath
        );

        if (!file_exists($path))
            touch($path);

        chmod($path, 0666);

        return $path;
    }

    /**
     * 
     * @param string $key
     * @return string content cached
     */
    public function getCache($key) {
        $key = str_replace(DIRECTORY_SEPARATOR, '_', $key);
        return json_decode($this->fetchFileCache($this->getFullPath($key)));
    }

    public function makeCache($key, $content) {
        $key = str_replace(DIRECTORY_SEPARATOR, '_', $key);
        if (!is_string($content)) {
            $content = json_encode($content);
        }
        return $this->updateFileCache($this->getFullPath($key), $content);
    }

}