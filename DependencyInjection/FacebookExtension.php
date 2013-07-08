<?php

namespace Laelaps\Bundle\Facebook\DependencyInjection;

use Laelaps\Bundle\Facebook\Configuration\FacebookAdapter as FacebookAdapterConfiguration;
use Laelaps\Bundle\Facebook\Configuration\FacebookApplication as FacebookApplicationConfiguration;
use Laelaps\Bundle\Facebook\Configuration\FacebookBundle as FacebookBundleConfiguration;
use Laelaps\Bundle\Facebook\Exception\InvalidFacebookConfigurationPrefix;
use Laelaps\Bundle\Facebook\FacebookAdapter;
use Laelaps\Bundle\Facebook\FacebookExtensionInterface;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Facebook container extension.
 *
 * @author Mateusz Charytoniuk <mateusz.charytoniuk@gmail.com>
 */
class FacebookExtension extends Extension implements PrependExtensionInterface
{
    /**
     * @var string
     */
    const CONTAINER_DEFAULT_SERVICE_ALIAS_FACEBOOK_ADAPTER = 'facebook';

    /**
     * @var string
     */
    const CONTAINER_DEFAULT_SESSION_FACEBOOK_ADAPTER_NAMESPACE = 'facebook_';

    /**
     * @var string
     */
    const CONTAINER_SERVICE_ID_FACEBOOK_ADAPTER = 'laelaps.facebook.facebook_adapter';

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
        $prefix = $extension->getFacebookConfigurationPrefix($config, $container);
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
        $this->configurationSchema = new FacebookBundleConfiguration;
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
        $config = $this->getExtensionConfiguration($container);

        $definition = new Definition('Laelaps\Bundle\Facebook\FacebookAdapter');
        $definition->addArgument([
            'appId' => $config[FacebookApplicationConfiguration::CONFIG_NODE_NAME_APPLICATION_ID],
            'secret' => $config[FacebookApplicationConfiguration::CONFIG_NODE_NAME_SECRET],
            'fileUpload' => $config[FacebookApplicationConfiguration::CONFIG_NODE_NAME_FILE_UPLOAD],
            'trustForwarded' => $config[FacebookApplicationConfiguration::CONFIG_NODE_NAME_TRUST_PROXY_HEADERS],
        ]);
        $definition->addArgument(new Reference('session'));
        $definition->addArgument(self::CONTAINER_DEFAULT_SESSION_FACEBOOK_ADAPTER_NAMESPACE);

        $container->setDefinition(self::CONTAINER_SERVICE_ID_FACEBOOK_ADAPTER, $definition);
        $container->setAlias($config[FacebookAdapterConfiguration::CONFIG_NODE_NAME_ADAPTER_SERVICE_ALIAS], self::CONTAINER_SERVICE_ID_FACEBOOK_ADAPTER);
    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @return void
     * @throws \Laelaps\Bundle\Facebook\Exception\InvalidFacebookConfigurationPrefix
     */
    public function prepend(ContainerBuilder $container)
    {
        $config = $this->getExtensionConfiguration($container);
        $stripped = $this->configurationSchema->stripFacebookAdapterConfiguration($config);

        foreach ($container->getExtensions() as $name => $extension) {
            if ($extension instanceof FacebookExtensionInterface) {
                if ($extension->getFacebookApplicationConfigurationOnly($container)) {
                    $container->prependExtensionConfig($name, $this->prefixFacebookConfiguration($stripped, $extension, $container));
                } else {
                    $container->prependExtensionConfig($name, $this->prefixFacebookConfiguration($config, $extension, $container));
                }
            }
        }
    }
}
