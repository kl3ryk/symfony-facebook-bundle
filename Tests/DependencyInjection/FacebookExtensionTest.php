<?php

namespace Laelaps\Bundle\Facebook\Tests\DependencyInjection;

use Laelaps\Bundle\Facebook\Tests\Fixture\Bundle\Test\DependencyInjection\TestExtension;
use Laelaps\Bundle\Facebook\Tests\KernelTestCase;

class FacebookExtensionTest extends KernelTestCase
{
    /**
     * {@inheritDoc}
     */
    public function getKernelBundles()
    {
        return array_merge(parent::getKernelBundles(), [
            new \Laelaps\Bundle\Facebook\Tests\Fixture\Bundle\Test\TestBundle,
        ]);
    }

    public function testThatGuestBundleConfigurationIsParsed()
    {
        TestExtension::isLoaded(false);
        TestExtension::setPhpUnitTestCase($this);

        $this->assertFalse(TestExtension::isLoaded());

        $this->getKernel();

        $this->assertTrue(TestExtension::isLoaded());
    }

    public function testThatGuestBundleConfigurationIsPrefixed()
    {
        TestExtension::isLoaded(false);
        TestExtension::setFacebookConfigurationPrefix(uniqid());
        TestExtension::setPhpUnitTestCase($this);

        $this->assertFalse(TestExtension::isLoaded());

        $this->getKernel();

        $this->assertTrue(TestExtension::isLoaded());
    }

    /**
     * @expectedException \Laelaps\Bundle\Facebook\Exception\InvalidFacebookConfigurationPrefix
     */
    public function testThatInvalidPrefixIsDetected()
    {
        TestExtension::isLoaded(false);
        TestExtension::setFacebookConfigurationPrefix($arbitraryNumberInsteadOfString = 123);

        $this->assertFalse(TestExtension::isLoaded());

        $this->getKernel();

        $this->assertTrue(TestExtension::isLoaded());
    }
}
