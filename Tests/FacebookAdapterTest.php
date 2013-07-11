<?php

namespace Laelaps\Bundle\Facebook\Tests;

use Laelaps\Bundle\Facebook\DependencyInjection\FacebookExtension;
use Laelaps\Bundle\Facebook\FacebookAdapter;
use Laelaps\Bundle\Facebook\Tests\Fixture\Bundle\Test\Controller\TestController;
use Laelaps\Bundle\Facebook\Tests\Fixture\Bundle\Test\DependencyInjection\TestExtension;
use Symfony\Component\HttpFoundation\Request;

class FacebookAdapterTest extends KernelTestCase
{
    /**
     * {@inheritDoc}
     */
    public function getKernelBundles()
    {
        return array_merge(parent::getKernelBundles(), [
            new \Laelaps\Bundle\Facebook\Tests\Fixture\Bundle\Test\TestBundle,
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        TestExtension::setPhpUnitTestCase($this);
    }

    /**
     * {@inheritDoc}
     */
    public function tearDown()
    {
        TestExtension::setPhpUnitTestCase(null);
    }

    /**
     * @return \Laelaps\Bundle\Facebook\FacebookAdapter
     */
    private function getFacebookAdapter()
    {
        $container = $this->getContainer();

        return new FacebookAdapter([
            'appId' => uniqid(),
            'secret' => uniqid(),
        ], $container->get('session'), uniqid());
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    private function getRequest()
    {
        return new Request($query = [], $request = [], $attributes = [
            'callee' => __CLASS__,
        ], $cookies = [], $files = [], $server = array_merge($_SERVER, [
            'REQUEST_URI' => '/test-facebook-adapter-request',
        ]));
    }

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

    /**
     * @expectedException \BadMethodCallException
     */
    public function testThatContainerCannotBeFetchedWhenNotSet()
    {
        $facebook = $this->getFacebookAdapter();
        $facebook->getContainer();
    }

    public function testThatContainerCanBeSet()
    {
        $container = $this->getContainer();
        $facebook = $this->getFacebookAdapter();

        $facebook->setContainer($container);

        $this->assertSame($container, $facebook->getContainer());
    }

    public function testThatContainerCanBeUnset()
    {
        $container = $this->getContainer();
        $facebook = $this->getFacebookAdapter();

        $facebook->setContainer($container);

        $this->assertSame($container, $facebook->getContainer());

        $facebook->setContainer(null);

        $this->setExpectedException('BadMethodCallException');
        $facebook->getContainer();
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testThatRequestCannotBeFetchedWhenNotSet()
    {
        $facebook = $this->getFacebookAdapter();
        $facebook->getRequest();
    }

    /**
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage scope
     */
    public function testThatRequestCannotBeFetchWhenNotSetAndContainerRequestIsOutOfScope()
    {
        $facebook = $this->getFacebookAdapter();
        $facebook->setContainer($this->getContainer());

        $facebook->getRequest();
    }

    /**
     * @see \Laelaps\Bundle\Facebook\Tests\Fixture\Bundle\Test\Controller\TestController::testFacebookAdapterRequestAction
     */
    public function testThatRequestCanBeFetchWhenNotSetAndContainerHasRequest()
    {
        TestController::setFacebookAdapter($this->getFacebookAdapter());
        TestController::setPHPUnit($this);

        $request = $this->getRequest();
        $response = $this->getKernel()->handle($request);

        $this->assertSame(__CLASS__, $response->getContent());

        TestController::setFacebookAdapter(null);
        TestController::setPHPUnit(null);
    }

    public function testThatRequestCanBeSet()
    {
        $facebook = $this->getFacebookAdapter();

        $request = $this->getRequest();
        $facebook->setRequest($request);

        $this->assertSame($request, $facebook->getRequest());
    }

    public function testThatRequestCanBeUnset()
    {
        $request = $this->getRequest();
        $facebook = $this->getFacebookAdapter();

        $facebook->setRequest($request);

        $this->assertSame($request, $facebook->getRequest());

        $facebook->setRequest(null);

        $this->setExpectedException('BadMethodCallException');
        $facebook->getRequest();
    }
}
