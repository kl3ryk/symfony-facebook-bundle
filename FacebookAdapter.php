<?php

namespace Laelaps\Bundle\Facebook;

use BaseFacebook;
use Symfony\Component\HttpFoundation\Session\Session;

class FacebookAdapter extends BaseFacebook
{
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
    protected function getPersistentData($key, $default = false)
    {
        $key = $this->namespaceSessionKey($key);

        return $this->session->get($key, $default);
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
        $this->session->remove($this->namespaceSessionKey($key));
    }

    /**
    * {@inheritDoc}
    */
    protected function setPersistentData($key, $value)
    {
        $this->storedPersistentData[$key] = true;

        $this->session->set($this->namespaceSessionKey($key), $value);
    }

    /**
     * @param mixed $config
     * @param \Symfony\Component\HttpFoundation\Session\Session $session
     * @param string $sessionNamespace
     */
    public function __construct($config, Session $session, $sessionNamespace = null)
    {
        $this->setSession($session);
        $this->setSessionNamespace($sessionNamespace);

        parent::__construct($config);
    }

    /**
    * @return \Symfony\Component\HttpFoundation\Session\Session
    */
    public function getSession()
    {
        return $this->session;
    }

    /**
    * @return string
    */
    public function getSessionNamespace()
    {
        return $this->sessionNamespace;
    }

    /**
    * @param \Symfony\Component\HttpFoundation\Session\Session $session
    * @return void
    */
    public function setSession(Session $session)
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
}
