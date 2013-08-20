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

    /**
     * @param  string $method
     * @param  int    $line
     * @return RuntimeException
     */
    public static function multipleResultsFound($method, $line)
    {
        return new self(
            sprintf(
                'Multiple results were found in %s (%d) when a single one was expected.',
                $method,
                $line
            )
        );
    }
}
