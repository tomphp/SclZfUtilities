<?php
/**
 * Contains the getEntityManager controller plugin.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
namespace SclZfUtilities\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * A controller plugin that provides a getEntityManager method.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class EntityManager extends AbstractPlugin
{
    const ENTITY_MANAGER_SERVICE = 'doctrine.entitymanager.orm_default';
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $entityManager = null;

    /**
     * Get the service locator.
     *
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    private function getServiceLocator()
    {
        $controller = $this->getController();
        return $controller->getServiceLocator();
    }

    /**
     * Returns the entity manager.
     *
     * @return \Doctrine\ORM\EntityManager
     */
    public function __invoke()
    {
        if (null === $this->entityManager) {
            $serviceLocator = $this->getServiceLocator();
            $this->entityManager = $serviceLocator->get(self::ENTITY_MANAGER_SERVICE);
        }

        return $this->entityManager;
    }
}
