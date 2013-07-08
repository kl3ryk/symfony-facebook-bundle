<?php

namespace Laelaps\Bundle\Facebook;

use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * This trait covers methods defined directly in FacebookExtensionInferace.
 */
trait FacebookExtensionTrait
{
    /**
     * Optionally prefix imported configuration with given string.
     *
     * @param array $config
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @return null|string
     */
    public function getFacebookConfigurationPrefix(array $config, ContainerBuilder $container) {}

    /**
     * Tell whether to strip dependency injection configuration or not.
     *
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @return bool if true then strip dependencyinjection configuration section
     * @see \Laelaps\Bundle\Facebook\Configuration\FacebookApplication::addFacebookAdapterSection
     */
    public function getFacebookApplicationConfigurationOnly(ContainerBuilder $container)
    {
        return true;
    }
}
