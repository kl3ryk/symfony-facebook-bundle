<?php

namespace Laelaps\Bundle\Facebook\Exception;

use DomainException;
use Exception;
use Laelaps\Bundle\Facebook\FacebookExtensionInterface;

class InvalidFacebookConfigurationPrefix extends DomainException
{
    /**
     * @param mixed $prefix
     * @param \Laelaps\Bundle\Facebook\FacebookExtensionInterface $extension
     * @param \Exception $previous
     * @param
     */
    public function __construct($prefix, FacebookExtensionInterface $extension, Exception $previous = null)
    {
        $message = sprintf('"%s" returned "%s" as a configuration prefix, expected "string".', gettype($prefix));

        parent::__construct($message, $code = 0, $previous);
    }
}
