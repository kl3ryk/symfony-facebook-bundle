<?php

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

$collection = new RouteCollection();
$collection->add('test-facebook-adapter-request', new Route('/test-facebook-adapter-request', [
    '_controller' => 'TestBundle:Test:testFacebookAdapterRequest',
]));

return $collection;
