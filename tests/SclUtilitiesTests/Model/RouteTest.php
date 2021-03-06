<?php

namespace SclZfUtilitiesTests\Model;

use SclZfUtilities\Model\Route;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2013-02-08 at 17:05:30.
 */
class RouteTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $route = 'abc';
        $params = array('no1' => 'test');

        $routeObject = new Route($route, $params);

        $this->assertEquals($route, $routeObject->route);
        $this->assertEquals($params, $routeObject->params);
    }
}
