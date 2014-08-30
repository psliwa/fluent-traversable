<?php


namespace FluentTraversable\Semantics;


use FluentTraversable\Functions;

/**
 * This class contains aliases to few functions in {@link Functions} class
 *
 * @author Piotr Śliwa <peter.pl7@gmail.com>
 */
class func
{
    /**
     * Wraps passed function and suppresses all arguments except first one.
     *
     * This function is very useful to fix native functions with optional second argument. "map" family functions in
     * {@link FluentTraversable} passes to callback index of element as second argument, so if native function has second
     * argument with different meaning, index will be provided as second argument. To prevent that you have to fix this
     * function.
     *
     * Example:
     *
     * <code>
     *  FluentTraversable::from(...)
     *      ->map(func::fix('count')) //count second argument is mode
     *      ->toArray();
     * </code>
     *
     * @param callable $func
     * @return callable
     */
    public static function fix($func)
    {
        return Functions::oneArgumentFunction($func);
    }
} 