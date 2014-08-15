<?php


namespace FluentTraversable;

use PhpOption\Option;

interface TraversableFlow
{
    //intermediate operations

    /**
     * @param $func
     * @return TraversableFlow
     */
    public function map($func);

    /**
     * @param $func
     * @return TraversableFlow
     */
    public function flatMap($func);

    /**
     * @param $func
     * @return TraversableFlow
     */
    public function filter($func);

    /**
     * @return TraversableFlow
     */
    public function unique();

    /**
     * @param $func
     * @return TraversableFlow
     */
    public function group($func);

    /**
     * @param $comparator
     * @return TraversableFlow
     */
    public function order($comparator = null);

    /**
     * @param $i
     * @return TraversableFlow
     */
    public function skip($i);

    /**
     * @param $i
     * @return TraversableFlow
     */
    public function limit($i);

    /**
     * @param $func
     * @return TraversableFlow
     */
    public function partition($func);

    /**
     * @param $traversable
     * @return TraversableFlow
     */
    public function intersect($traversable);

    /**
     * @param $traversable
     * @return TraversableFlow
     */
    public function difference($traversable);

    /**
     * @param $element
     * @return TraversableFlow
     */
    public function append($element);

    //terminal operations

    /**
     * @param $predicate
     * @return mixed
     */
    public function allMatch($predicate);

    /**
     * @param $predicate
     * @return mixed
     */
    public function anyMatch($predicate);

    /**
     * @param $predicate
     * @return mixed
     */
    public function noneMatch($predicate);

    /**
     * @return mixed
     */
    public function size();

    /**
     * @param $comparator
     * @return Option
     */
    public function max($comparator = null);

    /**
     * @param $comparator
     * @return Option
     */
    public function min($comparator = null);

    /**
     * @param $func
     * @return Option
     */
    public function firstMatch($func);

    /**
     * @return mixed
     */
    public function toArray();

    /**
     * @return mixed
     */
    public function toMap();

    /**
     * @param $collector
     * @return mixed
     */
    public function collect($collector);

    /**
     * @param $separator
     * @return mixed
     */
    public function join($separator);

    /**
     * @return Option
     */
    public function first();

    /**
     * @return Option
     */
    public function last();

    /**
     * @param $func
     * @return Option
     */
    public function reduce($func);

    /**
     * @param $identity
     * @param $biOperation
     * @return mixed
     */
    public function reduceFromIdentity($identity, $biOperation);
} 