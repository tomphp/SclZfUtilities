<?php

namespace SclZfUtilitiesTests\Form;

use SclZfUtilities\Form\EntityFormBuilder;

/**
 * Unit tests for {@see EntityFormBuilder}.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class EntityFormBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * The instance to be tested.
     *
     * @var EntityFormBuilder
     */
    protected $instance;

    /**
     * options
     *
     * @var \SclZfUtilities\Options\FormBuilderOptionsInterface
     */
    protected $options;

    protected $elementManager;

    /**
     * Mock HTTP Request.
     *
     * @var \Zend\Http\Request
     */
    protected $request;

    /**
     * Mock annotation builder.
     *
     * @var \Zend\Form\Annotation\AnnotationBuilder
     */
    protected $builder;

    /**
     * Mock hydrator.
     *
     * @var \Zend\Stdlib\Hydrator\HydratorInterface
     */
    protected $hydrator;

    /**
     * Mock mapper.
     *
     * @var \SclZfUtilities\Mapper\GenericMapperInterface
     */
    protected $mapper;

    /**
     * Prepare the instance to be tested.
     *
     * @return void
     */
    protected function setUp()
    {
        $this->options = $this->getMock('SclZfUtilities\Options\FormBuilderOptionsInterface');

        $this->elementManager = $this->getMock('Zend\Form\FormElementManager');

        $this->request = $this->getMock('Zend\Http\Request');

        $this->builder = $this->getMock('Zend\Form\Annotation\AnnotationBuilder');

        $this->hydrator = $this->getMock('Zend\Stdlib\Hydrator\HydratorInterface');

        $this->mapper = $this->getMock('SclZfUtilities\Mapper\GenericMapperInterface');

        $this->instance = new EntityFormBuilder(
            $this->options,
            $this->elementManager,
            $this->request,
            $this->hydrator,
            $this->builder
        );
    }

    /**
     * Creates a mock of the ZF2 form class.
     *
     * @return \Zend\Form\Form
     */
    protected function getFormMock()
    {
        return $this->getMock('Zend\Form\Form');
    }

    /**
     * Test that the prepareForm method sets the hydrator, adds a submit button
     * and binds to the entity.
     *
     * @covers SclZfUtilities\Form\EntityFormBuilder::prepareForm
     *
     * @return void
     */
    public function testPrepareFormWithSubmitButton()
    {
        $form   = $this->getFormMock();
        $submit = 'Add';
        $entity = new \stdClass();

        $elementSpec = array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => $submit,
                'id'    => 'submitbutton',
            ),
        );

        $form->expects($this->once())
             ->method('setHydrator')
             ->with($this->equalTo($this->hydrator));

        $form->expects($this->once())
             ->method('add')
             ->with($this->equalTo($elementSpec));

        $form->expects($this->once())
             ->method('bind')
             ->with($this->equalTo($entity));

        $result = $this->instance->prepareForm($form, $entity, $submit);

        $this->assertEquals($form, $result, 'prepareForm() should return the form.');
    }

    /**
     * Test that if a hydrator is already provided then it isn't overwritten.
     *
     * @covers SclZfUtilities\Form\EntityFormBuilder::prepareForm
     *
     * @return void
     */
    public function testPrepareFormWithPresetHydrator()
    {
        $form   = $this->getFormMock();
        $entity = new \stdClass();

        $form->expects($this->atLeastOnce())
             ->method('getHydrator')
             ->will($this->returnValue($this->getMock('Zend\Stdlib\Hydrator\HydratorInterface')));

        $form->expects($this->never())
             ->method('setHydrator');

        $form->expects($this->once())
             ->method('bind')
             ->with($this->equalTo($entity));

        $result = $this->instance->prepareForm($form, $entity);

        $this->assertEquals($form, $result, 'prepareForm() should return the form.');
    }

    /**
     * Test that the prepareForm method sets the hydrator, and binds to the
     * entity but no submit button is created when the submit param is not provided.
     *
     * @covers SclZfUtilities\Form\EntityFormBuilder::prepareForm
     *
     * @return void
     */
    public function testPrepareFormWithNoSubmitButton()
    {
        $form   = $this->getFormMock();
        $entity = new \stdClass();

        $form->expects($this->once())
             ->method('setHydrator')
             ->with($this->equalTo($this->hydrator));

        $form->expects($this->never())
             ->method('add');

        $form->expects($this->once())
             ->method('bind')
             ->with($this->equalTo($entity));

        $result = $this->instance->prepareForm($form, $entity);

        $this->assertEquals($form, $result, 'prepareForm() should return the form.');
    }

    /**
     * Test that the createForm method sets the hydrator, adds a submit button

     public function getterSetterProvider()
     {RuntimeException

     }
     * and binds to the entity.
     *
     * @covers SclZfUtilities\Form\EntityFormBuilder::createForm
     * @covers SclZfUtilities\Form\EntityFormBuilder::prepareForm
     *
     * @return void
     */
    public function testCreateFormWithSubmitButton()
    {
        $form   = $this->getFormMock();

        $submit = 'Add';
        $entity = new \stdClass();

        $elementSpec = array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => $submit,
                'id'    => 'submitbutton',
            ),
        );

        $this->options
             ->expects($this->any())
             ->method('getFormEntityMap')
             ->will($this->returnValue(array()));

        $this->builder
             ->expects($this->once())
             ->method('createForm')
             ->with($this->equalTo($entity))
             ->will($this->returnValue($form));

        $form->expects($this->once())
             ->method('setHydrator')
             ->with($this->equalTo($this->hydrator));

        $form->expects($this->once())
             ->method('add')
             ->with($this->equalTo($elementSpec));

        $form->expects($this->once())
             ->method('bind')
             ->with($this->equalTo($entity));

        $result = $this->instance->createForm($entity, $submit);

        $this->assertEquals($form, $result, 'createForm() should return the form.');
    }

    /**
     * Test that the createForm method sets the hydrator, and binds to the
     * entity but no submit button is created when the submit param is not provided.
     *
     * @covers SclZfUtilities\Form\EntityFormBuilder::createForm
     * @covers SclZfUtilities\Form\EntityFormBuilder::prepareForm
     *
     * @return void
     */
    public function testCreateFormWithNoSubmitButton()
    {
        $form   = $this->getFormMock();

        $entity = new \stdClass();

        $this->options
             ->expects($this->any())
             ->method('getFormEntityMap')
             ->will($this->returnValue(array()));

        $this->builder
             ->expects($this->once())
             ->method('createForm')
             ->with($this->equalTo($entity))
             ->will($this->returnValue($form));

        $form->expects($this->once())
             ->method('setHydrator')
             ->with($this->equalTo($this->hydrator));

        $form->expects($this->never())
             ->method('add');

        $form->expects($this->once())
             ->method('bind')
             ->with($this->equalTo($entity));

        $result = $this->instance->createForm($entity);

        $this->assertEquals($form, $result, 'save() should return the form.');
    }

    /**
     * Test that when save is call from within a non-post request false is returned.
     *
     * @covers SclZfUtilities\Form\EntityFormBuilder::save
     *
     * @return void
     */
    public function testSaveWithGetRequest()
    {
        $entity = new \stdClass();
        $form   = $this->getFormMock();

        $this->request
             ->expects($this->once())
             ->method('isPost')
             ->will($this->returnValue(false));

        $this->mapper
             ->expects($this->never())
             ->method('save');

        $result = $this->instance->save($entity, $form);

        $this->assertFalse($result);
    }

    /**
     * Test that when save is call from within a post request with invalid
     * post data then false is returned.
     *
     * @covers SclZfUtilities\Form\EntityFormBuilder::save
     *
     * @return void
     */
    public function testSaveWithInvalidFormData()
    {
        $entity = new \stdClass();
        $form   = $this->getFormMock();

        $postData = array('demo', 'post', 'data');

        $this->request
             ->expects($this->once())
             ->method('isPost')
             ->will($this->returnValue(true));

        $this->request
             ->expects($this->once())
             ->method('getPost')
             ->will($this->returnValue($postData));

        $form->expects($this->once())
             ->method('setData')
             ->with($this->equalTo($postData));

        $form->expects($this->once())
             ->method('isValid')
             ->will($this->returnValue(false));

        $this->mapper
             ->expects($this->never())
             ->method('save');

        $result = $this->instance->save($entity, $form);

        $this->assertFalse($result);
    }

    /**
     * Test that when save is called but no mapper is set an exception is thrown.
     *
     * @covers            SclZfUtilities\Form\EntityFormBuilder::save
     * @expectedException SclZfUtilities\Exception\RuntimeException
     *
     * @return void
     */
    public function testSaveWithNoMapper()
    {
        $entity = new \stdClass();
        $form   = $this->getFormMock();

        $postData = array('demo', 'post', 'data');

        $this->request
             ->expects($this->once())
             ->method('isPost')
             ->will($this->returnValue(true));

        $this->request
             ->expects($this->once())
             ->method('getPost')
             ->will($this->returnValue($postData));

        $form->expects($this->once())
             ->method('setData')
             ->with($this->equalTo($postData));

        $form->expects($this->once())
             ->method('isValid')
             ->will($this->returnValue(true));

        $this->mapper
             ->expects($this->never())
             ->method('save');

        $result = $this->instance->save($entity, $form);

        $this->assertFalse($result);
    }

    /**
     * Test that when save is called but with a good mapper the entity is
     * is saved by the mapper and true is returned.
     *
     * @covers SclZfUtilities\Form\EntityFormBuilder::save
     * @covers SclZfUtilities\Form\EntityFormBuilder::setMapper
     *
     * @return void
     */
    public function testSaveWithGoodMapper()
    {
        $entity = new \stdClass();
        $form   = $this->getFormMock();

        $postData = array('demo', 'post', 'data');

        $this->request
             ->expects($this->once())
             ->method('isPost')
             ->will($this->returnValue(true));

        $this->request
             ->expects($this->once())
             ->method('getPost')
             ->will($this->returnValue($postData));

        $form->expects($this->once())
             ->method('setData')
             ->with($this->equalTo($postData));

        $form->expects($this->once())
             ->method('isValid')
             ->will($this->returnValue(true));

        $this->mapper
             ->expects($this->once())
             ->method('save')
             ->with($this->equalTo($entity));

        $this->instance->setMapper($this->mapper);

        $result = $this->instance->save($entity, $form);

        $this->assertTrue($result);
    }
}
