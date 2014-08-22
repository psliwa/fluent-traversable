<?php


namespace FluentTraversable;

use PhpOption\Option;

/**
 * Describes operations on arrays and traversable objects
 *
 * @author Piotr Śliwa <peter.pl7@gmail.com>
 */
interface TraversableFlow
{
    //intermediate operations

    /**
     * Transforms each element using given function.
     *
     * Element value will be provided as first argument of $func, element index will be provided as second argument, so
     * if you want to use functions with more than one argument as a map function (for example strtolower), you should
     * use call::func('strtolower') to suppress second argument that have would be an index.
     *
     * @param callable $func Function that should transform an argument
     * @return TraversableFlow
     *
     * @see flatMap
     */
    public function map($func);

    /**
     * Transforms each element to array values using given function and merges all result arrays into one array
     *
     * Example:
     * <code>
     *     FluentTraversable::from(array('some', 'words'))
     *          ->flatMap(call::func('str_split'))
     *          ->toArray();
     *
     *     //result:
     *     array('s', 'o', 'm', 'e', 'w', 'o', 'r', 'd', 's')
     * </code>
     *
     * @param callable $func Function that should transform an argument to array
     * @return TraversableFlow
     *
     * @see map
     */
    public function flatMap($func);

    /**
     * Removes elements that do not match given predicate
     *
     * @param callable $predicate Function that evaluates element to boolean value
     * @return TraversableFlow
     */
    public function filter($predicate);

    /**
     * Removes duplicates from array
     *
     * @return TraversableFlow
     */
    public function unique();

    /**
     * Groups elements by value returned by given function
     *
     * Example:
     * <code>
     *     FluentTraversable::from(array(1, 2, 3, 4))
     *          ->group(function($number){
     *              return $number % 2 === 0;
     *          })
     *          ->toMap();
     * //result:
     * array(0 => array(2, 4), 1 => array(1, 3))
     * </code>
     *
     * @param callable $keyFunction Function generating grouping key for each element
     * @return TraversableFlow
     *
     * @see partition
     */
    public function groupBy($keyFunction);

    /**
     * Re-indexes elements using provided function. Use this function only when you are sure the indexes for all elements
     * are unique. If indexes will duplicate, exception will be thrown.
     *
     * @param callable $indexFunction
     * @return TraversableFlow
     *
     * @throws \FluentTraversable\Exception\RuntimeException
     */
    public function indexBy($indexFunction);

    /**
     * Merges given traversable to current elements
     *
     * @param array|FluentTraversable|\Traversable $traversable
     * @return TraversableFlow
     */
    public function merge($traversable);

    /**
     * Orders elements by natural order or using comparing function
     *
     * @param callable|null $comparator Comparing function
     * @return TraversableFlow
     */
    public function order($comparator = null);

    /**
     * Orders elements using provided function as value provider for objects.
     *
     * Example:
     * <code>
     *      FluentTraversable::from(array(
     *              array('name' => 'John'),
     *              array('name' => 'Jolka'))
     *          )
     *          //order by "name" property
     *          ->orderBy(get::value('name'))
     *          ->toArray();
     * </code>
     *
     * @param callable $valFunction
     * @param string $direction ASC or DESC - direction of ordering
     * @return TraversableFlow
     */
    public function orderBy($valFunction, $direction = 'ASC');

    /**
     * Skips first few elements
     *
     * @param int $i
     * @return TraversableFlow
     */
    public function skip($i);

    /**
     * Limits number of element to given size
     *
     * @param int $i
     * @return TraversableFlow
     */
    public function limit($i);

    /**
     * Splits elements into two groups using given predicate. Elements that match predicate are stored under "0" index, other
     * elements under "1" index
     *
     * Example:
     * <code>
     *      list($males, $females) = FluentTraversable::from($persons)
     *          ->partition(function($person){
     *              return $person->getSex() === 'male';
     *          })
     *          ->toMap();
     * </code>
     *
     * @param callable $predicate
     * @return TraversableFlow
     *
     * @see groupBy
     */
    public function partition($predicate);

    /**
     * Keeps elements that exists also in given array
     *
     * @param array|\Traversable|FluentTraversable $traversable
     * @return TraversableFlow
     *
     * @see difference
     */
    public function intersect($traversable);

    /**
     * Removes elements that exists also in given array
     *
     * @param array|\Traversable|FluentTraversable $traversable
     * @return TraversableFlow
     *
     * @see intersect
     */
    public function difference($traversable);

    /**
     * Adds element to array
     *
     * @param $element
     * @return TraversableFlow
     */
    public function append($element);

    /**
     * Extracts keys from the array
     *
     * @return TraversableFlow
     */
    public function keys();

    //terminal operations

    /**
     * Returns true when all elements match given predicate. When array is empty result is true
     *
     * @param callable $predicate
     * @return mixed
     *
     * @see anyMatch
     * @see noneMatch
     */
    public function allMatch($predicate);

    /**
     * Returns true when at least one element matches given predicate. When array is empty result is false
     *
     * @param callable $predicate
     * @return mixed
     *
     * @see allMatch
     * @see noneMatch
     */
    public function anyMatch($predicate);

    /**
     * Returns true when all elements don match given predicate. When array is empty result is true
     *
     * @param callable $predicate
     * @return mixed
     *
     * @see anyMatch
     * @see allMatch
     */
    public function noneMatch($predicate);

    /**
     * Size of the array
     *
     * @return mixed
     */
    public function size();

    /**
     * Gets {@link Option} containing max element by natural order or by given comparator
     *
     * @param callable|null $comparator Comparing function
     * @return Option
     *
     * @see min
     */
    public function max($comparator = null);

    /**
     * Gets {@link Option} containing element with max value produced by provided $valFunction.
     *
     * Example:
     * [code]
     *      FluentTraversable::from(array(
     *              array('name' => 'John', 'age' => 33),
     *              array('name' => 'Jolka', 'age' => 21))
     *          )
     *          ->maxBy(get::value('age'))
     *          //there is Option value
     *          ->map(function($john){
     *              //executes only for John
     *          });
     * [/code]
     *
     * @param callable $valFunction
     * @return Option
     *
     * @see max
     * @see orderBy
     */
    public function maxBy($valFunction);

    /**
     * Gets {@link Option} containing min element by natural order or by given comparator
     *
     * @param callable|null $comparator Comparing function
     * @return Option
     *
     * @see max
     */
    public function min($comparator = null);

    /**
     * Gets {@link Option} containing element with min value produced by provided $valFunction.
     *
     * Example:
     * [code]
     *      FluentTraversable::from(array(
     *              array('name' => 'John', 'age' => 33),
     *              array('name' => 'Jolka', 'age' => 21))
     *          )
     *          ->minBy(get::value('age'))
     *          //there is Option value
     *          ->map(function($jolka){
     *              //executes only for Jolka
     *          });
     * [/code]
     *
     * @param callable $valFunction
     * @return Option
     *
     * @see max
     * @see orderBy
     */
    public function minBy($valFunction);

    /**
     * Gets {@link Option} containing first element that match given predicate
     *
     * @param callable $predicate
     * @return Option
     */
    public function firstMatch($predicate);

    /**
     * Returns array of elements - indexes are ignored, all values are re-indexed. Use this function when indexes in use
     * context have not meaning.
     *
     * @return mixed
     *
     * @see toMap
     * @see collect
     */
    public function toArray();

    /**
     * Returns array of elements - indexes are preserved. Use this function when indexes in use context have meaning.
     *
     * @return mixed
     *
     * @see toArray
     * @see collect
     */
    public function toMap();

    /**
     * Creates and returns object of given class. Constructor of given class should has one array argument. This method
     * can be used to convert array to given collection class.
     *
     * @param string $className
     * @return mixed
     */
    public function to($className);

    /**
     * Collects elements using given collector function.
     *
     * @param callable $collector
     * @return mixed
     *
     * @see toArray
     * @see toMap
     */
    public function collect($collector);

    /**
     * Joins string representation of elements using given separator. When array is empty, the result is empty string
     *
     * @param string $separator
     * @return mixed
     *
     * @see reduce
     * @see reduceFromIdentity
     */
    public function join($separator);

    /**
     * Gets {@link Option} containing first element in array
     *
     * @return Option
     *
     * @see last
     */
    public function first();

    /**
     * Gets {@link Option) containing last element in array
     *
     * @return Option
     *
     * @see first
     */
    public function last();

    /**
     * Reduces an array to {@link Option} containing single value - result of reduction. When array is empty the result
     * is {@link None} value, otherwise it is {@link Some} with value.
     *
     * @param callable $binaryOperator Function that has two arguments and returns result of operation on two arguments
     * @return Option
     *
     * @see reduceFromIdentity
     */
    public function reduce($binaryOperator);

    /**
     * Reduces an array to single value using given identity value. When array is empty the result is equal to given
     * identity value
     *
     * @param mixed $identity Identity value for given operation, for sum it should be 0, for multiplication 1 etc.
     * @param callable $binaryOperation Function that has two arguments and returns result of operation on two arguments
     * @return mixed
     *
     * @see reduce
     */
    public function reduceFromIdentity($identity, $binaryOperation);
} 