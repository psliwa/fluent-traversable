<?php


namespace FluentTraversable\Semantics;

use FluentTraversable\Functions;

/**
 * This class is shortcut for {@link Functions#getPropertyValue()}
 *
 * @author Piotr Śliwa <peter.pl7@gmail.com>
 */
final class get
{
    public static function value($property)
    {
        return Functions::getPropertyValue($property);
    }
}