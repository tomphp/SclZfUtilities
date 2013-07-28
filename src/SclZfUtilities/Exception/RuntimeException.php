<?php

namespace SclZfUtilities\Exception;

/**
 * RuntimeException
 *
 * @author Tom Oram <tom@scl.co.uk>
 */
class RuntimeException extends \RuntimeException implements
    ExceptionInterface
{
    /**
     * methodShouldNotBeCalled
     *
     * @param  string $method
     * @param  int    $line
     * @return RuntimeException
     */
    public static function methodShouldNotBeCalled($method, $line)
    {
        return new self(
            sprintf(
                '%s should never be called (%d).',
                $method,
                $line
            )
        );
    }
}
