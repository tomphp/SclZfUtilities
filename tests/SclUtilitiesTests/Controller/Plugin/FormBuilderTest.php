<?php

namespace SclZfUtilitiesTest\Controller\Plugin;

namespace SclZfUtilitiesTests\Controller\Plugin;

use SclZfUtilities\Controller\Plugin\FormBuilder;

/**
 * Unit tests for {@see FormBuilder}.
 *
 * @covers SclZfUtilities\Controller\Plugin\FormBuilder
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class FormBuilderTest extends \PHPUnit_Framework_TestCase
{
    private $plugin;

    private $serviceLocator;

    /**
     * Set up the instance to be tested.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->plugin = new FormBuilder();

        $controller = $this->getMock('Zend\Mvc\Controller\AbstractActionController');

        $this->serviceLocator = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface');

        $controller->expects($this->any())
                   ->method('getServiceLocator')
                   ->will($this->returnValue($this->serviceLocator));

        $this->plugin->setController($controller);
    }

    public function test_invoke_takes_generic_mapper()
    {
        $formBuilder = $this->getMockBuilder('SclZfUtilities\Form\EntityFormBuilder')
                            ->disableOriginalConstructor()
                            ->getMock();

        $this->serviceLocator
             ->expects($this->any())
             ->method('get')
             ->will($this->returnValue($formBuilder));

        $mapper = $this->getMock('SclZfGenericMapper\MapperInterface');

        $this->plugin->__invoke($mapper);
    }
}
