<?php

namespace SclZfUtilities\Route;

use Traversable;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\Router\RouteMatch;
use Zend\Mvc\Router\RouteStackInterface;
use Zend\Stdlib\ArrayUtils;
use SclZfUtilities\Exception;
use Zend\Stdlib\Exception as StdlibException;

/**
 * Used to construct URLs from route params anywhere in your code.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class UrlBuilder
{
    /**
     * RouteStackInterface instance.
     *
     * @var RouteStackInterface
     */
    protected $router;

    /**
     * Generates an url given the name of a route.
     *
     * @param  string            $name            Name of the route
     * @param  array|Traversable $params          Parameters for the link
     * @param  array|Traversable $options         Options for the route
     * @return string Url                         For the link href attribute
     * @throws Exception\RuntimeException         If no RouteStackInterface was provided
     * @throws Exception\InvalidArgumentException If the params object was not an array or \Traversable object
     */
    public function getUrl($name, $params = array(), $options = array())
    {
        if (null === $this->router) {
            throw new Exception\RuntimeException('No RouteStackInterface instance provided');
        }

        if (!is_array($params)) {
            if (!$params instanceof Traversable) {
                throw new Exception\InvalidArgumentException(
                    'Params is expected to be an array or a Traversable object'
                );
            }
            $params = iterator_to_array($params);
        }


        $options['name'] = $name;

        return $this->router->assemble($params, $options);
    }

    /**
     * Set the router to use for assembling.
     *
     * @param RouteStackInterface $router
     * @return Url
     */
    public function setRouter(RouteStackInterface $router)
    {
        $this->router = $router;
        return $this;
    }
}
