<?php
namespace SclZfUtilities\Controller;

/**
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
interface ControllerInitInterface
{
    /**
     * This gets called after the controller has been set up, it can be
     * useful for initializing things that cannot be initialized in the
     * constructor.
     */
    public function init();
}
