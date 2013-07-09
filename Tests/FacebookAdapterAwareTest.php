<?php

namespace Laelaps\Bundle\Facebook\Tests;

use Laelaps\Bundle\Facebook\FacebookAdapterAware;
use PHPUnit_Framework_TestCase;

class FacebookAdapterAwareTest extends PHPUnit_Framework_TestCase
{
    public function testThatFacebookAdapterAwareTraitMatchesInterface()
    {
        // instanciating this class is enough
        new FacebookAdapterAware;
    }
}
