<?php

namespace Laelaps\Bundle\Facebook\Tests\DependencyInjection;

use Laelaps\Bundle\Facebook\Configuration\FacebookAdapter as FacebookAdapterConfiguration;
use Laelaps\Bundle\Facebook\Configuration\FacebookApplication as FacebookApplicationConfiguration;
use Laelaps\Bundle\Facebook\DependencyInjection\FacebookExtension;
use Laelaps\Bundle\Facebook\Tests\Fixture\Bundle\Test\DependencyInjection\TestExtension;
use Laelaps\Bundle\Facebook\Tests\KernelTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

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

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        TestExtension::isLoaded(false);
        TestExtension::setPhpUnitTestCase($this);
    }

    /**
     * {@inheritDoc}
     */
    public function tearDown()
    {
        TestExtension::isLoaded(false);
        TestExtension::setFacebookConfigurationPrefix(null);
        TestExtension::setPhpUnitTestCase(null);
    }

    public function testThatGuestBundleConfigurationIsParsed()
    {
        $this->assertFalse(TestExtension::isLoaded());

        $this->getKernel();

        $this->assertTrue(TestExtension::isLoaded());
    }

    public function testThatGuestBundleConfigurationIsPrefixed()
    {
        TestExtension::setFacebookConfigurationPrefix(uniqid());

        $this->assertFalse(TestExtension::isLoaded());

        $this->getKernel();

        $this->assertTrue(TestExtension::isLoaded());
    }

    /**
     * @expectedException \Laelaps\Bundle\Facebook\Exception\InvalidFacebookConfigurationPrefix
     */
    public function testThatInvalidPrefixIsDetected()
    {
        TestExtension::setFacebookConfigurationPrefix($arbitraryNumberInsteadOfString = 123);

        $this->assertFalse(TestExtension::isLoaded());

        $this->getKernel();

        $this->assertTrue(TestExtension::isLoaded());
    }

    public function testThatFacebookAdapterServiceIsCreated()
    {
        $config = [
            FacebookAdapterConfiguration::CONFIG_NODE_NAME_ADAPTER_SESSION_NAMESPACE => uniqid(),
            FacebookApplicationConfiguration::CONFIG_NODE_NAME_APPLICATION_ID => uniqid(),
            FacebookApplicationConfiguration::CONFIG_NODE_NAME_SECRET => uniqid(),
            FacebookApplicationConfiguration::CONFIG_NODE_NAME_FILE_UPLOAD => true,
            FacebookApplicationConfiguration::CONFIG_NODE_NAME_TRUST_PROXY_HEADERS => true,
        ];
        $facebookAdapterServiceId = uniqid();
        $facebookExtension = new FacebookExtension;

        $container = new ContainerBuilder;
        $container->set('session', $this->getContainer()->get('session'));

        $this->assertFalse($container->has($facebookAdapterServiceId));

        $facebookExtension->createFacebookAdapterService($config, $facebookAdapterServiceId, $container);

        $this->assertTrue($container->has($facebookAdapterServiceId));
        $this->assertInstanceOf('Laelaps\Bundle\Facebook\FacebookAdapter', $container->get($facebookAdapterServiceId));
    }
}
