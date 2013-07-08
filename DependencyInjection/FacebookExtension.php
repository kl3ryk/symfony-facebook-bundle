<?php

namespace Laelaps\Bundle\Facebook\DependencyInjection;

use Laelaps\Bundle\Facebook\Configuration\FacebookApplication as FacebookApplicationConfiguration;
use Laelaps\Bundle\Facebook\Exception\InvalidFacebookConfigurationPrefix;
use Laelaps\Bundle\Facebook\FacebookExtensionInterface;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Facebook container extension.
 *
 * @author Mateusz Charytoniuk <mateusz.charytoniuk@gmail.com>
 */
class FacebookExtension extends Extension implements PrependExtensionInterface
{
    /**
     * @var \Laelaps\Bundle\Facebook\Configuration\FacebookApplication
     */
    private $configurationSchema;

    /**
     * @var array
     */
    private $processedConfiguration;

    /**
     * @param array $config
     * @param \Laelaps\Bundle\Facebook\FacebookExtensionInterface $extension
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @throws \Laelaps\Bundle\Facebook\Exception\InvalidFacebookConfigurationPrefix
     */
    private function prefixFacebookConfiguration(array & $config, FacebookExtensionInterface $extension, ContainerBuilder $container)
    {
        $prefix = $extension->getFacebookConfigurationPrefix($config, $this, $container);
        if (is_null($prefix)) {
            return $config;
        }
        if (!is_string($prefix)) {
            throw new InvalidFacebookConfigurationPrefix($prefix, $extension);
        }

        $ret = [];
        foreach ($config as $index => & $value) {
            $ret[$prefix . $index] = $value;
        }

        return $ret;
    }

    public function __construct()
    {
        $this->configurationSchema = new FacebookApplicationConfiguration;
    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @return array
     */
    public function getExtensionConfiguration(ContainerBuilder $container)
    {
        if (isset($this->processedConfiguration)) {
            return $this->processedConfiguration;
        }

        $this->processedConfiguration = $container->getExtensionConfig($this->getAlias());
        $this->processedConfiguration = $this->processConfiguration($this->configurationSchema, $this->processedConfiguration);

        return $this->processedConfiguration;
    }

    /**
     * @param array $configs
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @return void
     * @throws \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function load(array $configs, ContainerBuilder $container)
    {
    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @return void
     * @throws \Laelaps\Bundle\Facebook\Exception\InvalidFacebookConfigurationPrefix
     */
    public function prepend(ContainerBuilder $container)
    {
        $config = $this->getExtensionConfiguration($container);

        foreach ($container->getExtensions() as $name => $extension) {
            if ($extension instanceof FacebookExtensionInterface) {
                $container->prependExtensionConfig($name, $this->prefixFacebookConfiguration($config, $extension, $container));
            }
        }
    }
}
