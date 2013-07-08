<?php

namespace Laelaps\Bundle\Facebook\Configuration;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class FacebookBundle implements ConfigurationInterface
{
    /**
     * @var string
     */
    const ROOT_NODE = 'facebook';

    /**
     * @var \Laelaps\Bundle\Facebook\Configuration\FacebookAdapter
     */
    private $facebookAdapterConfiguration;

    /**
     * @var \Laelaps\Bundle\Facebook\Configuration\FacebookApplication
     */
    private $facebookApplicationConfiguration;

    public function __construct()
    {
        $this->facebookAdapterConfiguration = new FacebookAdapter;
        $this->facebookApplicationConfiguration = new FacebookApplication;
    }

    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder;
        $rootNode = $treeBuilder->root(self::ROOT_NODE);

        $this->facebookAdapterConfiguration->addFacebookAdapterSection($rootNode);
        $this->facebookApplicationConfiguration->addFacebookApplicationSection($rootNode);

        return $treeBuilder;
    }

    /**
     * @param array $config
     * @return array
     */
    public function stripFacebookAdapterConfiguration(array $config)
    {
        return $this->facebookAdapterConfiguration
            ->stripFacebookAdapterConfiguration($config)
        ;
    }
}
