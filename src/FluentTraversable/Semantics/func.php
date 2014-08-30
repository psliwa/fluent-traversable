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
     * This function is very useful to wrap native functions with optional second argument. "map" family functions in
     * {@link FluentTraversable} passes index of element as second argument to callback, so if native function has second
     * argument with different meaning, index will be provided as second argument. To prevent that you have to wrap this
     * function.
     *
     * Example:
     *
     * <code>
     *  FluentTraversable::from(...)
     *      ->map(func::wrap('count')) //count second argument is mode
     *      ->toArray();
     * </code>
     *
     * @param callable $func
     * @return callable
     */
    public static function wrap($func)
    {
        return Functions::oneArgumentFunction($func);
    }
} 