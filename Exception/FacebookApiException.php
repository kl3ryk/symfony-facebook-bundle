<?php

namespace Laelaps\Bundle\Facebook\Exception;

use FacebookApiException as BaseFacebookApiException;
use Laelaps\Bundle\Facebook\FacebookAdapter;
use Laelaps\Bundle\Facebook\FacebookAdapterAwareInterface;
use Laelaps\Bundle\Facebook\FacebookAdapterAwareTrait;

/**
 * @see https://developers.facebook.com/docs/reference/api/errors/
 */
class FacebookApiException extends BaseFacebookApiException implements FacebookAdapterAwareInterface
{
    use FacebookAdapterAwareTrait;

    const ERROR_CODE_API_UNKNOWN = 1;
    const ERROR_CODE_API_SERVICE = 2;
    const ERROR_CODE_API_TOO_MANY_CALLS = 4;
    const ERROR_CODE_API_PERMISSION_DENIED = 10;
    const ERROR_CODE_API_USER_TOO_MANY_CALLS = 17;
    const ERROR_CODE_API_SESSION = 102;
    const ERROR_CODE_OAUTH = 190;

    const ERROR_SUBCODE_APP_NOT_INSTALLED = 458;
    const ERROR_SUBCODE_USER_CHECKPOINTED = 459;
    const ERROR_SUBCODE_PASSWORD_CHANGED = 460;
    const ERROR_SUBCODE_EXPIRED = 463;
    const ERROR_SUBCODE_UNCONFIRMED_USER = 464;
    const ERROR_SUBCODE_INVALID_ACCESS_TOKEN = 467;

    const RECOVERY_SCENARIO_UNAVAILABLE         = 0b0001;
    const RECOVERY_SCENARIO_REAUTHORIZE_USER    = 0b0010;
    const RECOVERY_SCENARIO_REQUEST_PERMISSIONS = 0b0100;
    const RECOVERY_SCENARIO_RETRY_AFTER_WAITING = 0b1000;

    /**
     * @param \FacebookApiException $facebookApiException
     * @param \Laelaps\Bundle\Facebook\FacebookAdapter $thrower
     */
    public function __construct(BaseFacebookApiException $facebookApiException, FacebookAdapter $thrower)
    {
        parent::__construct($facebookApiException->getResult());

        $this->setFacebookAdapter($thrower);
    }

    /**
     * @return int
     */
    public function getRecoveryScenario()
    {
        $code = $this->getCode();

        if ($code >= 200 && $code < 300) {
            return self::RECOVERY_SCENARIO_REQUEST_PERMISSIONS;
        }

        switch ($code) {
            case self::ERROR_CODE_API_PERMISSION_DENIED:   return self::RECOVERY_SCENARIO_REQUEST_PERMISSIONS;
            case self::ERROR_CODE_API_SERVICE:             return self::RECOVERY_SCENARIO_RETRY_AFTER_WAITING;
            case self::ERROR_CODE_API_SESSION:             return self::RECOVERY_SCENARIO_REAUTHORIZE_USER;
            case self::ERROR_CODE_API_TOO_MANY_CALLS:      return self::RECOVERY_SCENARIO_RETRY_AFTER_WAITING;
            case self::ERROR_CODE_API_UNKNOWN:             return self::RECOVERY_SCENARIO_RETRY_AFTER_WAITING;
            case self::ERROR_CODE_API_USER_TOO_MANY_CALLS: return self::RECOVERY_SCENARIO_RETRY_AFTER_WAITING;
            case self::ERROR_CODE_OAUTH:                   return self::RECOVERY_SCENARIO_REAUTHORIZE_USER;
        }

        return self::RECOVERY_SCENARIO_UNAVAILABLE;
    }

    /**
     * @return int|null
     */
    public function getSubcode()
    {
        if (isset($this->result['error_subcode'])) {
            return intval($this->result['error_subcode']);
        }
    }

    /**
     * @return bool
     */
    public function isAccessTokenInvalid()
    {
        switch ($this->getSubcode()) {
            case self::ERROR_SUBCODE_EXPIRED:
            case self::ERROR_SUBCODE_INVALID_ACCESS_TOKEN:
                return true;
        }

        switch ($this->getType()) {
            case 'OAuthException':
            case 'invalid_token':
            case 'Exception':
                return strpos($this->getMessage(), 'access token') !== false;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isFacebookServerSideProblem()
    {
        switch ($this->getCode()) {
            case self::ERROR_CODE_API_UNKNOWN:
            case self::ERROR_CODE_API_SERVICE:
                return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isFacebookServerSideThrottling()
    {
        switch ($this->getCode()) {
            case self::ERROR_CODE_API_TOO_MANY_CALLS:
            case self::ERROR_CODE_API_USER_TOO_MANY_CALLS:
                return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function isRecoveryScenarioAvailable()
    {
        return $this->getRecoveryScenario() !== self::RECOVERY_SCENARIO_UNAVAILABLE;
    }

    /**
     * @return bool
     */
    public function shouldReauthorizeUserToRecover()
    {
        return $this->getRecoveryScenario() === self::RECOVERY_SCENARIO_REAUTHORIZE_USER;
    }

    /**
     * @return bool
     */
    public function shouldRetryAfterWaitingToRecover()
    {
        return $this->getRecoveryScenario() === self::RECOVERY_SCENARIO_RETRY_AFTER_WAITING;
    }

    /**
     * @return bool
     */
    public function shouldRequestPermissionsToRecover()
    {
        return $this->getRecoveryScenario() === self::RECOVERY_SCENARIO_REQUEST_PERMISSIONS;
    }
}
