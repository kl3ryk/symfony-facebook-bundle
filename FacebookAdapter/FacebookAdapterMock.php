<?php

namespace Laelaps\Bundle\Facebook\FacebookAdapter;

use BadMethodCallException;
use Laelaps\Bundle\Facebook\FacebookAdapter;

/**
 * This variation of FacebookAdapter should be used only for unit testing.
 *
 * Be careful when using this library, because changing internal parameters
 * of Facebook SDK and then making some API calls may lead to creating
 * requests that may be idenified by Facebook as malicious.
 */
class FacebookAdapterMock extends FacebookAdapter
{
    /**
     * @var null|string
     */
    private $mockedExtendedAccessToken;

    /**
     * @var array
     */
    private $mockedGraphApiCalls = [];

    /**
     * {@inheritDoc}
     */
    protected function _graph($path, $method = 'GET', $params = [])
    {
        if ($this->hasMockedGraphApiCall($path, $method, $params)) {
            return $this->getMockedGraphApiCall($path, $method, $params);
        }

        return parent::_graph($path, $method, $params);
    }

    /**
     * @param string $path
     * @param string $method
     * @param array $params
     * @return bool
     * @throws \Laelaps\Bundle\Facebook\Exception\FacebookApiException
     */
    public function getMockedGraphApiCall($path, $method = 'GET', array $params = [])
    {
        if (!$this->hasMockedGraphApiCall($path, $method, $params)) {
            throw new BadMethodCallException('This Facebook API call is not mocked.');
        }

        $encodedParams = json_encode($params);

        $ret = $this->mockedGraphApiCalls[$method][$path][$encodedParams];
        if (is_array($ret) && isset($ret['error'])) {
            $this->throwAPIException($ret);
        }

        return $ret;
    }

    /**
     * @param string $path
     * @param string $method
     * @param array $params
     * @return bool
     */
    public function hasMockedGraphApiCall($path, $method = 'GET', array $params = [])
    {
        if (!isset($this->mockedGraphApiCalls[$method])) {
            return false;
        }

        if (!isset($this->mockedGraphApiCalls[$method][$path])) {
            return false;
        }

        $encodedParams = json_encode($params);

        return isset($this->mockedGraphApiCalls[$method][$path][$encodedParams]);
    }

    /**
     * {@inheritDoc}
     */
    public function setExtendedAccessToken()
    {
        if (is_null($this->mockedExtendedAccessToken)) {
            return parent::setExtendedAccessToken();
        }

        $this->destroySession();
        $this->setPersistentData('access_token', $this->mockedExtendedAccessToken);
    }

    /**
     * @param null|string $extendedAccessToken
     * @return void
     */
    public function setMockedExtendedAccessToken($extendedAccessToken = null)
    {
        $this->mockedExtendedAccessToken = $extendedAccessToken;
    }

    /**
     * @param string $path
     * @param mixed $response
     * @param string $method
     * @param array $params
     * @return void
     */
    public function setMockedGraphApiCall($path, $response, $method = 'GET', array $params = [])
    {
        if (!isset($this->mockedGraphApiCalls[$method])) {
            $this->mockedGraphApiCalls[$method] = [];
        }

        if (!isset($this->mockedGraphApiCalls[$method][$path])) {
            $this->mockedGraphApiCalls[$method][$path] = [];
        }

        $encodedParams = json_encode($params);
        $this->mockedGraphApiCalls[$method][$path][$encodedParams] = $response;
    }

    /**
     * @param null|string $user
     * @return void
     */
    public function setUser($user = null)
    {
        $this->user = $user;
    }
}
