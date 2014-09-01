<?php


namespace FluentTraversable\Semantics;

use FluentTraversable\Functions;

/**
 * This class is shortcut for {@link Functions#count()}
 *
 * @author Piotr Śliwa <peter.pl7@gmail.com>
 */
class size
{
    public static function of($property = null)
    {
        return Functions::count($property);
    }
}