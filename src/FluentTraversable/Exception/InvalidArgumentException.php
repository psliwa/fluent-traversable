<?php


namespace FluentTraversable\Exception;
use FluentTraversable\Internal\NonCallablePuppet;

/**
 * InvalidArgumentException class
 *
 * @author Piotr Śliwa <peter.pl7@gmail.com>
 */
class InvalidArgumentException extends \InvalidArgumentException implements Exception
{
    public static function assertCallbackIfNotNull($callback, $method)
    {
        if($callback !== null) {
            self::assertCallback($callback, $method);
        }
    }

    public static function assertCallback($callback, $method)
    {
        if(!is_callable($callback)) {
            throw new self(
                sprintf(
                    'Invalid callback "%s" was provided to method "%s"',
                    self::callbackToString($callback),
                    $method
                )
            );
        } else if($callback instanceof NonCallablePuppet) {
            throw new self(
                sprintf(
                    'FluentComposer passed to method "%s" cannot be directly chained when terminal operation that you called returns Option. '.
                    'You should use "$f = compose::forArray(), $f->..." walkaround if you want to use FluentComposer '.
                    'as predicate or mapping function in FluentTraversable. For more details refer to documentation.',
                    $method
                )
            );
        }
    }

    private static function callbackToString($callback)
    {
        if(is_string($callback)) {
            return $callback;
        } else if(is_object($callback)) {
            return get_class($callback);
        } else if(is_array($callback) && count($callback) === 2) {
            list($class, $method) = array_values($callback);

            return self::callbackToString($class).'::'.self::callbackToString($method);
        } else {
            return gettype($callback);
        }
    }
}