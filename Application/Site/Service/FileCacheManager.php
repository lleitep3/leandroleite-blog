<?php

namespace Site\Service;

/**
 * Description of FileCacheManager
 *
 * @author leandro <leandro@leandroleite.info>
 */
class FileCacheManager {

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
    public function updateFileCache($filePath, $content) {
        $fullPath = $this->getFullPath($filePath);
        return (bool) file_put_contents($fullPath, $content);
    }

    /**
     * return content of fileCache
     * @param string $filePath
     * @return string
     */
    public function fetchFileCache($filePath) {
        return file_get_contents($this->getFullPath($filePath));
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

        if (!file_exists($path)) {
            touch($path);
        }
        chmod($path, 0666);

        return $path;
    }

}