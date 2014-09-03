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

    public static function notEq($property, $value = null)
    {
        return self::not(call_user_func_array(array(__CLASS__, 'eq'), func_get_args()));
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

    public static function notIdentical($property, $value = null)
    {
        //call_user_* instead self::identical() to maintain original number of passed args
        return self::not(call_user_func_array(array(__CLASS__, 'identical'), func_get_args()));
    }

    /**
     * Strict false predicate
     *
     * @param $property
     * @return callable
     */
    public static function false($property = null)
    {
        return self::identical($property, false);
    }

    /**
     * Strict not false predicate
     *
     * @param $property
     * @return callable
     */
    public static function notFalse($property = null)
    {
        return self::not(self::false($property));
    }

    /**
     * Not strict false predicate
     *
     * @param $property
     * @return callable
     */
    public static function falsy($property = null)
    {
        return self::eq($property, false);
    }

    /**
     * Strict true predicate
     *
     * @param $property
     * @return callable
     */
    public static function true($property = null)
    {
        return self::identical($property, true);
    }

    /**
     * Strict not true predicate
     *
     * @param $property
     * @return callable
     */
    public static function notTrue($property = null)
    {
        return self::not(self::true($property));
    }

    /**
     * Not strict true predicate
     *
     * @param $property
     * @return callable
     */
    public static function truthy($property = null)
    {
        return self::eq($property, true);
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
        /**
         * @var FluentTraversable $predicates
         * @var FluentTraversable $values
         */
        list($predicates, $values) = self::partitionPredicatesAndValues(func_get_args());

        if($values->anyMatch(self::notTrue())) {
            return function(){
                return false;
            };
        }

        return function($object) use($predicates) {
            return $predicates->allMatch(function($predicate) use($object){
                return $predicate($object);
            });
        };
    }

    /**
     * Alias to {@link Predicates#andX()}
     *
     * @see andX
     */
    public static function allTrue($predicate1, $predicate2 = null)
    {
        return call_user_func_array(array(__CLASS__, 'andX'), func_get_args());
    }

    public static function orX($predicate1, $predicate2 = null)
    {
        /**
         * @var FluentTraversable $predicates
         * @var FluentTraversable $values
         */
        list($predicates, $values) = self::partitionPredicatesAndValues(func_get_args());

        if($values->anyMatch(self::true())) {
            return function(){
                return true;
            };
        }

        return function($object) use($predicates) {
            return $predicates->anyMatch(function($predicate) use($object){
                return $predicate($object);
            });
        };
    }

    /**
     * Alias to {@link Predicates#orX()}
     *
     * @see orX
     */
    public static function anyTrue($predicate1, $predicate2 = null)
    {
        return call_user_func_array(array(__CLASS__, 'orX'), func_get_args());
    }

    public static function in($property, $values = null)
    {
        list($property, $values) = self::fixBinaryArgs($property, $values, func_num_args());

        $propertyGetter = self::getPropertyGetter();
        return function($object) use($property, $values, $propertyGetter) {
            return in_array($propertyGetter->getValue($object, $property), $values);
        };
    }

    public static function notIn($property, $values = null)
    {
        return self::not(call_user_func_array(array(__CLASS__, 'in'), func_get_args()));
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

    public static function blank($property = null)
    {
        $propertyGetter = self::getPropertyGetter();
        return function($object) use($propertyGetter, $property){
            $result = $propertyGetter->getValue($object, $property);
            return empty($result);
        };
    }

    public static function notBlank($property = null)
    {
        return self::not(self::blank($property));
    }

    private static function partitionPredicatesAndValues(array $arguments)
    {
        return FluentTraversable::from($arguments)
            ->partition(function ($arg) {
                return is_callable($arg);
            })
            ->map(function (array $elements) {
                return FluentTraversable::from($elements);
            })
            ->toMap();
    }
}