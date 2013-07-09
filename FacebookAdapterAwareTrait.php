<?php

namespace Laelaps\Bundle\Facebook;

use BadMethodCallException;

/**
 * This trait is a minimal implementation of FacebookAdapterAwareInterface
 */
trait FacebookAdapterAwareTrait
{
    /**
     * @var \Laelaps\Bundle\Facebook\FacebookAdapter
     */
    private $facebookAdapter;

    /**
     * @api
     * @return \Laelaps\Bundle\Facebook $facebookAdapter
     * @throws \BadMethodCallException If FacebookAdapter is not set
     * @see \Laelaps\Bundle\Facebook\FacebookAdapterAwareInterface::getFacebookAdapter
     */
    public function getFacebookAdapter()
    {
        if (!($this->facebookAdapter instanceof FacebookAdapter)) {
            throw new BadMethodCallException('FacebookAdapter is not set.');
        }

        return $this->facebookAdapter;
    }

    /**
     * @api
     * @param \Laelaps\Bundle\Facebook $facebookAdapter
     * @return void
     * @see \Laelaps\Bundle\Facebook\FacebookAdapterAwareInterface::setFacebookAdapter
     */
    public function setFacebookAdapter(FacebookAdapter $facebookAdapter = null)
    {
        $this->facebookAdapter = $facebookAdapter;
    }
}
