<?php


namespace FluentTraversable;

use FluentTraversable\Internal\NonCallablePuppet;
use PhpOption\Option;

/**
 * Class that can be used to compose complex functions that operates on arrays.
 *
 * @author Piotr Śliwa <peter.pl7@gmail.com>
 */
class FluentComposer implements TraversableFlow
{
    const ARG_TYPE_ARRAY = 1;
    const ARG_TYPE_VARARGS = 2;
    const ARG_TYPE_VALUE = 3;

    private $operations = array();
    private $terminalOperation;
    private $argumentType = false;

    protected function __construct($argType)
    {
        $this->argumentType = $argType;
    }

    /**
     * Creates composer that creates function accepting one array or \Traversable argument
     *
     * @return FluentComposer
     */
    public static function forArray()
    {
        return new static(self::ARG_TYPE_ARRAY);
    }

    /**
     * Creates composer that creates function accepting varargs, each argument is threaten as an array element
     *
     * @return FluentComposer
     */
    public static function forVarargs()
    {
        return new static(self::ARG_TYPE_VARARGS);
    }

    /**
     * Creates composer that creates function accepting one argument that will be only element of array. This factory
     * method is similar to {@link FluentComposer::forVarargs}, the difference is all arguments are ignored except
     * the first.
     *
     * @return FluentComposer
     */
    public static function forValue()
    {
        return new static(self::ARG_TYPE_VALUE);
    }

    /**
     * @inheritdoc
     *
     * @return FluentComposer
     */
    public function map($func)
    {
        $this->checkTerminalOperation();

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return FluentComposer
     */
    public function flatMap($func)
    {
        $this->checkTerminalOperation();

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return FluentComposer
     */
    public function flatten()
    {
        $this->checkTerminalOperation();

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return FluentComposer
     */
    public function filter($predicate)
    {
        $this->checkTerminalOperation();

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return FluentComposer
     */
    public function unique()
    {
        $this->checkTerminalOperation();

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return FluentComposer
     */
    public function groupBy($keyFunction)
    {
        $this->checkTerminalOperation();

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return FluentComposer
     */
    public function indexBy($indexFunction)
    {
        $this->checkTerminalOperation();

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return FluentComposer
     */
    public function order($comparator = null)
    {
        $this->checkTerminalOperation();

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return FluentComposer
     */
    public function orderBy($valFunction, $direction = 'ASC')
    {
        $this->checkTerminalOperation();

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return FluentComposer
     */
    public function skip($i)
    {
        $this->checkTerminalOperation();

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return FluentComposer
     */
    public function limit($i)
    {
        $this->checkTerminalOperation();

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return FluentComposer
     */
    public function partition($predicate)
    {
        $this->checkTerminalOperation();

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return FluentComposer
     */
    public function intersection($traversable)
    {
        $this->checkTerminalOperation();

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return FluentComposer
     */
    public function difference($traversable)
    {
        $this->checkTerminalOperation();

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return FluentComposer
     */
    public function keys()
    {
        $this->checkTerminalOperation();

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return FluentComposer
     */
    public function zip($traversable, $func = null)
    {
        $this->checkTerminalOperation();

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return FluentComposer
     */
    public function append($element)
    {
        $this->checkTerminalOperation();

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return FluentComposer
     */
    public function merge($traversable)
    {
        $this->checkTerminalOperation();

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return FluentComposer
     */
    public function allMatch($predicate)
    {
        $this->markTerminalOperation(__FUNCTION__);

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return FluentComposer
     */
    public function anyMatch($predicate)
    {
        $this->markTerminalOperation(__FUNCTION__);

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return FluentComposer
     */
    public function noneMatch($predicate)
    {
        $this->markTerminalOperation(__FUNCTION__);

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return FluentComposer
     */
    public function size()
    {
        $this->markTerminalOperation(__FUNCTION__);

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function max($comparator = null)
    {
        $this->markTerminalOperation(__FUNCTION__);

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this->optionPuppet();
    }

    /**
     * @return Option
     */
    private function optionPuppet()
    {
        $puppet = NonCallablePuppet::record();
        $this->operations[] = $puppet;

        return $puppet;
    }

    /**
     * @inheritdoc
     */
    public function maxBy($valFunction)
    {
        $this->markTerminalOperation(__FUNCTION__);

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this->optionPuppet();
    }

    /**
     * @inheritdoc
     */
    public function min($comparator = null)
    {
        $this->markTerminalOperation(__FUNCTION__);

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this->optionPuppet();
    }

    /**
     * @inheritdoc
     */
    public function minBy($valFunction)
    {
        $this->markTerminalOperation(__FUNCTION__);

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this->optionPuppet();
    }

    /**
     * @inheritdoc
     */
    public function firstMatch($predicate)
    {
        $this->markTerminalOperation(__FUNCTION__);

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this->optionPuppet();
    }

    /**
     * @inheritdoc
     *
     * @return FluentComposer
     */
    public function toArray()
    {
        $this->markTerminalOperation(__FUNCTION__);

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return FluentComposer
     */
    public function toMap()
    {
        $this->markTerminalOperation(__FUNCTION__);

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return FluentComposer
     */
    public function to($className)
    {
        $this->markTerminalOperation(__FUNCTION__);

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return FluentComposer
     */
    public function collect($collector)
    {
        $this->markTerminalOperation(__FUNCTION__);

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @inheritdoc
     *
     * @return FluentComposer
     */
    public function join($separator)
    {
        $this->markTerminalOperation(__FUNCTION__);

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @inheritdoc
     */
    public function first()
    {
        $this->markTerminalOperation(__FUNCTION__);

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this->optionPuppet();
    }

    /**
     * @inheritdoc
     */
    public function get($index)
    {
        $this->markTerminalOperation(__FUNCTION__);

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this->optionPuppet();
    }

    /**
     * @inheritdoc
     */
    public function last()
    {
        $this->markTerminalOperation(__FUNCTION__);

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this->optionPuppet();
    }

    /**
     * @inheritdoc
     */
    public function reduce($binaryOperator)
    {
        $this->markTerminalOperation(__FUNCTION__);

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this->optionPuppet();
    }


    /**
     * @inheritdoc
     * @return FluentComposer
     */
    public function reduceFromIdentity($identity, $binaryOperation)
    {
        $this->markTerminalOperation(__FUNCTION__);

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @inheritdoc
     * @return FluentComposer
     */
    public function each($func)
    {
        $this->markTerminalOperation(__FUNCTION__);

        $this->operations[] = array(__FUNCTION__, func_get_args());

        return $this;
    }

    /**
     * @param $traversable
     * @return mixed Depends on composer instrumentation
     */
    public function __invoke($traversable)
    {
        switch($this->argumentType) {
            case self::ARG_TYPE_VARARGS:
                $traversable = func_get_args();
                break;
            case self::ARG_TYPE_VALUE:
                $traversable = func_num_args() ? array(func_get_arg(0)) : array();
                break;
        }

        $result = FluentTraversable::from($traversable);

        foreach($this->operations as $operation) {
            if($operation instanceof Puppet) {
                $result = $operation($result);
            } else {
                list($method, $args) = $operation;

                $result = call_user_func_array(array($result, $method), $args);
            }
        }

        return $result;
    }

    private function markTerminalOperation($method)
    {
        $this->checkTerminalOperation();

        $this->terminalOperation = $method;
    }

    private function checkTerminalOperation()
    {
        if ($this->terminalOperation !== null) {
            throw new \RuntimeException(
                sprintf(
                    'Only one terminal operation can be called, "%s" has been already called.',
                    $this->terminalOperation
                )
            );
        }
    }
}