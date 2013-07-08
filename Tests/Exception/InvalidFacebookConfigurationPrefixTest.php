<?php

namespace Laelaps\Bundle\Facebook\Tests\Exception;

use Laelaps\Bundle\Facebook\Exception\InvalidFacebookConfigurationPrefix;
use PHPUnit_Framework_TestCase;

class InvalidFacebookConfigurationPrefixTest extends PHPUnit_Framework_TestCase
{
    public function testThatExceptionCanBeInstanciated()
    {
        $extension = $this->getMock('\Laelaps\Bundle\Facebook\FacebookExtensionInterface');

        new InvalidFacebookConfigurationPrefix($prefix = uniqid(), $extension);
    }
}
