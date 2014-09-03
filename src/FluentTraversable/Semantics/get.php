<?php


namespace FluentTraversable\Semantics;

use FluentTraversable\Functions;

/**
 * This class is shortcut for {@link Functions#getPropertyValue()} and {@link Functions#getPropertyOptionValue()}
 *
 * @author Piotr Śliwa <peter.pl7@gmail.com>
 */
final class get
{
    public static function value($property)
    {
        return Functions::getPropertyValue($property);
    }

    public static function option($property)
    {
        return Functions::getPropertyOptionValue($property);
    }
}