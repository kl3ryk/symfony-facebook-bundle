<?php

namespace Laelaps\Bundle\Facebook\Tests\Fixture\Bundle\Test\Controller;

use Laelaps\Bundle\Facebook\FacebookAdapter;
use PHPUnit_Framework_TestCase;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function testFacebookAdapterRequestAction($callee, Request $request)
    {
        $phpunit = static::$phpunit;

        $container = $this->container;
        $phpunit->assertTrue($container->has('request'));

        static::$facebookAdapter->setContainer($container);

        $phpunit->assertSame($request, static::$facebookAdapter->getRequest());

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
