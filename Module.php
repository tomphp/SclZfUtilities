<?php

namespace SclZfUtilities;

use SclZfUtilities\Hydrator\DoctrineObjectHydrator;
use SclZfUtilities\View\Helper\ControllerActionName;
use Zend\Stdlib\Hydrator\ClassMethods;

class Module
{
    /**
     * @param unknown_type $e
     */
    public function onBootstrap($e)
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
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return array(
            'controller_plugins' => array(
                'invokables' => array(
                    'formSubmitted'    => 'SclZfUtilities\Controller\Plugin\FormSubmitted'
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
                'SclZfUtilities\Hydrator\DoctrineObjectHydrator' => function ($sm) {
                    return new DoctrineObjectHydrator(
                        $sm->get('doctrine.entitymanager.orm_default'),
                        new ClassMethods()
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
