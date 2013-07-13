<?php

namespace Laelaps\Bundle\Facebook\Tests\FacebookAdapter;

use Laelaps\Bundle\Facebook\FacebookAdapter;
use Laelaps\Bundle\Facebook\FacebookAdapter\FacebookAdapterMock;
use Laelaps\Bundle\Facebook\Tests\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;

class FacebookAdapterMockTest extends KernelTestCase
{
    /**
     * @return \Laelaps\Bundle\Facebook\FacebookAdapter\FacebookAdapterMock
     */
    private function getFacebookAdapterMock()
    {
        $container = $this->getContainer();

        return new FacebookAdapterMock([
            'appId' => uniqid(),
            'secret' => uniqid(),
        ], $container->get('session'), uniqid());
    }

    public function testThatExtendedAccessTokenCanBeMocked()
    {
        $extendedAccessToken = uniqid();

        $facebook = $this->getFacebookAdapterMock();
        $facebook->setMockedExtendedAccessToken($extendedAccessToken);
        $facebook->setExtendedAccessToken();

        $this->assertSame($extendedAccessToken, $facebook->getAccessToken());
    }

    public function testThatUserCanBeReplaced()
    {
        $user = uniqid();

        $facebook = $this->getFacebookAdapterMock();
        $facebook->setUser($user);

        $this->assertSame($user, $facebook->getUser());
    }

    public function testThatApiCallsCanBeMocked()
    {
        $response = [ uniqid() => uniqid() ];

        $facebook = $this->getFacebookAdapterMock();

        $this->assertFalse($facebook->hasMockedGraphApiCall('/me'));

        $facebook->setMockedGraphApiCall('/me', $response);

        $this->assertTrue($facebook->hasMockedGraphApiCall('/me'));
        $this->assertSame($response, $facebook->api('/me'));
    }

    public function testThatApiCallsCanBeUnmocked()
    {
        $response = [ uniqid() => uniqid() ];

        $facebook = $this->getFacebookAdapterMock();

        $this->assertFalse($facebook->hasMockedGraphApiCall('/me'));

        $facebook->setMockedGraphApiCall('/me', $response);

        $this->assertTrue($facebook->hasMockedGraphApiCall('/me'));

        $facebook->setMockedGraphApiCall('/me', null);

        $this->assertFalse($facebook->hasMockedGraphApiCall('/me'));
    }

    /**
     * @expectedException \Laelaps\Bundle\Facebook\Exception\FacebookApiException
     */
    public function testThatMockedApiCallCanLeadToException()
    {
        $response = [ 'error' => uniqid() ];

        $facebook = $this->getFacebookAdapterMock();

        $this->assertFalse($facebook->hasMockedGraphApiCall('/me'));

        $facebook->setMockedGraphApiCall('/me', $response);

        $this->assertTrue($facebook->hasMockedGraphApiCall('/me'));

        $facebook->api('/me');
    }

    public function testThatMockedAdapterCanBeCreatedFromOtherAdapter()
    {
        $container = $this->getContainer();

        $config = [
            'appId' => ($applicationId = uniqid()),
            'secret' => ($secret = uniqid()),
        ];
        $session = $container->get('session');
        $sessionNamespace = uniqid();
        $request = new Request;

        $facebookAdapter = new FacebookAdapter($config, $session, $sessionNamespace, $request, $container);

        $facebookAdapterMock = FacebookAdapterMock::fromFacebookAdapter($facebookAdapter);

        $this->assertInstanceOf('Laelaps\Bundle\Facebook\FacebookAdapter\FacebookAdapterMock', $facebookAdapterMock);

        $this->assertSame($applicationId, $facebookAdapterMock->getAppId());
        $this->assertSame($container, $facebookAdapterMock->getContainer());
        $this->assertSame($request, $facebookAdapterMock->getRequest());
        $this->assertSame($secret, $facebookAdapterMock->getAppSecret());
        $this->assertSame($session, $facebookAdapterMock->getSession());
    }
}
