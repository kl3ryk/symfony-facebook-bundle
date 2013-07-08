<?php

namespace Laelaps\Bundle\Facebook\Tests\Configuration;

use Laelaps\Bundle\Facebook\Configuration\FacebookApplication as FacebookApplicationConfiguration;
use PHPUnit_Framework_TestCase;
use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Processor;

class FacebookApplicationConfigurationTest extends PHPUnit_Framework_TestCase
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
        $applicationId = uniqid();
        $fileUpload = true;
        $permissions = [uniqid()];
        $secret = uniqid();
        $trustProxyHeaders = true;

        $config = [
            FacebookApplicationConfiguration::ROOT_NODE => [
                FacebookApplicationConfiguration::CONFIG_NODE_NAME_APPLICATION_ID => $applicationId,
                FacebookApplicationConfiguration::CONFIG_NODE_NAME_FILE_UPLOAD => $fileUpload,
                FacebookApplicationConfiguration::CONFIG_NODE_NAME_PERMISSIONS => $permissions,
                FacebookApplicationConfiguration::CONFIG_NODE_NAME_SECRET => $secret,
                FacebookApplicationConfiguration::CONFIG_NODE_NAME_TRUST_PROXY_HEADERS => $trustProxyHeaders,
            ],
        ];
        $correctlyProcessed = [
            FacebookApplicationConfiguration::CONFIG_NODE_NAME_APPLICATION_ID => $applicationId,
            FacebookApplicationConfiguration::CONFIG_NODE_NAME_FILE_UPLOAD => $fileUpload,
            FacebookApplicationConfiguration::CONFIG_NODE_NAME_PERMISSIONS => $permissions,
            FacebookApplicationConfiguration::CONFIG_NODE_NAME_SECRET => $secret,
            FacebookApplicationConfiguration::CONFIG_NODE_NAME_TRUST_PROXY_HEADERS => $trustProxyHeaders,
        ];

        $processed = $this->processConfiguration(new FacebookApplicationConfiguration, $config);
        $this->assertEquals($correctlyProcessed, $processed);
    }

    public function testThatMinimalConfigurationIsProperlyNormalized()
    {
        $applicationId = uniqid();
        $secret = uniqid();

        $config = [
            FacebookApplicationConfiguration::ROOT_NODE => [
                FacebookApplicationConfiguration::CONFIG_NODE_NAME_APPLICATION_ID => $applicationId,
                FacebookApplicationConfiguration::CONFIG_NODE_NAME_SECRET => $secret,
            ],
        ];
        $correctlyProcessed = [
            FacebookApplicationConfiguration::CONFIG_NODE_NAME_APPLICATION_ID => $applicationId,
            FacebookApplicationConfiguration::CONFIG_NODE_NAME_FILE_UPLOAD => false,
            FacebookApplicationConfiguration::CONFIG_NODE_NAME_PERMISSIONS => [],
            FacebookApplicationConfiguration::CONFIG_NODE_NAME_SECRET => $secret,
            FacebookApplicationConfiguration::CONFIG_NODE_NAME_TRUST_PROXY_HEADERS => false,
        ];

        $processed = $this->processConfiguration(new FacebookApplicationConfiguration, $config);
        $this->assertEquals($correctlyProcessed, $processed);
    }
}
