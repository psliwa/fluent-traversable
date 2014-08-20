<?php


namespace FluentTraversable;
use FluentTraversable\Internal\PropertyGetter;

/**
 * Factory for general purpose functions
 *
 * @author Piotr Śliwa <peter.pl7@gmail.com>
 */
class Functions
{
    public static function getPropertyValue($property)
    {
        $propertyGetter = new PropertyGetter();
        return function($object) use($property, $propertyGetter){
            return $propertyGetter->getValue($object, $property);
        };
    }
} 