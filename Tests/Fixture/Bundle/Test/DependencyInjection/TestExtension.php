<?php

namespace Laelaps\Bundle\Facebook\Tests\Fixture\Bundle\Test\DependencyInjection;

use BadMethodCallException;
use Laelaps\Bundle\Facebook\Configuration\FacebookApplication as FacebookApplicationConfiguration;
use Laelaps\Bundle\Facebook\FacebookExtensionInterface;
use PHPUnit_Framework_TestCase;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class TestExtension extends Extension implements FacebookExtensionInterface
{
    /**
     * @var bool
     */
    private static $isLoaded;

    /**
     * @var \PHPUnit_Framework_TestCase
     */
    private static $phpunit;

    /**
     * @var null|string
     */
    private static $prefix;

    /**
     * @param array $config
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @return null|string
     */
    public function getFacebookConfigurationPrefix(array $config, ContainerBuilder $container)
    {
        return self::$prefix;
    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @return bool
     */
    public function getFacebookApplicationConfigurationOnly(ContainerBuilder $container)
    {
        return true;
    }

    /**
     * @param array $configs
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @return void
     * @throws \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $phpunit = $this->getPhpUnitTestCase();

        $phpunit->assertCount(2, $configs, 'bundle should have its own config and facebook bundle prepended config');

        $config = $configs[0];

        $phpunit->assertCount(5, $config, 'bundle configuration is oversized');

        $phpunit->assertArrayHasKey(self::$prefix . FacebookApplicationConfiguration::CONFIG_NODE_NAME_APPLICATION_ID, $config);
        $phpunit->assertArrayHasKey(self::$prefix . FacebookApplicationConfiguration::CONFIG_NODE_NAME_FILE_UPLOAD, $config);
        $phpunit->assertArrayHasKey(self::$prefix . FacebookApplicationConfiguration::CONFIG_NODE_NAME_PERMISSIONS, $config);
        $phpunit->assertArrayHasKey(self::$prefix . FacebookApplicationConfiguration::CONFIG_NODE_NAME_SECRET, $config);
        $phpunit->assertArrayHasKey(self::$prefix . FacebookApplicationConfiguration::CONFIG_NODE_NAME_TRUST_PROXY_HEADERS, $config);

        self::$isLoaded = true;
    }

    /**
     * @return \PHPUnit_Framework_TestCase
     * @throws \BadMethodCallException
     */
    public static function getPhpUnitTestCase()
    {
        if (!(self::$phpunit instanceof PHPUnit_Framework_TestCase)) {
            throw new BadMethodCallException('PHPUnit Test Case is not set.');
        }

        return self::$phpunit;
    }

    /**
     * @param bool|null $isLoaded
     */
    public static function isLoaded($isLoaded = null)
    {
        if (is_null($isLoaded)) {
            return self::$isLoaded;
        }

        self::$isLoaded = $isLoaded;
    }

    /**
     * @param \PHPUnit_Framework_TestCase $phpunit
     * @return void
     */
    public static function setPhpUnitTestCase(PHPUnit_Framework_TestCase $phpunit)
    {
        self::$phpunit = $phpunit;
    }

    /**
     * @param null|string $prefix
     * @return void
     */
    public static function setFacebookConfigurationPrefix($prefix)
    {
        self::$prefix = $prefix;
    }
}
