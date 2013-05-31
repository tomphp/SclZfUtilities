<?php

namespace SclZfUtilitiesTests\Controller\Plugin;

use SclZfUtilities\Controller\Plugin\FormSubmitted;

/**
 * Unit tests for {@see FormSubmitted}.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class FormSubmittedTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The instance to be tested.
     *
     * @var FormSubmitted
     */
    protected $plugin;

    /**
     * A mock controller object.
     *
     * @var \Zend\Mvc\Controller\AbstractController
     */
    protected $controller;

    /**
     * A mock request object.
     *
     * @var \Zend\Http\Request
     */
    protected $request;

    /**
     * A mock form object to pass to the plugin.
     *
     * @var \Zend\Form\Form
     */
    protected $form;

    /**
     * Prepare the instance to be tested and the mock objects.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->controller = $this->getMock('Zend\Mvc\Controller\AbstractController');

        $this->request = $this->getMock('Zend\Http\Request');

        $this->controller
             ->expects($this->any())
             ->method('getRequest')
             ->will($this->returnValue($this->request));

        $this->plugin = new FormSubmitted();

        $this->plugin->setController($this->controller);

        $this->form = $this->getMock('Zend\Form\Form');
    }

    /**
     * If the request is not a post then false should be returned.
     *
     * @covers SclZfUtilities\Controller\Plugin\FormSubmitted::__invoke
     *
     * @return void
     */
    public function testRequestNotPost()
    {
        $this->request
             ->expects($this->any())
             ->method('isPost')
             ->will($this->returnValue(false));

        $this->assertFalse($this->plugin->__invoke($this->form));
    }

    /**
     * If the request is a post and the data is invalid then false should be returned.
     *
     * @covers SclZfUtilities\Controller\Plugin\FormSubmitted::__invoke
     *
     * @return void
     */
    public function testFormIsNotValid()
    {
        $this->markTestIncomplete('Need to get the flashMessenger calls working');
        /*
        $postData = array('POST DATA');

        $this->request
             ->expects($this->any())
             ->method('isPost')
             ->will($this->returnValue(true));

        $this->request
             ->expects($this->once())
             ->method('getPost')
             ->will($this->returnValue($postData));

        $this->form
             ->expects($this->once())
             ->method('setData')
             ->with($this->equalTo($postData));

        $this->form
             ->expects($this->any())
             ->method('isValid')
             ->will($this->returnValue(false));

        // @todo Add support for flashMessenger plugin

        $this->assertFalse($this->plugin->__invoke($this->form));
        */
    }

    /**
     * If the request is a post and the data is valid then true should be returned.
     *
     * @covers SclZfUtilities\Controller\Plugin\FormSubmitted::__invoke
     *
     * @return void
     */
    public function testFormIsValid()
    {
        $postData = array('POST DATA');

        $this->request
             ->expects($this->any())
             ->method('isPost')
             ->will($this->returnValue(true));

        $this->request
             ->expects($this->once())
             ->method('getPost')
             ->will($this->returnValue($postData));

        $this->form
             ->expects($this->once())
             ->method('setData')
             ->with($this->equalTo($postData));

        $this->form
             ->expects($this->any())
             ->method('isValid')
             ->will($this->returnValue(true));

        $this->assertTrue($this->plugin->__invoke($this->form));
    }
}
