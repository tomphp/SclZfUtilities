<?php

namespace SclZfUtilitiesTests;

use SclZfUtilities\Module;

/**
 * Unit tests for {@see Module}.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class ModuleTest extends \PHPUnit_Framework_TestCase
{

    /**
     * The instance to test.
     *
     * @var Module
     */
    protected $module;

    /**
     * Setup the instance to be tested.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->module = new Module();
    }

    /**
     * Test the module bootstrapping code.
     *
     * @covers SclZfUtilities\Module::onBootstrap
     *
     * @return void
     */
    public function testOnBootstrap()
    {
        $event          = $this->getMock('Zend\Mvc\MvcEvent');
        $application    = $this->getMock('Zend\Mvc\ApplicationInterface');
        $serviceManager = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface');
        $pluginManager  = $this->getMockBuilder('Zend\View\HelperPluginManager')
                               ->disableOriginalConstructor()
                               ->getMock();

        $event->expects($this->any())
              ->method('getApplication')
              ->will($this->returnValue($application));

        $application->expects($this->any())
                    ->method('getServiceManager')
                    ->will($this->returnValue($serviceManager));

        $serviceManager->expects($this->once())
                       ->method('get')
                       ->with($this->equalTo('\View\Helper\Manager'))
                       ->will($this->returnValue($pluginManager));

        $this->module->onBootstrap($event);
    }

    /**
     * testGetAutoloaderConfig
     *
     * @covers SclZfUtilities\Module::getAutoloaderConfig
     *
     * @return void
     */
    public function testGetAutoloaderConfig()
    {
        $config = $this->module->getAutoloaderConfig();

        $this->assertArrayHasKey(
            'Zend\Loader\ClassMapAutoloader',
            $config,
            'ClassMapAutoloader config not set.'
        );

        $this->assertFileExists(
            $config['Zend\Loader\ClassMapAutoloader'][0],
            'Class map file doesn\'t exist.'
        );

        $this->assertArrayHasKey(
            'Zend\Loader\StandardAutoloader',
            $config,
            'StandardAutoloader config not set.'
        );
    }
}
