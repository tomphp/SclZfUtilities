<?php

/**
 * Contains the ControllerActionName view helper class
 * @author Tom Oram
 */

namespace SclZfUtilities\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * View helper to display the controller name and action as a hyphenated string.
 * @author Tom Oram
 */
class ControllerActionName extends AbstractHelper
{
    /**
     * The current route
     * @var \Zend\Mvc\Router\RouteMatch
     */
    private $route;

    /**
     * Default construction
     * @param \Zend\Mvc\Router\RouteMatch $route
     */
    public function __construct($route)
    {
        $this->route = $route;
    }

    /**
     * Invoke the action of the view helper.
     * @return string
     */
    public function __invoke()
    {
        if (null === $this->route) {
            return '';
        }

        $controller = $this->route->getParam('controller', 'index');
        $action     = $this->route->getParam('action', 'index');

        $controller = strtolower(preg_replace('!^.*\\\\([^\\\\]+)$!', '\\1', $controller));

        return "{$controller}-{$action}";
    }
}
