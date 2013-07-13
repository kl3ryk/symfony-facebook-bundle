<?php

namespace Laelaps\Bundle\Facebook;

use BadMethodCallException;
use BaseFacebook;
use FacebookApiException as BaseFacebookApiException;
use Laelaps\Bundle\Facebook\Exception\FacebookApiException;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\InactiveScopeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class FacebookAdapter extends BaseFacebook
{
    /**
     * Config is stored for easier inheritance and logging purposes.
     * Changing this variable WILL NOT affect object configuration.
     *
     * @var mixed
     * @see \Laelaps\Bundle\Facebook\FacebookAdapter\FacebookAdapterMock::fromFacebookAdapter
     */
    protected $config;

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    private $request;

    /**
     * @var \Symfony\Component\HttpFoundation\Session\Session
     */
    private $session;

    /**
     * @var string
     */
    private $sessionNamespace;

    /**
     * @var array
     */
    private $storedPersistentData = [];

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private static $logger;

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private static $staticContainer;

    /**
     * @param string $key
     * @return string
     */
    private function namespaceSessionKey($key)
    {
        if (!is_string($this->sessionNamespace)) {
            return $key;
        }

        return $this->sessionNamespace . $key;
    }

    /**
     * {@inheritDoc}
     */
    protected function getCurrentUrl()
    {
        try {
            $request = $this->getRequest();
        } catch (BadMethodCallException $e) {
            return parent::getCurrentUrl();
        }

        return $request->getUri();
    }

    /**
     * {@inheritDoc}
     */
    protected function getHttpHost()
    {
        try {
            $request = $this->getRequest();
        } catch (BadMethodCallException $e) {
            return parent::getHttpHost();
        }

        return $request->getHttpHost();
    }

    /**
     * {@inheritDoc}
     */
    protected function getHttpProtocol()
    {
        try {
            $request = $this->getRequest();
        } catch (BadMethodCallException $e) {
            return parent::getHttpProtocol();
        }

        return $request->getScheme();
    }

    /**
     * {@inheritDoc}
     */
    protected function getPersistentData($key, $default = false)
    {
        $key = $this->namespaceSessionKey($key);

        return $this->getSession()->get($key, $default);
    }

    /**
     * {@inheritDoc}
     */
    protected function clearAllPersistentData()
    {
        foreach ($this->storedPersistentData as $key => $value) {
            $this->clearPersistentData($key);
        }
    }

    /**
     * {@inheritDoc}
     */
    protected function clearPersistentData($key)
    {
        $this->getSession()->remove($this->namespaceSessionKey($key));
    }

    /**
     * {@inheritDoc}
     */
    protected function setPersistentData($key, $value)
    {
        $this->storedPersistentData[$key] = true;

        $this->getSession()->set($this->namespaceSessionKey($key), $value);
    }

    /**
     * {@inheritDoc}
     */
    protected function throwAPIException($result)
    {
        try {
            parent::throwAPIException($result);
        } catch (BaseFacebookApiException $e) {
            throw new FacebookApiException($e, $this);
        }
    }

    /**
     * @param string $message
     */
    protected static function errorLog($message)
    {
        if (static::$logger instanceof LoggerInterface) {
            static::$logger->error($message, $context = $this->config);
        } else {
            parent::errorLog($message);
        }
    }

    /**
     * @param array $config
     * @param \Symfony\Component\HttpFoundation\Session\Session $session
     * @param string $sessionNamespace
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct(array $config, Session $session = null, $sessionNamespace = null, Request $request = null, ContainerInterface $container = null)
    {
        $this->config = $config;

        $this->setContainer($container);
        $this->setRequest($request);
        $this->setSession($session);
        $this->setSessionNamespace($sessionNamespace);

        parent::__construct($config);
    }

    /**
     * @return \Symfony\Component\DependencyInjection\ContainerInterface
     * @throws \BadMethodCallException If Container is not set
     */
    public function getContainer()
    {
        if (!($this->container instanceof ContainerInterface)) {
            throw new BadMethodCallException('ContainerInterface is not set.');
        }

        return $this->container;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array $parameters
     */
    public function getLoginUrlForRequest(Request $request, array $parameters = [])
    {
        $backupRequest = $this->request;
        $this->request = $request;
        $ret = parent::getLoginUrl($parameters);
        $this->request = $backupRequest;

        return $ret;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request $request
     * @throws \BadMethodCallException If Request is not set
     */
    public function getRequest()
    {
        if ($this->request instanceof Request) {
            return $this->request;
        }

        if ($this->container instanceof ContainerInterface && $this->container->has('request')) {
            try {
                return $this->container->get('request');
            } catch (InactiveScopeException $e) {
                throw new BadMethodCallException('Request is not set and out of container scope.');
            }
        }

        throw new BadMethodCallException('Request is not set.');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Session\Session
     * @throws \BadMethodCallException If Session is not set
    */
    public function getSession()
    {
        if ($this->session instanceof Session) {
            return $this->session;
        }

        if ($this->request instanceof Request) {
            return $this->request->getSession();
        }

        if ($this->container instanceof ContainerInterface && $this->container->has('session')) {
            return $this->container->get('session');
        }

        throw new BadMethodCallException('Session is not set.');
    }

    /**
     * @return string
    */
    public function getSessionNamespace()
    {
        return $this->sessionNamespace;
    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    public function setRequest(Request $request = null)
    {
        $this->request = $request;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Session\Session $session
     * @return void
     */
    public function setSession(Session $session = null)
    {
        $this->session = $session;
    }

    /**
     * @param string $namespace
     * @return void
     */
    public function setSessionNamespace($namespace = null)
    {
        if (is_null($namespace)) {
            $this->sessionNamespace = null;
        } else {
            $this->sessionNamespace = (string) $namespace;
        }
    }

    /**
     * @return \Psr\Log\LoggerInterface
     * @throws \BadMethodCallException If Logger is not set
     */
    public static function getLogger()
    {
        if (static::$logger instanceof LoggerInterface) {
            return static::$logger;
        }

        if (static::$staticContainer instanceof ContainerInterface && static::$staticContainer->has('logger')) {
            return static::$staticContainer->get('logger');
        }

        throw new BadMethodCallException('Logger is not set.');
    }

    /**
     * @param \Psr\Log\LoggerInterface $logger
     */
    public static function setLogger(LoggerInterface $logger = null)
    {
        static::$logger = $logger;
    }

    /**
     * @param \Psr\Log\LoggerInterface $logger
     */
    public static function staticSetContainer(ContainerInterface $container = null)
    {
        static::$staticContainer = $container;
    }
}
