<?php

namespace SclZfUtilities;

use SclZfUtilities\Hydrator\DoctrineObjectHydrator;
use SclZfUtilities\View\Helper\ControllerActionName;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

/**
 * Module for SclZfUtilities library.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class Module implements
    AutoloaderProviderInterface,
    BootstrapListenerInterface,
    ConfigProviderInterface,
    ServiceProviderInterface
{
    /**
     * {@inheritDoc}
     *
     * @param EventInterface $e
     */
    public function onBootstrap(EventInterface $e)
    {
        $app = $e->getApplication();

        $serviceManager = $app->getServiceManager();

        // Setting up the view helper here as we want to be able to access the Route
        // from with in the view helper.
        $serviceManager->get('\View\Helper\Manager')->setFactory(
            'controllerActionName',
            function ($serviceManager) use ($e) {
                return new ControllerActionName($e->getRouteMatch());
            }
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/../../autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__,
                ),
            ),
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return array
     */
    public function getConfig()
    {
        return array(
            'controller_plugins' => array(
                'invokables' => array(
                    'formSubmitted'  => 'SclZfUtilities\Controller\Plugin\FormSubmitted',
                    'getFormBuilder' => 'SclZfUtilities\Controller\Plugin\FormBuilder',
                ),
            ),
            'view_helpers' => array(
                'invokables' => array(
                    'formatPrice' => 'SclZfUtilities\View\Helper\FormatPrice',
                    'formatDate'  => 'SclZfUtilities\View\Helper\FormatDate',
                    'idUrl'       => 'SclZfUtilities\View\Helper\UrlWithId',
                    'pageTitle'   => 'SclZfUtilities\View\Helper\PageTitle',
                ),
            ),
        );
    }

    /**
     * {@inheritDoc}
     *
     * @return array
     */
    public function getServiceConfig()
    {
        return array(
            'invokables' => array(
                'SclZfUtilities\Model\Messages' => 'SclZfUtilities\Model\Messages',
            ),
            'factories' => array(
                'SclZfUtilities\Doctrine\FlushLock' => function ($sm) {
                    return new \SclZfUtilities\Doctrine\FlushLock(
                        $sm->get('doctrine.entitymanager.orm_default')
                    );
                },
                'SclZfUtilities\Form\EntityFormBuilder' => function ($sm) {
                    $hydratorManager = $sm->get('HydratorManager');

                    $annotationBuilder = $sm->get('doctrine.formannotationbuilder.orm_default');
                    $factory = new \Zend\Form\Factory($sm->get('FormElementManager'));
                    $annotationBuilder->setFormFactory($factory);

                    return new \SclZfUtilities\Form\EntityFormBuilder(
                        $sm->get('Request'),
                        $annotationBuilder,
                        $hydratorManager->get('DoctrineModule\Stdlib\Hydrator\DoctrineObject')
                    );
                },
                'SclZfUtilities\Mapper\GenericDoctrineMapper' => function ($sm) {
                    return new \SclZfUtilities\Mapper\GenericDoctrineMapper(
                        $sm->get('doctrine.entitymanager.orm_default'),
                        $sm->get('SclZfUtilities\Doctrine\FlushLock')
                    );
                },
                'SclZfUtilities\Route\UrlBuilder' => function ($sm) {
                    $builder = new \SclZfUtilities\Route\UrlBuilder();
                    $builder->setRouter($sm->get('HttpRouter'));
                    return $builder;
                },
            ),
            'shared' => array(
                'SclZfUtilities\Mapper\GenericDoctrineMapper' => false,
            ),
        );
    }
}
