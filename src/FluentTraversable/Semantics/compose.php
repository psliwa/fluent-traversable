<?php


namespace FluentTraversable\Semantics;

use FluentTraversable\TraversableComposer;

/**
 * Factory for {@link TraversableComposer} that adds more concise syntax to create composer.
 *
 * @author Piotr Śliwa <peter.pl7@gmail.com>
 */
final class compose
{
    /**
     * Creates composer that creates function accepting one array or \Traversable argument
     *
     * @return TraversableComposer
     */
    public static function forArray()
    {
        return TraversableComposer::forArray();
    }

    /**
     * Creates composer that creates function accepting one argument that will be only element of array. This factory
     * method is similar to {@link TraversableComposer::forVarargs}, the difference is all arguments are ignored except
     * the first.
     *
     * @return TraversableComposer
     */
    public static function forValue()
    {
        return TraversableComposer::forValue();
    }

    /**
     * Creates composer that creates function accepting varargs, each argument is threaten as an array element
     *
     * @return TraversableComposer
     */
    public static function forVarargs()
    {
        return TraversableComposer::forVarargs();
    }
} 