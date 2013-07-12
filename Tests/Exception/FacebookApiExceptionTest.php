<?php

namespace Laelaps\Bundle\FacebookBundle\Tests\Exception;

use FacebookApiException as BaseFacebookApiException;
use Laelaps\Bundle\Facebook\Exception\FacebookApiException;
use Laelaps\Bundle\Facebook\FacebookAdapter;
use Laelaps\Bundle\Facebook\Tests\KernelTestCase;

/**
 * @see https://developers.facebook.com/docs/reference/api/errors/
 */
class FacebookApiExceptionTest extends KernelTestCase
{
    /**
     * @param int $code
     * @param int $subcode
     * @return \Laelaps\Bundle\Facebook\Exception\FacebookApiException
     */
    private function getFacebookApiException($code = 0, $subcode = 0)
    {
        static $facebookAdapter;

        if (!($facebookAdapter instanceof FacebookAdapter)) {
            $facebookAdapter = $this->getFacebookAdapter();
        }

        $baseFacebookApiException = new BaseFacebookApiException([
            'error_code' => $code,
            'error_subcode' => $subcode,
        ]);

        return new FacebookApiException($baseFacebookApiException, $facebookAdapter);
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
     * @return array
     */
    public function reauthorizeUserScenarioCodesProvider()
    {
        return [
            [102, 0],
            [102, 458],
            [102, 460],
            [102, 463],
            [102, 467],
            [190, 0],
            [190, 458],
            [190, 460],
            [190, 463],
            [190, 467],
        ];
    }

    /**
     * @return array
     */
    public function requestPermissionsScenarioCodesProvider()
    {
        return array_merge([
            [10, 0],
        ], array_map(function ($i) {
            return [$i, 0];
        }, range(200, 299)));
    }

    /**
     * @return array
     */
    public function retryAfterWaitingScenarioCodesProvider()
    {
        return [
            [1, 0],
            [2, 0],
            [4, 0],
            [17, 0],
        ];
    }

    public function testThatErrorCoddeIsStored()
    {
        $code = rand(1, 10);
        $subcode = rand(11, 20);

        $exception = $this->getFacebookApiException($code, $subcode);

        $this->assertSame($code, $exception->getCode());
        $this->assertSame($subcode, $exception->getSubcode());
    }

    /**
     * @dataProvider reauthorizeUserScenarioCodesProvider
     */
    public function testThatReauthorizeScenarioIsCorrectlyIdentified($code, $subcode)
    {
        $exception = $this->getFacebookApiException($code, $subcode);

        $this->assertTrue($exception->isRecoveryScenarioAvailable(), 'failed asserting that recovery scenario is available');

        $this->assertTrue($exception->shouldReauthorizeUserToRecover(), 'failed asserting that should reauthorize user to recover');
        $this->assertFalse($exception->shouldRetryAfterWaitingToRecover(), 'failed asserting that should not retry after waiting to recover');
        $this->assertFalse($exception->shouldRequestPermissionsToRecover(), 'failed asserting that should not request permissions to recover');
    }

    /**
     * @dataProvider requestPermissionsScenarioCodesProvider
     */
    public function testThatRequestPermissionsScenarioIsCorrectlyIdentified($code, $subcode)
    {
        $exception = $this->getFacebookApiException($code, $subcode);

        $this->assertTrue($exception->isRecoveryScenarioAvailable(), 'failed asserting that recovery scenario is available');

        $this->assertFalse($exception->shouldReauthorizeUserToRecover(), 'failed asserting that should not reauthorize user to recover');
        $this->assertFalse($exception->shouldRetryAfterWaitingToRecover(), 'failed asserting that should not retry after waiting to recover');
        $this->assertTrue($exception->shouldRequestPermissionsToRecover(), 'failed asserting that should request permissions to recover');
    }

    /**
     * @dataProvider retryAfterWaitingScenarioCodesProvider
     */
    public function testThatRetryAfterWaitingScenarioIsCorrectlyIdentified($code, $subcode)
    {
        $exception = $this->getFacebookApiException($code, $subcode);

        $this->assertTrue($exception->isRecoveryScenarioAvailable(), 'failed asserting that recovery scenario is available');

        $this->assertFalse($exception->shouldReauthorizeUserToRecover(), 'failed asserting that should not reauthorize user to recover');
        $this->assertTrue($exception->shouldRetryAfterWaitingToRecover(), 'failed asserting that should retry after waiting to recover');
        $this->assertFalse($exception->shouldRequestPermissionsToRecover(), 'failed asserting that should not request permissions to recover');
    }
}
