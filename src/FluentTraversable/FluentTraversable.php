<?php

namespace FluentTraversable;

use PhpOption\None;
use PhpOption\Option;

class FluentTraversable implements TraversableFlow
{
    private $elements;

    protected function __construct($traversable)
    {
        $this->elements = is_array($traversable) ? $traversable : $this->convertToArray($traversable);
    }

    public static function from($traversable)
    {
        if($traversable instanceof FluentTraversable) {
            return $traversable;
        }

        self::ensureTraversable($traversable);

        return new static($traversable);
    }

    private function convertToArray($traversable)
    {
        $elements = array();

        foreach($traversable as $key => $value)
        {
            $elements[$key] = $value;
        }

        return $elements;
    }

    private static function getTypeOf($traversable)
    {
        return is_object($traversable) ? get_class($traversable) : gettype($traversable);
    }

    private static function ensureTraversable($traversable)
    {
        if (!self::isTraversable($traversable)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'FluentTraversable supports only arrays and Traversable objects, %s given',
                    self::getTypeOf($traversable)
                )
            );
        }
    }

    private static function isTraversable($traversable)
    {
        return is_array($traversable) && !$traversable instanceof \Traversable;
    }

    //intermediate operations

    /**
     * @param $func
     * @return FluentTraversable
     */
    public function map($func)
    {
        $this->elements = array_map($func, $this->elements);
        return $this;
    }

    /**
     * @param null $func
     * @return FluentTraversable
     */
    public function order($func = null)
    {
        if($func === null) {
            asort($this->elements);
        } else {
            uasort($this->elements, $func);
        }

        return $this;
    }

    /**
     * @param $value
     * @return FluentTraversable
     */
    public function append($value)
    {
        $this->elements[] = $value;

        return $this;
    }

    /**
     * @param $predicate
     * @return FluentTraversable
     */
    public function filter($predicate)
    {
        $this->elements = array_filter($this->elements, $predicate);

        return $this;
    }

    /**
     * @return FluentTraversable
     */
    public function unique()
    {
        $this->elements = array_unique($this->elements, SORT_REGULAR);

        return $this;
    }


    /**
     * @param $keyFunction
     * @return FluentTraversable
     */
    public function group($keyFunction)
    {
        $elements = array();

        foreach($this->elements as $value) {
            $key = call_user_func($keyFunction, $value);
            $elements[$key][] = $value;
        }

        $this->elements = $elements;

        return $this;
    }

    /**
     * @param $predicate
     * @return FluentTraversable
     */
    public function partition($predicate)
    {
        $this->group(function($value) use($predicate){
            return !call_user_func($predicate, $value);
        });

        for($i=0; $i<2; $i++) {
            if(!isset($this->elements[$i])) {
                $this->elements[$i] = array();
            }
        }

        ksort($this->elements);

        return $this;
    }

    /**
     * @param $i
     * @return FluentTraversable
     */
    public function skip($i)
    {
        $this->elements = array_slice($this->elements, $i);

        return $this;
    }

    /**
     * @param $i
     * @return FluentTraversable
     */
    public function limit($i)
    {
        $this->elements = array_slice($this->elements, 0, $i);

        return $this;
    }

    /**
     * @param $traversable
     * @return FluentTraversable
     */
    public function intersect($traversable)
    {
        $this->elements = array_intersect($this->elements, self::from($traversable)->toArray());

        return $this;
    }

    /**
     * @param $traversable
     * @return FluentTraversable
     */
    public function difference($traversable)
    {
        $this->elements = array_diff($this->elements, self::from($traversable)->toArray());

        return $this;
    }

    /**
     * @param $func
     * @return FluentTraversable
     */
    public function flatMap($func)
    {
        return $this
            ->map($func)
            ->flatten();
    }

    /**
     * @return FluentTraversable
     * @throws \LogicException
     */
    public function flatten()
    {
        $elements = array();

        foreach($this->elements as $values) {
            if(!self::isTraversable($values)) {
                throw new \LogicException(
                    sprintf('"%s" can not be flatted, because it is not traversable', self::getTypeOf($values))
                );
            }
            foreach($values as $value) {
                $elements[] = $value;
            }
        }

        $this->elements = $elements;

        return $this;
    }

    //terminal operations

    /**
     * @return array
     */
    public function toArray()
    {
        return array_values($this->elements);
    }

    /**
     * @return array
     */
    public function toMap()
    {
        return $this->elements;
    }

    /**
     * @param $separator
     * @return string
     */
    public function join($separator)
    {
        return implode($separator, $this->elements);
    }

    /**
     * @return Option
     */
    public function first()
    {
        return Option::fromValue(current($this->elements), false);
    }

    /**
     * @param $comparator
     * @return Option
     */
    public function max($comparator = null)
    {
        if(!$this->elements) {
            return None::create();
        }

        if($comparator === null) {
            return Option::fromValue(max($this->elements));
        }

        $elements = $this->elements;
        usort($elements, $comparator);

        return Option::fromValue(end($elements));
    }

    /**
     * @param $comparator
     * @return Option
     */
    public function min($comparator = null)
    {
        if(!$this->elements) {
            return None::create();
        }

        if($comparator === null) {
            return Option::fromValue(min($this->elements));
        }

        $elements = $this->elements;
        usort($elements, $comparator);

        return Option::fromValue(current($elements));
    }

    /**
     * @return Option
     */
    public function last()
    {
        if(!$this->elements) {
            return None::create();
        }

        $value = end($this->elements);
        reset($this->elements);

        return Option::fromValue($value);
    }

    /**
     * @param $predicate
     * @return Option
     */
    public function firstMatch($predicate)
    {
        foreach($this->elements as $value) {
            if(call_user_func($predicate, $value)) {
                return Option::fromValue($value);
            }
        }

        return None::create();
    }

    /**
     * @param $biOperation
     * @return Option
     */
    public function reduce($biOperation)
    {
        if(!$this->elements) {
            return None::create();
        }

        return Option::fromValue(array_reduce($this->elements, $biOperation));
    }

    /**
     * @param $identity
     * @param $biOperation
     * @return mixed
     */
    public function reduceFromIdentity($identity, $biOperation)
    {
        return array_reduce($this->elements, $biOperation, $identity);
    }

    /**
     * @return int
     */
    public function size()
    {
        return count($this->elements);
    }

    /**
     * @param $predicate
     * @return bool
     */
    public function allMatch($predicate)
    {
        foreach($this->elements as $value) {
            if(!call_user_func($predicate, $value)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param $predicate
     * @return bool
     */
    public function anyMatch($predicate)
    {
        foreach($this->elements as $value) {
            if(call_user_func($predicate, $value)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $predicate
     * @return bool
     */
    public function noneMatch($predicate)
    {
        foreach($this->elements as $value) {
            if(call_user_func($predicate, $value)) {
                return false;
            }
        }

        return true;
    }

    public function collect($collector)
    {
        return call_user_func($collector, $this->elements);
    }
}