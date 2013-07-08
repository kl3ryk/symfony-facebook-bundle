<?php

namespace Laelaps\Bundle\Facebook;

use Laelaps\Bundle\Facebook\DependencyInjection\FacebookExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @implements \Laelaps\Bundle\Facebook\FacebookExtensionInterface
 */
trait FacebookExtensionTrait
{
    /**
     * Optionally prefix imported configuration with given string.
     *
     * @param array $config
     * @param \Laelaps\Bundle\Facebook\DependencyInjection\FacebookExtension $extension
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @return null|string
     */
    public function getFacebookConfigurationPrefix(array $config, FacebookExtension $extension, ContainerBuilder $container) {}

    /**
     * Tell whether to strip dependency injection configuration or not.
     *
     * @param \Laelaps\Bundle\Facebook\DependencyInjection\FacebookExtension $extension
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @return bool if true then strip dependencyinjection configuration section
     * @see \Laelaps\Bundle\Facebook\Configuration\FacebookApplication::addFacebookAdapterSection
     */
    public function getFacebookApplicationConfigurationOnly(FacebookExtension $extension, ContainerBuilder $container)
    {
        return true;
    }
}
