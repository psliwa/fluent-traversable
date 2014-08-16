<?php


namespace FluentTraversable;

use FluentTraversable\Internal\PropertyGetter;

/**
 * Factory for basic predicates
 *
 * @author Piotr Śliwa <peter.pl7@gmail.com>
 */
class Predicate
{
    private static $propertyGetter;

    private static function getPropertyGetter()
    {
        if(self::$propertyGetter === null) {
            self::$propertyGetter = new PropertyGetter();
        }

        return self::$propertyGetter;
    }

    public static function eq($property, $value)
    {
        $propertyGetter = self::getPropertyGetter();
        return function($object) use($property, $value, $propertyGetter){
            return $propertyGetter->getValue($object, $property) == $value;
        };
    }

    public static function gt($property, $value)
    {
        $propertyGetter = self::getPropertyGetter();
        return function($object) use($property, $value, $propertyGetter){
            return $propertyGetter->getValue($object, $property) > $value;
        };
    }

    public static function lt($property, $value)
    {
        $propertyGetter = self::getPropertyGetter();
        return function($object) use($property, $value, $propertyGetter){
            return $propertyGetter->getValue($object, $property) < $value;
        };
    }

    public static function gte($property, $value)
    {
        $propertyGetter = self::getPropertyGetter();
        return function($object) use($property, $value, $propertyGetter){
            return $propertyGetter->getValue($object, $property) >= $value;
        };
    }

    public static function lte($property, $value)
    {
        $propertyGetter = self::getPropertyGetter();
        return function($object) use($property, $value, $propertyGetter){
            return $propertyGetter->getValue($object, $property) <= $value;
        };
    }

    public static function not($predicate)
    {
        return function($object) use($predicate) {
            return !$predicate($object);
        };
    }

    public static function andX($predicate1, $predicate2 = null)
    {
        $predicates = FluentTraversable::from(func_get_args());

        return function($object) use($predicates) {
            return $predicates->allMatch(function($predicate) use($object){
                return $predicate($object);
            });
        };
    }

    public static function orX($predicate1, $predicate2 = null)
    {
        $predicates = FluentTraversable::from(func_get_args());

        return function($object) use($predicates) {
            return $predicates->anyMatch(function($predicate) use($object){
                return $predicate($object);
            });
        };
    }

    public static function in($property, array $values)
    {
        $propertyGetter = self::getPropertyGetter();
        return function($object) use($property, $values, $propertyGetter) {
            return in_array($propertyGetter->getValue($object, $property), $values);
        };
    }

    public static function notNull()
    {
        return function($object){
            return $object !== null;
        };
    }

    public static function contains($property, $needle)
    {
        $propertyGetter = self::getPropertyGetter();
        return function($object) use($property, $needle, $propertyGetter) {
            return strpos($propertyGetter->getValue($object, $property), $needle) !== false;
        };
    }
}