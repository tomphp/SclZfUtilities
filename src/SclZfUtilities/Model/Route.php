<?php
namespace SclZfUtilities\Model;

/**
 * Stores the details of a route.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class Route
{
    /**
     * The route
     *
     * @var string
     */
    public $route;

    /**
     * The parameters
     *
     * @var array()
     */
    public $params;

    /**
     * Initialises the properties
     *
     * @param string $route
     * @param array $params
     */
    public function __construct($route, array $params = array())
    {
        $this->route = $route;
        $this->params = $params;
    }
}
