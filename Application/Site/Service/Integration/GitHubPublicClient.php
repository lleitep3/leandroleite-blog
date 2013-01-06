<?php

namespace Site\Service\Integration;

use Site\Service\CurlService;
use \Exception;

/**
 * Description of GitHubClient
 *
 * @author leandro <leandro@leandroleite.info>
 */
class GitHubPublicClient {

    protected $curl;
    protected $user;

    const GITHUB_URL = 'https://api.github.com/';

    public function __construct(CurlService $curl, $user = false) {
        $this->curl = $curl;
        $this->user = $user;
    }

    public function setUser($user) {
        $this->user = $user;
    }

    public function listRepos($query) {
        return $this->doRequest(
                        $this->makeUrlService(
                                array('users', $this->user, 'repos'), $query
                        )
        );
    }

    public function getRepo($repo) {
        return $this->doRequest(
                        $this->makeUrlService(
                                array('repos', $this->user, $repo)
                        )
        );
    }

    public function getRepoContents($repo) {
        return $this->doRequest(
                        $this->makeUrlService(
                                array('repos', $this->user, $repo, 'contents')
                        )
        );
    }

    public function getRepoContent($repo, $content) {
        return $this->doRequest(
                        $this->makeUrlService(
                                array('repos', $this->user, $repo, 'contents', $content)
                        )
        );
    }

    protected function makeUrlService(array $parameters, array $query = array()) {
        $url = GitHubPublicClient::GITHUB_URL . implode('/', $parameters);
        if ($query)
            $url .= '?' . http_build_query($query);
        return $url;
    }

    protected function doRequest($url) {
        if (!$this->user) {
            throw new Exception('user Not setted');
        }

        try {
            return $this->curl->get($url)->fetch();
        } catch (Exception $e) {
            error_log('Error on Class{' . __CLASS__ . '} : ' . $e->getMessage());
            return false;
        }
    }

}
