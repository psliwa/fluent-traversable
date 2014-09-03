<?php


namespace FluentTraversable;

use FluentTraversable\Internal\PropertyGetter;
use PhpOption\Option;

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

    public static function getPropertyOptionValue($property)
    {
        $getter = self::getPropertyValue($property);
        return function($object) use($getter){
            return Option::fromValue($getter($object));
        };
    }

    public static function oneArgumentFunction($innerFunction)
    {
        return function($value) use($innerFunction) {
            return call_user_func($innerFunction, $value);
        };
    }

    public static function count($property = null)
    {
        $propertyGetter = new PropertyGetter();
        return function($object) use($property, $propertyGetter) {
            return count($propertyGetter->getValue($object, $property));
        };
    }
}