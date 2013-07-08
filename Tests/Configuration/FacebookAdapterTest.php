<?php

namespace Laelaps\Bundle\Facebook\Tests\Configuration;

use Laelaps\Bundle\Facebook\Configuration\FacebookAdapter as FacebookAdapterConfiguration;
use Laelaps\Bundle\Facebook\DependencyInjection\FacebookExtension;
use PHPUnit_Framework_TestCase;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;

class FacebookAdapterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @param \Symfony\Component\Config\Definition\ConfigurationInterface $configuration
     * @param array $configs
     * @return array
     */
    protected function processConfiguration(ConfigurationInterface $configuration, array $configs)
    {
        $processor = new Processor;

        return $processor->processConfiguration($configuration, $configs);
    }

    public function testThatCompleteConfigurationIsProperlyNormalized()
    {
        $adapterServiceAlias = uniqid();
        $adapterSessionNamespace = uniqid();

        $config = [
            FacebookAdapterConfiguration::ROOT_NODE => [
                FacebookAdapterConfiguration::CONFIG_NODE_NAME_ADAPTER_SERVICE_ALIAS => $adapterServiceAlias,
                FacebookAdapterConfiguration::CONFIG_NODE_NAME_ADAPTER_SESSION_NAMESPACE => $adapterSessionNamespace,
            ],
        ];
        $correctlyProcessed = [
            FacebookAdapterConfiguration::CONFIG_NODE_NAME_ADAPTER_SERVICE_ALIAS => $adapterServiceAlias,
            FacebookAdapterConfiguration::CONFIG_NODE_NAME_ADAPTER_SESSION_NAMESPACE => $adapterSessionNamespace,
        ];

        $processed = $this->processConfiguration(new FacebookAdapterConfiguration, $config);
        $this->assertEquals($correctlyProcessed, $processed);
    }

    public function testThatMinimalConfigurationIsProperlyNormalized()
    {
        $config = [
            FacebookAdapterConfiguration::ROOT_NODE => [],
        ];
        $correctlyProcessed = [
            FacebookAdapterConfiguration::CONFIG_NODE_NAME_ADAPTER_SERVICE_ALIAS => FacebookExtension::CONTAINER_DEFAULT_SERVICE_ALIAS_FACEBOOK_ADAPTER,
            FacebookAdapterConfiguration::CONFIG_NODE_NAME_ADAPTER_SESSION_NAMESPACE => FacebookExtension::CONTAINER_DEFAULT_SESSION_FACEBOOK_ADAPTER_NAMESPACE,
        ];

        $processed = $this->processConfiguration(new FacebookAdapterConfiguration, $config);
        $this->assertEquals($correctlyProcessed, $processed);
    }
}
