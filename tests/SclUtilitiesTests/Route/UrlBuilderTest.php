<?php

namespace SclZfUtilitiesTests\Route;

use SclZfUtilities\Route\UrlBuilder;

/**
 * Unit tests for {@see UrlBuilder}.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class UrlBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The instance to be tested.
     *
     * @var mixed
     */
    protected $urlBuilder;

    /**
     * A mock router object.
     *
     * @var mixed
     */
    protected $router;

    /**
     * Set up the instance to be tested.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->router = $this->getMock('Zend\Mvc\Router\RouteStackInterface');

        $this->urlBuilder = new UrlBuilder();

        $this->urlBuilder->setRouter($this->router);
    }

    /**
     * Test that if getUrl is called but no router is set a RuntimeException is thrown.
     *
     * @expectedException SclZfUtilities\Exception\RuntimeException
     *
     * @return void
     */
    public function testGetUrlWithNoRouterThrowsRuntimeException()
    {
        $urlBuilder = new UrlBuilder();

        $urlBuilder->getUrl('random-name');
    }

    /**
     * Check a basic all of getUrl works properly.
     *
     * @covers SclZfUtilities\Route\UrlBuilder::setRouter
     * @covers SclZfUtilities\Route\UrlBuilder::getUrl
     *
     * @return void
     */
    public function testGetUrl()
    {
        $options = array('option1' => 'xxx');

        $expectedOptions = array_merge(array('name' => 'the-name'), $options);

        $this->router
             ->expects($this->once())
             ->method('assemble')
             ->with($this->equalTo(array()), $this->equalTo($expectedOptions))
             ->will($this->returnValue('the-url'));

        $this->assertEquals(
            'the-url',
            $this->urlBuilder->getUrl('the-name', array(), $options)
        );
    }

    /**
     * Check a basic all of getUrl works properly.
     *
     * @covers SclZfUtilities\Route\UrlBuilder::setRouter
     * @covers SclZfUtilities\Route\UrlBuilder::getUrl
     * @expectedException SclZfUtilities\Exception\InvalidArgumentException
     *
     * @return void
     */
    public function testGetUrlWithInvalidParams()
    {
        $params = new \stdClass();

        $this->urlBuilder->getUrl('the-name', $params);
    }

    /**
     * Check a basic all of getUrl with params of type \Traverable works.
     *
     * @covers SclZfUtilities\Route\UrlBuilder::setRouter
     * @covers SclZfUtilities\Route\UrlBuilder::getUrl
     *
     * @return void
     */
    public function testGetUrlWithTraversableParams()
    {
        $paramValues = array('param1' => 'xxx');

        $params = new \ArrayObject($paramValues);

        $this->router
             ->expects($this->once())
             ->method('assemble')
             ->with($this->equalTo($paramValues), $this->equalTo(array('name' => 'the-name')))
             ->will($this->returnValue('the-url'));

        $this->assertEquals(
            'the-url',
            $this->urlBuilder->getUrl('the-name', $params)
        );
    }
}
