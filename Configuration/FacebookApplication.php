<?php

namespace Laelaps\Bundle\Facebook\Configuration;

use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class FacebookApplication implements ConfigurationInterface
{
    /**
     * @var string
     */
    const CONFIG_NODE_NAME_APPLICATION_ID = 'application_id';

    /**
     * @var string
     */
    const CONFIG_NODE_NAME_FILE_UPLOAD = 'file_upload';

    /**
     * @var string
     */
    const CONFIG_NODE_NAME_PERMISSIONS = 'permissions';

    /**
     * @var string
     */
    const CONFIG_NODE_NAME_SECRET = 'secret';

    /**
     * @var string
     */
    const CONFIG_NODE_NAME_TRUST_PROXY_HEADERS = 'trust_proxy_headers';

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
    public function addFacebookApplicationSection(NodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->scalarNode(self::CONFIG_NODE_NAME_APPLICATION_ID)
                    ->cannotBeEmpty()
                    ->isRequired()
                ->end()
                ->booleanNode(self::CONFIG_NODE_NAME_FILE_UPLOAD)
                    ->defaultFalse()
                ->end()
                ->arrayNode(self::CONFIG_NODE_NAME_PERMISSIONS)
                    ->prototype('scalar')
                    ->end()
                ->end()
                ->scalarNode(self::CONFIG_NODE_NAME_SECRET)
                    ->cannotBeEmpty()
                    ->isRequired()
                ->end()
                ->booleanNode(self::CONFIG_NODE_NAME_TRUST_PROXY_HEADERS)
                    ->defaultFalse()
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

        $this->addFacebookApplicationSection($rootNode);

        return $treeBuilder;
    }
}
