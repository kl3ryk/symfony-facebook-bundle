<?php

namespace Laelaps\Bundle\Facebook\Configuration;

use Laelaps\Bundle\Facebook\DependencyInjection\FacebookExtension;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class FacebookAdapter implements ConfigurationInterface
{
    /**
     * @var string
     */
    const CONFIG_NODE_NAME_ADAPTER_SERVICE_ALIAS = 'adapter_service_id';

    /**
     * @var string
     */
    const CONFIG_NODE_NAME_ADAPTER_SESSION_NAMESPACE = 'adapter_session_namespace';

    /**
     * @var string
     */
    const ROOT_NODE = 'facebook';

    /**
     * @api
     * @param \Symfony\Component\Config\Definition\Builder\NodeDefinition $rootNode
     * @param bool $isRequired
     * @return void
     * @see \Laelaps\Bundle\Facebook\Configuration\FacebookBundle::getConfigTreeBuilder
     */
    public function addFacebookAdapterSection(NodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->scalarNode(self::CONFIG_NODE_NAME_ADAPTER_SERVICE_ALIAS)
                    ->cannotBeEmpty()
                    ->defaultValue(FacebookExtension::CONTAINER_DEFAULT_SERVICE_ALIAS_FACEBOOK_ADAPTER)
                ->end()
                ->scalarNode(self::CONFIG_NODE_NAME_ADAPTER_SESSION_NAMESPACE)
                    ->defaultValue(FacebookExtension::CONTAINER_DEFAULT_SESSION_FACEBOOK_ADAPTER_NAMESPACE)
                ->end()
            ->end()
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder;
        $rootNode = $treeBuilder->root(self::ROOT_NODE);

        $this->addFacebookAdapterSection($rootNode);

        return $treeBuilder;
    }

    /**
     * @param array $config
     * @return array
     */
    public function stripFacebookAdapterConfiguration(array $config)
    {
        unset($config[self::CONFIG_NODE_NAME_ADAPTER_SERVICE_ALIAS]);
        unset($config[self::CONFIG_NODE_NAME_ADAPTER_SESSION_NAMESPACE]);

        return $config;
    }
}
