<?php

namespace Laelaps\Bundle\Facebook;

interface FacebookAdapterAwareInterface
{
    /**
     * @api
     * @return \Laelaps\Bundle\Facebook $facebookAdapter
     * @throws \BadMethodCallException If FacebookAdapter is not set
     */
    public function getFacebookAdapter();

    /**
     * @api
     * @param \Laelaps\Bundle\Facebook $facebookAdapter
     * @return void
     */
    public function setFacebookAdapter(FacebookAdapter $facebookAdapter = null);
}
