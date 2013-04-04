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
                'SclZfUtilities\Hydrator\DoctrineObjectHydrator' => function ($sm) {
                    $entityManager = $sm->get('doctrine.entitymanager.orm_default');
                    $hydrator = new DoctrineObjectHydrator($entityManager, new ClassMethods());
                    return $hydrator;
                },
            ),
        );
    }
}
