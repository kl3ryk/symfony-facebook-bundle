<?php

namespace Laelaps\Bundle\Facebook;

use Laelaps\Bundle\Facebook\DependencyInjection\FacebookExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

interface FacebookExtensionInterface extends ExtensionInterface
{
    /**
     * Optionally prefix imported configuration with given string.
     *
     * @param array $config
     * @param \Laelaps\Bundle\Facebook\DependencyInjection\FacebookExtension $extension
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @return null|string
     */
    public function getFacebookConfigurationPrefix(array $config, FacebookExtension $extension, ContainerBuilder $container);
}
