<?php

namespace SclZfUtilities\Options;

/**
 * Options for the {@see \SclZfUtilities\Form\EntityFormBuilder} system.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
interface FormBuilderOptionsInterface
{
    /**
     * Set the map of which forms are created for which entities.
     *
     * @param  array $map
     * @return self
     */
    public function setFormEntityMap(array $map);

    /**
     * Get the list of which forms are created for which entities.
     *
     * @return array
     */
    public function getFormEntityMap();
}
