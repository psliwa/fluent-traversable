<?php


namespace FluentTraversable\Semantics;

use FluentTraversable\FluentComposer;

/**
 * Factory for {@link FluentComposer} that adds more concise syntax to create composer.
 *
 * @author Piotr Śliwa <peter.pl7@gmail.com>
 *
 * @deprecated Use {@link FluentTraversable\FluentComposer} instead
 */
final class compose
{
    /**
     * Creates composer that creates function accepting one array or \Traversable argument
     *
     * @return FluentComposer
     */
    public static function forArray()
    {
        return FluentComposer::forArray();
    }

    /**
     * Creates composer that creates function accepting one argument that will be only element of array. This factory
     * method is similar to {@link FluentComposer::forVarargs}, the difference is all arguments are ignored except
     * the first.
     *
     * @return FluentComposer
     */
    public static function forValue()
    {
        return FluentComposer::forValue();
    }

    /**
     * Creates composer that creates function accepting varargs, each argument is threaten as an array element
     *
     * @return FluentComposer
     */
    public static function forVarargs()
    {
        return FluentComposer::forVarargs();
    }
} 