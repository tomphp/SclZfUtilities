<?php

namespace SclZfUtilities\Hydrator;

use SclZfUtilities\Exception\RuntimeException;
use Zend\Stdlib\Hydrator\HydratorInterface;

/**
 * This can be used with {@see \SclZfUtilities\Form\EntityFormBuilder} to tell
 * it to attach a default hydrator.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class Placeholder implements HydratorInterface
{
    /**
     * {@inheritDoc}
     *
     * @param  object $object
     * @return array
     * @throws RuntimeException This method is never meant to be called.
     */
    public function extract($object)
    {
        throw RuntimeException::methodShouldNotBeCalled(__METHOD__, __LINE__);
    }

    /**
     * {@inheritDoc}
     *
     * @param  array $data
     * @param  object $object
     * @return object
     * @throws RuntimeException This method is never meant to be called.
     */
    public function hydrate(array $data, $object)
    {
        throw RuntimeException::methodShouldNotBeCalled(__METHOD__, __LINE__);
    }
}
