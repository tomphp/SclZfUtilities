<?php

namespace SclZfUtilities\Options;

use Zend\Stdlib\AbstractOptions;

/**
 * Options for the {@see \SclZfUtilities\Form\EntityFormBuilder} system.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class FormBuilderOptions extends AbstractOptions implements
    FormBuilderOptionsInterface
{
    /**
     * The map of which forms are created for which entities.
     *
     * @var array
     */
    protected $formEntityMap = array();

    /**
     * Set the map of which forms are created for which entities.
     *
     * @param  array $map
     * @return self
     */
    public function setFormEntityMap(array $map)
    {
        $this->formEntityMap = $map;

        return $this;
    }

    /**
     * Get the list of which forms are created for which entities.
     *
     * @return array
     */
    public function getFormEntityMap()
    {
        return $this->formEntityMap;
    }
}
