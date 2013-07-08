<?php

namespace Laelaps\Bundle\Facebook\Tests;

use Laelaps\Bundle\Facebook\DependencyInjection\FacebookExtension;

class FacebookAdapterTest extends KernelTestCase
{
    public function testThatFacebookAdapterCanBeFetchedViaServiceAlias()
    {
        $facebook = $this->getContainer()
            ->get(FacebookExtension::CONTAINER_DEFAULT_SERVICE_ALIAS_FACEBOOK_ADAPTER)
        ;

        $this->assertInstanceOf('Laelaps\Bundle\Facebook\FacebookAdapter', $facebook);
    }

    public function testThatFacebookAdapterCanBeFetchedViaServiceI()
    {
        $facebook = $this->getContainer()
            ->get(FacebookExtension::CONTAINER_SERVICE_ID_FACEBOOK_ADAPTER)
        ;

        $this->assertInstanceOf('Laelaps\Bundle\Facebook\FacebookAdapter', $facebook);
    }


    public function testFacebookAdapter()
    {
        $facebook = $this->getContainer()
            ->get(FacebookExtension::CONTAINER_SERVICE_ID_FACEBOOK_ADAPTER)
        ;

        $this->assertInstanceOf('Laelaps\Bundle\Facebook\FacebookAdapter', $facebook);
    }
}
