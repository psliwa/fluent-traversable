<?php


namespace FluentTraversable;

use FluentTraversable\Internal\PropertyGetter;

/**
 * Factory for basic predicates
 *
 * @author Piotr Śliwa <peter.pl7@gmail.com>
 */
class Predicates
{
    private static $propertyGetter;

    private static function getPropertyGetter()
    {
        if(self::$propertyGetter === null) {
            self::$propertyGetter = new PropertyGetter();
        }

        return self::$propertyGetter;
    }

    public static function eq($property, $value = null)
    {
        list($property, $value) = self::fixBinaryArgs($property, $value, func_num_args());

        $propertyGetter = self::getPropertyGetter();
        return function($object) use($property, $value, $propertyGetter){
            return $propertyGetter->getValue($object, $property) == $value;
        };
    }

    private static function fixBinaryArgs($property, $value, $argsCount)
    {
        if($argsCount === 1) {
            return array(null, $property);
        } else {
            return array($property, $value);
        }
    }

    public static function identical($property, $value = null)
    {
        list($property, $value) = self::fixBinaryArgs($property, $value, func_num_args());

        $propertyGetter = self::getPropertyGetter();
        return function($object) use($property, $value, $propertyGetter){
            return $propertyGetter->getValue($object, $property) === $value;
        };
    }

    public static function false($property = null)
    {
        return self::identical($property, false);
    }

    public static function true($property = null)
    {
        return self::identical($property, true);
    }

    public static function gt($property, $value = null)
    {
        list($property, $value) = self::fixBinaryArgs($property, $value, func_num_args());

        $propertyGetter = self::getPropertyGetter();
        return function($object) use($property, $value, $propertyGetter){
            return $propertyGetter->getValue($object, $property) > $value;
        };
    }

    public static function lt($property, $value = null)
    {
        list($property, $value) = self::fixBinaryArgs($property, $value, func_num_args());

        $propertyGetter = self::getPropertyGetter();
        return function($object) use($property, $value, $propertyGetter){
            return $propertyGetter->getValue($object, $property) < $value;
        };
    }

    public static function gte($property, $value = null)
    {
        list($property, $value) = self::fixBinaryArgs($property, $value, func_num_args());

        $propertyGetter = self::getPropertyGetter();
        return function($object) use($property, $value, $propertyGetter){
            return $propertyGetter->getValue($object, $property) >= $value;
        };
    }

    public static function lte($property, $value = null)
    {
        list($property, $value) = self::fixBinaryArgs($property, $value, func_num_args());

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

    public static function in($property, $values = null)
    {
        list($property, $values) = self::fixBinaryArgs($property, $values, func_num_args());

        $propertyGetter = self::getPropertyGetter();
        return function($object) use($property, $values, $propertyGetter) {
            return in_array($propertyGetter->getValue($object, $property), $values);
        };
    }

    public static function notNull($property = null)
    {
        $propertyGetter = self::getPropertyGetter();
        return function($object) use($propertyGetter, $property){
            return $propertyGetter->getValue($object, $property) !== null;
        };
    }

    public static function null($property = null)
    {
        return self::identical($property, null);
    }

    public static function contains($property, $needle = null)
    {
        list($property, $needle) = self::fixBinaryArgs($property, $needle, func_num_args());

        $propertyGetter = self::getPropertyGetter();
        return function($object) use($property, $needle, $propertyGetter) {
            return strpos($propertyGetter->getValue($object, $property), $needle) !== false;
        };
    }
}