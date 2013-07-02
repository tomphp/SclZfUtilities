<?php

namespace SclZfUtilities\Controller\Plugin;

use SclZfUtilities\Form\EntityFormBuilder;
use SclZfUtilities\Mapper\GenericMapperInterface;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * Returns an instance of the {@see EntityFormBuilder} class.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class FormBuilder extends AbstractPlugin
{
    /**
     * The service name for the EntityFormBuilder.
     */
    const FORM_BUILDER_SERVICE = 'SclZfUtilities\Form\EntityFormBuilder';

    /**
     * Saved instance of the EntityFormBuilder for multiple calls.
     *
     * @var EntityFormBuilder
     */
    protected $formBuilder;

    /**
     * Return the application service manager.
     *
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    protected function getServiceLocator()
    {
        return $this->getController()->getServiceLocator();
    }

    /**
     * Returns an instance of the {@see EntityFormBuilder} class initialised
     * with the provided mapper.
     *
     * @param  GenericMapperInterface $objectManager
     * @return EntityFormBuilder
     */
    public function __invoke(GenericMapperInterface $mapper)
    {
        if (null == $this->formBuilder) {
            $serviceLocator    = $this->getServiceLocator();

            $this->formBuilder = $serviceLocator->get(self::FORM_BUILDER_SERVICE);

            $this->formBuilder->setMapper($mapper);
        }

        return $this->formBuilder;
    }
}
