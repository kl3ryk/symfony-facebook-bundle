<?php

namespace Laelaps\Bundle\Facebook\Tests\Fixture\Bundle\Test\Controller;

use Laelaps\Bundle\Facebook\FacebookAdapter;
use PHPUnit_Framework_TestCase;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TestController extends Controller
{
    /**
     * @var \Laelaps\Bundle\Facebook\FacebookAdapter
     */
    private static $facebookAdapter;

    /**
     * @var \PHPUnit_Framework_TestCase
     */
    private static $phpunit;

    /**
     * @param \Laelaps\Bundle\Facebook\FacebookAdapter $facebookAdapter
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \PHPUnit_Framework_TestCase $phpunit
     * @return void
     */
    public function testThatRequestCanBeFetchedBasingContainer(FacebookAdapter $facebookAdapter, ContainerInterface $container, Request $request, PHPUnit_Framework_TestCase $phpunit)
    {
        $phpunit->assertTrue($container->has('request'));

        $facebookAdapter->setContainer($container);
        $facebookAdapter->setRequest(null);

        $phpunit->assertSame($request, static::$facebookAdapter->getRequest());
    }

    /**
     * @param \Laelaps\Bundle\Facebook\FacebookAdapter $facebookAdapter
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     * @param \PHPUnit_Framework_TestCase $phpunit
     * @return void
     */
    public function testThatSessionCanBeFetchedBasingOnContainer(FacebookAdapter $facebookAdapter, ContainerInterface $container, PHPUnit_Framework_TestCase $phpunit)
    {
        $facebookAdapter->setContainer($container);
        $facebookAdapter->setRequest(null);
        $facebookAdapter->setSession(null);

        $phpunit->assertSame($facebookAdapter->getSession(), $container->get('session'));
    }

    /**
     * @param \Laelaps\Bundle\Facebook\FacebookAdapter $facebookAdapter
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \PHPUnit_Framework_TestCase $phpunit
     * @return void
     */
    public function testThatSessionCanBeFetchedBasingOnRequest(FacebookAdapter $facebookAdapter, Request $request, PHPUnit_Framework_TestCase $phpunit)
    {
        $facebookAdapter->setContainer(null);
        $facebookAdapter->setRequest($request);
        $facebookAdapter->setSession(null);

        $phpunit->assertSame($facebookAdapter->getSession(), $request->getSession());
    }

    /**
     * @param string $callee
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function testFacebookAdapterRequestAction($callee, Request $request)
    {
        $phpunit = static::$phpunit;

        $this->testThatRequestCanBeFetchedBasingContainer(static::$facebookAdapter, $this->container, $request, $phpunit);
        $this->testThatSessionCanBeFetchedBasingOnContainer(static::$facebookAdapter, $this->container, $phpunit);
        $this->testThatSessionCanBeFetchedBasingOnRequest(static::$facebookAdapter, $request, $phpunit);

        return new Response($callee);
    }

    /**
     * @param \Laelaps\Bundle\Facebook\FacebookAdapter $facebookAdapter
     * @return void
     */
    public static function setFacebookAdapter(FacebookAdapter $facebookAdapter = null)
    {
        static::$facebookAdapter = $facebookAdapter;
    }

    /**
     * @param \PHPUnit_Framework_TestCase $phpunit
     * @return void
     */
    public static function setPHPUnit(PHPUnit_Framework_TestCase $phpunit = null)
    {
        static::$phpunit = $phpunit;
    }
}
