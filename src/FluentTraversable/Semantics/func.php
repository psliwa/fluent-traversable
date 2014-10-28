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
     * @param callable $func
     * @return callable
     *
     * @deprecated Use {@link func#unary()} instead
     */
    public static function fix($func)
    {
        return Functions::oneArgumentFunction($func);
    }

    /**
     * Wraps passed function and suppresses all arguments except first one.
     *
     * This function is very useful to make unary native functions with optional second argument. "map" family functions in
     * {@link FluentTraversable} passes to callback index of element as second argument, so if native function has second
     * argument with different meaning, index will be provided as second argument. To prevent that you have to make this
     * function unary.
     *
     * Example:
     *
     * <code>
     *  FluentTraversable::from(...)
     *      ->map(func::unary('count')) //count second argument is mode
     *      ->toArray();
     * </code>
     *
     * @param callable $func
     * @return callable
     */
    public static function unary($func)
    {
        return Functions::oneArgumentFunction($func);
    }
}