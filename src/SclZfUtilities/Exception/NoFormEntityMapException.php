<?php

namespace SclZfUtilities\Exception;

/**
 * Exception for when the form builder is asked to build a form for an entity
 * it doesn't know about.
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class NoFormEntityMapException extends \RuntimeException implements
    ExceptionInterface
{
}
