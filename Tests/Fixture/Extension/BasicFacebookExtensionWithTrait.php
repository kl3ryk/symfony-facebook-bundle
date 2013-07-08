<?php

namespace Laelaps\Bundle\Facebook\Tests\Fixture\Extension;

use Laelaps\Bundle\Facebook\FacebookExtensionInterface;
use Laelaps\Bundle\Facebook\FacebookExtensionTrait;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class BasicFacebookExtensionWithTrait extends Extension implements FacebookExtensionInterface
{
    use FacebookExtensionTrait;

    /**
     * @param array $configs
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @return void
     * @throws \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function load(array $configs, ContainerBuilder $container) {}
}
