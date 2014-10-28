<?php


namespace FluentTraversable\Semantics;


use FluentTraversable\Functions;

/**
 * This class adds semantic meaning to {@link Functions#oneArgumentFunction()} function.
 *
 * You can use call::func('strtolower') to wrap functions with multiple arguments and make it works with map family
 * function.
 *
 * @author Piotr Śliwa <peter.pl7@gmail.com>
 *
 * @deprecated Use {@link func#fix()} instead. It will be removed in 1.0.0 version.
 */
final class call
{
    /**
     * @param $func
     * @return callable
     *
     * @deprecated Use {@link func#unary()} instead
     */
    public static function func($func)
    {
        return Functions::oneArgumentFunction($func);
    }
} 